# Python sample apps

Here are some apps to simplify the process of debugging issues with the tracer, or just to play around with it.

## Agent

In order to collect traces, you can spin up an agent.

In order to provide the datadog api key, an environment variable `DATADOG_API_KEY` has to be set. You can use your
`.bashrc` or `.bash_profile` files.

From this folder:
```
docker-compose up -d agent
```

## Django sample app

The django sample app is a super simple app as per the django getting started tutorial.

While you can certainly run the app through the `manage.py` script, you can also use a Apache2+WSGI container which
replicated a common deployment scenario for our apps.

```
docker-compose up django2-apache2
```

Note that both the error log and the access log are redirected to the std out for debug purposes.

## Django 1.4 sample app

The django sample app is a super simple app as per the django getting started tutorial.

At the moment a WSGI image is not provided to run this image.

Note that you may need a virtual env with python2.7 installed. Once you have it you will find a requirements.txt file in the app folder.


