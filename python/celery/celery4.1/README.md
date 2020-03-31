# celery4.1

## Install/Setup

```sh
$ docker-compose up -d
```

To install the Python dependencies into virtual environment:

```sh
$ pipenv install
$ pipenv sh
```

Run with:

```sh
$ DATADOG_TRACE_DEBUG=true ddtrace-run celery -A consumer worker --loglevel=info
```

and in a new shell/env:

```sh
$ ddtrace-run python producer.py
```

