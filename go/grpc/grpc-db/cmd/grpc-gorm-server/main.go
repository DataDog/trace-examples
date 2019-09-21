package main

import (
	"context"
	"log"
	"net"
	"os"

	"github.com/DataDog/trace-examples/go/grpc/grpc-db/proto/crud"

	"github.com/jinzhu/gorm"
	"github.com/lib/pq"
	"google.golang.org/grpc"

	sqltrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/database/sql"
	grpctrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/google.golang.org/grpc"
	gormtrace "gopkg.in/DataDog/dd-trace-go.v1/contrib/jinzhu/gorm"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

type Thing struct {
	gorm.Model
	Name string
}

func main() {
	tracer.Start()
	defer tracer.Stop()

	postgresHost := getEnvWithDefault("POSTGRES_HOST", "localhost")
	postgresPort := getEnvWithDefault("POSTGRES_PORT", "5432")
	postgresAddr := net.JoinHostPort(postgresHost, postgresPort)

	sqltrace.Register("postgres", &pq.Driver{})
	db, err := gorm.Open("postgres", "postgres://postgres:postgres@"+postgresAddr+"/postgres?sslmode=disable")
	if err != nil {
		log.Fatalf("error connecting to postgres: %v", err)
	}

	db = gormtrace.WithCallbacks(db)
	defer db.Close()
	db.AutoMigrate(&Thing{})

	lis, err := net.Listen("tcp", ":12345")
	if err != nil {
		log.Fatalf("failed to listen: %v", err)
	}

	si := grpctrace.StreamServerInterceptor(grpctrace.WithServiceName("traced-example-server"))
	ui := grpctrace.UnaryServerInterceptor(grpctrace.WithServiceName("traced-example-server"))
	s := grpc.NewServer(grpc.StreamInterceptor(si), grpc.UnaryInterceptor(ui))

	crud.RegisterCRUDServer(s, crudServer{db: db})
	log.Printf("serving on %s\n", lis.Addr())
	if err := s.Serve(lis); err != nil {
		log.Fatalf("failed to serve: %v", err)
	}
}

type crudServer struct {
	db *gorm.DB
}

func (c crudServer) Create(ctx context.Context, req *crud.CreateRequest) (*crud.CreateResponse, error) {
	db := gormtrace.WithContext(ctx, c.db)
	t := &Thing{Name: req.GetName()}
	db.Create(t)
	if db.Error != nil {
		return &crud.CreateResponse{Id: 0}, nil
	}
	return &crud.CreateResponse{Id: int32(t.ID)}, nil
}

func (c crudServer) Query(ctx context.Context, req *crud.QueryRequest) (*crud.QueryResponse, error) {
	db := gormtrace.WithContext(ctx, c.db)
	t := &Thing{}
	db.Where("id = ?", req.GetId()).First(&t)
	if db.Error != nil {
		return &crud.QueryResponse{Found: false}, nil
	}
	return &crud.QueryResponse{Found: true, Name: t.Name}, nil
}

func (c crudServer) Update(ctx context.Context, req *crud.UpdateRequest) (*crud.UpdateResponse, error) {
	db := gormtrace.WithContext(ctx, c.db)
	t := &Thing{}
	db.Where("id = ?", req.GetId()).First(&t)
	if db.Error != nil {
		return &crud.UpdateResponse{Updated: false}, nil
	}
	t.Name = req.GetName()
	db.Save(t)
	if db.Error != nil {
		return &crud.UpdateResponse{Updated: false}, nil
	}
	return &crud.UpdateResponse{Updated: true}, nil
}

func (c crudServer) Delete(ctx context.Context, req *crud.DeleteRequest) (*crud.DeleteResponse, error) {
	db := gormtrace.WithContext(ctx, c.db)
	t := &Thing{Model: gorm.Model{ID: uint(req.GetId())}}
	db.Delete(t)
	if db.Error != nil {
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
