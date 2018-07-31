package main

import (
	"context"
	"fmt"
	"net"
	"os"

	"github.com/DataDog/trace-examples/go/grpc/grpc-trace-test/example"
	"golang.org/x/sync/errgroup"
	"google.golang.org/grpc"
	grpctrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/google.golang.org/grpc"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

//go:generate protoc -I ./ example.proto --go_out=plugins=grpc:example

type exampleServer struct {
}

func (es *exampleServer) Echo(srv example.Example_EchoServer) error {
	for {
		msg, err := srv.Recv()
		if err != nil {
			return err
		}

		err = srv.Send(msg)
		if err != nil {
			return err
		}
	}
}

func main() {
	tracer.Start()
	defer tracer.Stop()

	li, err := net.Listen("tcp", "127.0.0.1:0")
	if err != nil {
		fmt.Fprintf(os.Stderr, "failed to create listener: %v", err)
		os.Exit(1)
	}
	defer li.Close()

	si := grpctrace.StreamServerInterceptor(
		grpctrace.WithServiceName("example-grpc-server"),
		grpctrace.WithStreamMessages(false),
	)
	ci := grpctrace.StreamClientInterceptor(
		grpctrace.WithServiceName("example-grpc-client"),
		grpctrace.WithStreamCalls(false),
	)
	s := grpc.NewServer(grpc.StreamInterceptor(si))

	var eg errgroup.Group
	eg.Go(func() error {
		example.RegisterExampleServer(s, new(exampleServer))
		return s.Serve(li)
	})
	eg.Go(func() error {
		defer s.GracefulStop()

		cc, err := grpc.Dial(li.Addr().String(), grpc.WithInsecure(), grpc.WithStreamInterceptor(ci))
		if err != nil {
			fmt.Fprintf(os.Stderr, "failed to dial listener: %v", err)
			os.Exit(1)
		}
		defer cc.Close()

		c := example.NewExampleClient(cc)
		ec, err := c.Echo(context.Background())
		if err != nil {
			return err
		}
		defer ec.CloseSend()

		for i := 0; i < 10; i++ {
			err := ec.Send(&example.Message{Data: "Hello World"})
			if err != nil {
				return err
			}

			msg, err := ec.Recv()
			if err != nil {
				return err
			}
			fmt.Println(msg)
		}
		return nil
	})
	eg.Wait()
}
