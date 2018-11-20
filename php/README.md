## Welcome to the php tracer sample apps

### Laravel 4.2 APM sample app

Start up an agent: you have to set the env `DATADOG_API_KEY` with your own DD api key.

    $ docker-composer up -d agent

Build the image you are interested in:

    # Php 5.6 - Apache - Laravel 4.2
    $ docker-compose build laravel42_php56_apache

    # Php 5.6 - Nginx/fpm - Laravel 4.2
    $ docker-compose build laravel42_php56_nginx

Run the image you intend to try out:

    # Php 5.6 - Apache - Laravel 4.2
    $ docker-compose run --rm laravel42_php56_apache composer update
    $ docker-compose up laravel42_php56_apache

    # Php 5.6 - Nginx/fpm - Laravel 4.2
    $ docker-compose run --rm laravel42_php56_nginx composer update
    $ docker-compose up laravel42_php56_nginx
