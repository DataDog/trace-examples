// Copyright 2024-Present Datadog, Inc. https://www.datadoghq.com/
// SPDX-License-Identifier: Apache-2.0

use anyhow::Error;
use opentelemetry::{global, trace::TracerProvider};
use opentelemetry_sdk::trace::TracerProvider as SdkTracerProvider;
use tracing::{subscriber::DefaultGuard, Subscriber};
use tracing_subscriber::{filter::EnvFilter, layer::SubscriberExt, registry::LookupSpan, Layer};

pub(crate) fn initialize_logging() -> DefaultGuard {
    let subscriber = tracing_subscriber::registry()
        .with(create_loglevel_filter())
        .with(create_text_logger());
    tracing::subscriber::set_default(subscriber)
}

pub(crate) fn set_up_otel_and_tracing(tracer_provider: SdkTracerProvider) -> Result<(), Error> {
    let otel_layer = tracing_opentelemetry::layer()
        .with_error_records_to_exceptions(true)
        .with_tracer(tracer_provider.tracer(""));
    global::set_tracer_provider(tracer_provider);

    let subscriber = tracing_subscriber::registry()
        .with(otel_layer)
        .with(create_loglevel_filter())
        .with(create_text_logger());
    tracing::subscriber::set_global_default(subscriber)?;
    Ok(())
}

fn create_text_logger<S>() -> Box<dyn Layer<S> + Send + Sync + 'static>
where
    S: Subscriber + for<'a> LookupSpan<'a>,
{
    use tracing_subscriber::fmt::format::FmtSpan;
    Box::new(
        tracing_subscriber::fmt::layer()
            .pretty()
            .with_line_number(true)
            .with_thread_names(true)
            .with_span_events(FmtSpan::NEW | FmtSpan::CLOSE)
            .with_timer(tracing_subscriber::fmt::time::uptime()),
    )
}

pub(crate) fn create_loglevel_filter() -> tracing_subscriber::filter::EnvFilter {
    std::env::set_var(
        "RUST_LOG",
        format!(
            "{},otel::tracing=trace,otel=debug",
            std::env::var("RUST_LOG")
                .or_else(|_| std::env::var("OTEL_LOG_LEVEL"))
                .unwrap_or_else(|_| "info".to_string())
        ),
    );
    EnvFilter::from_default_env()
}
