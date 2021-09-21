# rq

This directory contains examples for the [rq][rq] library

[rq]: https://github.com/rq/rq


## Install/Setup

These examples use an instance of redis that can up started using the
following command:


```sh
$ docker-compose up -d
```

To install the Python dependencies into virtual environment:

```sh
$ virtualenv --python=python3 .venv/
$ source .venv/bin/activate
$ pip install -r requirements.txt
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!


In a separate shell start the `rq` worker:

```sh
$ DD_SERVICE=worker ddtrace-run rq worker
```


Then start the app with:

```sh
$ DD_SERVICE=app ddtrace-run python app.py
```
