from opentelemetry import trace

class CustomDatadogLogProcessor(object):
    def __call__(self, logger, method_name, event_dict):
        # An example of adding datadog formatted trace context to logs
        # from: https://github.com/open-telemetry/opentelemetry-python-contrib/blob/b53b9a012f76c4fc883c3c245fddc29142706d0d/exporter/opentelemetry-exporter-datadog/src/opentelemetry/exporter/datadog/propagator.py#L122-L129 
        current_span = trace.get_current_span()

        if current_span is not None:
            event_dict['dd.trace_id'] = str(current_span.context.trace_id & 0xFFFFFFFFFFFFFFFF)
            event_dict['dd.span_id'] = str(current_span.context.span_id)

        return event_dict        
