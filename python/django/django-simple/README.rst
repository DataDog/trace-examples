ddtrace-django
==============

ddtrace-django

.. image:: https://img.shields.io/badge/built%20with-Cookiecutter%20Django-ff69b4.svg?logo=cookiecutter
     :target: https://github.com/pydanny/cookiecutter-django/
     :alt: Built with Cookiecutter Django
.. image:: https://img.shields.io/badge/code%20style-black-000000.svg
     :target: https://github.com/ambv/black
     :alt: Black code style

:License: Apache Software License 2.0

Running
-------

* Create a virtual environment and activate::

    $ python -m venv venv
    $ source venv/bin/activate

* Install Python dependencies::

    $ pip install -r requirements/production.txt

* Set up environment variables::

    $ export DJANGO_ALLOWED_HOSTS=127.0.0.1
    $ export DJANGO_SECRET_KEY=vNIF0IeM1IN9YAOOB8yEmNddSvnnXxx7E7D3TwWsdno
    $ export DATABASE_URL=sqlite:///django.db
    $ export USE_DOCKER=false

* Run db migration::

    $ python manage.py migrate

* Run gunicorn server::

    $ gunicorn config.wsgi
