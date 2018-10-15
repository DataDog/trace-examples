import datetime
import time
import random

from ddtrace import tracer
from kombu import Connection


@tracer.wrap('create_message')
def create_message():
    message = 'helloworld, sent at {0}'.format(datetime.datetime.today())
    time.sleep(random.uniform(0.001, 0.003))
    return message


with Connection('amqp://guest:guest@127.0.0.1:5672//') as conn:
    simple_queue = conn.SimpleQueue('simple_queue')
    while True:
        with tracer.trace('produce', service='producer'):
            message = create_message()
            simple_queue.put(message)
            print('Sent: {0}'.format(message))
        time.sleep(1)
    simple_queue.close()
