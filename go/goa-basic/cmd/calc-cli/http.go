package main

import (
	"net/http"
	"time"

	cli "github.com/DataDog/trace-examples/go/goa-basic/gen/http/cli/calc"
	goahttp "goa.design/goa/v3/http"
	goa "goa.design/goa/v3/pkg"
	httptrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/net/http"
)

func doHTTP(scheme, host string, timeout int, debug bool) (goa.Endpoint, interface{}, error) {
	var (
		doer goahttp.Doer
	)
	{
		client := &http.Client{Timeout: time.Duration(timeout) * time.Second}
		doer = httptrace.WrapClient(client, httptrace.RTWithServiceName("basic-http-client"))
		if debug {
			doer = goahttp.NewDebugDoer(doer)
		}
	}

	return cli.ParseEndpoint(
		scheme,
		host,
		doer,
		goahttp.RequestEncoder,
		goahttp.ResponseDecoder,
		debug,
	)
}

func httpUsageCommands() string {
	return cli.UsageCommands()
}

func httpUsageExamples() string {
	return cli.UsageExamples()
}
