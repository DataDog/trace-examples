from ddtrace import Pin, patch
import grpc

from hello_pb2 import HelloRequest
from hello_pb2_grpc import HelloStub

# Use a pin to specify metadata related to this connection
Pin.override(grpc, service='demo.grpc')

def run():
    # If not patched yet, you can patch grpc specifically
    patch(grpc=True)
    with grpc.insecure_channel('localhost:50051') as channel:
        stub = HelloStub(channel)
        response = stub.SayHello(HelloRequest(name="test"))
        print(response)

if __name__ == '__main__':
    run()
