# Tornado 6 example

Simple application that creates some traces using Tornado 6. It uses multiple
coroutines and synchronous functions with many custom and built-in handlers. It
also serves static files and has access to a local Redis cache.

## Compatibility stack

* Python 3.7
* Tornado 6.0

## Backing services

* Redis 5

## Getting started

Launch all backing services using `docker-compose`, and then execute the
application:

    docker-compose up -d
    env APP_PORT=8000 python server.py

## Test application

Run one of the following commands and confirm traces on [https://app.datadoghq.com/apm/traces]:

    curl -i localhost:8000/count/
    curl -i localhost:8000/broken/
    curl -i localhost:8000/executor/
    curl -i localhost:8000/redirect/
    curl -i localhost:8000/statics/
    
To confirm traces for concurrent requests:

    ab -n 100 -c 4 localhost:8000/executor/
