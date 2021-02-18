'use strict';

// ##### OPENTELEMETRY-JS CONFIG #######
const opentelemetry = require('@opentelemetry/api');
const { NodeTracerProvider } = require('@opentelemetry/node');
const { BatchSpanProcessor, ConsoleSpanExporter, SimpleSpanProcessor } = require('@opentelemetry/tracing');

// export to otel-collector
const { CollectorTraceExporter } = require('@opentelemetry/exporter-collector');

// // export to datadog-agent
// const { DatadogSpanProcessor, DatadogExporter, DatadogProbabilitySampler } = require('opentelemetry-exporter-datadog');

module.exports = (serviceName) => {

  // #### Example of how to configure specific plugins ####

  // const provider = new NodeTracerProvider(
  //   {
  //     plugins: {
  //       express: {
  //         enabled: true,
  //       },
  //       http: {
  //         enabled: true,
  //         // applyCustomAttributesOnSpan: (span, request, response) => {
  //         //   span.setAttribute('x-ratelimit-remaining', Math.floor(Math.random() * 100) );
  //         // }
  //       },
  //       mongodb: {
  //         enabled: true,
  //       }
  //     }
  //   }
  // );

  // enables all plugins
  const provider = new NodeTracerProvider();

  // export to otel-collector
  const exporter = new CollectorTraceExporter({
    serviceName: serviceName || 'sandbox_test_node',
    url: `http://${process.env.OTEL_EXPORTER_OTLP_HTTP_ENDPOINT}/v1/trace`,
  });

  provider.addSpanProcessor(new BatchSpanProcessor(exporter));

  // // export to datadog-agent instead
  // const exporterOptions = {
  //   serviceName: 'my-service-node', // optional
  //   agentUrl: 'http://localhost:8126', // optional
  //   tags: 'example_key:example_value,example_key_two:value_two', // optional
  //   env: 'testing', // optional
  //   version: 'v2' // optional
  // }
  // const exporter = new DatadogExporter(exporterOptions);
  // provider.addSpanProcessor(new DatadogSpanProcessor(exporter));

  //  Now, register the exporter.
  provider.register();

  return { tracer: opentelemetry.trace.getTracer('datadogExample'), opentelemetry: opentelemetry};
};

// ##### END OPENTELEMETRY-JS CONFIG #######
