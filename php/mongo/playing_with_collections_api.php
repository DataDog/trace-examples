<?php

namespace DDTraceExamples\Mongo;

use DDTrace\Integrations\IntegrationsLoader;
use DDTrace\Tracer;
use OpenTracing\GlobalTracer;

require_once './vendor/autoload.php';

$tracer = new Tracer();
GlobalTracer::set($tracer);
IntegrationsLoader::load();

register_shutdown_function(function() {
    GlobalTracer::get()->flush();
});

const HOST = 'mongodb_4_server';
const PORT = '27017';
const USER = 'test';
const PASSWORD = 'test';
const DATABASE = 'admin';

$rootSpanScope = $tracer->startActiveSpan('mongo_sample_app');

$mongo = new \MongoClient(
    'mongodb://' . HOST . ':' . PORT,
    [
        'username' => USER,
        'password' => PASSWORD,
        'db' => DATABASE,
    ]
);
$collection = $mongo->{DATABASE}->createCollection('foo_collection');
$collection->insert(['foo' => 'bar']);
$collection->findOne(['foo' => 'bar', 'nested' => ['key' => 'bar']]);
$collection->drop();
$mongo->close(true);

$rootSpanScope->close();
