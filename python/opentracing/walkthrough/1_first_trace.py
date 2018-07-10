import opentracing
import time
from ddtrace.opentracer import Tracer
from ddtrace.opentracer.tracer import set_global_tracer


# initialize our tracer
def init_dd_tracer():
    tracer = Tracer()
    set_global_tracer(tracer)


if __name__ == '__main__':
    init_dd_tracer()
    with opentracing.tracer.start_span('simple') as span:
        time.sleep(2)
