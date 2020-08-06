import time

from consumer import add


while True:
    add.delay(4, 4)
    time.sleep(0.2)
