## Laravel 4.2 APM sample app

### Usage

From here, go to the php sample apps root.

    $ cd ..

Start up an agent: you have to set the env `DATADOG_API_KEY` with your own DD api key.

    $ docker-composer up -d agent

Build the image you are interested in:

    # Php 5.6 - Apache - Laravel 4.2
    $ docker-compose build laravel42_php56_apache

Run the image you intend to try out:

    # Php 5.6 - Apache - Laravel 4.2
    $ docker-compose run --rm laravel42_php56_apache composer update
    $ docker-compose up laravel42_php56_apache
