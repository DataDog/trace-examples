<?php

namespace Some\App;

use sample_thrift\CalculatorProcessor;
use shared\SharedStruct;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TPhpStream;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/function.php';
registerThriftNamespace();

error_log("Request init hook is: " . print_r(ini_get('ddtrace.request_init_hook'), 1));

class CalculatorHandler implements \sample_thrift\CalculatorIf
{
    /**
     * @param int $num1
     * @param int $num2
     * @return int
     */
    public function add($num1, $num2)
    {
        return $num1 + $num2;
    }

    /**
     * @param int $key
     * @return \shared\SharedStruct
     */
    public function getStruct($key)
    {
        return new SharedStruct();
    }
}

function thriftServer()
{
    header('Content-Type', 'application/x-thrift');
    $handler = new CalculatorHandler();
    $processor = new CalculatorProcessor($handler);

    $transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
    $protocol = new TBinaryProtocol($transport, true, true);

    $transport->open();
    $processor->process($protocol, $protocol);
    $transport->close();
}

thriftServer();
