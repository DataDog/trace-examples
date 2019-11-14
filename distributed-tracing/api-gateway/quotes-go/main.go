package main

import (
	"log"
	"net/http"
	"time"

	sqltrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/database/sql"
	sqlxtrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/jmoiron/sqlx"
	httptrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/net/http"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"

	"github.com/jmoiron/sqlx"
	"github.com/lib/pq"
)

func main() {
	// Initialize the tracer
	tracer.Start()
	defer tracer.Stop()

	// Configure sql tracing
	sqltrace.Register("postgres", &pq.Driver{})

	// Connect to the database
	connectString := "host=quotes-db user=postgres dbname=postgres sslmode=disable"
	db, err := sqlxtrace.Connect("postgres", connectString)
	connected := err == nil

	if err != nil {
		log.Println("error connecting to database:", err)

		backoffDelays := []int{1, 1, 1, 1, 1, 5, 5, 5, 5, 5}

		for _, b := range backoffDelays {
			db, err = sqlxtrace.Connect("postgres", connectString)
			if err != nil {
				backoffDelay := time.Second * time.Duration(b)
				log.Println("error connecting to database:", err, "retrying in", backoffDelay)
				time.Sleep(backoffDelay)
				continue
			}
			connected = true
			break
		}
	}

	// Configure http service
	mux := httptrace.NewServeMux(httptrace.WithServiceName("quotes"))
	if connected {
		log.Println("using random quotes")
		mux.HandleFunc("/", randomQuote(db))
	} else {
		log.Println("using static quotes")
		mux.HandleFunc("/", staticQuotes)
	}

	log.Fatal(http.ListenAndServe(":80", mux))
}

func randomQuote(db *sqlx.DB) func(http.ResponseWriter, *http.Request) {
	return func(w http.ResponseWriter, r *http.Request) {
		quote := []byte{}

		err := db.GetContext(r.Context(), &quote, "SELECT text FROM quotes ORDER BY random() LIMIT 1")
		if err != nil {
			log.Println("error executing query:", err)
			http.Error(w, "error executing query", http.StatusInternalServerError)
			return
		}

		if len(quote) == 0 {
			log.Println("empty result from query")
			http.Error(w, "empty result from query", http.StatusInternalServerError)
			return
		}
		w.Header().Set("Content-Type", "text/plain")
		w.WriteHeader(http.StatusOK)
		w.Write(quote)
	}
}

func staticQuotes(w http.ResponseWriter, r *http.Request) {
	quote := "Deploying an unmonitored app is like going on a roadtrip without a gas gauge."

	w.Header().Set("Content-Type", "text/plain")
	w.WriteHeader(http.StatusOK)
	w.Write([]byte(quote))
}
