import datadog.opentracing.DDTracer;
import datadog.trace.api.DDTags;
import io.opentracing.Scope;
import io.opentracing.ScopeManager;
import io.opentracing.Span;
import io.opentracing.Tracer;
import lombok.extern.slf4j.Slf4j;
import okhttp3.Call;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

import java.io.IOException;

@Slf4j
public class Main {

    static Tracer tracer;
    Span parentspan;

    public void doSomeThing() throws InterruptedException, IOException {

        Thread.sleep(120);
        ScopeManager sm = tracer.scopeManager();
        Tracer.SpanBuilder tb = tracer.buildSpan("receiving").asChildOf(parentspan);

        Span span = tb.start();
        try(Scope scope = sm.activate(span)) {
            span.setTag(DDTags.SERVICE_NAME, "ServiceB");
            span.setTag(DDTags.RESOURCE_NAME, "dosomethingmore");
            span.setTag(DDTags.SPAN_TYPE, "web");
            //
            doSomethingMore();
            //
            span.finish();
        }

        System.out.println("In doSometThing...");
    }


    public void doSomethingMore() throws InterruptedException, IOException {
        Thread.sleep(250);
        System.out.println("In doSomethingMore...");
        httpRequest();
    }



    public void httpRequest() throws IOException {
        OkHttpClient client = new OkHttpClient();
        Request request = new Request.Builder()
                .url("https://www.google.fr")
                .build();

        Call call = client.newCall(request);
        Response response = call.execute();
        System.out.println("http response code: " + response.code());
    }


    public static void main(String[] args) throws InterruptedException, IOException {

        Main mn  = new Main();
        //Tracer tracer = new DDTracer("ServiceA");

        //tracer  = new DDTracer.DDTracerBuilder().build();
        tracer = DDTracer.builder().build();


        Span span  = tracer.buildSpan("sending").start();
        try(Scope scope = tracer.activateSpan(span)){
            span.setTag(DDTags.SERVICE_NAME, "ServiceA");
            span.setTag(DDTags.RESOURCE_NAME, "dosomething");
            span.setTag(DDTags.SPAN_TYPE, "web");
            Thread.sleep(20);
            System.out.println("In main...");
            mn.doSomeThing();
            span.finish();
        }

        //System.out.println("hh");
        log.info("Terminating...");
        System.exit(0);

    }
}
