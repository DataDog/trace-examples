package com.datadog.pej;

import datadog.opentracing.DDTracer;
import datadog.trace.api.DDTags;
import io.opentracing.Scope;
import io.opentracing.ScopeManager;
import io.opentracing.Span;
import io.opentracing.Tracer;

import java.util.HashMap;

public class Main {


    public static void main(String[] args) throws Exception{


        Tracer tracer = DDTracer.builder().build();
        Producer producer = new Producer("spring-boot", tracer);

        for (int i = 0; i < 19; i++) {
            HashMap message = new HashMap();
            ScopeManager sm = tracer.scopeManager();
            Tracer.SpanBuilder tb = tracer.buildSpan("sending");
            message.put("message number", i);
            Span span = tb.start();
            try(Scope scope = sm.activate(span)) {
                span.setTag(DDTags.SERVICE_NAME, "client");
                span.setTag(DDTags.RESOURCE_NAME, "send message");
                span.setTag(DDTags.SPAN_TYPE, "web");
                producer.sendMessage(message);
                System.out.println("Message Number " + i + " sent.");
                Thread.sleep(20);
                //scope.close();
                span.finish();
            }
        }

        System.exit(0);
    }
}