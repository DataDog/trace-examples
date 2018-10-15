# kombu

This directory contains examples for the [kombu][kombu] library

[kombu]: https://github.com/celery/kombu


## Install/Setup

These examples use an instance of rabbit that can up started using the
following command:


```sh
$ docker-compose up -d
```

To install the Python dependencies into virtual environment:

```sh
$ pipenv install
$ pipenv sh
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!


## Examples

### Autoinstrumented Example

Run with:

```sh
$ DATADOG_TRACE_DEBUG=true ddtrace-run python producer.py
```

and in a new shell/env:

```sh
$ DATADOG_TRACE_DEBUG=true ddtrace-run python consumer.py
```

