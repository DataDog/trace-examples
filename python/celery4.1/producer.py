import time
import random

from ddtrace import tracer

from consumer import add
# from demoapp.tasks import add

while True:
    add.delay(4, 4)
    time.sleep(0.2)
