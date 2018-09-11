# OpenTracing/django

This example demonstrates usage of the Datadog opentracer when used with the
Django OpenTracing library `django_opentracing`.

This example simply configures the example provided in the
repository
(https://github.com/opentracing-contrib/python-django/tree/master/example)
to use a Datadog opentracer.


##  Install/Setup

```sh
$ virtualenv env
$ . ./env/bin/activate
$ pip install -r requirements.txt
```

##  Running

```sh
$ python manage.py runserver 8000
```

## Usage

### http://localhost:8000/client/simple

Navigating to this URL will have the client query the server. This will
generate a trace on the client and a trace on the server.

Refer to the original project for what else you should expect to be traced:
https://github.com/opentracing-contrib/python-django.


## Adding Additional Datadog tracing

To use the built-in Datadog tracing in addition to the tracing provided by
`django_opentracing`, simply prefix the run command with `ddtrace-run`:

```sh
$ ddtrace-run python manage.py runserver 8000
```

This will provide more information about the request

### Notes

Since the Datadog tracing is, by default, disabled when running in debug mode,
we manually enable it in `settings.py`:

```python
DATADOG_TRACE = {
    'ENABLED': True,
}
```
