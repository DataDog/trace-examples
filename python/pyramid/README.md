# pyramid

This directory contains an example for the [pyramid][pyramid] web framework.

[pyramid]: https://github.com/Pylons/pyramid


## Install/Setup

To install the Python dependencies into virtual environment:

```sh
$ virtualenv --python=python2.7 env
$ . ./env/bin/activate
$ pip install -r requirements.txt
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!


## Running

Run:

```sh
$ ddtrace-run python tasks.py
```
