import time
import random

from celery import Celery
from celery.exceptions import Retry
from ddtrace import tracer

app = Celery('app', broker='redis://127.0.0.1:6379/0')


@app.task(autoretry_for=(Exception,), retry_kwargs=dict(max_retries=5, interval_start=0, interval_step=0.5))
def add(x, y):
    with tracer.trace('sleep'):
        time.sleep(1)

    if random.random() > 0.0001:
        raise Exception()

    return x + y


add.default_retry_delay = 5


if __name__ == '__main__':
    add.delay(5, 10)
