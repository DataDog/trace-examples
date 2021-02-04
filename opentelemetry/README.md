## OpenTelemetry Datadog Sandbox

This Repository allows users to deploy a sandbox distributed tracing application instrumented with OpenTelemetry SDKs, which emit traces to OpenTelemetry Collectors, and export Telemetry data to Datadog via the Datadog Exporter.

To learn more about OpenTelemetry, please review the [Datadog Documentation](https://docs.datadoghq.com/tracing/setup_overview/open_standards/#opentelemetry-collector-datadog-exporter)

### Docker-Compose

This test environment is useful for testing Docker-specific behavior of the exporter.
It defines a standalone collector setup within a docker network, and generates traffic at a rate of 1 request per second for 15 minutes.

1. Replace <YOUR_API_KEY> with your API key in `otel-collector-config.yml` 
2. `$ docker-compose build`
3. `$ docker-compose up`

### Kubernetes

This test environment is useful for testing Kubernetes-specific behavior of the exporter.
It defines an agent-collector setup within Kubernetes which deployes opentelemetry collectors as a daemonset, which forwards to a standalone opentelemetry-collector service. It generates trafffic at a rate of 1 request per second for 15 minutes.

To use it you need to [install `minikube`](https://minikube.sigs.k8s.io/docs/start/).

1. Replace <YOUR_API_KEY> with your API key in `k8s-collector.yml` manifest.
2. `$ sh ./build.sh`
3. `$ sh ./run.sh`

To make individual requests to the sandbox:

1. After starting the cluster, create a tunnell to the `python-microservice` service:
  
  ```
  minikube service python-microservice --url
  ```

2. visit the url returned by the command above in your browser or via `curl`:

