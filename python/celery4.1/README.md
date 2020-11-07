# celery4.1

## Install/Setup

```sh
$ docker-compose up -d
```

To install the Python dependencies into virtual environment:

```sh
$ pipenv install
$ pipenv shell
```

Run with:

```sh
$ DATADOG_TRACE_DEBUG=true ddtrace-run celery -A consumer worker --loglevel=info
```

and in a new shell/env:

```sh
$ ddtrace-run python producer.py
```

## Package Installs
To install `pipenv` and `pyenv` for Mac ([https://formulae.brew.sh/formula/pipenv/](https://formulae.brew.sh/formula/pipenv/)):

```sh
brew install pipenv 
brew install pyenv 
```

Linux commands can be found here: [https://pypi.org/project/pipenv/](https://pypi.org/project/pipenv/)
