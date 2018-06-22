# OpenTracing/basic example

## Install/Setup

### Dependencies

install the dependencies run:
```sh
  $ virtualenv env
  $ . ./env/bin/activate
  $ pip install -r requirements.txt
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!

## Running

Simply run:

```sh
  $ python app.py
```

## Explanation

This example provides a simple example of how to use Datadog's opentracer to
trace your applications with Datadog.

### Tracing


### What it should look like

