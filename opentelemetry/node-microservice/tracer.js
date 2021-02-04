'use strict';

// ##### OPENTELEMETRY-JS CONFIG #######
const opentelemetry = require('@opentelemetry/api');
const { NodeTracerProvider } = require('@opentelemetry/node');
const { BatchSpanProcessor, ConsoleSpanExporter, SimpleSpanProcessor } = require('@opentelemetry/tracing');
const { CollectorTraceExporter } = require('@opentelemetry/exporter-collector');

module.exports = (serviceName) => {
  // const provider = new NodeTracerProvider(
  //   {
  //     plugins: {
  //       http: {
  //         enabled: false,
  //         path: '@opentelemetry/plugin-http',
  //         // applyCustomAttributesOnSpan: (span, request, response) => {
  //         //   span.setAttribute('x-ratelimit-remaining', response.headers['x-ratelimit-remaining']);
  //         // }
  //       },
  //       express: {
  //         enabled: true,
  //         path: "@opentelemetry/plugin-express",
  //       },
  //       mongodb: {
  //         enabled: true,
  //         path: "@opentelemetry/plugin-mongodb",
  //       }
  //     }
  //   }
  // );

  const provider = new NodeTracerProvider();

  const exporter = new CollectorTraceExporter({
    serviceName: serviceName || 'sandbox_test_node',
    url: `http://${process.env.OTEL_EXPORTER_OTLP_HTTP_ENDPOINT}/v1/trace`,
  });

  provider.addSpanProcessor(new BatchSpanProcessor(exporter));
  provider.register();

  return opentelemetry.trace.getTracer('datadogExample');
};

// ##### END OPENTELEMETRY-JS CONFIG #######


// // ##### SXF-JS CONFIG #######
// // init() invocation must occur before importing any traced library (e.g. Express)
// const tracer = require('signalfx-tracing').init({
//   // Service name, also configurable via
//   // SIGNALFX_SERVICE_NAME environment variable
//   service: 'community-day-demo',
//   // Smart Agent or Gateway endpoint, also configurable via
//   // SIGNALFX_ENDPOINT_URL environment variable
//   url: 'http://otel-collector:9411/v1/trace', // http://localhost:9080/v1/trace by default
//   // Optional organization access token, also configurable via
//   // SIGNALFX_ACCESS_TOKEN environment variable
  
//   // Optional environment tag
//   // tags: {environment: 'myEnvironment'}
// })


// // ##### END SFX-JS CONFIG #######