# pymemcache

This directory contains an example for the [pymemcache][pymemcache] library

[pymemcache]: https://github.com/pinterest/pymemcache


## Install/Setup

To get an instance of `memcached` up and running run the following command:

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
$ python simple_manual.py
```


## Examples

### Simple Manual

Simple manual is an application which increments a value stored in a `memcached`
instance.

