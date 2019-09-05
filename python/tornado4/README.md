# Tornado example

Simple application that creates some traces using Tornado. It uses multiple
coroutines and synchronous functions with many custom and built-in handlers.
It also serves static files and has access to a local Redis cache.

## Compatibility stack

* Python 2.7
* Tornado 4.3

## Backing services

* Redis 3.2

## Getting started

Launch all backing services using `docker-compose`, and then execute the
application:

    docker-compose up -d
    python server.py

## Test application

Run one of the following commands and confirm traces on [https://app.datadoghq.com/apm/traces]:

    curl -i localhost:8000/count/
    curl -i localhost:8000/broken/
    curl -i localhost:8000/executor/
    curl -i localhost:8000/redirect/
    curl -i localhost:8000/statics/
    
To confirm traces for concurrent requests:

    ab -n 100 -c 4 localhost:8000/executor/
