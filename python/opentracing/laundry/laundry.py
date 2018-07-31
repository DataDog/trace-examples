import opentracing
import random
import time
from ddtrace.opentracer import Tracer, set_global_tracer


def collect_laundry_from(area):
    print('  from %s' % area)
    num_items = random.uniform(0, 1) * 10
    with opentracing.tracer.start_active_span('collect_laundry_from') as scope:
        scope.span.set_tag('name', area)
        scope.span.set_tag('num_items', int(num_items))
        time.sleep(num_items/10)
    return int(num_items)


def collect_laundry():
    print('collecting laundry:')
    with opentracing.tracer.start_active_span('collect_all_laundry') as scope:
        areas = ['bedroom', 'washroom', 'floor']
        # provide some context on the areas
        scope.span.set_tag('areas', ','.join(areas))

        num_laundry = 0
        for area in areas:
            num_laundry += collect_laundry_from(area)
        return num_laundry


def bring_laundry_to_washer(num_laundry):
    print('bringing laundry to washer')
    with opentracing.tracer.start_active_span('bring_laundry_to_washer') as scope:
        scope.span.set_tag('info', 'laundry is heavy')
        time.sleep(num_laundry * 0.1)


def wash(num_laundry):
    print('washing')
    if num_laundry > 8:
        cycle = 'heavy'
    elif num_laundry > 3:
        cycle = 'medium'
    else:
        cycle = 'light'

    with opentracing.tracer.start_active_span('washing') as scope:
        scope.span.set_tag('cycle_type', cycle)
        time.sleep(num_laundry * 0.2)


def dry(num_laundry):
    print('drying')
    with opentracing.tracer.start_active_span('drying'):
        time.sleep(num_laundry * 0.2)


def fold_item(item, difficulty):
    print('  %s' % item)

    with opentracing.tracer.start_active_span('fold') as scope:
        scope.span.set_tag('item', item)
        time.sleep(difficulty * 0.3)


def fold(num_laundry):
    print('folding:')

    # item name and difficulty to fold
    items = [('shirt', 4), ('pants', 2), ('towel', 2), ('socks', 1)]

    with opentracing.tracer.start_active_span('fold_all'):
        for i in range(0, num_laundry):
            item = random.choice(items)
            fold_item(item[0], item[1])


def do_laundry():
    with opentracing.tracer.start_active_span('laundry') as scope:
        # baggage tags will be copied down to all children of this span
        scope.span.set_baggage_item('laundry_run', (random.uniform(0, 1)*100))
        # set some metadata giving us some context about the event
        scope.span.set_tag('date', time.strftime('%d/%m/%Y'))
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
