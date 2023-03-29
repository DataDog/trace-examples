import random
import ddtrace
import time

STARTED = time.ctime(time.time())

print("Pythonfib started!!!")


def fib(n):
    if n <= 1:
        return n
    else:
        return fib(n - 1) + fib(n - 2)


while True:
    lst = [i for i in range(35, 41)]
    n = random.choice(lst)
    print("fib({}) = {} (Running since {})".format(n, fib(n), STARTED))
