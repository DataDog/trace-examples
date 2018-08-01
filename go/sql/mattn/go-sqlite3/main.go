// Copied from the github.com/mattn/go-sqlite3 example

package main

import (
	"context"
	"fmt"
	"log"
	"os"

	sqlite "github.com/mattn/go-sqlite3" // Setup application to use Sqlite
	sqltrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/database/sql"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	tracer.Start(tracer.WithDebugMode(true), tracer.WithServiceName("sqlite-example"))
	defer tracer.Stop()

	sqltrace.Register(
		"sqlite",
		&sqlite.SQLiteDriver{},
		sqltrace.WithServiceName("sqlite-example"))

	os.Remove("./foo.db")
	db, err := sqltrace.Open("sqlite", "./foo.db")
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	// Create a root span, giving name, server and resource.
	span, ctx := tracer.StartSpanFromContext(
		context.Background(),
		"my-query",
		tracer.SpanType("db"),
		tracer.ResourceName("initial-access"),
	)
	defer span.Finish()

	sqlStmt := `
	create table foo (id integer not null primary key, name text);
	delete from foo;
	`
	_, err = db.ExecContext(ctx, sqlStmt)
	if err != nil {
		log.Printf("%q: %s\n", err, sqlStmt)
		return
	}

	tx, err := db.BeginTx(ctx, nil)
	if err != nil {
		log.Fatal(err)
	}
	stmt, err := tx.PrepareContext(ctx, "insert into foo(id, name) values(?, ?)")
	if err != nil {
		log.Fatal(err)
	}
	defer stmt.Close()
	for i := 0; i < 100; i++ {
		_, err = stmt.ExecContext(ctx, i, fmt.Sprintf("Number: %03d", i))
		if err != nil {
			log.Fatal(err)
		}
	}
	tx.Commit()

	rows, err := db.QueryContext(ctx, "select id, name from foo")
	if err != nil {
		log.Fatal(err)
	}
	defer rows.Close()
	for rows.Next() {
		var id int
		var name string
		err = rows.Scan(&id, &name)
		if err != nil {
			log.Fatal(err)
		}
		fmt.Println(id, name)
	}
	err = rows.Err()
	if err != nil {
		log.Fatal(err)
	}
}
