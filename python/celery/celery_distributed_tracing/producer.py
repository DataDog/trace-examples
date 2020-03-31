import random
import time

from ddtrace import tracer

from consumer import add


@tracer.wrap()
def prod_work():
    time.sleep(random.uniform(0, 0.05))


while True:
    with tracer.trace("myapp.request"):
        # Send off the task
        result = add.apply_async((4, 4))

        # Do some work in the meantime
        for _ in range(3):
            prod_work()

        # Get the result
        result.get()

        # Do some more work
        for _ in range(2):
            prod_work()

    time.sleep(0.5)
