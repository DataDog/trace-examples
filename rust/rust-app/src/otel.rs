// Copyright 2024-Present Datadog, Inc. https://www.datadoghq.com/
// SPDX-License-Identifier: Apache-2.0

use crate::tracing::{initialize_logging, set_up_otel_and_tracing};
use anyhow::Error;
use opentelemetry::{global, KeyValue};
use opentelemetry_otlp::{SpanExporterBuilder, WithExportConfig};
use opentelemetry_sdk::{propagation::TraceContextPropagator, trace::TracerProvider, Resource};
use opentelemetry_semantic_conventions as semconv;
use tracing::info;

pub(crate) fn initialize() {
    let guard = initialize_logging();
    info!("Initializing OpenTelemetry");
    let tracer_provider = create_tracing_provider().expect("Failed to initialize TracerProvider");
    global::set_text_map_propagator(TraceContextPropagator::new());
    set_up_otel_and_tracing(tracer_provider).expect("Failed to set up OpenTelemetry and tracing");
    drop(guard);
}

pub(crate) async fn shutdown() {
    #[cfg(unix)]
    let sig_term = async {
        tokio::signal::unix::signal(tokio::signal::unix::SignalKind::terminate())
            .expect("Unable to register SIGTERM handler")
            .recv()
            .await;
    };

    #[cfg(not(unix))]
    let sig_term = std::future::pending::<()>();

    let ctrl_c = async {
        tokio::signal::ctrl_c()
            .await
            .expect("Unable to register CTRL+C handler");
    };

    tokio::select! {
        _ = ctrl_c => {},
        _ = sig_term => {},
    }

    tracing::debug!("Shutting down OpenTelemetry tracer provider");
    opentelemetry::global::shutdown_tracer_provider();
}

fn create_tracing_provider() -> Result<TracerProvider, Error> {
    let pipeline = opentelemetry_otlp::new_pipeline()
        .tracing()
        .with_exporter(create_exporter())
        .with_trace_config(
            opentelemetry_sdk::trace::Config::default().with_resource(create_resource()),
        );
    let provider = pipeline.install_batch(opentelemetry_sdk::runtime::Tokio)?;

    Ok(provider)
}

fn create_exporter() -> SpanExporterBuilder {
    let host = get_envs(&["OTLP_HOST"]).unwrap_or_else(|| "127.0.0.1".to_string());
    opentelemetry_otlp::new_exporter()
        .http()
        .with_endpoint(format!("http://{}:4318/v1/traces", host))
        .into()
}

fn create_resource() -> Resource {
    let service_name =
        get_envs(&["DD_SERVICE", "OTEL_SERVICE_NAME"]).unwrap_or_else(|| "rust-app".to_string());
    let env = get_envs(&["DD_ENV"]);
    let mut kvs = vec![KeyValue::new(semconv::resource::SERVICE_NAME, service_name)];
    if let Some(env) = env {
        kvs.push(KeyValue::new(
            semconv::resource::DEPLOYMENT_ENVIRONMENT_NAME,
            env,
        ));
    }
    Resource::new(kvs)
}

fn get_envs(names: &[&str]) -> Option<String> {
    for name in names {
        if let Ok(value) = std::env::var(name) {
            return Some(value);
        }
    }
    None
}
