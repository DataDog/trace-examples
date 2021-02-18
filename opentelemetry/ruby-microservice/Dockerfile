FROM ruby:2.6.3

RUN gem install bundler
ENV BUNDLE_PATH "/bundle"

RUN mkdir -p /sinatra-multivac
WORKDIR /sinatra-multivac

COPY . /sinatra-multivac

RUN bundle install --no-cache

EXPOSE 3000

CMD ["bundle", "exec", "puma", "config.ru", "-C", "puma.rb"]
