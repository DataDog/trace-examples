import opentracing
import time


if __name__ == '__main__':
    with opentracing.tracer.start_span('simple'):
        time.sleep(2)
