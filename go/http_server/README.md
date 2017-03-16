# Go simple HTTP Server

This application is a simple HTTP server that creates a new
trace after each request. For each call, a new set of
goroutines is created so that they can run in parallel.

## Compatibility stack

* Go 1.8+

## Backing services

None.

## Getting started

Just build the application and run with:

    go build -o server
    ./server
