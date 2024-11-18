import requests
import threading
from flask import Flask, request, jsonify
from ddtrace import tracer
from ddtrace.propagation.http import HTTPPropagator

# Initialize the first server
app1 = Flask(__name__)
app2 = Flask(__name__)

@app1.route('/send', methods=['POST'])
def send_request():
    # Start a new span and set baggage
    with tracer.trace("first_server") as span:
        # Unpack baggage headers
        incoming_headers = dict(request.headers)
        prior_context = HTTPPropagator.extract(incoming_headers)
        print("send_request#incoming_baggage: ", prior_context.get_all_baggage_items())
        for i, (k, v) in enumerate(prior_context.get_all_baggage_items().items()):
            span.context.set_baggage_item(k, v)

        # Test set_baggage_item
        span.context.set_baggage_item("key1", "value1")
        span.context.set_baggage_item("key2", "value2")

        # Test get_all_baggage_items()
        all_baggage = span.context.get_all_baggage_items()
        print("send_request#all_baggage: ", all_baggage)

        # Test remove_baggage_item() by removing 'key1'
        span.context.remove_baggage_item("key1")
        print("send_request#remove_baggage_item: ", span.context.get_all_baggage_items())

        # Inject the context into the headers
        outgoing_headers = {}
        HTTPPropagator.inject(span.context, outgoing_headers)

        # Example request body
        data = {"message": "Hello from the first server!"}

        # Send the request to the second server
        response = requests.post("http://localhost:5001/receive", headers=outgoing_headers, json=data)

        # Print the response
        print("send_request#response: ", response.text)
        return jsonify({"status": "Request sent to second server"}), 200

@app2.route('/receive', methods=['POST'])
def receive_request():
    # Extract the context from the incoming request headers
    incoming_headers = dict(request.headers)
    context = HTTPPropagator.extract(incoming_headers)

    # Test get_all_baggage_items
    all_baggage = context.get_all_baggage_items()
    print("receive_request#all_baggage: ", all_baggage)

    # Test remove_all_baggage_items()
    span.context.remove_all_baggage_items()
    print("receive_request#remove_all_baggage_items: ", span.context.get_all_baggage_items())

    return jsonify({"all_baggage": all_baggage}), 200

# Function to run the first Flask app
def run_app1():
    app1.run(port=5000)  # Specify the port for the first app

# Function to run the second Flask app
def run_app2():
    app2.run(port=5001)  # Specify the port for the second app

if __name__ == '__main__':
    threading.Thread(target=run_app1).start()
    threading.Thread(target=run_app2).start()

    # make request to first server with baggage
    with tracer.trace("initiator") as span:
        span.context.set_baggage_item("key3", "value3")
        headers = {}
        HTTPPropagator.inject(span.context, headers)
        requests.post("http://localhost:5000/send", headers=headers)
