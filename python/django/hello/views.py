from django.shortcuts import render
from django.http import HttpResponse
from django.views.generic import TemplateView

from .models import Greeting, explicit_query_error, explicit_query_pass

# Create your views here.

def index(request):

    greeting = Greeting()
    greeting.save()
    greetings = Greeting.objects.all()

    try:
        explicit_query_error()
    except Exception:
        pass

    explicit_query_pass()

    return render(request, 'index.html', {'greetings': greetings})

def error(request):
    raise Exception("it's a sin!")


class IndexView(TemplateView):
    template_name = "index.html"

    def get_context_data(self, **kwargs):
        return {'greetings':[]}

