import time
import random

from django.http import HttpResponse, StreamingHttpResponse, FileResponse


def file_response(request):
    return FileResponse(open("manage.py", "rb"))


def get_resp():
    for i in range(0, 10):
        time.sleep(random.random() / 10)
        yield "%d\n" % i


def streaming_response(request):
    return StreamingHttpResponse(get_resp())
