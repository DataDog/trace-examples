package com.datadog.pej.kafka;

import com.datadog.pej.kafka.consumer.Receiver;
import com.datadog.pej.kafka.producer.Sender;
import datadog.trace.api.DDTags;
import io.opentracing.Scope;
import io.opentracing.ScopeManager;
import io.opentracing.Span;
import io.opentracing.Tracer;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.concurrent.TimeUnit;

@RestController
public class KafkaController {

    @Autowired
    private Receiver receiver;

    @Autowired
    private Sender sender;

    @Autowired
    private Tracer tracer;

    @RequestMapping("/test")
    public String index() {


        ScopeManager sm = tracer.scopeManager();

        Tracer.SpanBuilder tb = tracer.buildSpan("servkafkaa");
        Span span = tb.start(); // ou tb.startActive() + casting => Scope


        //try(Scope scope = sm.activate(tb.start())) {
        /*try(Scope scope = sm.activate(span)) {
            //Span span = sm.activeSpan();
            span.setTag(DDTags.RESOURCE_NAME, "GET /test");
            span.setTag(DDTags.SPAN_TYPE, "web");
            sender.send("Un message plus long");
            try {
                Thread.sleep(20L);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
            span.finish();

        }*/

        Scope scope = sm.activate(span);
        span.setTag(DDTags.RESOURCE_NAME, "GET /test");
        span.setTag(DDTags.SPAN_TYPE, "web");
        sender.send("Un message plus long");
        scope.close();
        span.finish();


        return "\ntest";
    }


}
