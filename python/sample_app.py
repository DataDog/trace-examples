"""
a fake trace app
"""

import time
import logging
import sqlite3

from ddtrace.contrib.sqlite3 import connection_factory
from ddtrace.ext import http as httpx
from ddtrace.ext import sql as sqlx
from ddtrace.tracer import Tracer


s = 0.01
tracer = Tracer()
log = logging.getLogger("dd.trace-example")

factory = connection_factory(tracer)
db_conn = sqlite3.connect(":memory:", factory=factory)


def _init_database():
    db_cursor = db_conn.cursor()
    db_cursor.execute('''CREATE TABLE IF NOT EXISTS stocks
                 (date text, trans text, symbol text, qty real, price real)''')
    db_cursor.execute("DELETE FROM stocks")
    db_conn.commit()

def _handle_web_request():
    db_cursor = db_conn.cursor()
    query = '''INSERT INTO stocks VALUES ('2006-01-05','BUY','RHAT',100,35.14)'''
    db_cursor.execute(query)
    db_conn.commit()

    db_cursor = db_conn.cursor()
    query = "SELECT * FROM stocks WHERE symbol='RHAT'"
    db_cursor.execute(query)
    res=db_cursor.fetchall()

def _simulate_web_request():
    with tracer.trace("web_request", service="web_server", resource="/home") as span:
        span.set_tag("http.header.user_agent", "DDTrace/0.1")
        span.set_tag("org.user_name", "awang")
        _handle_web_request()
    print "finished span: %s" % span


def run():
    _init_database()
    while True:
        _simulate_web_request()
        time.sleep(s)
        log.info("doing it")


if __name__ == '__main__':
    run()
