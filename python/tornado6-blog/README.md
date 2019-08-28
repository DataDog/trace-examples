# Tornado 6 blog example

Based on the [blog][1] demo from Tornado for creating a simple blog engine.
Tracing is enabled with the =ddtrace-run= command.

## Compatibility stack

* Python 3.7
* Tornado 6.0.3
* Aiopg 0.16.0

## Backing services

* Postgres 10.3
* Datadog Agent

## Getting started

Launch all services using `docker-compose` adding you Datadog API key:

    env DD_API_KEY=... docker-compose up --build
    
## Test application

1. Open [http://localhost:8888/] and create a user
2. Create new post
3. Go to main page
4. Go to archive page
5. Confirm traces on [https://app.datadoghq.com/apm/traces]

[1]: https://github.com/tornadoweb/tornado/tree/master/demos/blog
