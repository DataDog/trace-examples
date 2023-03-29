package com.example.messagingrabbitmq;

import java.util.concurrent.CountDownLatch;

import org.springframework.amqp.rabbit.annotation.RabbitListener;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.web.client.RestTemplate;

@Component
public class Receiver {

	private CountDownLatch latch = new CountDownLatch(1);

	@Autowired
	RestTemplate restTemplate;


	@RabbitListener(queues = "${consumer.rabbitmq.queue}")
	public void receiveMessage(String message) {
		System.out.println("Received <" + message + ">");
		String result = restTemplate.getForObject("https://www.google.fr", String.class);
		System.out.println(result);
		latch.countDown();
	}

	public CountDownLatch getLatch() {
		return latch;
	}

}
