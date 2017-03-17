package main

import (
	"fmt"
	"math/rand"
	"net/http"
	"os"
	"sync"
	"time"

	"github.com/DataDog/dd-trace-go/tracer"
)

const (
	GO_ROUTINES = 10
)

// utils
func getEnv(key, fallback string) string {
	value := os.Getenv(key)
	if len(value) == 0 {
		return fallback
	}
	return value
}

// tracer configuration
var hostname = getEnv("DATADOG_TRACE_HOST", "localhost")
var port = getEnv("DATADOG_TRACE_PORT", "8126")
var transport = tracer.NewTransport(hostname, port)
var tr = tracer.NewTracerTransport(transport)

// random service list
var services = []string{
	"goroutine-1",
	"goroutine-2",
	"goroutine-3",
	"goroutine-4",
}

var resources = []string{
	"/r1/",
	"/r2/",
	"/r3/",
	"/r4/",
	"/r5/",
	"/r6/",
	"/r7/",
}

func handler1(w http.ResponseWriter, r *http.Request) {
	// trace the endpoint
	path := r.URL.Path[1:]
	rootSpan := tr.NewRootSpan("http.client.request", "go-parallel", "/type_1/")
	rootSpan.SetMeta("http.url", path)

	// give the response
	fmt.Fprintf(w, "Hi there, I love %s!", path)

	// wait for goroutine termination
	var wg sync.WaitGroup

	// spawn parallel goroutines
	for i := 0; i < GO_ROUTINES; i++ {
		wg.Add(1)
		go func() {
			span := tr.NewChildSpan("http.goroutine", rootSpan)

			// pick a random service
			n := rand.Int() % len(services)
			span.Service = services[n]

			// wait for a random time
			rand := time.Duration(rand.Intn(100))
			time.Sleep(rand * time.Millisecond)
			span.Finish()
			wg.Done()
		}()
	}

	wg.Wait()
	rootSpan.Finish()
}

func handler2(w http.ResponseWriter, r *http.Request) {
	// trace the endpoint
	path := r.URL.Path[1:]
	rootSpan := tr.NewRootSpan("http.client.request", "go-parallel", "/type_2/")
	rootSpan.SetMeta("http.url", path)

	// give the response
	fmt.Fprintf(w, "Hi there, I love %s!", path)

	// spawn parallel goroutines
	for i := 0; i < GO_ROUTINES; i++ {
		go func() {
			span := tr.NewChildSpan("http.goroutine", rootSpan)

			// pick a random service
			n := rand.Int() % len(services)
			span.Service = services[n]
			n = rand.Int() % len(resources)
			span.Resource = resources[n]

			// wait for a random time
			rand := time.Duration(rand.Intn(100))
			time.Sleep(rand * time.Millisecond)
			span.Finish()
		}()
	}

	// wait for a random time
	rand := time.Duration(rand.Intn(50))
	time.Sleep(rand * time.Millisecond)
	rootSpan.Finish()
}

func outOfControl(w http.ResponseWriter, r *http.Request) {
	// trace the endpoint
	path := r.URL.Path[1:]
	rootSpan := tr.NewRootSpan("http.client.request", "go-parallel", "/out_of_control/")
	rootSpan.SetMeta("http.url", path)

	// give the response
	fmt.Fprintf(w, "Hi there, I love %s!", path)

	// spawn parallel goroutines
	for i := 0; i < GO_ROUTINES; i++ {
		go func() {
			// wait for a random time
			pre := time.Duration(rand.Intn(50))
			time.Sleep(pre * time.Millisecond)

			span := tr.NewChildSpan("http.goroutine", rootSpan)
			// pick a random service
			n := rand.Int() % len(services)
			span.Service = services[n]
			n = rand.Int() % len(resources)
			span.Resource = resources[n]

			// wait for a random time
			post := time.Duration(rand.Intn(100))
			time.Sleep(post * time.Millisecond)
			span.Finish()
		}()
	}

	// wait for a random time
	rand := time.Duration(rand.Intn(120))
	time.Sleep(rand * time.Millisecond)
	rootSpan.Finish()
}

func main() {
	// welcome
	fmt.Println("--- Starting web server on port 8000 ---")

	// web server
	http.HandleFunc("/type_1/", handler1)
	http.HandleFunc("/type_2/", handler2)
	http.HandleFunc("/out_of_control/", outOfControl)
	http.ListenAndServe(":8000", nil)
}
