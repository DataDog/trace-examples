package main

import (
	"fmt"
	"net/http"
)

func handler(w http.ResponseWriter, r *http.Request) {
	// give the response
	path := r.URL.Path[1:]
	fmt.Fprintf(w, "Hi there, I love %s!", path)
}

func main() {
	// welcome
	fmt.Println("--- Starting web server on port 8000 ---")

	// web server
	http.HandleFunc("/", handler)
	http.ListenAndServe(":8000", nil)
}
