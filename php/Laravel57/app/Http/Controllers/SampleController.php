<?php

namespace App\Http\Controllers;

use DDTrace\GlobalTracer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SampleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function sampleAction(Request $request) {
        $span = GlobalTracer::get()->getActiveSpan();
        if (null !== $span) {
            $span->setTag('customer_id', $request->get('customer_id'));
        }

        // ... custom code here

        return new Response("Serving content to user...");
    }
}
