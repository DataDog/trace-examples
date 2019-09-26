package main

import (
	"context"
	"database/sql"
	"log"
	"net"
	"os"

	"github.com/DataDog/trace-examples/go/grpc/grpc-db/proto/crud"

	"github.com/lib/pq"
	"google.golang.org/grpc"

	sqltrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/database/sql"
	grpctrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/google.golang.org/grpc"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	tracer.Start()
	defer tracer.Stop()

	postgresHost := getEnvWithDefault("POSTGRES_HOST", "localhost")
	postgresPort := getEnvWithDefault("POSTGRES_PORT", "5432")
	postgresAddr := net.JoinHostPort(postgresHost, postgresPort)

	sqltrace.Register("postgres", &pq.Driver{})
	db, err := sqltrace.Open("postgres", "postgres://postgres:postgres@"+postgresAddr+"/postgres?sslmode=disable")
	if err != nil {
		log.Fatalf("error connecting to postgres: %v", err)
	}
	defer db.Close()

	_, err = db.Exec(createTableSQL)
	if err != nil {
		log.Fatalf("error initializing database tables: %v", err)
	}

	lis, err := net.Listen("tcp", ":12345")
	if err != nil {
		log.Fatalf("failed to listen: %v", err)
	}

	si := grpctrace.StreamServerInterceptor(grpctrace.WithServiceName("grpc-dbsql-server"))
	ui := grpctrace.UnaryServerInterceptor(grpctrace.WithServiceName("grpc-dbsql-server"))
	s := grpc.NewServer(grpc.StreamInterceptor(si), grpc.UnaryInterceptor(ui))

	crud.RegisterCRUDServer(s, crudServer{db: db})
	log.Printf("serving on %s\n", lis.Addr())
	if err := s.Serve(lis); err != nil {
		log.Fatalf("failed to serve: %v", err)
	}
}

type crudServer struct {
	db *sql.DB
}

const (
	createTableSQL = `
CREATE TABLE IF NOT EXISTS "items" (
	"id" SERIAL PRIMARY KEY,
	"name" TEXT DEFAULT NULL
);`
	createSQL = `INSERT INTO "items" ("name") VALUES ($1) RETURNING "id"`
	querySQL  = `SELECT "name" FROM "items" WHERE "id" = $1`
	updateSQL = `UPDATE "items" SET "name" = $1 WHERE "id" = $2`
	deleteSQL = `DELETE FROM "items" WHERE "id" = $1`
)

func (c crudServer) Create(ctx context.Context, req *crud.CreateRequest) (*crud.CreateResponse, error) {
	var id int
	err := c.db.QueryRowContext(ctx, createSQL, req.GetName()).Scan(&id)
	if err != nil {
		return &crud.CreateResponse{Id: 0}, nil
	}
	return &crud.CreateResponse{Id: int32(id)}, nil
}

func (c crudServer) Query(ctx context.Context, req *crud.QueryRequest) (*crud.QueryResponse, error) {
	var name string
	err := c.db.QueryRowContext(ctx, querySQL, req.GetId()).Scan(&name)
	if err != nil {
		return &crud.QueryResponse{Found: false}, nil
	}
	return &crud.QueryResponse{Found: true, Name: name}, nil
}

func (c crudServer) Update(ctx context.Context, req *crud.UpdateRequest) (*crud.UpdateResponse, error) {
	_, err := c.db.ExecContext(ctx, updateSQL, req.GetName(), req.GetId())
	if err != nil {
		return &crud.UpdateResponse{Updated: false}, nil
	}
	return &crud.UpdateResponse{Updated: true}, nil
}

func (c crudServer) Delete(ctx context.Context, req *crud.DeleteRequest) (*crud.DeleteResponse, error) {
	_, err := c.db.ExecContext(ctx, deleteSQL, req.GetId())
	if err != nil {
		return &crud.DeleteResponse{Deleted: false}, nil
	}
	return &crud.DeleteResponse{Deleted: true}, nil
}

func getEnvWithDefault(name string, defaultVal string) string {
	val := os.Getenv(name)
	if val == "" {
		val = defaultVal
	}
	return val
}
