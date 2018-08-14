# Rails 5.2 example

Blog application built using the [official Rails tutorial][1]. This version is meant to test
tracing compatibility issues with Rails.

[1]: http://guides.rubyonrails.org/getting_started.html

## Compatibility stack

* Ruby 2.5.1
* Rails 5.2.1
* MySQL adapter (mysql2)
* Unicorn 5.4.1
* Passenger 5.3.4

## Backing services

* MySQL 8.0
* Memcached 1.5

## Getting started

Launch all services (it will trigger a Docker build):

    docker-compose up

When the MySQL service is up and running, launch in another console the following
command to create the application database:

    docker-compose run --rm web rake db:create
    docker-compose run --rm web rake db:migrate

Back in the first console, stop docker with CTRL-C, then:

    docker-compose stop
    DD_API_KEY=<your Datadog key> docker-compose up

The above sends data to your Datadog account if the ``DD_API_KEY`` is valid.

Alternatively, if you use Direnv, you can copy the `.envrc.sample` file to `.envrc`, insert your API key, then run `direnv allow .` to load the API key into your environment. In which case you can run to start the application:

    docker-compose up

### Note about the build

Dependencies listed in the ``Gemfile`` are installed at build time, so if you need to change
them for any reason, remember to remove then re-build the image via:

    docker-compose build

### Using the example application

Access the blog at http://localhost:3000/ and login using the default user/password `me`/`123456`.
