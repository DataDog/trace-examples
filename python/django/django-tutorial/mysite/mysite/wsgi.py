"""
WSGI config for mysite project.

It exposes the WSGI callable as a module-level variable named ``application``.

For more information on this file, see
https://docs.djangoproject.com/en/2.2/howto/deployment/wsgi/
"""

import os

from django.core.wsgi import get_wsgi_application
from django.utils.functional import SimpleLazyObject


os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'mysite.settings')

application = get_wsgi_application()

# Warm-up the django application instead of letting it lazy-load
application(
    {
        "REQUEST_METHOD": "GET",
        "SERVER_NAME": SimpleLazyObject(lambda: "localhost"),
        "REMOTE_ADDR": "127.0.0.1",
        "SERVER_PORT": 80,
        "PATH_INFO": "/polls/",
        "wsgi.input": b"",
        "wsgi.multiprocess": True,
    },
    lambda x, y: None,
)
