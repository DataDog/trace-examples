import time
import random

from celery import Celery, shared_task
from ddtrace import tracer


@shared_task
def add(x, y):
    return x + y


app = Celery('consumer', broker='redis://localhost')
app.autodiscover_tasks()
