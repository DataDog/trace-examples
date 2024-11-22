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

import com.rabbitmq.client.DeliverCallback;
import com.rabbitmq.client.Delivery;
import io.opentracing.Scope;
import io.opentracing.Span;
import io.opentracing.Tracer;

import java.io.IOException;

public class TracingDeliverCallback implements DeliverCallback {
  private final DeliverCallback deliverCallback;
  private final String queue;
  private final Tracer tracer;

  public TracingDeliverCallback(DeliverCallback deliverCallback, String queue, Tracer tracer) {
    this.deliverCallback = deliverCallback;
    this.queue = queue;
    this.tracer = tracer;
  }

  @Override
  public void handle(String consumerTag, Delivery message) throws IOException {
    Span child = TracingUtils.buildChildSpan(message.getProperties(), queue, tracer);
    try (Scope ignored = tracer.scopeManager().activate(child)) {
      deliverCallback.handle(consumerTag, message);
    } finally {
      child.finish();
    }
  }
}
