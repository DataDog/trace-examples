from starlette.applications import Starlette
from starlette.responses import JSONResponse
from starlette.routing import Route
from ddtrace.contrib.asgi import TraceMiddleware # import the middleware


async def homepage(request):
    return JSONResponse({'hello': 'world'})

routes = [
    Route("/", endpoint=homepage)
]

app = Starlette(debug=True, routes=routes)

# wrap the app for tracing
app = TraceMiddleware(app)
