import pymemcache
from ddtrace import Pin, patch

patch(pymemcache=True)

MEM_HOST = "localhost"  # host of the memcached server
MEM_PORT = 11211  # port of the memcached server


KEY = "counter"
INITIAL_VALUE = 0
INCR_AMOUNT = 1


def get_count(client):
    val = client.get(KEY)
    val = int(val) if val else None
    return val or INITIAL_VALUE


def store_count(client, count):
    client.set(KEY, count)


def increment_count(count):
    return count + INCR_AMOUNT


def run_app(client):
    count = get_count(client)
    count = increment_count(count)
    store_count(client, count)
    print("current count {}".format(count))


if __name__ == "__main__":
    client = pymemcache.client.base.Client((MEM_HOST, MEM_PORT))

    # configure the client with some custom metadata
    Pin.override(client, service="memcached")

    run_app(client)
