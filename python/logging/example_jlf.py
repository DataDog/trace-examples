import logging
from ddtrace import tracer

import json_log_formatter

formatter = json_log_formatter.JSONFormatter()

json_handler = logging.StreamHandler()
json_handler.setFormatter(formatter)

logger = logging.getLogger()
logger.addHandler(json_handler)

@tracer.wrap()
def hello():
    logger.warn('Hello, World!')

hello()
