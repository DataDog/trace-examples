from django.http import HttpResponse
import logging

def index(request):

    print("View!")
    return HttpResponse("Hello!!!, world. You're at the polls index.")