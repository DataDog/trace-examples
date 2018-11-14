import time
import random

from ddtrace import tracer
from kombu import Connection


@tracer.wrap('process_message')
def process_message(message):
    time.sleep(random.uniform(0.001, 0.01))


with Connection('amqp://guest:guest@127.0.0.1:5672//') as conn:
    simple_queue = conn.SimpleQueue('simple_queue')
    while True:
        with tracer.trace('consume', service='consumer'):
            message = simple_queue.get(block=True, timeout=2)
            message.ack()
            process_message(message)
            print('Received: {0}'.format(message.payload))

    simple_queue.close()
