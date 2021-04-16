from ddtrace import tracer
from ddtrace.helpers import get_correlation_ids

import structlog


def tracer_injection(logger, log_method, event_dict):
    # get correlation ids from current tracer context
    trace_id, span_id = get_correlation_ids()

    # add ids to structlog event dictionary
    # if no trace present, set ids to 0
    event_dict['dd.trace_id'] = trace_id or 0
    event_dict['dd.span_id'] = span_id or 0

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


# Check handling of traced function that writes to log
traced_func()

# Check handling if no tracer contect present
log.warn('No tracer context')
