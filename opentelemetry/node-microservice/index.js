'use strict';

// eslint-disable-next-line
const otel = require('./tracer')('sandbox_test');
const logger = require('./logger');

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
    logger.info("error creating connection to mongodb server")
  } else {
    db = database.db("mydb");
    db.createCollection("users", function(err, res) {
      if (err) {
        logger.info('Error creating collection, code:', err.code);
      } else {
        logger.info("1 collection created");
      }
    });    
  }
});

const app = express();

const getOtelStats = function (req, res) {
  logger.info('recd a req')
  // make api request to add response headers
  const obj = { name: "John", age: "20" };

  axios.get("http://ruby-microservice:3000/next_launch").then( () => {
    if (db === undefined) {
        MongoClient.connect("mongodb://mongo:27017/mydb", function(err, database) {
          if(err) {
            logger.info("error creating connection to mongodb server",  err)
            res.send('ok')
          } else {
            db = database.db("mydb");
            db.createCollection("users", function(err, response) {
              if (err) {
                logger.info('Error creating collection, code:', err.code);
                res.send('ok')
              } else {
                logger.info("a collection created");
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
              logger.info('Error code:', err.code);
              res.send('ok')
            } else {
              logger.info("some document inserted");
              res.send('ok')
            }
          })
        } else {
          db.collection("users").find({}, function(err, response) {
            if (err) {
              logger.info('Error code:', err.code);
              res.send('ok')
            } else {
              logger.info("document served");
              res.send('ok')
            }
          })
        }
      }
  }).catch( () => {
    logger.info('Error making request')
    res.send('ok')
  })
}

app.get('/api', getOtelStats);

// Launch app to listen to specified port
app.listen(port, function () {
    logger.info("Running on port " + port);
});
