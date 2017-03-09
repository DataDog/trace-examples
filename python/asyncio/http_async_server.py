import os
import asyncio
import ddtrace
import redis

from ddtrace import tracer, Pin, patch
from ddtrace.contrib.asyncio import context_provider, helpers


DATADOG_TRACER = os.getenv('DATADOG_TRACER', 'localhost')
tracer.configure(hostname=DATADOG_TRACER, context_provider=context_provider)


# patch redis
patch(redis=True)
url = os.getenv('REDIS_URL', 'redis://localhost:6379')
client = redis.StrictRedis.from_url(url, db=0)
Pin.override(client, service='asyncio-redis-queue')


async def get_redis_value():
    with tracer.trace('async.cache'):
        # we may not block here
        await asyncio.sleep(0.01)
        value = client.get('sync:key')
        return client.set('sync:key', 42)


async def delayed_job(parent_span):
    with tracer.start_child_span('async.worker', parent_span, service='asyncio-workers') as span:
        await asyncio.sleep(3)


async def handle_request(reader, writer):
    # trace something
    with tracer.trace('async.handler', service='asyncio-web') as span:
        # do something
        await asyncio.sleep(0.02)
        # in the meantime do something else
        value = await get_redis_value()
        # do something that will be conclude in the future
        future = helpers.ensure_future(delayed_job(tracer.current_span()))

    # response
    start_response(writer)
    writer.write(b'OK\r\n')
    writer.close()
    await future
    print('200: request handled')


def start_response(writer, content_type=b'text/html'):
    writer.write(b'HTTP/1.0 200 NA\r\n')
    writer.write(b'Content-Type: ')
    writer.write(content_type)
    writer.write(b'\r\n\r\n')


hostname = os.getenv('APP_HOSTNAME', '127.0.0.1')
port = int(os.getenv('APP_PORT', '8000'))

loop = asyncio.get_event_loop()
try:
    loop.create_task(asyncio.start_server(handle_request, hostname, port))
    print('-- Starting the server --')
    loop.run_forever()
finally:
    loop.close()
    print('-- Server closed --')
