# Rails instrumentation
Rails.configuration.datadog_trace = {
  auto_instrument: true,
  default_service: 'example-rails-mongoid'
}

# Mongoid instrumentation with a custom service name
Datadog::Monkey.patch_module(:mongo)
pin = Datadog::Pin.get_from(Mongoid::Clients.default)
pin.service = 'example-mongodb'
