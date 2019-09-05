# patch before importing tornado
from ddtrace import Pin, patch, tracer
patch(tornado=True)

import asyncio
import os
import time
import random

import redis

import tornado.httpserver
import tornado.httpclient

from tornado.concurrent import run_on_executor
from tornado.web import (
    Application, RequestHandler, RedirectHandler, StaticFileHandler,
)

from concurrent.futures import ThreadPoolExecutor


# env vars for deploying purpose
DATADOG_TRACER = os.getenv('DATADOG_TRACER', 'localhost')
PORT = int(os.getenv('APP_PORT', '8000'))
BASE_DIR = os.path.dirname(os.path.realpath(__file__))
STATIC_DIR = os.path.join(BASE_DIR, 'statics')

# configure the tracer
tracer.configure(hostname=DATADOG_TRACER)

# patch redis with Pin
patch(redis=True)
url = os.getenv('REDIS_URL', 'redis://localhost:6379')
client = redis.StrictRedis.from_url(url, db=0)
Pin.override(client, service='tornado-redis')


# trace a synchronous function
@tracer.wrap('tornado.unit_counter')
def count_unit():
    # this call is automatically traced
    client.incr('tornado:unit_counter')


class MainHandler(RequestHandler):
    async def get(self):
        await asyncio.sleep(0.05)
        await self.delayed_work()

        # external interaction that is automatically traced
        count_unit()
        self.write('OK')

    # trace a coroutine
    @tracer.wrap()
    async def delayed_work(self):
        # fake some yielding
        await asyncio.sleep(0.15)


class BrokenHandler(RequestHandler):
    async def get(self):
        initial_value = random.randint(0, 1000)
        await asyncio.sleep(0.01)

        # trace more work
        with tracer.trace('tornado.broken_queue', span_type='http') as span:
            span.set_tag('tornado.initial_value', initial_value)
            await asyncio.sleep(0.015)

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

    async def get(self):
        await asyncio.sleep(0.5)

        result_1 = await self.workload_1()
        result_2 = await self.workload_2()
        result_3 = await self.do_access()

        results = []
        results.append(result_1)
        results.append(result_2)
        results.append(result_3)

        # This span will have the right parent and duration
        with tracer.trace('tornado.executors.sleeps'):
            sleeps = [self.do_sleep() for i in range(8)]
            with tracer.trace('number_crunching'):
                results.append(42 * 42)
            sleeps = await asyncio.gather(*sleeps)
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
            'default_service': 'tornado-website',
        }
    }
    app = make_app(settings=settings)
    app.listen(PORT)
    print('-- Starting the server --')
    tornado.ioloop.IOLoop.current().start()
