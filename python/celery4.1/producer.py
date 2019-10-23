import time
import random

from celery import shared_task
from ddtrace import tracer

from consumer import add


while True:
    add.delay(4, 4)
    time.sleep(0.2)
