# Rust OpenTelemetry Datadog Example

This is an example on how to use [OpenTelemetry from rust][1] and send traces to Datadog via OTLP using either the [Datadog Agent OTLP ingest][2] or the [OpenTelemetry Collector Datadog Exporter][3]. The example assumes Docker Compose together with Docker Desktop or Docker Engine with support for [BuildKit][4].

## The Example Application

The example application is a simple [rust][4] application using the [axum][5] web framework and [reqwest][6] web client to receive and send HTTP requests. It will be built automatically in a Docker container at first use, so there is no need to install any rust development tools.

The web application listens on port `8080` on `localhost` and serves two paths:

- `http://localhost:8080/hello/{name}`
  - This path will reply with a small hello message JSON response
- `http://localhost:8080/forwarding/{name}`
  - This path will forward the request using an HTTP client call to the `/hello/{name}` path and return the reply. A simple example of a _distributed_ trace.

## Running the Example

The example is split into three different flavors and uses _profiles_ in Docker to start the relevant VMs, they are:

- **rust-app-ddagent**: rust application exporting to Datadog via [Datadog Agent OTLP ingest][2]
- **rust-app-ddotelexporter**: rust application exporting to Datadog via [OpenTelemetry Collector Datadog Exporter][3]
- **rust-app-ddotelexportertransformed**: rust application exporting to Datadog via [OpenTelemetry Collector Datadog Exporter][3] while also transforming the Datadog resource name

### Docker Compose

To start the [rust][4] example application and the [Datadog Agent][2] or [OpenTelemetry Collector Datadog Exporter][3], you use `docker compose`. The Docker files also expect there to be an environment variable named `DATADOG_API_KEY` that should contain your [Datadog API Key][7].

Here is the command line for starting the `rust-app-ddagent` example:

```shell
DATADOG_API_KEY="<api key here>" docker compose --profile rust-app-ddagent up
```

### Sending Requests

Now it's possible to send requests to the web application on port `8080`:

```shell
curl "http://localhost:8080/forwarding/world"
```

### Seeing the Traces in the UI

You should now be able to see the traces in the [Datadog UI][8]. Here is an example:
![example spans](https://github.com/user-attachments/assets/68550670-5e3a-4bf0-8fe3-587c44add166)
![example graph](https://github.com/user-attachments/assets/f922674f-67e1-4a15-b1df-688ef7295e08)


[1]: https://opentelemetry.io/docs/languages/rust/
[2]: https://docs.datadoghq.com/opentelemetry/interoperability/otlp_ingest_in_the_agent/
[3]: https://docs.datadoghq.com/opentelemetry/collector_exporter/
[4]: https://www.rust-lang.org/
[5]: https://github.com/tokio-rs/axum?tab=readme-ov-file#axum
[6]: https://github.com/seanmonstar/reqwest?tab=readme-ov-file#reqwest
[7]: https://docs.datadoghq.com/account_management/api-app-keys/
[8]: https://app.datadoghq.com/apm/traces?query=env%3Adev&sort=time&spanType=all&storage=hot&view=spans&paused=false
