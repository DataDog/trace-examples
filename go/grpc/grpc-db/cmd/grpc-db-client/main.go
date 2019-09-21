package main

import (
	"context"
	"flag"
	"log"
	"time"

	"github.com/DataDog/trace-examples/go/grpc/grpc-db/proto/crud"

	"google.golang.org/grpc"

	grpctrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/google.golang.org/grpc"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	address := flag.String("address", "localhost:12345", "address of gRPC CRUD service (host:port)")
	flag.Parse()

	tracer.Start()
	defer tracer.Stop()

	si := grpctrace.StreamClientInterceptor(grpctrace.WithServiceName("grpc-db-client"))
	ui := grpctrace.UnaryClientInterceptor(grpctrace.WithServiceName("grpc-db-client"))

	conn, err := grpc.Dial(*address, grpc.WithInsecure(),
		grpc.WithStreamInterceptor(si), grpc.WithUnaryInterceptor(ui))
	if err != nil {
		log.Fatalf("did not connect: %v", err)
	}
	defer conn.Close()
	c := crud.NewCRUDClient(conn)

	ctx, cancel := context.WithTimeout(context.Background(), time.Second)
	defer cancel()

	creq := &crud.CreateRequest{Name: "foo"}
	cres, err := c.Create(ctx, creq)
	if err != nil {
		log.Fatalf("error creating: %v", err)
	}
	log.Println("created, id =", cres.GetId())

	qreq := &crud.QueryRequest{Id: cres.GetId()}
	qres, err := c.Query(ctx, qreq)
	if err != nil {
		log.Fatalf("error querying: %v", err)
	}
	log.Println("queried. found =", qres.GetFound(), "name =", qres.GetName())

	ureq := &crud.UpdateRequest{Id: cres.GetId(), Name: "bar"}
	ures, err := c.Update(ctx, ureq)
	if err != nil {
		log.Fatalf("error updating: %v", err)
	}
	log.Println("updated =", ures.GetUpdated())

	dreq := &crud.DeleteRequest{Id: cres.GetId()}
	dres, err := c.Delete(ctx, dreq)
	if err != nil {
		log.Fatalf("error deleting: %v", err)
	}
	log.Println("deleted =", dres.GetDeleted())
}
