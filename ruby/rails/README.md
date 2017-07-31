# Rails 3.2 example

Blog application built using the [official Rails tutorial][1]. This version is meant to test
tracing compatibility issues with early versions of Rails.

[1]: http://guides.rubyonrails.org/getting_started.html

## Compatibility stack

* Ruby 1.9.3
* Rails 3.2.22.4
* MySQL adapter
* Redis adapter
* Unicorn 4.8.3
* Passenger 5.0.28

## Backing services

* MySQL 5.6
* Redis 3.2

## Getting started

Launch all services (it will trigger a Docker build):

    docker-compose up

When the MySQL service is up and running, launch in another console the following
command to create the application database:

    docker-compose run web rake db:create
    docker-compose run web rake db:migrate

Back in the first console, stop docker with CTRL-C, then:

    docker-compose stop
    DD_API_KEY=<your Datadog key> docker-compose up

The above sends data to your Datadog account if the ``DD_API_KEY`` is valid.

### Note about the build

Dependencies listed in the ``Gemfile`` are installed at build time, so if you need to change
them for any reason, remember to re-build the container via:

    docker-compose build
