package com.datadog.pej;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.TimeoutException;

import datadog.trace.api.DDTags;
import io.opentracing.Scope;
import io.opentracing.Span;
import io.opentracing.Tracer;
import io.opentracing.contrib.rabbitmq.TracingChannel;
import okhttp3.Call;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;
import org.apache.commons.lang3.SerializationUtils;

import com.rabbitmq.client.AMQP.BasicProperties;
import com.rabbitmq.client.Consumer;
import com.rabbitmq.client.Envelope;
import com.rabbitmq.client.ShutdownSignalException;


/**
 * The endpoint that consumes messages off of the queue. Happens to be runnable.
 * @author syntx
 *
 */
public class QueueConsumer extends EndPoint implements Runnable, Consumer{

    Tracer tracer;

    public QueueConsumer(String endPointName, Tracer tracer) throws IOException, TimeoutException {
        super(endPointName);
        this.tracer = tracer;
    }

    public void run() {
        try {
            //start consuming messages. Auto acknowledge messages.
            TracingChannel tracingChannel = new TracingChannel(channel, tracer);
            //channel.basicConsume(endPointName, true,this);
            tracingChannel.basicConsume(endPointName, true,this);

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Called when consumer is registered.
     */
    public void handleConsumeOk(String consumerTag) {
        System.out.println("Consumer "+consumerTag +" registered");
    }

    /**
     * Called when new message is available.
     */
    /*public void handleDelivery(String consumerTag, Envelope env,
                               BasicProperties props, byte[] body) throws IOException {
        Map map = (HashMap)SerializationUtils.deserialize(body);
        System.out.println("Message Number "+ map.get("message number") + " received.");

    }*/

    public void handleDelivery(String consumerTag, Envelope env,
                               BasicProperties props, byte[] body) throws IOException {
        Map map = (HashMap)SerializationUtils.deserialize(body);
        System.out.println("Message Number "+ map.get("message number") + " received.");

        Span resultingspan = tracer.activeSpan();


        Tracer.SpanBuilder httpspan = tracer.buildSpan("okhttp google").asChildOf(resultingspan);
        Span childspan = httpspan.start();

        try(Scope scope = tracer.activateSpan(childspan)){
            childspan.setTag(DDTags.RESOURCE_NAME, "GET /");
            childspan.setTag(DDTags.SPAN_TYPE, "web");
            childspan.setTag(DDTags.SERVICE_NAME, "Google");
            httpRequest();
            System.out.println("Hello google");
            childspan.finish();
        }


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


    public void handleCancel(String consumerTag) {}
    public void handleCancelOk(String consumerTag) {}
    public void handleRecoverOk(String consumerTag) {}
    public void handleShutdownSignal(String consumerTag, ShutdownSignalException arg1) {}
}