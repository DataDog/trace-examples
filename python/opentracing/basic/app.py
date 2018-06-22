import time
import random
import opentracing
from ddtrace.opentracer import Tracer
from ddtrace.opentracer.tracer import set_global_tracer



def collect_laundry_from(area):
    print('  from %s' % area)
    num_items = random.uniform(0, 1) * 10
    with opentracing.tracer.start_span('collect_laundry_from') as span:
        span.set_tag('name', area)
        span.set_tag('num_items', int(num_items))
        time.sleep(num_items/10)
    return int(num_items)


def collect_laundry():
    print('collecting laundry:')
    with opentracing.tracer.start_span('collect_all_laundry') as span:
        areas = ['bedroom', 'washroom', 'floor']
        # provide some context on the areas
        span.set_tag('areas', ','.join(areas))

        num_laundry = 0
        for area in areas:
            num_laundry += collect_laundry_from(area)
        return num_laundry


def bring_laundry_to_washer(num_laundry):
    print('bringing laundry to washer')
    with opentracing.tracer.start_span('bring_laundry_to_washer') as span:
        span.set_tag('info', 'laundry is heavy')
        time.sleep(num_laundry * 0.1)


def wash(num_laundry):
    print('washing')
    if num_laundry > 8:
        cycle = 'heavy'
    elif num_laundry > 3:
        cycle = 'medium'
    else:
        cycle = 'light'

    with opentracing.tracer.start_span('washing') as span:
        span.set_tag('cycle_type', cycle)
        time.sleep(num_laundry * 0.2)


def dry(num_laundry):
    print('drying')
    with opentracing.tracer.start_span('drying'):
        time.sleep(num_laundry * 0.2)


def fold_item(item, difficulty):
    print('  %s' % item)

    with opentracing.tracer.start_span('fold') as span:
        span.set_tag('item', item)
        time.sleep(difficulty * 0.3)


def fold(num_laundry):
    print('folding:')

    # item name and difficulty to fold
    items = [('shirt', 4), ('pants', 2), ('towel', 2), ('socks', 1)]

    with opentracing.tracer.start_span('fold_all'):
        for i in range(0, num_laundry):
            item = random.choice(items)
            fold_item(item[0], item[1])


def do_laundry():
    with opentracing.tracer.start_span('laundry') as span:
        # set some metadata giving us some context about the event
        span.set_tag('date', time.strftime('%d/%m/%Y'))
        num = collect_laundry()
        bring_laundry_to_washer(num)
        wash(num)
        dry(num)
        fold(num)
    print('done!')


# initialize our tracer
def init_dd_tracer():
    tracer = Tracer('laundry', config={})
    set_global_tracer(tracer)


if __name__ == '__main__':
    init_dd_tracer()
    do_laundry()
