package com.datadog.pej.kafka.producer;

import lombok.extern.slf4j.Slf4j;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.kafka.core.KafkaTemplate;

@Slf4j
public class Sender {

  @Autowired
  private KafkaTemplate<String, String> kafkaTemplate;

  public void send(String payload) {
    //LOGGER.info("sending payload='{}'", payload);
    log.info("sending payload='{}'", payload);
    kafkaTemplate.send("users", payload);
  }
}
