package main

import (
	"fmt"
	"math/rand"
	"net/http"
	"sync"
	"time"

	"github.com/Datadog/dd-trace-go/tracer"
)

const (
	GO_ROUTINES = 10
)

func handler(w http.ResponseWriter, r *http.Request) {
	// trace the endpoint
	path := r.URL.Path[1:]
	rootSpan := tracer.NewRootSpan("http.client.request", "go-parallel", "/")
	rootSpan.SetMeta("http.url", path)

	// give the response
	fmt.Fprintf(w, "Hi there, I love %s!", path)

	// wait for goroutine termination
	var wg sync.WaitGroup

	// spawn parallel goroutines
	for i := 0; i < GO_ROUTINES; i++ {
		wg.Add(1)
		go func() {
			span := tracer.NewChildSpan("http.goroutine", rootSpan)
			rand := time.Duration(rand.Intn(100))
			time.Sleep(rand * time.Millisecond)
			span.Finish()
			wg.Done()
		}()
	}

	wg.Wait()
	rootSpan.Finish()
}

func main() {
	// welcome
	fmt.Println("--- Starting web server on port 8000 ---")

	// web server
	http.HandleFunc("/", handler)
	http.ListenAndServe(":8000", nil)
}
