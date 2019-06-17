# Tornado example

Simple application that creates some traces using Tornado. It uses multiple
coroutines and synchronous functions with many custom and built-in handlers.
It also serves static files and has access to a local Redis cache.

## Getting started

Launch services using `docker-compose`:

    env DD_API_KEY=... docker-compose up --build -d

Requests to these urls will generate traces:

    $ curl http://localhost:8000/count/
    $ curl http://localhost:8000/broken/
    $ curl http://localhost:8000/executor/
