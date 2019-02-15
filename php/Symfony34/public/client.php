<?php

namespace Some\App;

use sample_thrift\CalculatorClient;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\THttpClient;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/function.php';
registerThriftNamespace();

function thriftClient($host, $port, $uri)
{

    header('Content-Type', 'application/x-thrift');

    $socket = new THttpClient($host, $port, $uri);
    $transport = new TBufferedTransport($socket, 1024, 1024);
    $protocol = new TBinaryProtocol($transport, true, true);
    $client = new CalculatorClient($protocol);

    $transport->open();
    $sum = $client->add(1,1);
    $transport->close();

    echo "Used thrift to calculate sum of 1 + 1 = $sum\n";
}

$host = isset($argv[1]) ? $argv[1] : '127.0.0.1';
$port = isset($argv[2]) ? (int)$argv[2] : 80;
$uri = isset($argv[3]) ? $argv[3] : '';
thriftClient($host, $port, $uri);
