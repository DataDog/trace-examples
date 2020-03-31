## spring-kafka-ot33v2


A detailed step-by-step tutorial on how to implement an Apache Kafka Consumer and Producer using Spring Kafka and Spring Boot.

**_Preliminary tasks and first time steps_**

Install Kafka on mac OSX (High Sierra)

`COMP10619:Kafka pejman.tabassomi$ brew install kafka`

Start Zookeeper

`COMP10619:Kafka pejman.tabassomi$ zookeeper-server-start /usr/local/etc/kafka/zookeeper.properties`

Start kafka server

`COMP10619:Kafka pejman.tabassomi$ kafka-server-start /usr/local/etc/kafka/server.properties`

Create a Topic (First time)

`COMP10619:Kafka pejman.tabassomi$ kafka-topics --create --zookeeper localhost:2181 --replication-factor 1 --partitions 1 --topic users`


**_Administration tasks_** 

Start Zookeeper

`COMP10619:Kafka pejman.tabassomi$ zookeeper-server-start /usr/local/etc/kafka/zookeeper.properties`

Start kafka server

`COMP10619:Kafka pejman.tabassomi$ kafka-server-start /usr/local/etc/kafka/server.properties`

List all Topics

`COMP10619:Kafka pejman.tabassomi$ kafka-topics --list --zookeeper localhost:2181`

Check if data is landing in kafka

`COMP10619:Kafka pejman.tabassomi$ kafka-console-consumer --bootstrap-server localhost:9092 --topic users --from-beginning`


**_Spin up the Datadog Agent (Provide your API key  to the  belown command_** 


`docker run -d --name datadog_agent -v /var/run/docker.sock:/var/run/docker.sock:ro -v /proc/:/host/proc/:ro -v /sys/fs/cgroup/:/host/sys/fs/cgroup:ro -p 127.0.0.1:8126:8126/tcp -e DD_API_KEY=<Your API key> -e DD_APM_ENABLED=true -e DD_APM_IGNORE_RESOURCES="GET /api/random" datadog/agent:latest`


**_Start the spring boot app_**

`COMP10619:spring-kafka-ot33v2 pejman.tabassomi$ java -jar build/libs/spring-kafka-ot33v2.jar`


**_Run the tests_**

`COMP10619:Kafka pejman.tabassomi$ curl localhost:8080/test`
