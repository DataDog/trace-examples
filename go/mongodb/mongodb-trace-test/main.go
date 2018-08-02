package main

import (
	"context"
	"log"
	"os"

	"github.com/mongodb/mongo-go-driver/bson"
	"github.com/mongodb/mongo-go-driver/mongo"
	"github.com/mongodb/mongo-go-driver/mongo/clientopt"
	mongotrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/mongodb/mongo-go-driver/mongo"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	tracer.Start(tracer.WithDebugMode(true))
	defer tracer.Stop()

	span, ctx := tracer.StartSpanFromContext(context.Background(), "mongodb-trace-test",
		tracer.ServiceName("mongodb-trace-test"))

	// connect to MongoDB
	client, err := mongo.Connect(ctx, "mongodb://localhost:27017",
		clientopt.Monitor(mongotrace.NewMonitor()))
	if err != nil {
		log.Fatal(err)
	}
	db := client.Database("example")
	inventory := db.Collection("inventory")

	inventory.InsertOne(ctx, bson.NewDocument(
		bson.EC.String("item", "canvas"),
		bson.EC.Int32("qty", 100),
		bson.EC.ArrayFromElements("tags",
			bson.VC.String("cotton"),
		),
		bson.EC.SubDocumentFromElements("size",
			bson.EC.Int32("h", 28),
			bson.EC.Double("w", 35.5),
			bson.EC.String("uom", "cm"),
		),
	))

	span.Finish()

	os.Stdin.Read([]byte{0})
}
