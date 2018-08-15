require 'ddtrace'

Datadog.configure do |c|
  # Configure tracer settings
  c.tracer hostname: ENV['DATADOG_TRACER'] || 'datadog'

   # Activate and configure integrations
  c.use :rails,
        service_name: ENV['RAILS_SERVICE'] || 'rails-local',
        database_service: ENV['RAILS_MYSQL_SERVICE'] || 'db-local',
        cache_service: ENV['RAILS_CACHE_SERVICE'] || 'cache-local',
        controller_service: ENV['RAILS_CONTROLLER_SERVICE'] || 'controller-local'
  c.use :grape
  c.use :redis, service_name: 'redis-cache'
end
