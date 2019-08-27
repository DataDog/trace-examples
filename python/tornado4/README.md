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
