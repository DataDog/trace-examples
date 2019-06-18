# patch before importing tornado
from ddtrace import Pin, patch_all, tracer
patch_all(tornado=True, redis=True)

import os
import time
import random

import redis

import tornado.httpserver
import tornado.httpclient

from tornado.gen import coroutine, sleep as gen_sleep
from tornado.concurrent import run_on_executor
from tornado.web import (
    Application, RequestHandler, RedirectHandler, StaticFileHandler,
)

from concurrent.futures import ThreadPoolExecutor


# env vars for deploying purpose
PORT = int(os.getenv('APP_PORT', '8000'))
BASE_DIR = os.path.dirname(os.path.realpath(__file__))
STATIC_DIR = os.path.join(BASE_DIR, 'statics')
url = os.getenv('REDIS_URL', 'redis://localhost:6379')
client = redis.StrictRedis.from_url(url, db=0)
Pin.override(client, service='trace-examples-tornado-redis')


# trace a synchronous function
@tracer.wrap('tornado.unit_counter')
def count_unit():
    # this call is automatically traced
    client.incr('tornado:unit_counter')


class MainHandler(RequestHandler):
    @coroutine
    def get(self):
        yield gen_sleep(0.05)
        yield self.delayed_work()

        # external interaction that is automatically traced
        count_unit()
        self.write('OK')

    # trace a coroutine
    @tracer.wrap()
    @coroutine
    def delayed_work(self):
        # fake some yielding
        yield gen_sleep(0.15)


class BrokenHandler(RequestHandler):
    @coroutine
    def get(self):
        initial_value = random.randint(0, 1000)
        yield gen_sleep(0.01)

        # trace more work
        with tracer.trace('tornado.broken_queue', span_type='http') as span:
            span.set_tag('tornado.initial_value', initial_value)
            yield gen_sleep(0.015)

            # but something bad happen
            raise Exception('Ouch!')


class ExecutorHandler(RequestHandler):
    executor = ThreadPoolExecutor(max_workers=10)

    @tracer.wrap('tornado.workload_1')
    @run_on_executor
    def workload_1(self):
        time.sleep(0.5)
        return 'workload_1'

    @run_on_executor
    @tracer.wrap('tornado.workload_2')
    def workload_2(self):
        time.sleep(0.5)
        return 'workload_2'

    @tracer.wrap('tornado.do_access')
    @run_on_executor
    def do_access(self):
        time.sleep(0.5)
        count_unit()
        return 'redis_access'

    @run_on_executor
    def do_sleep(self):
        with tracer.trace('sleep'):
            time.sleep(random.uniform(0.1, 0.5))
            return 'sleeping'

    @coroutine
    def get(self):
        yield gen_sleep(0.5)

        result_1 = yield self.workload_1()
        result_2 = yield self.workload_2()
        result_3 = yield self.do_access()

        results = []
        results.append(result_1)
        results.append(result_2)
        results.append(result_3)

        # This span will have the right parent and duration
        with tracer.trace('tornado.executors.sleeps'):
            sleeps = [self.do_sleep() for i in range(8)]
            with tracer.trace('number_crunching'):
                results.append(42 * 42)
            sleeps = yield sleeps
            results += sleeps

        self.write('Results: {}'.format(results))


def make_app(settings={}):
    return Application([
        (r'/count/', MainHandler),
        (r'/broken/', BrokenHandler),
        (r'/executor/', ExecutorHandler),
        (r'/redirect/', RedirectHandler, {'url': '/count/'}),
        (r'/statics/(.*)', StaticFileHandler, {'path': STATIC_DIR}),
    ], **settings)


if __name__ == "__main__":
    settings = {
        'datadog_trace': {
            'default_service': 'trace-examples-tornado-web',
            'distributed_tracing': True,
        },
    }
    app = make_app(settings=settings)
    app.listen(PORT)
    print('-- Starting the server --')
    tornado.ioloop.IOLoop.current().start()
