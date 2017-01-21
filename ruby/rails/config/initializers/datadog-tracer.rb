require 'ddtrace'

# Tracing configuration
Rails.configuration.datadog_trace = {
  auto_instrument: true,
  auto_instrument_redis: true,
  default_service: 'rails3-demo',
  default_database_service: 'mysql',
  trace_agent_hostname: ENV['DATADOG_TRACER'] || 'localhost'
}
