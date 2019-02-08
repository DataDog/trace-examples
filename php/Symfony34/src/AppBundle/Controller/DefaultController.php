<?php

namespace AppBundle\Controller;

use DDTrace\GlobalTracer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/distributed-tracing", name="distributed_tracing_frontend")
     */
    public function distributedTracing(Request $request)
    {
        $ch = curl_init("http://127.0.0.1/distributed-tracing-backend");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseText = curl_exec($ch);

        curl_close($ch);

        return new Response("This is frontend - $responseText");
    }

    /**
     * @Route("/distributed-tracing-backend", name="distributed_tracing_backend")
     */
    public function distributedTracingBackend(Request $request)
    {
        $tracer = GlobalTracer::get();
        $scope = $tracer->startActiveSpan('manual_tracing');
        usleep(200 * 1000);
        $scope->close();

        return new Response("This is backend");
    }
}
