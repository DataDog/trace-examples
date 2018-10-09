package main

// A simple program reporting very simple spans on a regular basis.

import (
	"context"
	"time"

	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

const (
	tickerDuration = time.Second
	spanDuration   = time.Millisecond
)

func main() {
	tracer.Start(tracer.WithDebugMode(true), tracer.WithServiceName("go-heartbeat"))
	defer tracer.Stop()

	ticker := time.NewTicker(tickerDuration)
	defer ticker.Stop()

	for range ticker.C {
		span, _ := tracer.StartSpanFromContext(
			context.Background(),
			"beat",
			tracer.SpanType("custom"),
			tracer.ResourceName("beat"),
		)
		time.Sleep(spanDuration)
		span.Finish()
	}
}
