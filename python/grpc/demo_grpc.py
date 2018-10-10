from ddtrace import Pin, patch
import grpc

# If not patched yet, you can patch grpc specifically
patch(grpc=True)

# This will report a span with the default settings
with grpc.insecure_channel('localhost:50051') as channel:
    stub = HelloStub(channel)
    response = stub.SayHello(hello_pb2.HelloRequest(name="test"))

# Use a pin to specify metadata related to this connection
Pin.override(grpc, service='demo.grpc')