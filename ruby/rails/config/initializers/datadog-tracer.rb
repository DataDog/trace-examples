require 'ddtrace'

# Tracing configuration
Rails.configuration.datadog_trace = {
  auto_instrument: Rails.env.production? || Rails.env.staging?,
  auto_instrument_redis: Rails.env.production? || Rails.env.staging?,
  default_service: 'rails3-demo',
  default_database_service: 'mysql',
  trace_agent_hostname: ENV['DATADOG_TRACER'] || 'localhost'
}
