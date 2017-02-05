import redis
import tornado.web
import tornado.httpserver
import tornado.httpclient

from ddtrace import Pin, patch
from ddtrace.contrib.tornado import tracer
from ddtrace.contrib.tornado.middlewares import TraceMiddleware


# patch redis
patch(redis=True, tracer=tracer)
client = redis.StrictRedis(host="localhost", port=6379, db=0)
Pin.override(client, service='redis-queue')


@tornado.gen.coroutine
def redis_coroutine():
    # these calls are automatically traced
    client.set('sync:key', 42)
    return client.get('sync:key')


class MainHandler(tornado.web.RequestHandler):
    @tornado.gen.coroutine
    def get(self):
        print("querying redis")
        value = yield redis_coroutine()
        print("done")
        self.write("Value: {}".format(value))

    @tornado.gen.coroutine
    def on_finish(self):
        # this call has been wrapped from our TraceMiddleware
        print("response gone")


def make_app():
    return tornado.web.Application([
        (r"/", MainHandler),
    ])


if __name__ == "__main__":
    app = make_app()
    http_server = app.listen(8000)
    TraceMiddleware(http_server, tracer, service='mytornado')
    tornado.ioloop.IOLoop.current().start()
