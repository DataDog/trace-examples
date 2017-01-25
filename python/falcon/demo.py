
import logging
import sys
import falcon

from ddtrace import tracer
from ddtrace.contrib.falcon import TraceMiddleware





logging.basicConfig(stream=sys.stdout, level=logging.DEBUG)
tracer.debug_logging = True


class Resource200(object):

    BODY = "yaasss"
    ROUTE = "/200"

    def on_get(self, req, resp, **kwargs):

        # throw a handled exception here to ensure our use of
        # set_traceback doesn't affect 200s
        try:
            1/0
        except Exception:
            pass

        resp.status = falcon.HTTP_200
        resp.body = self.BODY


class Resource500(object):

    BODY = "noo"
    ROUTE = "/500"

    def on_get(self, req, resp, **kwargs):
        resp.status = falcon.HTTP_500
        resp.body = self.BODY


class ResourceExc(object):

    ROUTE = "/exc"

    def on_get(self, req, resp, **kwargs):
        raise Exception("argh")



# run the thing
trace_middleware = TraceMiddleware(tracer, 'my-falcon-app')
app = falcon.API(middleware=[trace_middleware])


resources = [
    Resource200,
    Resource500,
    ResourceExc,
]

for r in resources:
    app.add_route(r.ROUTE, r())

