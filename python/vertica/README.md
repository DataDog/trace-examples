# vertica

This directory contains an example for the [vertica-python][verticadb] library

[verticadb]: https://github.com/vertica/vertica-python


## Install/Setup

To get an instance of `vertica` up and running run the following command:

```sh
$ docker-compose up -d
```

To install the Python dependencies into virtual environment:

```sh
$ virtualenv env
$ . ./env/bin/activate
$ pip install -r requirements.txt
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!


## Running

Run:

```sh
$ ddtrace-run python vertica_example.py
```
