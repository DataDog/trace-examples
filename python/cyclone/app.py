import os
import sys

import cyclone.web

from twisted.internet import reactor
from twisted.python import log

from ddtrace import tracer


class MainHandler(cyclone.web.RequestHandler):
    def get(self):
        self.write("Hello, world")


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
            (r"/template", TemplateHandler),
            (r"/error", ErrorHandler),
            (r"/posts", PostsHandler),
        ],
        ui_modules={"Post": PostModule},
    )

    log.startLogging(sys.stdout)
    reactor.listenTCP(8888, application, interface="127.0.0.1")
    reactor.run()
