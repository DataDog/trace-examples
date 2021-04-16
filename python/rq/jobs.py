import random
import time


def collatz(n):
    assert n > 0
    while n != 1:
        if n % 2 == 0:
            n /= 2
        else:
            n = 3 * n + 1
    return True


def rng_collatz():
    # generates random arguments for the collatz job
    return [int(random.uniform(0, 2**64))]


def stall(n):
    time.sleep(n)
    return n


def rng_stall():
    return [random.uniform(0, 0.02)]


def maybe_fail(n):
    return 10 / n;


def rng_maybe_fail():
    return [random.choice([0, 1])]


jobs = [
    (collatz, rng_collatz),
    (stall, rng_stall),
    (maybe_fail, rng_maybe_fail),
]
