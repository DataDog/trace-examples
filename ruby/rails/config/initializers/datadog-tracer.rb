require 'ddtrace'

# Tracing configuration
Rails.configuration.datadog_trace = {
  auto_instrument: true,
  auto_instrument_redis: true,
  default_service: ENV['RAILS_SERVICE'],
  default_database_service: ENV['RAILS_MYSQL_SERVICE'],
  default_cache_service: ENV['RAILS_CACHE_SERVICE'],
  trace_agent_hostname: ENV['DATADOG_TRACER'] || 'localhost'
}
