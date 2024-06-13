# Datadog Profiling --> Haproxy

This repository contains an example of sending profiles from a simple python app through haproxy. We use docker compose to make reproducing the environment easier,
but this setup should be applicable for other environments as well.

To run from project root: 

1. Create a file called "docker.env" in the project root. This file is not tracked by git because it will contain your API keys. 
2. Put your API key into "docker.env" like below by replacing PLACEHOLDER with the API key:

```
DD_API_KEY=PLACEHOLDER
```

3. To configure dual shipping, insert another line to "docker.env" where you replace PLACEHOLDER with the API key for the different intake. Note: your API key will be different if you are shipping data to another datacenter (e.g. us + eu).

```
DD_API_KEY=PLACEHOLDER
DD_APM_PROFILING_ADDITIONAL_ENDPOINTS: '{"http://proxy:3837/api/v2/profile": ["PLACEHOLDER"]}'
```

4. Run the command below in the project root to start services (note you may have to run `docker-compose up --build` while debugging to make sure new env vars are loaded).

 ```
 docker-compose up
 ```
 
 
 ## Debugging

You can see the haproxy stats page by visiting `http://localhost:3833`, should look something like below. If data is being sent through the proxy, `profiles-forwarder-us` 
show sessions and non-zero in/out bytes. If dual shipping is configured you should also see data for `profiles-forwarder-eu` and both of the backends.

<img width="1858" alt="haproxy-stats-dual-shipping" src="https://user-images.githubusercontent.com/2456071/202285471-9a67ef38-585f-45c7-a2d1-1741159a030f.png">

To make sure that the proper profiling config is set by exec-ing into the agent and printing out config:
- `docker-compose exec -it datadog-agent bash` 
- `agent config | grep -A 20 apm_config`



## Other Resources
### Datadog specific
https://docs.datadoghq.com/agent/proxy/?tab=linux#haproxy
https://docs.datadoghq.com/agent/guide/dual-shipping/?tab=apm#overview

### Haproxy
https://www.haproxy.com/blog/how-to-run-haproxy-with-docker/
https://www.haproxy.com/blog/haproxy-ssl-termination/

### Docker
https://docs.datadoghq.com/agent/guide/compose-and-the-datadog-agent/
https://github.com/DataDog/docker-compose-example
https://docs.docker.com/compose/networking/
