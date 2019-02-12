# Microservices example

This example has all Node APM features enabled on 3 services.

The following modules are automatically instrumented by our integrations:

- express
- graphql
- http
- mongodb-core
- redis
- winston

## Running

```sh
DD_API_KEY=<a_valid_api_key> docker-compose up -d --build
```

## Generating Traces

Visit `http://localhost:8080/users` to generate traces.
