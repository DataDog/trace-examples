import grpc
import time
from concurrent import futures
from hello_pb2 import HelloReply
from hello_pb2_grpc import add_HelloServicer_to_server

GRPC_PORT = 50051
_ONE_DAY_IN_SECONDS = 60 * 60 * 24

class SendBackDatadogHeaders(object):
    def SayHello(self, request, context):
        """Send back Hello!
        """
        print('Incoming request')
        context.set_code(grpc.StatusCode.OK)
        return HelloReply(message='Hello!')

def serve():
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=2))
    server.add_insecure_port('[::]:%d' % (GRPC_PORT))
    add_HelloServicer_to_server(SendBackDatadogHeaders(), server)
    server.start()
    print('Waiting on port %d' % (GRPC_PORT))
    try:
        while True:
            time.sleep(_ONE_DAY_IN_SECONDS)
    except KeyboardInterrupt:
        server.stop(0)

if __name__ == '__main__':
    serve()
