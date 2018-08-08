package main

import (
	"context"
	"fmt"
	"log"

	"github.com/globalsign/mgo"
	"github.com/globalsign/mgo/bson"

	mgotracer "gopkg.in/DataDog/dd-trace-go.v1/contrib/globalsign/mgo"
	"gopkg.in/DataDog/dd-trace-go.v1/ddtrace/tracer"
)

func main() {
	tracer.Start(tracer.WithDebugMode(true), tracer.WithServiceName("mongodb-example"))
	defer tracer.Stop()

	span, ctx := tracer.StartSpanFromContext(
		context.Background(),
		"execute-example",
		tracer.SpanType("app"),
		tracer.ResourceName("initial-access"),
	)
	defer span.Finish()

	session, err := mgotracer.Dial("192.168.33.10:27017", mgotracer.WithContext(ctx))
	defer session.Close()

	if err != nil {
		log.Fatal(err)
		return
	}

	dbNames, _ := session.DatabaseNames()
	for _, dbName := range dbNames {
		fmt.Println(dbName)
	}

	db := session.DB("my_db")
	collection := db.C("MyCollection")
	collection.DropCollection()

	insertNDocsIntoCollection(5, collection)
	removeDocument(0, collection)
	removeDocumentByID(collection)
	updateDocument(2, collection)
	updateByID(collection)
	upsertDocument(10, collection)
	upsertByID(collection)
	printCollection(collection)

	fmt.Println("\nUpdate All")
	updateAll(collection)
	printCollection(collection)

	fmt.Println("\nRemove All")
	removeAll(collection)
	fmt.Println("Contents of Collection:")
	printCollection(collection)

	bulkCollection(10, collection)
	useIterAll(collection)

	var count int
	count, err = collection.Count()
	if err != nil {
		log.Println(err)
	} else {
		fmt.Printf("Documents in Collection = %d\n", count)
	}

	// Test Index Functions
	indexes, _ := collection.Indexes()
	fmt.Println("\nIndexes")
	for i, index := range indexes {
		fmt.Printf("%d - %v\n", i, index.Name)
	}
	err = collection.EnsureIndex(indexes[0])
	if err != nil {
		log.Println(err)
	}
	err = collection.EnsureIndexKey("_id_")
	if err != nil {
		log.Println(err)
	}

	err = collection.DropAllIndexes()
	if err != nil {
		log.Println(err)
	}

	err = collection.DropIndexName("_id_")
	if err != nil {
		log.Println(err)
	}

	err = collection.DropCollection()
	if err != nil {
		log.Println(err)
	}

	err = collection.Create(&mgo.CollectionInfo{})
	if err != nil {
		log.Println(err)
	}
}

func printCollection(collection *mgotracer.Collection) {
	results := collection.Find(nil)
	iter := results.Iter()
	var r bson.D
	for iter.Next(&r) {
		fmt.Printf("%v\n", r)
	}
	iter.Close()
}

func useIterAll(collection *mgotracer.Collection) {
	results := collection.Find(nil).Limit(10)
	iter := results.Iter()

	var result []bson.D
	iter.All(&result)
	for i, r := range result {
		fmt.Printf("%d - %v\n", i, r)
	}
	iter.Close()
}

func insertNDocsIntoCollection(n int, collection *mgotracer.Collection) {
	for i := 0; i < n; i++ {
		var set bson.D
		set = append(
			set,
			bson.DocElem{
				Name: "insert",
				Value: bson.DocElem{
					Name:  "index",
					Value: i}})

		err := collection.Insert(set)
		if err != nil {
			log.Fatal(err)
		}
	}
}

func bulkCollection(n int, collection *mgotracer.Collection) {
	var documents []bson.D
	for i := 0; i < n; i++ {
		var set bson.D
		set = append(
			set,
			bson.DocElem{
				Name: "bulk-insert",
				Value: bson.DocElem{
					Name:  "index",
					Value: i}})
		documents = append(documents, set)
	}
	interfaces := make([]interface{}, len(documents))
	for i, s := range documents {
		interfaces[i] = s
	}
	bulk := collection.Bulk()
	bulk.Insert(interfaces...)
	bulk.Remove(documents[0])
	bulk.Remove(documents[1])

	var update bson.D
	update = append(
		update,
		bson.DocElem{
			Name: "bulk-update",
			Value: bson.DocElem{
				Name:  "index",
				Value: 2}})
	bulk.Update(documents[2], update)
	bulk.Run()
}

func updateDocument(index int, collection *mgotracer.Collection) {
	var target bson.D
	target = append(
		target,
		bson.DocElem{
			Name: "insert",
			Value: bson.DocElem{
				Name:  "index",
				Value: index}})

	var update bson.D
	update = append(
		update,
		bson.DocElem{
			Name: "updated",
			Value: bson.DocElem{
				Name:  "index",
				Value: index}})
	collection.Update(target, update)
}

func updateByID(collection *mgotracer.Collection) {
	var set bson.D
	set = append(
		set,
		bson.DocElem{
			Name: "insert",
			Value: bson.DocElem{
				Name:  "index",
				Value: 100}})
	var update bson.D
	update = append(
		update,
		bson.DocElem{
			Name: "updated",
			Value: bson.DocElem{
				Name:  "index",
				Value: 100}})
	collection.Insert(set)
	query := collection.Find(set)
	iter := query.Iter()
	var r bson.D
	for iter.Next(&r) {
		id := r.Map()["_id"]
		fmt.Printf("Removing Id: %v\n", id)
		err := collection.UpdateId(id, update)
		if err != nil {
			log.Fatal(err)
		}
	}
}

func updateAll(collection *mgotracer.Collection) {
	update := bson.M{
		"$inc": bson.M{"index": 100},
	}
	collection.UpdateAll(nil, update)
}

func upsertDocument(index int, collection *mgotracer.Collection) {
	var target bson.D
	target = append(
		target,
		bson.DocElem{
			Name: "insert",
			Value: bson.DocElem{
				Name:  "index",
				Value: index}})

	var update bson.D
	update = append(
		update,
		bson.DocElem{
			Name: "upsert",
			Value: bson.DocElem{
				Name:  "index",
				Value: index}})

	collection.Upsert(target, update)
}

func upsertByID(collection *mgotracer.Collection) {
	var set bson.D
	set = append(
		set,
		bson.DocElem{
			Name: "insert",
			Value: bson.DocElem{
				Name:  "index",
				Value: 100}})
	var update bson.D
	update = append(
		update,
		bson.DocElem{
			Name: "upsert",
			Value: bson.DocElem{
				Name:  "index",
				Value: 100}})
	collection.Insert(set)
	query := collection.Find(set)
	iter := query.Iter()
	var r bson.D
	for iter.Next(&r) {
		id := r.Map()["_id"]
		fmt.Printf("Upserting Id: %v\n", id)
		_, err := collection.UpsertId(id, update)
		if err != nil {
			log.Fatal(err)
		}
	}
}

func removeDocument(index int, collection *mgotracer.Collection) {
	var set bson.D
	set = append(
		set,
		bson.DocElem{
			Name: "insert",
			Value: bson.DocElem{
				Name:  "index",
				Value: index}})
	collection.Remove(set)
}

func removeDocumentByID(collection *mgotracer.Collection) {
	var set bson.D
	set = append(
		set,
		bson.DocElem{
			Name: "remove-id",
			Value: bson.DocElem{
				Name:  "index",
				Value: 100}})
	collection.Insert(set)
	query := collection.Find(set)
	iter := query.Iter()
	var r bson.D
	for iter.Next(&r) {
		id := r.Map()["_id"]
		fmt.Printf("Removing Id: %v\n", id)
		err := collection.RemoveId(id)
		if err != nil {
			log.Fatal(err)
		}
	}
}

func removeAll(collection *mgotracer.Collection) {
	_, err := collection.RemoveAll(nil)
	if err != nil {
		log.Fatal(err)
	}
}
