import os
import redis
import random
import tornado.web
import tornado.httpserver
import tornado.httpclient

from ddtrace import Pin, patch, tracer
from ddtrace.contrib.tornado import trace_app


# env vars for deploying purpose
DATADOG_TRACER = os.getenv('DATADOG_TRACER', 'localhost')
PORT = int(os.getenv('APP_PORT', '8000'))
BASE_DIR = os.path.dirname(os.path.realpath(__file__))
STATIC_DIR = os.path.join(BASE_DIR, 'statics')

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


class MainHandler(tornado.web.RequestHandler):
    @tornado.gen.coroutine
    def get(self):
        yield self.delayed_work()

        # external interaction that is automatically traced
        count_unit()
        self.write('OK')

    # trace a coroutine
    @tracer.wrap()
    @tornado.gen.coroutine
    def delayed_work(self):
        # fake some yielding
        yield tornado.gen.sleep(0.15)


class BrokenHandler(tornado.web.RequestHandler):
    @tornado.gen.coroutine
    def get(self):
        initial_value = random.randint(0, 1000)
        yield tornado.gen.sleep(0.01)

        # trace more work
        with tracer.trace('tornado.broken_queue', span_type='http') as span:
            span.set_tag('tornado.initial_value', initial_value)
            yield tornado.gen.sleep(0.015)

            # but something bad happen
            raise Exception('Ouch!')


def make_app():
    return tornado.web.Application([
        (r'/count/', MainHandler),
        (r'/broken/', BrokenHandler),
    ])


if __name__ == "__main__":
    app = make_app()
    trace_app(app, tracer, service='tornado-website')
    app.listen(PORT)
    print('-- Starting the server --')
    tornado.ioloop.IOLoop.current().start()
