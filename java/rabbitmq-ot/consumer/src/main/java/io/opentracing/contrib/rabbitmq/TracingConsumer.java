/*
 * Copyright 2017-2019 The OpenTracing Authors
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */
package io.opentracing.contrib.rabbitmq;

import com.rabbitmq.client.AMQP;
import com.rabbitmq.client.Consumer;
import com.rabbitmq.client.Envelope;
import com.rabbitmq.client.ShutdownSignalException;
import io.opentracing.Scope;
import io.opentracing.Span;
import io.opentracing.Tracer;

import java.io.IOException;


public class TracingConsumer implements Consumer {

  private final Consumer consumer;
  private final String queue;
  private final Tracer tracer;

  public TracingConsumer(Consumer consumer, String queue, Tracer tracer) {
    this.consumer = consumer;
    this.queue = queue;
    this.tracer = tracer;
  }

  @Override
  public void handleConsumeOk(String consumerTag) {
    consumer.handleConsumeOk(consumerTag);
  }

  @Override
  public void handleCancelOk(String consumerTag) {
    consumer.handleCancelOk(consumerTag);

  }

  @Override
  public void handleCancel(String consumerTag) throws IOException {
    consumer.handleCancel(consumerTag);

  }

  @Override
  public void handleShutdownSignal(String consumerTag, ShutdownSignalException sig) {
    consumer.handleShutdownSignal(consumerTag, sig);
  }

  @Override
  public void handleRecoverOk(String consumerTag) {
    consumer.handleRecoverOk(consumerTag);
  }

  @Override
  public void handleDelivery(String consumerTag, Envelope envelope, AMQP.BasicProperties properties,
      byte[] body) throws IOException {
    Span child = TracingUtils.buildChildSpan(properties, queue, tracer);

    try (Scope ignored = tracer.scopeManager().activate(child)) {
      consumer.handleDelivery(consumerTag, envelope, properties, body);
    } finally {
      child.finish();
    }
  }
}
