# bottle

This directory contains an example for the [bottle][bottle] library

[bottle]: http://bottlepy.org/docs/dev/


## Install/Setup

To install the Python dependencies into virtual environment:

```sh
$ virtualenv --python=python3 env
$ . ./env/bin/activate
$ pip install -r requirements.txt
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!


## Running

Run:

```sh
$ ddtrace-run python bottle_example.py
```
