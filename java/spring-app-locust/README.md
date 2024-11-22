## Usage

### Run the app

Download the latest version of the agent:

    wget -O dd-java-agent.jar 'https://search.maven.org/classic/remote_content?g=com.datadoghq&a=dd-java-agent&v=LATEST'

Build the app:

    ./gradlew :spring-app:build

Run the app with the agent enabled:

    java -Ddd.service.name=trace-examples-spring -javaagent:dd-java-agent.jar -jar spring-app/build/libs/trace-examples-0.1.0.jar

### Load test with locust

Locust is based on python.

    pip install -r requirements.txt

Run a load tests

    locust -f locust-files/HomeControllerProfiling.py --no-web -c 100 -r 5
