require 'bundler/setup'

require 'connection_pool'
require 'date'
require 'json'
require 'redis'
require 'sinatra'
require 'sinatra/json'

require 'ddtrace'
require 'ddtrace/contrib/sinatra/tracer'

require './post.rb'

class App < Sinatra::Application
  REDIS_HOST = ENV['SINATRA_REDIS_HOST'] || '127.0.0.1'.freeze()
  REDIS_PORT = ENV['SINATRA_REDIS_PORT'] || 6379
  SERVICE = ENV['SINATRA_SERVICE'] || 'sinatra-demo'

  configure do
    settings.datadog_tracer.configure(
        default_service: SERVICE,
    )

    pool = ConnectionPool.new(size: 10) do
      Redis.new(host: REDIS_HOST, port: REDIS_PORT)
    end
    set :redis_pool, pool
  end

  helpers do
    def with_redis_conn(&block)
      settings.redis_pool.with do |conn|
        yield conn
      end
    end
  end

  get '/' do
    erb :posts
  end

  get '/api/posts', provides: :json do
    posts = []
    with_redis_conn do |conn|
      posts = Post.load_all(conn)
    end

    post_data = posts.map {|post| post.marshal()}
    json post_data
  end

  get '/api/posts', provides: :html do
    posts = []
    with_redis_conn do |conn|
      posts = Post.load_all(conn)
    end

    erb :posts_fragment, layout: nil, locals: {posts: posts}
  end

  post '/api/posts' do
    data = JSON.parse(request.body.read)

    post = Post.unmarshal(data)
    post.cdate = DateTime.now()
    halt 400, 'invalid post data' unless post.valid?

    with_redis_conn do |conn|
      post.store(conn)
    end

    status 201
    json({id: post.id})
  end
end

App.run!
