package com.datadog.pej;

import io.opentracing.Tracer;
import io.opentracing.contrib.rabbitmq.TracingChannel;
import org.apache.commons.lang3.SerializationUtils;

import java.io.IOException;
import java.io.Serializable;
import java.util.concurrent.TimeoutException;


/**
 * The producer endpoint that writes to the queue.
 * @author syntx
 *
 */
public class Producer extends EndPoint{

    Tracer tracer;
    public Producer(String endPointName, Tracer tracer) throws IOException, TimeoutException {
        super(endPointName);
        this.tracer = tracer;
    }

    public void sendMessage(Serializable object) throws IOException {
        TracingChannel tracingChannel = new TracingChannel(channel, tracer);
        //channel.basicPublish("",endPointName, null, SerializationUtils.serialize(object));
        tracingChannel.basicPublish("",endPointName, null, SerializationUtils.serialize(object));
    }
}
