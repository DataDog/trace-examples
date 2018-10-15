import time
import random

from kombu import Connection

with Connection('amqp://guest:guest@127.0.0.1:5672//') as conn:
    simple_queue = conn.SimpleQueue('simple_queue')
    while True:
        message = simple_queue.get(block=True, timeout=1)
        message.ack()
        print('Received: {0}'.format(message.payload))

    simple_queue.close()
