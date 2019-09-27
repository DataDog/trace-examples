FROM ruby:2.5.5

# install build-tools
RUN apt-get update -qq \
  && apt-get install -y \
       build-essential \
       libpq-dev \
       nodejs

# install application dependencies
RUN mkdir /app
WORKDIR /app
ADD Gemfile /app
ADD Gemfile.lock /app
RUN gem update bundler
RUN bundle install

#CMD ["bundle", "exec", "rails", "db:setup" ]
CMD ["bundle", "exec", "puma", "-p", "3000"]
