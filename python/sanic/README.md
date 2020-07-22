# [Sanic](https://sanic.readthedocs.io/en/latest/index.html) Example App

This is an example sanic app to instrument the application as well as for testing purposes.

## Code Requirements

- <a href="https://docs.datadoghq.com/agent/basic_agent_usage/?tab=agentv6v7">datadog-agent</a>, 
- <a href="https://docs.datadoghq.com/tracing/setup/python/">datadog-tracing-library</a> & 
- <a href="https://sanic.readthedocs.io/en/latest/index.html">sanic</a>

The best way to get started is to clone this repo and run:

### Execution Option 1:

If you already have datadog agent running, you could run: ```ddtrace-run python basic_app.py```.

### Execution Option 2:

Note: before running ```docker-compose```, update the ```env_file``` (e.g: ```.bashrc```, ```.bash_profile```) which contains ```Datadog API key``` or specify API key as environment variable.

```
docker-compose build --no-cache
docker-compose up
```

Then open your browser to http://0.0.0.0:<your_port>/

### Implementations:

- Basic request and response traces would look like:

![](https://p-qkfgo2.t2.n0.cdn.getcloudapp.com/items/6qu260YR/Image%202020-07-22%20at%2012.25.34%20PM.png?v=88a7c0746edd328bfc44f7313e4b6c72)

![](https://p-qkfgo2.t2.n0.cdn.getcloudapp.com/items/wbuW2Jz8/Image%202020-07-22%20at%2012.27.04%20PM.png?v=db356cda678401d10c4cd526e248fe5c)
