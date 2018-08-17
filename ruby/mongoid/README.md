# Rails 5.2 + Mongoid example

A web application built on [Rails][1] that uses MongoDB. This version is meant to test
tracing compatibility issues with Rails and MongoDB.

[1]: http://guides.rubyonrails.org/getting_started.html

## Compatibility stack

* Ruby 2.5.1
* Rails 5.2.1
* Mongoid 7.0.1
* Unicorn 5.4.1

## Backing services

* MongoDB 4.1.1

## Getting started

Build the Docker images first with:

    docker-compose build

Then run the application with:

    DD_API_KEY=<your Datadog key> docker-compose up

The above sends data to your Datadog account if the ``DD_API_KEY`` is valid.

Alternatively, if you use Direnv, you can copy the `.envrc.sample` file to `.envrc`, insert your API key, then run `direnv allow .` to load the API key into your environment. In which case you can run to start the application:

    docker-compose up

### Note about the build

Dependencies listed in the ``Gemfile`` are installed at build time, so if you need to change
them for any reason, remember to remove then re-build the image via:

    docker-compose build

### Using the example application

Access the application at http://localhost:3000/ to view a list of documents.
Add a document by visiting http://localhost:3000/document/add
