package main

import (
	"fmt"
	"os"

	"github.com/aws/aws-sdk-go/aws"
	"github.com/aws/aws-sdk-go/aws/session"
	"github.com/aws/aws-sdk-go/service/s3"
	awstrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/aws/aws-sdk-go/aws"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	tracer.Start(tracer.WithDebugMode(true))
	defer tracer.Stop()

	cfg := aws.NewConfig().WithRegion("us-east-1")
	session := session.Must(session.NewSession(cfg))
	session = awstrace.WrapSession(session)

	s3api := s3.New(session)
	objects, err := s3api.ListObjects(&s3.ListObjectsInput{
		Bucket: aws.String("dd-series-staging-test"),
	})
	if err != nil {
		panic(err)
	}
	fmt.Println(len(objects.Contents))

	os.Stdin.Read([]byte{0})
}
