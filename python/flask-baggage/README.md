# Flask Baggage Example App

The goal of this `Flask` Baggage example app is to create a simple app to show usages of the Baggage API.

The best way to get started is to clone this repo and run:

```bash
cd trace-examples/python/flask-baggage/
pipenv install

pipenv run python app.py
```

From there, your terminal will open two Flask apps on ports 5000 and 5001 and then send a request to the app on port 5000 to show Baggage being propagated to the app on port 5001.

Afterward, you may cancel the app or make requests to the individual apps to test out baggage propagation.
