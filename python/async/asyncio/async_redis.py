import asyncio
import ddtrace
import redis

from ddtrace import Pin, patch
from ddtrace.async.tracer import AsyncTracer
from ddtrace.async.helpers import ensure_future


# async tracer
# TODO: this will not be the public API
tracer = AsyncTracer()
ddtrace.tracer = tracer

# patch redis
patch(redis=True)
client = redis.StrictRedis(host="localhost", port=6379, db=0)
Pin.override(client, service='redis-queue')


async def more_work_same_coroutine():
    with tracer.trace("async.work"):
        print("doing more work")
        await asyncio.sleep(1)
        # not async-friendly access
        # TODO: replace that with a run_in_executor() call
        value = client.get('sync:key')
        print("finished async.work with value {}".format(value))


async def handle_request():
    with tracer.trace("async.handler", service="http"):
        print("-- handling the request")
        # not async-friendly access
        client.set('sync:key', 42)

        # continue the job on this coroutine
        await more_work_same_coroutine()
        print("-- request handled")


# main loop
loop = asyncio.get_event_loop()

try:
    # execute the main coroutine
    loop.run_until_complete(handle_request())

    # wait for all tasks to finished (delayed_job())
    pending = asyncio.Task.all_tasks()
    loop.run_until_complete(asyncio.gather(*pending))
finally:
    loop.close()
