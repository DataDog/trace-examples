require 'ddtrace'

# Tracing configuration
Rails.configuration.datadog_trace = {
  auto_instrument: true,
  auto_instrument_redis: true,
  default_service: ENV['RAILS_SERVICE'] || 'rails-local',
  default_database_service: ENV['RAILS_MYSQL_SERVICE'] || 'db-local',
  default_cache_service: ENV['RAILS_CACHE_SERVICE'] || 'cache-local',
  trace_agent_hostname: ENV['DATADOG_TRACER'] || 'localhost'
}
