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

import com.rabbitmq.client.AddressResolver;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;
import io.opentracing.Tracer;
import io.opentracing.util.GlobalTracer;

import java.io.IOException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.TimeoutException;

public class TracingConnectionFactory extends ConnectionFactory {

  private final Tracer tracer;

  public TracingConnectionFactory(Tracer tracer) {
    this.tracer = tracer;
  }

  /**
   * GlobalTracer is used to get tracer
   */
  public TracingConnectionFactory() {
    this(GlobalTracer.get());
  }

  @Override
  public Connection newConnection(
      ExecutorService executor,
      AddressResolver addressResolver,
      String clientProvidedName
  ) throws IOException, TimeoutException {
    return new TracingConnection(
        super.newConnection(executor, addressResolver, clientProvidedName),
        tracer
    );
  }
}
