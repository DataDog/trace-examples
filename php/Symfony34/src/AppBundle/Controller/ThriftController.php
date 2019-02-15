<?php

namespace AppBundle\Controller;

use AppBundle\ThriftService\CalculatorHandler;
use sample_thrift\CalculatorClient;
use sample_thrift\CalculatorProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TPhpStream;

class ThriftController extends Controller
{
    /**
     * @Route("/thrift-client", name="thrift_client")
     */
    public function thriftClientAction()
    {
        $this->registerThriftNamespace();

        $socket = new THttpClient('127.0.0.1', 80, '/thrift-server');
        $transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport, true, true);
        $client = new CalculatorClient($protocol);

        $transport->open();
        $sum = $client->add(1,1);
        $transport->close();

        return new Response("Used thrift to calculate sum of 5 + 10 = $sum;");
    }

    /**
     * @Route("/thrift-server", name="thrift_server")
     */
    public function thriftServerAction()
    {
        $this->registerThriftNamespace();

        header('Content-Type', 'application/x-thrift');

        $handler = new CalculatorHandler();
        $processor = new CalculatorProcessor($handler);

        $transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
        $protocol = new TBinaryProtocol($transport, true, true);

        $transport->open();
        $processor->process($protocol, $protocol);
        $transport->close();

        return new Response();
    }

    /**
     * Register the thrift namespace
     */
    private function registerThriftNamespace()
    {
        $GEN_DIR = __DIR__ . '/../../../../gen-php';
        $loader = new ThriftClassLoader();
        $loader->registerDefinition('shared', $GEN_DIR);
        $loader->registerDefinition('sample_thrift', $GEN_DIR);
        $loader->register();
    }
}
