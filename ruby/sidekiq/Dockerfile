FROM ruby:2.5.1

# install application dependencies
RUN mkdir /app
WORKDIR /app
ADD Gemfile /app
ADD Gemfile.lock /app
RUN bundle install

CMD ["bundle", "exec", "ruby", "app.rb"]
