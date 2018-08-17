require 'ddtrace'

Datadog.configure do |c|
  # Configure tracer settings
  c.tracer hostname: ENV['DATADOG_TRACER'] || 'datadog'

  # Activate and configure integrations
  c.use :rails, service_name: ENV['RAILS_SERVICE'] || 'example-rails-mongoid'
  c.use :mongo, service_name: ENV['MONGO_SERVICE'] || 'example-mongodb'
end
