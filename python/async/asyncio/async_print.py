import asyncio

from ddtrace.contrib.asyncio import tracer
from ddtrace.contrib.asyncio.helpers import ensure_future


async def delayed_job():
    with tracer.trace("async.delayed", service="workers"):
        print("running delayed job")
        await asyncio.sleep(1)
        print("delayed job done")


async def more_work_same_coroutine():
    with tracer.trace("async.work"):
        print("doing more work")
        await asyncio.sleep(1)
        print("finished async.work")


async def handle_request():
    with tracer.trace("async.handler", service="http"):
        print("-- handling the request")
        await asyncio.sleep(1)

        # continue the job on this coroutine
        await more_work_same_coroutine()

        # delegating some work after
        print("scheduling a future job")
        ensure_future(delayed_job())

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
