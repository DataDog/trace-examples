# asyncio example

Simple application that creates some traces using `asyncio`. It uses
multiple coroutines to handle concurrent calls, while doing synchronous
operations with Redis, just to test that the API is backward compatible.

## Compatibility stack

* Python 3.6.0
* `asyncio` (built-in library in Python 3.4+)

## Backing services

* Redis 3.2

## Getting started

Launch backing services:

    docker-compose up -d

Launch the application

    python http_async_server.py
