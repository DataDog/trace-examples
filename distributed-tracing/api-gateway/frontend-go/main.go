package main

import (
	"context"
	"html/template"
	"io"
	"io/ioutil"
	"log"
	"net/http"

	httptrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/net/http"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	// Initialize the tracer
	tracer.Start()
	defer tracer.Stop()

	// Configure http client for api calls
	client := httptrace.WrapClient(&http.Client{})

	// Configure http service
	mux := httptrace.NewServeMux(httptrace.WithServiceName("frontend"))

	tmpl := template.Must(template.ParseFiles("index.html.tmpl"))
	q := &quoteGenerator{client: client, bodyTemplate: tmpl}
	mux.Handle("/", q)

	log.Fatal(http.ListenAndServe(":80", mux))
}

type quoteGenerator struct {
	client       *http.Client
	bodyTemplate *template.Template
}

func (q *quoteGenerator) ServeHTTP(w http.ResponseWriter, r *http.Request) {
	log.Println("quoteGenerator ServeHTTP")
	span, ctx := tracer.StartSpanFromContext(r.Context(), "generateQuotePage")
	defer span.Finish()

	quote, err := q.fetchQuote(ctx)
	log.Println("quoteGenerator", quote, err)
	if err != nil {
		http.Error(w, "error fetching quote", http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "text/html")
	w.WriteHeader(http.StatusOK)
	err = q.bodyTemplate.Execute(w, quote)
	if err != nil {
		log.Println("quoteGenerator execute", err)
	}
}

func (q *quoteGenerator) fetchQuote(ctx context.Context) (string, error) {
	req, err := http.NewRequest("GET", "http://api/v1/quotes/", nil)
	if err != nil {
		log.Println("error creating quote request")
		return "", err
	}
	req = req.WithContext(ctx)
	res, err := q.client.Do(req)
	if err != nil {
		log.Println("error fetching quote:", err)
		return "", err
	}
	defer res.Body.Close()

	b, err := ioutil.ReadAll(&io.LimitedReader{R: res.Body, N: 1024})
	if err != nil {
		log.Println("error reading data for quote:", err)
		return "", err
	}
	return string(b), nil
}
