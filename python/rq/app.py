from random import choice, random
from time import sleep

from redis import Redis
from rq import Queue

from jobs import jobs


redis_conn = Redis()
q = Queue(connection=redis_conn)

# print(job.result)


# import time; time.sleep(2)
# print(job.result)

while True:
    job, rargs = choice(jobs)
    args = rargs()

    print(f'submitting job {job.__name__} with args {args}')
    job = q.enqueue(job, *args)
    # job.delay(*args)
    sleep(random())
