# OpenTracing/django

This example demonstrates usage of the Datadog opentracer when used with the
Django OpenTracing library `django_opentracing`.

This example simply configures the example provided in the repository (https://github.com/opentracing-contrib/python-django)
to use a Datadog opentracer.


# Install/Setup

```sh
$ virtualenv env
$ . ./env/bin/activate
$ pip install -r requirements.txt
```

# Running

```sh
$ python manage.py runserver 8000
```
