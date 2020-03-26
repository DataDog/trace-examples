package main

import (
	"fmt"
	"os"

	cli "github.com/DataDog/trace-examples/go/goa-basic/gen/grpc/cli/calc"
	goa "goa.design/goa/v3/pkg"
	"google.golang.org/grpc"
	grpctrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/google.golang.org/grpc"
)

func doGRPC(scheme, host string, timeout int, debug bool) (goa.Endpoint, interface{}, error) {
	si := grpctrace.StreamClientInterceptor(grpctrace.WithServiceName("basic-grpc-client"))
	ui := grpctrace.UnaryClientInterceptor(grpctrace.WithServiceName("basic-grpc-client"))
	conn, err := grpc.Dial(host, grpc.WithInsecure(),
		grpc.WithStreamInterceptor(si), grpc.WithUnaryInterceptor(ui))
	if err != nil {
		fmt.Fprintf(os.Stderr, "could not connect to gRPC server at %s: %v\n", host, err)
	}
	return cli.ParseEndpoint(conn)
}
