import datetime
from django.shortcuts import render_to_response
from django.http import HttpResponse
from django.views.decorators.cache import cache_page
from .models import Poll


def simple_view(request):
    now = datetime.datetime.now()
    html = "<html><body>It is now %s.</body></html>" % now
    return HttpResponse(html)


def templated_view(request):
    return render_to_response('some_template.html')


@cache_page(60 * 15)
def cached_view(request):
    print "Actually rendering the view (not using cached versions"
    return HttpResponse("Hey this is cache related")


def db_view(request):
    count = Poll.objects.count()
    return HttpResponse("Found %d entries in db." % count)


@cache_page(60 * 15)
def all_the_things(request):
    count = Poll.objects.count()
    print "Found entries: {}".format(count)
    return render_to_response('some_template.html')
