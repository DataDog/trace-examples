"""
An example of how the Datadog python tracing lib can interact
with code.
"""
# stdlib
import logging
import random
import time

# 3p
import sqlite3

# tracer
from ddtrace import tracer
from ddtrace.contrib.sqlite3 import connection_factory
from ddtrace.ext import net as netx


logging.basicConfig()
db = None


def init_database():
    cursor = db.cursor()
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS stocks
        (date text, trans text, symbol text, qty real, price real)
    """)
    cursor.execute("DELETE FROM stocks")
    db.commit()


# the decorator will set the span name to the name of your func
@tracer.wrap(name='request', service='sample-app', resource='db_calls')
def trace_db_calls():
    print("trying trace_db_calls")
    # this will create a first span for that query
    cursor = db.cursor()
    query = "INSERT INTO stocks VALUES ('2006-01-05', 'BUY', 'RHAT', 100, 35.14)"
    cursor.execute(query)
    db.commit()

    # a new span will be created for that other call
    cursor = db.cursor()
    cursor.execute("SELECT * FROM stocks WHERE symbol=?", ('RHAT',))
    return "returning result (%s)" % str(cursor.fetchone())


@tracer.wrap(name='request', service='sample-app', resource='ctx_manager')
def trace_ctx_manager():
    print("trying trace_ctx_manager")
    # some complicated work
    def fibo(n):
        from math import sqrt
        return ((1+sqrt(5))**n-(1-sqrt(5))**n)/(2**n*sqrt(5))

    # Use the context manager to create a span from a block code easily
    with tracer.trace("compute.fibo") as span:
        result = fibo(100)

        # set metadata about your span anytime
        span.set_tag("fibo.compute_method", "golden ratio")
        span.set_tag("fibo.result", result)

        return "returning result (%d)" % result


@tracer.wrap(name='request', service='sample-app', resource='rpc')
def trace_rpc():
    print("trying trace_rpc")
    # to allow tracing across apps/hosts you need to pass
    # as metadata to your remote call
    # * the trace ID (and the other side needs to set it as its trace ID as well)
    # * the current span ID (and the other side needs to set it as its parent ID)

    span = tracer.current_span()

    # use the ext modules to get "standard" metadata keys
    span.set_tag(netx.TARGET_HOST, "remote.server.datadoghq.com")
    span.set_tag(netx.TARGET_PORT, 4242)

    return rpc_response("hello", trace_id=span.trace_id, parent_id=span.span_id)


def rpc_response(request, trace_id=None, parent_id=None):
    """ would live typically on another host"""
    with tracer.trace('rpc.server') as span:
        if trace_id and parent_id:
            span.trace_id = trace_id
            span.parent_id = parent_id

        return "returning result (%s)" % request


def run():
    # use an integration to trace by default all of our DB calls
    traced_factory = connection_factory(tracer, service="master-db")
    global db
    db = sqlite3.connect(":memory:", factory=traced_factory)

    init_database()

    funcs = [trace_db_calls, trace_ctx_manager, trace_rpc]

    try:
        while True:
            print(random.choice(funcs)())
            time.sleep(1)
    except KeyboardInterrupt:
        print "exiting"


if __name__ == '__main__':
    run()
