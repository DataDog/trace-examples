require 'ddtrace'

# Tracing configuration
Rails.configuration.datadog_trace = {
  auto_instrument: true,
  auto_instrument_redis: true,
  auto_instrument_grape: true,
  default_service: ENV['RAILS_SERVICE'] || 'rails-local',
  default_database_service: ENV['RAILS_MYSQL_SERVICE'] || 'db-local',
  default_cache_service: ENV['RAILS_CACHE_SERVICE'] || 'cache-local',
  default_controller_service: ENV['RAILS_CONTROLLER_SERVICE'] || 'controller-local',
  trace_agent_hostname: ENV['DATADOG_TRACER'] || 'datadog'
}

Datadog::Monkey.patch_module(:dalli)
pin = Datadog::Pin.get_from(::Dalli)
pin.service = 'dalli-cache'
