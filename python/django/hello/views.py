from django.shortcuts import render
from django.views.generic import ListView

from .models import Greeting, explicit_query_error, explicit_query_pass


def index_page(request):
    Greeting.objects.create()
    greetings = Greeting.objects.all()

    try:
        explicit_query_error()
    except Exception:
        pass

    explicit_query_pass()

    return render(request, 'index.html', {'greetings': greetings})


def error_page(request):
    raise Exception('Something goes wrong!')


class GreetingsView(ListView):
    model = Greeting
    template_name = 'index.html'
    context_object_name = 'greetings'
