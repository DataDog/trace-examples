from ddtrace import tracer
from ddtrace.helpers import get_correlation_ids

import structlog

def tracer_injection(logger, log_method, event_dict):
    # get correlation ids from current tracer context
    trace_id, span_id = get_correlation_ids()

    # if no trace present, set ids to 0
    trace_id = 0 if not trace_id else trace_id
    span_id = 0 if not span_id else span_id

    # add ids to structlog event dictionary
    event_dict['trace_id'] = trace_id
    event_dict['span_id'] = span_id

    return event_dict

structlog.configure(
    processors=[
        tracer_injection,
        structlog.processors.JSONRenderer()
    ]
)
log = structlog.get_logger()

@tracer.wrap()
def traced_func():
    log.warn('In tracer context')

traced_func()

# Check handling if no tracer contect present
log.warn('No tracer context')
