# Datadog OpenTracing

This directory contains various examples demonstrating usage of the Datadog
OpenTracing-compatible tracer.


## Install/Setup

For each of the projects located under this directory run the following:

```sh
$ virtualenv env
$ . ./env/bin/activate
$ pip install -r requirements.txt
```

*Note:* you must have an agent running on your machine that will collect the
traces created by the tracer! See https://docs.datadoghq.com/agent/ for more
information about the agent. Make sure you install the *trace* agent!

## Examples

- walkthrough: steps through various OpenTracing features and how they correspond to
    Datadog features and appear on the Datadog platform
- laundry: an extremely simple example of how to trace doing your laundry
