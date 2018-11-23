import os

import pylibmc


MEMCACHED_CONFIG = {
    'host': os.getenv('TEST_MEMCACHED_HOST', '127.0.0.1'),
    'port': int(os.getenv("TEST_MEMCACHED_PORT", 11211)),
}


mc = pylibmc.Client([MEMCACHED_CONFIG['host']])

mc.set('a', 1)
mc.get('a')
