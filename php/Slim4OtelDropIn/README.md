# Distributed Tracing Example
This example uses `docker-compose`, and illustrates the distributed tracing functionality of OpenTelemetry with Datadog's Drop In support enabled. An HTTP request to service-one will make multiple asynchronous HTTP requests, each of which is injected with a `traceparent` header, it includes examples of adding Attributes, Links and Events to spans.

All trace data is sent through the [Datadog Agent Endpoint](https://docs.datadoghq.com/containers/docker/apm/?tab=linux#docker-network) when `DD_TRACE_ENABLED` is `true` otherwise is sent using the [Datadog Agent OTLP Ingestion]([https://docs.datadoghq.com/opentelemetry/interoperability/otlp_ingest_in_the_agent/?tab=host](https://docs.datadoghq.com/opentelemetry/interoperability/otlp_ingest_in_the_agent/?tab=docker#enabling-otlp-ingestion-on-the-datadog-agent)), where they are forwarded to Datadog after you set your `DD_API_KEY` in the `docker-compose.yml` file.

The example is presented as a [slim framework](https://www.slimframework.com/) single-file application for simplicity, and uses Guzzle as an HTTP client. The same application source is used for all services.

## Running the example
```bash
$ docker build -t php-service-with-dd-tracer .
$ docker-compose run service-one composer install
$ docker-compose up
# in a separate terminal
$ curl localhost:8000/users/otel
```
Update the `&otel-and-dd-common` section at the top of the `docker-compose.yml` to play around and match the screenshots below.

## Screenshots
### Opentelemetry
`DD_TRACE_ENABLED: false & DD_TRACE_OTEL_ENABLED: false`
![Opentelemetry](screenshots/distributed-otel-trace.png?raw=true "DD_TRACE_ENABLED: false & DD_TRACE_OTEL_ENABLED: false")

### Datadog
`DD_TRACE_ENABLED: true & DD_TRACE_OTEL_ENABLED: false`
![Datadog](screenshots/distributed-dd-trace.png?raw=true "DD_TRACE_ENABLED: true & DD_TRACE_OTEL_ENABLED: false")

### Opentelemetry + Datadog
`DD_TRACE_ENABLED: true & DD_TRACE_OTEL_ENABLED: true`
![Opentelemetry + Datadog](screenshots/distributed-otel-dd-trace.png?raw=true "DD_TRACE_ENABLED: true & DD_TRACE_OTEL_ENABLED: true")

## Notes
* A guzzle middleware is responsible for wrapping each outgoing HTTP request in a span with [http-based attributes](https://github.com/open-telemetry/opentelemetry-specification/blob/main/specification/trace/semantic_conventions/http.md), and injecting `traceparent` (and optionally `tracestate`) headers.
* A slim middleware is responsible for starting the root span, using the route pattern for the span name due to its low cardinality (see https://github.com/open-telemetry/opentelemetry-specification/blob/main/specification/trace/api.md#span). This is also where incoming trace headers are managed.
