from random import choice, random
from time import sleep

from ddtrace import tracer
from redis import Redis
from rq import Queue

from jobs import jobs


redis_conn = Redis()
q = Queue(connection=redis_conn)

while True:
    job, rargs = choice(jobs)
    args = rargs()

    with tracer.trace('main') as span:
        print(f'submitting job {job.__name__} with args {args}')
        job = q.enqueue(job, *args, job_id=job.__name__)
        with tracer.trace('meantime'):
            sleep(0.1)
        # print(job.get_status())
        with tracer.trace('handle results'):
            q.fetch_job(job.id)

    sleep(0.5)
