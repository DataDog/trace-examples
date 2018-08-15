FROM ruby:2.5.1

# install build-tools
RUN apt-get update -qq \
  && apt-get install -y \
       build-essential \
       nodejs

# install application dependencies
RUN mkdir /app
WORKDIR /app
COPY Gemfile /app
COPY Gemfile.lock /app
RUN bundle install

CMD ["bundle", "exec", "unicorn", "-c", "config/unicorn.rb", "-l", "3000"]
