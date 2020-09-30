import os
import sys
import time

from cyclone.httpclient import fetch
import cyclone.web
from twisted.internet import reactor, defer
from twisted.python import log
from twisted.enterprise import adbapi
import txredisapi as redis

from ddtrace import tracer


MYSQL_CONFIG = {
    "host": "127.0.0.1",
    "port": int(os.getenv("TEST_MYSQL_PORT", 3306)),
    "user": os.getenv("TEST_MYSQL_USER", "test"),
    "password": os.getenv("TEST_MYSQL_PASSWORD", "test"),
    "database": os.getenv("TEST_MYSQL_DATABASE", "test"),
}

REDIS_CONFIG = {
    'port': int(os.getenv('TEST_REDIS_PORT', 6379)),
}


class MainHandler(cyclone.web.RequestHandler):
    def get(self):
        self.write("Hello, world")

class RedisMixin(object):
    rc = None

    @classmethod
    def setup(self):
        # read settings from a conf file or something...
        RedisMixin.rc = redis.lazyConnection(port=REDIS_CONFIG["port"])

class RedisHandler(cyclone.web.RequestHandler, RedisMixin):
    @defer.inlineCallbacks
    def get(self):
        # rc = yield redis.Connection(port=REDIS_CONFIG["port"])
        yield self.rc.set("foo", "bar")
        v = yield self.rc.get("foo")
        self.write(v)
        self.finish()


class MySQLMixin(object):
    mysql = adbapi.ConnectionPool("MySQLdb", **MYSQL_CONFIG)


class RPCHandler(cyclone.web.RequestHandler):
    def get_template_path(self):
        return os.path.dirname(os.path.realpath(__file__))

    @defer.inlineCallbacks
    def get(self):
        from twisted.web.client import getPage

        r = yield getPage("http://localhost:8889/dbinline")
        r = yield getPage("http://localhost:8889/redis")
        self.set_status(200)
        # items = ["Item 1", "Item 2", "Item 3"]
        items = ["Item %s" % i for i in range(10000)]
        self.render("hello.html", title="My title", items=items)


class DBInlineHandler(cyclone.web.RequestHandler, MySQLMixin):
    @defer.inlineCallbacks
    def get(self):
        rs1 = yield self.mysql.runQuery("SELECT 1")
        rs2 = yield self.mysql.runQuery("SELECT 2")
        self.write("Processed")
        self.finish()


class DBHandler(cyclone.web.RequestHandler, MySQLMixin):
    @cyclone.web.asynchronous
    def get(self):
        d = self.mysql.runQuery("SELECT 1")
        d.addCallback(self.on_data)

        # d = self.mysql.runQuery("SELECT 1")
        # d.addCallback(self.on_data)

    def on_data(self, data):
        with tracer.trace("process_db_data"):
            time.sleep(0.0005)
        self.write("Processed")
        self.finish()


class TemplateHandler(cyclone.web.RequestHandler):
    def get_template_path(self):
        return os.path.dirname(os.path.realpath(__file__))

    @cyclone.web.asynchronous
    def get(self):
        items = ["Item 1", "Item 2", "Item 3"]
        self.render("hello.html", title="My title", items=items)


class PostsHandler(cyclone.web.RequestHandler):
    def get_template_path(self):
        return os.path.dirname(os.path.realpath(__file__))

    @cyclone.web.asynchronous
    def get(self):
        posts = [
            ("Michael Scott", "My mind is going a mile an hour"),
            ("Kevin Malone", "Why waste time say lot word when few word do trick"),
            (
                "Darryl Philbin",
                "I decided to stay home, eat a bunch of tacos in my basement. Now my basement smells like tacos. You can't air out a basement. And taco air is heavy. It settles at the lowest point",
            ),
        ]
        self.render("posts.html", posts=posts)


class ErrorHandler(cyclone.web.RequestHandler):
    @cyclone.web.asynchronous
    def get(self):
        raise Exception("uhoh")


class PostModule(cyclone.web.UIModule):
    def render(self, title, body):
        return self.render_string("post.html", title=title, body=body)


if __name__ == "__main__":
    application = cyclone.web.Application(
        [
            (r"/", MainHandler),
            (r"/db", DBHandler),
            (r"/dbinline", DBInlineHandler),
            (r"/template", TemplateHandler),
            (r"/error", ErrorHandler),
            (r"/posts", PostsHandler),
            (r"/rpc", RPCHandler),
            (r"/redis", RedisHandler),
        ],
        ui_modules={"Post": PostModule},
    )
    RedisMixin.setup()

    log.startLogging(sys.stdout)
    reactor.listenTCP(
        int(os.getenv("PORT") or 8888), application, interface="127.0.0.1"
    )
    reactor.run()
