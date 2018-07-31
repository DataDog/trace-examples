package main

import (
	"net/http"
	"net/http/httptest"
	"strings"

	graphql "github.com/graph-gophers/graphql-go"
	"github.com/graph-gophers/graphql-go/relay"
	graphqltrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/graph-gophers/graphql-go"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

type resolver struct{}

func (*resolver) Hello() string { return "Hello, world!" }

func main() {
	tracer.Start(tracer.WithDebugMode(true))
	defer tracer.Stop()
	s := `
		schema {
			query: Query
		}
		type Query {
			hello: String!
		}
	`
	schema := graphql.MustParseSchema(s, new(resolver),
		graphql.Tracer(graphqltrace.New(graphqltrace.WithServiceName("test-graphql-service"))))
	srv := httptest.NewServer(&relay.Handler{Schema: schema})
	defer srv.Close()

	for i := 0; i < 10; i++ {
		http.Post(srv.URL, "application/json", strings.NewReader(`{
		"query": "{ hello }"
	}`))
	}
}
