# Rails 3.2 example

Blog application built using the [official Rails tutorial][1]. This version is meant to test
tracing compatibility issues with early versions of Rails.

[1]: http://guides.rubyonrails.org/getting_started.html

## Compatibility stack

* Ruby 2.1.7
* Rails 3.2.22.4
* MySQL adapter
* Redis adapter
* Unicorn 4.8.3
* Passenger 5.0.28

## Backing services

* MySQL 5.6
* Redis 3.2

## Getting started

Launch backing services:

    docker-compose up -d

Set environment variables for POST permissions (Basic AUTH):

    export PROTECTED_USER=me
    export PROTECTED_PASSWORD=123456

Install dependencies and launch Unicorn:

    bundle install
    bundle exec unicorn -c config/unicorn.rb

or Passenger:

    bundle exec passenger start

## Available environment variables

Set the following environment variables during the deploy:

* ``RAILS_ENV=production``
* ``SECRET_KEY_BASE``
* ``DATABASE_URL``
* ``REDIS_URL``
* ``PROTECTED_USER``
* ``PROTECTED_PASSWORD``
