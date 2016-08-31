"""
a fake trace app
"""

import time
import logging

from ddtrace.tracer import Tracer


s = 0.01
tracer = Tracer()
log = logging.getLogger("dd.trace-faker")


def _mix():
    with tracer.trace("cake.mix") as span:
        span.set_tag("tool", "whisk")
        time.sleep(s)

def _bake():
    with tracer.trace("cake.bake") as span:
        span.set_tag("tool", "oven")
        time.sleep(s)

def _make_cake():

    span = tracer.trace("cake.make", service="baker", resource="cake")
    try:
        _mix()
        _bake()
    finally:
        span.finish()
        print "finished span: %s" % span


def run():

    while True:
        _make_cake()
        log.info("doing it")



if __name__ == '__main__':
    run()
