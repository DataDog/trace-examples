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

Launch application:

    docker-compose up

In a second console:

    docker-compose run web rake db:create
    docker-compose run web rake db:migrate

Back in the first console, stop docker with CTRL-C, then:

    docker-compose stop
    docker-compose up
