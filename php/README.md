## Welcome to the php tracer sample apps

### Laravel 4.2 APM sample app

Start up an agent: you have to set the env `DATADOG_API_KEY` with your own DD api key.

    $ docker-composer up -d agent

Build the image you are interested in:

    # Php 5.6 - Apache - Laravel 4.2
    $ docker-compose build laravel42_php56_apache

Run the image you intend to try out:

    # Php 5.6 - Apache - Laravel 4.2
    $ docker-compose up laravel42_php56_apache

The following end points are available

    GET: /                          : the Laravel welcome screen
    GET: /distributed-tracing       : a distributed tracing example

Now you should be able to visit [localhost:8042](http://localhost:8042/) and see the Laravel welcome screen.
A trace should be generated and be available for viewing in the [APM UI](https://app.datadoghq.com/apm/services)
within a minute or two.

## TMP Playing with thrift

Thrift support has been temporarily added to Symfony 3.4 and php 5.6 application.

Example of usage:

```
docker-compose build symfony34_php56_apache
docker-compose up symfony34_php56_apache

# On another shell
docker-compose exec symfony34_php56_apache bash
> cd /var/www/public
> DD_TRACE_DEBUG=true php -S 0.0.0.0:9876 server.php

# On a thrid shell
docker-compose exec symfony34_php56_apache bash
> cd /var/www/public

# Use the local simple server to
> php client.php 127.0.0.1 9876 ''
Used thrift to calculate sum of 1 + 1 = 2

# Use the real symfony 3.4 endpoint to demonstrate tgat it works
> php client.php 127.0.0.1 80 '/thrift-server'
Used thrift to calculate sum of 1 + 1 = 2
```
