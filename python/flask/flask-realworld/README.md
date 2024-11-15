# Flask Example App

The goal of this [Flask]() example app is to create a simple app which utilizes as many features
of Flask as possible so that multiple variable code flows can be tested.

The best way to get started is to clone this repo and run:

```bash
cd trace-examples/python/flask/
pipenv install

pipenv run python run.py

# OR

pipenv run ddtrace-run python run.py
```

Then open your browser to http://127.0.0.1:5000/

The main index page of this application lists all the available endpoints for testing
