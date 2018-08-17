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
  REDIS_HOST = ENV['REDIS_HOST'] || 'localhost'.freeze
  REDIS_PORT = ENV['REDIS_PORT'] || 6379
  SERVICE = ENV['SINATRA_SERVICE'] || 'sinatra-demo'
  PROTECTED_USER = ENV['PROTECTED_USER']
  PROTECTED_PASSWORD = ENV['PROTECTED_PASSWORD']
  CREDENTIALS = [PROTECTED_USER, PROTECTED_PASSWORD]
  DATADOG_TRACER = ENV['DATADOG_TRACER']
  PORT = ENV['PORT'] || 3000

  configure do
    Datadog.configure do |c|
      c.tracer hostname: DATADOG_TRACER
      c.use :sinatra, service: SERVICE
    end

    pool = ConnectionPool.new(size: 10) do
      Redis.new(host: REDIS_HOST, port: REDIS_PORT)
    end

    set :redis_pool, pool
    set :port, PORT
    set :bind, '0.0.0.0'
  end

  helpers do
    def with_redis_conn(&block)
      settings.redis_pool.with do |conn|
        yield conn
      end
    end

    def protected!
      return if authorized?
      headers['WWW-Authenticate'] = 'Basic realm="Restricted Area"'
      halt 401, "Not authorized\n"
    end

    def authorized?
      @auth ||= Rack::Auth::Basic::Request.new(request.env)

      return false unless @auth.provided? && @auth.basic?
      return false if @auth.credentials.nil?
      return false unless @auth.credentials == CREDENTIALS

      true
    end
  end

  get '/' do
    erb :posts
  end

  get '/api/posts', provides: :json do
    posts = []
    with_redis_conn do |conn|
      posts = Post.all(conn)
    end

    post_data = posts.map {|post| post.marshal}
    json post_data
  end

  get '/api/posts/:id', :provides => :json do
    with_redis_conn do |conn|
      post = Post.get(conn, params[:id])
      return status 404 unless post

      json post.marshal
    end
  end

  get '/api/posts', provides: :html do
    posts = []
    with_redis_conn do |conn|
      posts = Post.all(conn)
    end

    erb :posts_fragment, layout: nil, locals: {posts: posts}
  end

  post '/api/posts' do
    protected!
    data = JSON.parse(request.body.read)

    post = Post.unmarshal(data)
    post.cdate = DateTime.now
    halt 400, 'invalid post data' unless post.valid?

    with_redis_conn do |conn|
      post.store(conn)
    end

    status 201
    json({id: post.id})
  end
end

App.run!
