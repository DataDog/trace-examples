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
import io.opentracing.Tracer;

import java.io.IOException;
import java.net.InetAddress;
import java.util.Map;

public class TracingConnection implements Connection {

  private final Connection connection;

  private final Tracer tracer;

  public TracingConnection(Connection connection, Tracer tracer) {
    this.connection = connection;
    this.tracer = tracer;
  }

  @Override
  public InetAddress getAddress() {
    return connection.getAddress();
  }

  @Override
  public int getPort() {
    return connection.getPort();
  }

  @Override
  public int getChannelMax() {
    return connection.getChannelMax();
  }

  @Override
  public int getFrameMax() {
    return connection.getFrameMax();
  }

  @Override
  public int getHeartbeat() {
    return connection.getHeartbeat();
  }

  @Override
  public Map<String, Object> getClientProperties() {
    return connection.getClientProperties();
  }

  @Override
  public String getClientProvidedName() {
    return connection.getClientProvidedName();
  }

  @Override
  public Map<String, Object> getServerProperties() {
    return connection.getServerProperties();
  }

  @Override
  public Channel createChannel() throws IOException {
    return new TracingChannel(connection.createChannel(), tracer);
  }

  @Override
  public Channel createChannel(int channelNumber) throws IOException {
    return new TracingChannel(connection.createChannel(channelNumber), tracer);
  }

  @Override
  public void close() throws IOException {
    connection.close();
  }

  @Override
  public void close(int closeCode, String closeMessage) throws IOException {
    connection.close(closeCode, closeMessage);
  }

  @Override
  public void close(int timeout) throws IOException {
    connection.close(timeout);
  }

  @Override
  public void close(int closeCode, String closeMessage, int timeout) throws IOException {
    connection.close(closeCode, closeMessage, timeout);
  }

  @Override
  public void abort() {
    connection.abort();
  }

  @Override
  public void abort(int closeCode, String closeMessage) {
    connection.abort(closeCode, closeMessage);
  }

  @Override
  public void abort(int timeout) {
    connection.abort(timeout);
  }

  @Override
  public void abort(int closeCode, String closeMessage, int timeout) {
    connection.abort(closeCode, closeMessage, timeout);
  }

  @Override
  public void addBlockedListener(BlockedListener listener) {
    connection.addBlockedListener(listener);
  }

  @Override
  public BlockedListener addBlockedListener(BlockedCallback blockedCallback,
      UnblockedCallback unblockedCallback) {
    return connection.addBlockedListener(blockedCallback, unblockedCallback);
  }

  @Override
  public boolean removeBlockedListener(BlockedListener listener) {
    return connection.removeBlockedListener(listener);
  }

  @Override
  public void clearBlockedListeners() {
    connection.clearBlockedListeners();
  }

  @Override
  public ExceptionHandler getExceptionHandler() {
    return connection.getExceptionHandler();
  }

  @Override
  public String getId() {
    return connection.getId();
  }

  @Override
  public void setId(String id) {
    connection.setId(id);
  }

  @Override
  public void addShutdownListener(ShutdownListener listener) {
    connection.addShutdownListener(listener);
  }

  @Override
  public void removeShutdownListener(ShutdownListener listener) {
    connection.removeShutdownListener(listener);
  }

  @Override
  public ShutdownSignalException getCloseReason() {
    return connection.getCloseReason();
  }

  @Override
  public void notifyListeners() {
    connection.notifyListeners();
  }

  @Override
  public boolean isOpen() {
    return connection.isOpen();
  }
}
