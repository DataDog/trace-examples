'use strict';

// eslint-disable-next-line
require('./tracer')('sandbox_test_node');

const port = 4000;
const owner = 'open-telemetry';
const repo = 'opentelemetry-js';
const axios = require('axios');
const express = require('express');

// Retrieve
var MongoClient = require('mongodb').MongoClient;
let db;

MongoClient.connect("mongodb://mongo:27017/mydb", function(err, database) {
  if(err) {
    console.log("error creating connection to mongodb server")
  } else {
    db = database.db("mydb");
    db.createCollection("users", function(err, res) {
      if (err) {
        console.log('Error creating collection, code:', err.code);
      } else {
        console.log("1 collection created");
      }
    });    
  }
});

const app = express();

const getOtelStats = function (req, res) {
  console.log('getting request')
  // make api request to add response headers
  
  const obj = { name: "John", age: "20" };

  if (db === undefined) {
    MongoClient.connect("mongodb://mongo:27017/mydb", function(err, database) {
      if(err) {
        console.log("error creating connection to mongodb server",  err)
        res.send('ok')
      } else {
        db = database.db("mydb");
        db.createCollection("users", function(err, response) {
          if (err) {
            console.log('Error creating collection, code:', err.code);
            res.send('ok')
          } else {
            console.log("a collection created");
            res.send('ok')
          }
        });    
      }
    });    
  } else {
    // Connect to the db, randomly insert or find record
    if (Math.random() < 0.5) {
      db.collection("users").insertOne(obj, function(err, response) {
        if (err) {
          console.log('Error code:', err.code);
          res.send('ok')
        } else {
          console.log("some document inserted");
          res.send('ok')
        }
      })
    } else {
      db.collection("users").find({}, function(err, response) {
        if (err) {
          console.log('Error code:', err.code);
          res.send('ok')
        } else {
          console.log("document served");
          res.send('ok')
        }
      })
    }
  }
}

app.get('/api', getOtelStats);

// Launch app to listen to specified port
app.listen(port, function () {
    console.log("Running on port " + port);
});
