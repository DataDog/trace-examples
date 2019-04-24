# Tracing the Django Tutorial 

Example web application based on:

- https://docs.djangoproject.com/en/2.2/intro/tutorial01/
- https://docs.djangoproject.com/en/2.2/intro/tutorial02/
- https://docs.djangoproject.com/en/2.2/intro/tutorial03/
- https://docs.djangoproject.com/en/2.2/intro/tutorial04/
- https://docs.djangoproject.com/en/2.2/intro/tutorial07/

Datadog APM is enabled following [the documentation](http://pypi.datadoghq.com/trace/docs/web_integrations.html#django):

``` python
# mysite/mysite/settings.py

INSTALLED_APPS = [
    ...
    'ddtrace.contrib.django',
]

DATADOG_TRACE = {
    'DEFAULT_SERVICE': 'django-tutorial-docker',
    'TAGS': {'env': 'dev'},
    'AGENT_HOSTNAME': 'agent',
    'ENABLED': True,
    'DEBUG': True,
}
```

## Setup

Start the Docker containers and web server:

``` sh
DD_API_KEY=... docker-compose up --build -d
```

Run migration scripts to set up the database:

``` sh
docker-compose exec app python manage.py makemigrations
docker-compose exec app python manage.py migrate
```

Create an admin user:

```
docker-compose exec app python manage.py createsuperuser
# Enter username, email, password
```
