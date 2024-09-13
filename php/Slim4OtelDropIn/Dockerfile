# Extend the OpenTelemetry PHP base image
FROM ghcr.io/open-telemetry/opentelemetry-php/opentelemetry-php-base:8.1

# Install Datadog SDK
USER root
RUN curl -LO https://github.com/DataDog/dd-trace-php/releases/latest/download/datadog-setup.php
RUN apk add libgcc
RUN php datadog-setup.php --php-bin=all
