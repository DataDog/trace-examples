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

import com.rabbitmq.client.*;
import datadog.trace.api.DDTags;
import io.opentracing.Scope;
import io.opentracing.Span;
import io.opentracing.Tracer;
import io.opentracing.util.GlobalTracer;

import java.io.IOException;
import java.util.Map;
import java.util.concurrent.CompletableFuture;
import java.util.concurrent.TimeoutException;

import static io.opentracing.contrib.rabbitmq.TracingUtils.buildSpan;
import static io.opentracing.contrib.rabbitmq.TracingUtils.inject;


public class TracingChannel implements Channel {

  private final Channel channel;
  private final Tracer tracer;

  public TracingChannel(Channel channel, Tracer tracer) {
    this.channel = channel;
    this.tracer = tracer;
  }

  /**
   * GlobalTracer is used to get tracer
   */
  public TracingChannel(Channel channel) {
    this(channel, GlobalTracer.get());
  }

  @Override
  public int getChannelNumber() {
    return channel.getChannelNumber();
  }

  @Override
  public Connection getConnection() {
    return channel.getConnection();
  }

  @Override
  public void close() throws IOException, TimeoutException {
    channel.close();
  }

  @Override
  public void close(int closeCode, String closeMessage) throws IOException, TimeoutException {
    channel.close(closeCode, closeMessage);
  }

  @Override
  public void abort() throws IOException {
    channel.abort();
  }

  @Override
  public void abort(int closeCode, String closeMessage) throws IOException {
    channel.abort(closeCode, closeMessage);
  }

  @Override
  public void addReturnListener(ReturnListener listener) {
    channel.addReturnListener(listener);
  }

  @Override
  public ReturnListener addReturnListener(ReturnCallback returnCallback) {
    return channel.addReturnListener(returnCallback);
  }

  @Override
  public boolean removeReturnListener(ReturnListener listener) {
    return channel.removeReturnListener(listener);
  }

  @Override
  public void clearReturnListeners() {
    channel.clearReturnListeners();
  }

  @Override
  public void addConfirmListener(ConfirmListener listener) {
    channel.addConfirmListener(listener);
  }

  @Override
  public ConfirmListener addConfirmListener(ConfirmCallback confirmCallback,
      ConfirmCallback confirmCallback1) {
    return channel.addConfirmListener(confirmCallback, confirmCallback1);
  }

  @Override
  public boolean removeConfirmListener(ConfirmListener listener) {
    return channel.removeConfirmListener(listener);
  }

  @Override
  public void clearConfirmListeners() {
    channel.clearConfirmListeners();
  }

  @Override
  public Consumer getDefaultConsumer() {
    return channel.getDefaultConsumer();
  }

  @Override
  public void setDefaultConsumer(Consumer consumer) {
    channel.setDefaultConsumer(consumer);
  }

  @Override
  public void basicQos(int prefetchSize, int prefetchCount, boolean global) throws IOException {
    channel.basicQos(prefetchSize, prefetchCount, global);
  }

  @Override
  public void basicQos(int prefetchCount, boolean global) throws IOException {
    channel.basicQos(prefetchCount, global);
  }

  @Override
  public void basicQos(int prefetchCount) throws IOException {
    channel.basicQos(prefetchCount);
  }

  @Override
  public void basicPublish(String exchange, String routingKey, AMQP.BasicProperties props,
      byte[] body) throws IOException {
    basicPublish(exchange, routingKey, false, false, props, body);
  }

  @Override
  public void basicPublish(String exchange, String routingKey, boolean mandatory,
      AMQP.BasicProperties props, byte[] body) throws IOException {
    basicPublish(exchange, routingKey, mandatory, false, props, body);
  }

  @Override
  public void basicPublish(String exchange, String routingKey, boolean mandatory, boolean immediate,
      AMQP.BasicProperties props, byte[] body) throws IOException {

    Span span = buildSpan(exchange, routingKey, props, tracer);
    try (Scope ignored = tracer.scopeManager().activate(span)) {
      AMQP.BasicProperties properties = inject(props, span, tracer);
      channel.basicPublish(exchange, routingKey, mandatory, immediate, properties, body);
    } finally {
      span.finish();
    }
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, String type) throws IOException {
    return channel.exchangeDeclare(exchange, type);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, BuiltinExchangeType type)
      throws IOException {
    return channel.exchangeDeclare(exchange, type);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, String type, boolean durable)
      throws IOException {
    return channel.exchangeDeclare(exchange, type, durable);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, BuiltinExchangeType type,
      boolean durable) throws IOException {
    return channel.exchangeDeclare(exchange, type, durable);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, String type, boolean durable,
      boolean autoDelete, Map<String, Object> arguments) throws IOException {
    return channel.exchangeDeclare(exchange, type, durable, autoDelete, arguments);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, BuiltinExchangeType type,
      boolean durable, boolean autoDelete, Map<String, Object> arguments) throws IOException {
    return channel.exchangeDeclare(exchange, type, durable, autoDelete, arguments);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, String type, boolean durable,
      boolean autoDelete, boolean internal, Map<String, Object> arguments) throws IOException {
    return channel.exchangeDeclare(exchange, type, durable, autoDelete, internal, arguments);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclare(String exchange, BuiltinExchangeType type,
      boolean durable, boolean autoDelete, boolean internal, Map<String, Object> arguments)
      throws IOException {
    return channel.exchangeDeclare(exchange, type, durable, autoDelete, internal, arguments);
  }

  @Override
  public void exchangeDeclareNoWait(String exchange, String type, boolean durable,
      boolean autoDelete, boolean internal, Map<String, Object> arguments) throws IOException {
    channel.exchangeDeclareNoWait(exchange, type, durable, autoDelete, internal, arguments);
  }

  @Override
  public void exchangeDeclareNoWait(String exchange, BuiltinExchangeType type, boolean durable,
      boolean autoDelete, boolean internal, Map<String, Object> arguments) throws IOException {
    channel.exchangeDeclareNoWait(exchange, type, durable, autoDelete, internal, arguments);
  }

  @Override
  public AMQP.Exchange.DeclareOk exchangeDeclarePassive(String name) throws IOException {
    return channel.exchangeDeclarePassive(name);
  }

  @Override
  public AMQP.Exchange.DeleteOk exchangeDelete(String exchange, boolean ifUnused)
      throws IOException {
    return channel.exchangeDelete(exchange, ifUnused);
  }

  @Override
  public void exchangeDeleteNoWait(String exchange, boolean ifUnused) throws IOException {
    channel.exchangeDeleteNoWait(exchange, ifUnused);
  }

  @Override
  public AMQP.Exchange.DeleteOk exchangeDelete(String exchange) throws IOException {
    return channel.exchangeDelete(exchange);
  }

  @Override
  public AMQP.Exchange.BindOk exchangeBind(String destination, String source, String routingKey)
      throws IOException {
    return channel.exchangeBind(destination, source, routingKey);
  }

  @Override
  public AMQP.Exchange.BindOk exchangeBind(String destination, String source, String routingKey,
      Map<String, Object> arguments) throws IOException {
    return channel.exchangeBind(destination, source, routingKey, arguments);
  }

  @Override
  public void exchangeBindNoWait(String destination, String source, String routingKey,
      Map<String, Object> arguments) throws IOException {
    channel.exchangeBindNoWait(destination, source, routingKey, arguments);
  }

  @Override
  public AMQP.Exchange.UnbindOk exchangeUnbind(String destination, String source, String routingKey)
      throws IOException {
    return channel.exchangeUnbind(destination, source, routingKey);
  }

  @Override
  public AMQP.Exchange.UnbindOk exchangeUnbind(String destination, String source, String routingKey,
      Map<String, Object> arguments) throws IOException {
    return channel.exchangeUnbind(destination, source, routingKey, arguments);
  }

  @Override
  public void exchangeUnbindNoWait(String destination, String source, String routingKey,
      Map<String, Object> arguments) throws IOException {
    channel.exchangeUnbindNoWait(destination, source, routingKey, arguments);
  }

  @Override
  public AMQP.Queue.DeclareOk queueDeclare() throws IOException {
    return channel.queueDeclare();
  }

  @Override
  public AMQP.Queue.DeclareOk queueDeclare(String queue, boolean durable, boolean exclusive,
      boolean autoDelete, Map<String, Object> arguments) throws IOException {
    return channel.queueDeclare(queue, durable, exclusive, autoDelete, arguments);
  }

  @Override
  public void queueDeclareNoWait(String queue, boolean durable, boolean exclusive,
      boolean autoDelete, Map<String, Object> arguments) throws IOException {
    channel.queueDeclareNoWait(queue, durable, exclusive, autoDelete, arguments);
  }

  @Override
  public AMQP.Queue.DeclareOk queueDeclarePassive(String queue) throws IOException {
    return channel.queueDeclarePassive(queue);
  }

  @Override
  public AMQP.Queue.DeleteOk queueDelete(String queue) throws IOException {
    return channel.queueDelete(queue);
  }

  @Override
  public AMQP.Queue.DeleteOk queueDelete(String queue, boolean ifUnused, boolean ifEmpty)
      throws IOException {
    return channel.queueDelete(queue, ifUnused, ifEmpty);
  }

  @Override
  public void queueDeleteNoWait(String queue, boolean ifUnused, boolean ifEmpty)
      throws IOException {
    channel.queueDeleteNoWait(queue, ifUnused, ifEmpty);
  }

  @Override
  public AMQP.Queue.BindOk queueBind(String queue, String exchange, String routingKey)
      throws IOException {
    return channel.queueBind(queue, exchange, routingKey);
  }

  @Override
  public AMQP.Queue.BindOk queueBind(String queue, String exchange, String routingKey,
      Map<String, Object> arguments) throws IOException {
    return channel.queueBind(queue, exchange, routingKey, arguments);
  }

  @Override
  public void queueBindNoWait(String queue, String exchange, String routingKey,
      Map<String, Object> arguments) throws IOException {
    channel.queueBindNoWait(queue, exchange, routingKey, arguments);
  }

  @Override
  public AMQP.Queue.UnbindOk queueUnbind(String queue, String exchange, String routingKey)
      throws IOException {
    return channel.queueUnbind(queue, exchange, routingKey);
  }

  @Override
  public AMQP.Queue.UnbindOk queueUnbind(String queue, String exchange, String routingKey,
      Map<String, Object> arguments) throws IOException {
    return channel.queueUnbind(queue, exchange, routingKey, arguments);
  }

  @Override
  public AMQP.Queue.PurgeOk queuePurge(String queue) throws IOException {
    return channel.queuePurge(queue);
  }

  @Override
  public GetResponse basicGet(String queue, boolean autoAck) throws IOException {
    GetResponse response = channel.basicGet(queue, autoAck);
    if (response != null) {
      TracingUtils.buildAndFinishChildSpan(response.getProps(), queue, tracer);
    }
    return response;
  }

  @Override
  public void basicAck(long deliveryTag, boolean multiple) throws IOException {
    channel.basicAck(deliveryTag, multiple);
  }

  @Override
  public void basicNack(long deliveryTag, boolean multiple, boolean requeue) throws IOException {
    channel.basicNack(deliveryTag, multiple, requeue);
  }

  @Override
  public void basicReject(long deliveryTag, boolean requeue) throws IOException {
    channel.basicReject(deliveryTag, requeue);
  }

  @Override
  public String basicConsume(String queue, Consumer callback) throws IOException {
    return basicConsume(queue, false, "", false, false, null, callback);
  }

  @Override
  public String basicConsume(String queue, DeliverCallback deliverCallback,
      CancelCallback cancelCallback) throws IOException {
    return channel
        .basicConsume(queue, new TracingDeliverCallback(deliverCallback, queue, tracer),
            cancelCallback);
  }

  @Override
  public String basicConsume(String queue, DeliverCallback deliverCallback,
      ConsumerShutdownSignalCallback shutdownSignalCallback) throws IOException {
    return channel.basicConsume(queue, new TracingDeliverCallback(deliverCallback, queue, tracer),
        shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, DeliverCallback deliverCallback,
      CancelCallback cancelCallback, ConsumerShutdownSignalCallback shutdownSignalCallback)
      throws IOException {
    return channel
        .basicConsume(queue, new TracingDeliverCallback(deliverCallback, queue, tracer),
            cancelCallback,
            shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, Consumer callback) throws IOException {
    return basicConsume(queue, autoAck, "", false, false, null, callback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, DeliverCallback deliverCallback,
      CancelCallback cancelCallback) throws IOException {
    return channel
        .basicConsume(queue, autoAck, new TracingDeliverCallback(deliverCallback, queue, tracer),
            cancelCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, DeliverCallback deliverCallback,
      ConsumerShutdownSignalCallback shutdownSignalCallback) throws IOException {
    return channel
        .basicConsume(queue, autoAck, new TracingDeliverCallback(deliverCallback, queue, tracer),
            shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, DeliverCallback deliverCallback,
      CancelCallback cancelCallback, ConsumerShutdownSignalCallback shutdownSignalCallback)
      throws IOException {
    return channel
        .basicConsume(queue, autoAck, new TracingDeliverCallback(deliverCallback, queue, tracer),
            cancelCallback, shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, Map<String, Object> arguments,
      Consumer callback) throws IOException {
    return basicConsume(queue, autoAck, "", false, false, arguments, callback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, Map<String, Object> arguments,
      DeliverCallback deliverCallback, CancelCallback cancelCallback) throws IOException {
    return channel.basicConsume(queue, autoAck, arguments,
        new TracingDeliverCallback(deliverCallback, queue, tracer), cancelCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, Map<String, Object> arguments,
      DeliverCallback deliverCallback, ConsumerShutdownSignalCallback shutdownSignalCallback)
      throws IOException {
    return channel.basicConsume(queue, autoAck, arguments,
        new TracingDeliverCallback(deliverCallback, queue, tracer), shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, Map<String, Object> arguments,
      DeliverCallback deliverCallback, CancelCallback cancelCallback,
      ConsumerShutdownSignalCallback shutdownSignalCallback) throws IOException {
    return channel
        .basicConsume(queue, autoAck, arguments,
            new TracingDeliverCallback(deliverCallback, queue, tracer), cancelCallback,
            shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag, Consumer callback)
      throws IOException {
    return basicConsume(queue, autoAck, consumerTag, false, false, null, callback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag,
      DeliverCallback deliverCallback, CancelCallback cancelCallback) throws IOException {
    return channel.basicConsume(queue, autoAck, consumerTag,
        new TracingDeliverCallback(deliverCallback, queue, tracer), cancelCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag,
      DeliverCallback deliverCallback, ConsumerShutdownSignalCallback shutdownSignalCallback)
      throws IOException {
    return channel.basicConsume(queue, autoAck, consumerTag,
        new TracingDeliverCallback(deliverCallback, queue, tracer), shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag,
      DeliverCallback deliverCallback, CancelCallback cancelCallback,
      ConsumerShutdownSignalCallback shutdownSignalCallback) throws IOException {
    return channel
        .basicConsume(queue, autoAck, consumerTag,
            new TracingDeliverCallback(deliverCallback, queue, tracer), cancelCallback,
            shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag, boolean noLocal,
      boolean exclusive, Map<String, Object> arguments, Consumer callback) throws IOException {
    return channel.basicConsume(queue, autoAck, consumerTag, noLocal, exclusive, arguments,
        new TracingConsumer(callback, queue, tracer));
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag, boolean noLocal,
      boolean exclusive, Map<String, Object> arguments, DeliverCallback deliverCallback,
      CancelCallback cancelCallback) throws IOException {
    return channel.basicConsume(queue, autoAck, consumerTag, noLocal, exclusive, arguments,
        new TracingDeliverCallback(deliverCallback, queue, tracer), cancelCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag, boolean noLocal,
      boolean exclusive, Map<String, Object> arguments, DeliverCallback deliverCallback,
      ConsumerShutdownSignalCallback shutdownSignalCallback) throws IOException {
    return channel
        .basicConsume(queue, autoAck, consumerTag, noLocal, exclusive, arguments,
            new TracingDeliverCallback(deliverCallback, queue, tracer), shutdownSignalCallback);
  }

  @Override
  public String basicConsume(String queue, boolean autoAck, String consumerTag, boolean noLocal,
      boolean exclusive, Map<String, Object> arguments, DeliverCallback deliverCallback,
      CancelCallback cancelCallback, ConsumerShutdownSignalCallback shutdownSignalCallback)
      throws IOException {
    return channel
        .basicConsume(queue, autoAck, consumerTag, noLocal, exclusive, arguments,
            new TracingDeliverCallback(deliverCallback, queue, tracer),
            cancelCallback, shutdownSignalCallback);
  }

  @Override
  public void basicCancel(String consumerTag) throws IOException {
    channel.basicCancel(consumerTag);
  }

  @Override
  public AMQP.Basic.RecoverOk basicRecover() throws IOException {
    return channel.basicRecover();
  }

  @Override
  public AMQP.Basic.RecoverOk basicRecover(boolean requeue) throws IOException {
    return channel.basicRecover(requeue);
  }

  @Override
  public AMQP.Tx.SelectOk txSelect() throws IOException {
    return channel.txSelect();
  }

  @Override
  public AMQP.Tx.CommitOk txCommit() throws IOException {
    return channel.txCommit();
  }

  @Override
  public AMQP.Tx.RollbackOk txRollback() throws IOException {
    return channel.txRollback();
  }

  @Override
  public AMQP.Confirm.SelectOk confirmSelect() throws IOException {
    return channel.confirmSelect();
  }

  @Override
  public long getNextPublishSeqNo() {
    return channel.getNextPublishSeqNo();
  }

  @Override
  public boolean waitForConfirms() throws InterruptedException {
    return channel.waitForConfirms();
  }

  @Override
  public boolean waitForConfirms(long timeout) throws InterruptedException, TimeoutException {
    return channel.waitForConfirms(timeout);
  }

  @Override
  public void waitForConfirmsOrDie() throws IOException, InterruptedException {
    channel.waitForConfirmsOrDie();
  }

  @Override
  public void waitForConfirmsOrDie(long timeout)
      throws IOException, InterruptedException, TimeoutException {
    channel.waitForConfirmsOrDie(timeout);
  }

  @Override
  public void asyncRpc(Method method) throws IOException {
    channel.asyncRpc(method);
  }

  @Override
  public Command rpc(Method method) throws IOException {
    return channel.rpc(method);
  }

  @Override
  public long messageCount(String queue) throws IOException {
    return channel.messageCount(queue);
  }

  @Override
  public long consumerCount(String queue) throws IOException {
    return channel.consumerCount(queue);
  }

  @Override
  public CompletableFuture<Command> asyncCompletableRpc(
      Method method) throws IOException {
    return channel.asyncCompletableRpc(method);
  }

  @Override
  public void addShutdownListener(ShutdownListener listener) {
    channel.addShutdownListener(listener);
  }

  @Override
  public void removeShutdownListener(ShutdownListener listener) {
    channel.removeShutdownListener(listener);
  }

  @Override
  public ShutdownSignalException getCloseReason() {
    return channel.getCloseReason();
  }

  @Override
  public void notifyListeners() {
    channel.notifyListeners();
  }

  @Override
  public boolean isOpen() {
    return channel.isOpen();
  }

}
