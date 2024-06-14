package com.datadog.pej;

import datadog.opentracing.DDTracer;
import datadog.trace.api.DDTags;
import datadog.trace.api.GlobalTracer;
import io.opentracing.Scope;
import io.opentracing.ScopeManager;
import io.opentracing.Span;
import io.opentracing.Tracer;


public class Main {

    public static void main(String[] args) throws Exception{


        //Tracer tracer = new DDTracer("Consumer");
        Tracer tracer  = DDTracer.builder().build();
        GlobalTracer.registerIfAbsent((datadog.trace.api.Tracer) tracer);

        ScopeManager sm = tracer.scopeManager();
        Tracer.SpanBuilder tb = tracer.buildSpan("receiving");

        Span span = tb.start();

        try(Scope scope = sm.activate(span)) {
            span.setTag(DDTags.SERVICE_NAME, "Consumer");
            span.setTag(DDTags.RESOURCE_NAME, "receive message");
            span.setTag(DDTags.SPAN_TYPE, "web");

            QueueConsumer consumer = new QueueConsumer("spring-boot", tracer);
            Thread consumerThread = new Thread(consumer);
            consumerThread.start();
            span.finish();
        }

    }
}