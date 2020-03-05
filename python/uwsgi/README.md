# uWSGI Example

## Setup

Clone this repository and from the `trace-examples/python/uwsgi` run the following to build the Docker image and start the example app with uWSGI.  **Note that debug logging has been enabled for the tracing library in the `docker-compose.yml` file and the agent log level is currently set to `info`**

```
DD_API_KEY=<your_api_key> docker-compose up --build
```
Go to your local browser and visit `localhost:8080` to generate traces.