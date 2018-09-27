import datetime
from django.shortcuts import render_to_response
from django.http import HttpResponse
from django.views.decorators.cache import cache_page


def current_datetime(request):
    now = datetime.datetime.now()
    html = "<html><body>It is now %s.</body></html>" % now
    return HttpResponse(html)


def templated_view(request):
    return render_to_response('some_template.html')


@cache_page(60 * 15)
def cached_templated_view(request):
    print "Actually rendering the view (not using cached versions"
    return render_to_response('some_template.html')
