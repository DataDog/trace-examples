# frozen_string_literal: true

# config.ru
require 'opentelemetry/sdk'
require 'opentelemetry-instrumentation-all'
require 'opentelemetry-exporter-otlp'
require 'opentelemetry/exporter/jaeger'

require 'rack/protection'
require './app'

OpenTelemetry::SDK.configure do |c|
  c.service_name = "sandbox_test_ruby"
  c.use 'OpenTelemetry::Instrumentation::Rack'
  c.use 'OpenTelemetry::Instrumentation::Sinatra'
  c.use 'OpenTelemetry::Instrumentation::Faraday'
  c.use 'OpenTelemetry::Instrumentation::Redis'

  c.add_span_processor(
    OpenTelemetry::SDK::Trace::Export::BatchSpanProcessor.new(
      # for the jaeger exporter:
      exporter: OpenTelemetry::Exporter::Jaeger::CollectorExporter.new()
    )
    
  )
end

run Multivac
