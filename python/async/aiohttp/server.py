import os
import asyncio

from aiohttp import web

from ddtrace.async import tracer
from ddtrace.async.aio import TraceMiddleware


BASE_DIR = os.path.dirname(os.path.realpath(__file__))
STATIC_DIR = os.path.join(BASE_DIR, 'statics')


async def handle(request):
    # basic handling
    name = request.match_info.get("name", "Anonymous")
    text = "Hello {}".format(name)

    # trigger some async work
    await some_work(request)

    # close the request
    return web.Response(text=text)


async def some_work(request):
    # simulating a slow operation that would yield something;
    # this action is traced
    with tracer.trace('async.work'):
        await asyncio.sleep(2)

# your application
app = web.Application()
app.router.add_get("/", handle)
app.router.add_get("/{name}", handle)
app.router.add_static('/statics', STATIC_DIR)

# Trace it!
TraceMiddleware(app, tracer, service='async-web')

web.run_app(app)
