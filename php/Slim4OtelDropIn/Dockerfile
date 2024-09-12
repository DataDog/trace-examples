# Extend the OpenTelemetry PHP base image
FROM ghcr.io/open-telemetry/opentelemetry-php/opentelemetry-php-base:8.1

USER root

# Set the DD_TRACE_VERSION to the latest stable version
ENV DD_TRACE_VERSION=1.3.0

# Install Datadog SDK
ADD https://github.com/DataDog/dd-trace-php/releases/download/${DD_TRACE_VERSION}/datadog-php-tracer_${DD_TRACE_VERSION}_aarch64.apk /tmp/datadog-php-tracer.apk
RUN apk add --allow-untrusted /tmp/datadog-php-tracer.apk

# Clean up
RUN rm /tmp/datadog-php-tracer.apk && apk cache clean
