package com.datadog.pej.kafka.producer;

import java.util.HashMap;
import java.util.Map;

import io.opentracing.Tracer;
import io.opentracing.contrib.kafka.spring.TracingProducerFactory;
import org.apache.kafka.clients.producer.ProducerConfig;
import org.apache.kafka.common.serialization.StringSerializer;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.kafka.core.DefaultKafkaProducerFactory;
import org.springframework.kafka.core.KafkaTemplate;
import org.springframework.kafka.core.ProducerFactory;

@Configuration
public class SenderConfig {

  @Value("${kafka.bootstrap-servers}")
  private String bootstrapServers;

  @Autowired
  Tracer tracer;

  @Bean
  public Map<String, Object> producerConfigs() {
    Map<String, Object> props = new HashMap<>();
    // list of host:port pairs used for establishing the initial connections to the Kakfa cluster
    props.put(ProducerConfig.BOOTSTRAP_SERVERS_CONFIG,
        bootstrapServers);
    props.put(ProducerConfig.KEY_SERIALIZER_CLASS_CONFIG,
        StringSerializer.class);
    props.put(ProducerConfig.VALUE_SERIALIZER_CLASS_CONFIG,
        StringSerializer.class);

    return props;
  }

  @Bean
  public ProducerFactory<String, String> producerFactory() {
    //return new DefaultKafkaProducerFactory<>(producerConfigs());
    return new TracingProducerFactory<>(new DefaultKafkaProducerFactory<>(producerConfigs()), tracer);
  }

  @Bean
  public KafkaTemplate<String, String> kafkaTemplate() {
    return new KafkaTemplate<>(producerFactory());
  }

  @Bean
  public Sender sender() {
    return new Sender();
  }
}
