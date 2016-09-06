"""
a fake trace app
"""

import time
import logging
import sqlite3

from ddtrace.tracer import Tracer
from ddtrace.ext import http as httpx
from ddtrace.ext import sql as sqlx

db_conn = sqlite3.connect('sample_app.db')
db_cursor = db_conn.cursor()
db_cursor.execute('''CREATE TABLE IF NOT EXISTS stocks
             (date text, trans text, symbol text, qty real, price real)''')
db_cursor.execute("DELETE FROM stocks")
db_conn.commit()

s = 0.01
tracer = Tracer()
log = logging.getLogger("dd.trace-example")


def _handle_web_request():
    with tracer.trace("SQL", span_type=sqlx.TYPE) as child_span:
        db_cursor = db_conn.cursor()
        query = '''INSERT INTO stocks VALUES ('2006-01-05','BUY','RHAT',100,35.14)'''
        child_span.set_tag('sql.query',query)
        db_cursor.execute(query)
        db_conn.commit()
    with tracer.trace("SELECT stocks", span_type=sqlx.TYPE) as child_span:
        db_cursor = db_conn.cursor()
        query = "SELECT * FROM stocks WHERE symbol='RHAT'"
        child_span.set_tag('sql.query',query)
        db_cursor.execute(query)
        res=db_cursor.fetchall()
    with tracer.trace("sleep_span", span_type=httpx.TYPE) as child_span:
        child_span.set_tag('sleep_duration',s)
        time.sleep(s)

def _simulate_web_request():
    span = tracer.trace("web_request", service="web_server", resource="/home")
    span.set_tag("http.header.user_agent", "DDTrace/0.1")
    span.set_tag("org.user_name", "awang")
    try:
        _handle_web_request()
    finally:
        span.finish()
        print "finished span: %s" % span


def run():
    while True:
        _simulate_web_request()
        log.info("doing it")


if __name__ == '__main__':
    run()
