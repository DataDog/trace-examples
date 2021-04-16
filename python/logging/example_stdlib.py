import logging
from ddtrace import tracer

# ddtrace-run uses logging.basicConfig, so this function will not do anything
# since the root handler will have been configured.
logging.basicConfig(level=logging.DEBUG, format='%(message)s')

# you can change the logging level for a logger
log = logging.getLogger('example')
log.setLevel(logging.DEBUG)

@tracer.wrap()
def hello():
    log.debug('Hello, World!')


hello()

# you can overwrite the default formatter as well
ch = logging.StreamHandler()
# formatter = logging.Formatter('[dd.trace_id=%(dd.trace_id)s dd.span_id=%(dd.span_id)s] %(message)s')
# ch.setFormatter(formatter)
log.addHandler(ch)
hello()
