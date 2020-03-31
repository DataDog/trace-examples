import random
import time

from celery import Celery, shared_task
from ddtrace import tracer


@tracer.wrap()
def consumer_work():
    time.sleep(random.uniform(0, 0.02))


@shared_task
def add(x, y):
    for _ in range(0, 5):
        consumer_work()
    return x + y


app = Celery("consumer", broker="redis://redis", backend="redis://redis")
