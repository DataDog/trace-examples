Sample app for grpc client integration

# Install requirements

```
pip install -r requirements.txt
```

# Launching server

The server is just a really basic server replying 'Hello!' to an incoming request.

To launch it:
```
python server_grpc.py
```

This is a pre-requisite to be able to launch the sample client

# Launching demo

The sample client shows how to use Datadog integration and sends a basic request to the server we just launched. It also displays the response to show that it works.

To run it:
```
python demo_grpc.py
```
