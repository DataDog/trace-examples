// Copyright 2024-Present Datadog, Inc. https://www.datadoghq.com/
// SPDX-License-Identifier: Apache-2.0

use axum::extract::{Path, State};
use axum::http::StatusCode;
use axum::response::{IntoResponse, Response};
use axum::routing::get;
use axum::Json;
use axum_tracing_opentelemetry::middleware::{OtelAxumLayer, OtelInResponseLayer};
use reqwest::{Client, Url};
use reqwest_middleware::{ClientBuilder, ClientWithMiddleware, Extension};
use reqwest_tracing::{OtelPathNames, TracingMiddleware};
use tokio::net::TcpListener;

mod otel;
mod tracing;

#[derive(Clone)]
struct ApplicationState {
    other_url: Url,
    client: ClientWithMiddleware,
}

#[tokio::main]
async fn main() {
    otel::initialize();

    // This is the port that our application will listen on
    let port = 8080;

    // Create a new reqwest client with tracing middleware
    let reqwest_client = Client::builder().build().unwrap();
    let client = ClientBuilder::new(reqwest_client)
        .with_init(Extension(
            OtelPathNames::known_paths(["/hello/{name}"]).unwrap(),
        ))
        .with(TracingMiddleware::default())
        .build();

    // Create a state for our application
    let state = ApplicationState {
        other_url: Url::parse(format!("http://localhost:{port}/hello/").as_str()).unwrap(),
        client,
    };

    // Set up the routes for our application with tracing
    let app = axum::Router::new()
        .route("/hello/:name", get(get_hello))
        .route("/forwarding/:name", get(get_forwarding))
        .with_state(state)
        .layer(OtelInResponseLayer)
        .layer(OtelAxumLayer::default());

    let listener = TcpListener::bind(format!("0.0.0.0:{port}")).await.unwrap();

    // Start the server
    axum::serve(listener, app.into_make_service())
        .with_graceful_shutdown(otel::shutdown())
        .await
        .unwrap();
}

#[derive(Debug, serde::Serialize, serde::Deserialize)]
struct HelloResponse {
    message: String,
}

async fn get_hello(Path(name): Path<String>, State(_state): State<ApplicationState>) -> Response {
    if name == "error" {
        return (StatusCode::INTERNAL_SERVER_ERROR, "Error".to_string()).into_response();
    }
    Json(HelloResponse {
        message: format!("Hello, {}!", name).to_string(),
    })
    .into_response()
}

async fn get_forwarding(
    Path(name): Path<String>,
    State(state): State<ApplicationState>,
) -> Response {
    let url = state.other_url.join(&name).unwrap();
    let body = state
        .client
        .get(url.clone())
        .send()
        .await
        .unwrap()
        .json::<HelloResponse>()
        .await;

    match body {
        Ok(hello_response) => Json(hello_response).into_response(),
        Err(err) => (
            StatusCode::INTERNAL_SERVER_ERROR,
            format!("Error while calling other service {}, {:?}", url, err),
        )
            .into_response(),
    }
}
