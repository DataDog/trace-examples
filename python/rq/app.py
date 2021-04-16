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

    with tracer.trace('main', service='rq-app') as span:
        print(f'submitting job {job.__name__} with args {args}')
        job = q.enqueue(job, *args, job_id=job.__name__)
        with tracer.trace('meantime', service='rq-app'):
            sleep(0.25)
        # print(job.get_status())
        with tracer.trace('handle results', service='rq-app'):
            q.fetch_job(job.id)
        sleep(random())
