from ddtrace import tracer

import logging
import structlog

structlog.configure(
    logger_factory=structlog.stdlib.LoggerFactory(),
)

log = structlog.get_logger()

@tracer.wrap()
def hello():
    log.warn('Hello, World!')

hello()
