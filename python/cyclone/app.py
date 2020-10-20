import os
import sys
import time

from cyclone.httpclient import fetch
import cyclone.web
import six
from twisted.enterprise import adbapi
from twisted.internet import reactor, defer
from twisted.python import log
from twisted.web.client import getPage, Agent
from twisted.web.http_headers import Headers
import txredisapi as redis

from ddtrace import tracer


agent = Agent(reactor)

MYSQL_CONFIG = {
    "host": "127.0.0.1",
    "port": int(os.getenv("TEST_MYSQL_PORT", 3306)),
    "user": os.getenv("TEST_MYSQL_USER", "test"),
    "password": os.getenv("TEST_MYSQL_PASSWORD", "test"),
    "database": os.getenv("TEST_MYSQL_DATABASE", "test"),
}

REDIS_CONFIG = {
    "port": int(os.getenv("TEST_REDIS_PORT", 6379)),
}


def my_request():
    conn = six.moves.http_client.HTTPConnection("127.0.0.1:8889")
    conn.request("GET", "/")
    res = conn.getresponse()
    conn.close()
    return res.read()


class AsyncHandler(cyclone.web.RequestHandler):
    def get(self):
        d = defer.Deferred()
        reactor.callLater(10, my_request)
        self.write("Im done.\n")
        self.finish()


class SyncHandler(cyclone.web.RequestHandler):
    def get(self):
        my_request()
        self.write("Im done.\n")
        self.finish()


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


class SafeMySQLdbConnection(adbapi.Connection):
    """SafeMySQLdbConnection will wrap calls to `cursor` to create a Cursor that
    supports SafeSQL queries.

    This class is specific to Connection's wrapping MySQLdb connections because
    MySQLdb's cursor API is a small expansansion on DBAPI's cursor definition so
    it may behave fail for a different driver that didn't support `cursorclass`.
    """

    def cursor(self, cursorclass=None):
        # MySQLdb.Connection can support multiple Cursor classes, therefore we
        # need to wrap the expected Cursor class with our SafeSQLCursor wrapper
        # so we can support SafeSQL queries to DBAPI methods.

        # This is how MySQLdb.Connect.cursor determines the correct Cursor class
        # to use. We need to wrap this class.
        cls = self.cursorclass if cursorclass is None else cursorclass

        class SafeSQLCursor(cls):
            """A Cursor that supports SafeSQL queries.

            It largely defers to the provided `cursorclass` except for execute
            and executemany calls, which we augment to support SafeSQL objects.
            """

            def execute(self, query, args=None):
                # if isinstance(query, SafeSQL):
                #    query = query._SafeSQL__unsafe_query
                return super(SafeSQLCursor, self).execute(query, args)

            def executemany(self, query, args):
                # if isinstance(query, SafeSQL):
                #    query = query._SafeSQL__unsafe_query
                return super(SafeSQLCursor, self).executemany(query, args)

        # Return the wrapped Cursor object for this connection.
        return SafeSQLCursor(self)


class ReconnectingConnectionPool(adbapi.ConnectionPool, object):
    connectionFactory = SafeMySQLdbConnection


class MySQLMixin(object):
    # mysql = adbapi.ConnectionPool("MySQLdb", **MYSQL_CONFIG)
    mysql = ReconnectingConnectionPool("MySQLdb", **MYSQL_CONFIG)


class RPCInlineHandler(cyclone.web.RequestHandler):
    def get_template_path(self):
        return os.path.dirname(os.path.realpath(__file__))

    @defer.inlineCallbacks
    def get(self):
        r = yield getPage("http://localhost:8889/dbinline")
        r = yield getPage("http://localhost:8889/redis")
        self.set_status(200)
        # items = ["Item 1", "Item 2", "Item 3"]
        items = ["Item %s" % i for i in range(10000)]
        self.render("hello.html", title="My title", items=items)


class RPCHandler(cyclone.web.RequestHandler):
    def get_template_path(self):
        return os.path.dirname(os.path.realpath(__file__))

    def complete(self, res):
        items = ["Item %s" % i for i in range(1000)]
        self.render("hello.html", title="My title", items=items)

    @cyclone.web.asynchronous
    def get(self):
        conn = six.moves.http_client.HTTPConnection("localhost", 8889)
        conn.request("GET", "/redis")
        res = conn.getresponse()
        conn.close()
        d2 = getPage("http://localhost:8889/redis")
        d1 = getPage("http://localhost:8889/dbinline").addCallback(self.complete)


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


class PostsHandler(cyclone.web.RequestHandler, MySQLMixin):
    def get_template_path(self):
        return os.path.dirname(os.path.realpath(__file__))

    def on_data(self, _):
        posts = [
            ("Michael Scott", "My mind is going a mile an hour"),
            ("Kevin Malone", "Why waste time say lot word when few word do trick"),
            (
                "Darryl Philbin",
                "I decided to stay home, eat a bunch of tacos in my basement. Now my basement smells like tacos. You can't air out a basement. And taco air is heavy. It settles at the lowest point",
            ),
        ]
        self.render("posts.html", posts=posts)

    @cyclone.web.asynchronous
    def get(self):
        d = self.mysql.runQuery("SELECT 1")
        d.addCallback(self.on_data)


class ErrorHandler(cyclone.web.RequestHandler):
    @cyclone.web.asynchronous
    def get(self):
        raise Exception("uhoh")


class WebErrorHandler(cyclone.web.RequestHandler):
    def on_error(self, err):
        self.write("expected")
        self.set_status(500)
        self.finish()

    def on_success(self, _):
        self.write("unexpected")
        self.finish()

    @cyclone.web.asynchronous
    def get(self):
        getPage("http://localhost:").addCallbacks(self.on_success, self.on_error)


class AgentHandler(cyclone.web.RequestHandler):
    def done(self, _):
        self.write("complete")
        self.finish()

    @cyclone.web.asynchronous
    def get(self):
        d = agent.request(
            b"GET",
            b"http://localhost:8889/",
            Headers({"User-Agent": ["Twisted Web Client Example"]}),
            None,
        )
        d.addCallbacks(self.done)


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
            (r"/web-error", WebErrorHandler),
            (r"/posts", PostsHandler),
            (r"/rpc", RPCHandler),
            (r"/rpcinline", RPCInlineHandler),
            (r"/redis", RedisHandler),
            (r"/async-handler", AsyncHandler),
            (r"/sync-handler", SyncHandler),
            (r"/agent", AgentHandler),
        ],
        ui_modules={"Post": PostModule},
    )
    RedisMixin.setup()

    log.startLogging(sys.stdout)
    reactor.listenTCP(
        int(os.getenv("PORT") or 8888), application, interface="127.0.0.1"
    )
    reactor.run()
