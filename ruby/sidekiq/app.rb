require 'bundler/setup'

require 'date'
require 'json'

require 'connection_pool'
require 'redis'
require 'sinatra'
require 'sinatra/json'

require 'ddtrace'
require 'ddtrace/contrib/sinatra/tracer'

require './job.rb'

class App < Sinatra::Application
  REDIS_HOST = ENV['REDIS_HOST'] || 'localhost'.freeze
  REDIS_PORT = ENV['REDIS_PORT'] || 6379
  SERVICE = ENV['SIDEKIQ_SERVICE'] || 'sidekiq-demo'.freeze
  PROTECTED_USER = ENV['PROTECTED_USER']
  PROTECTED_PASSWORD = ENV['PROTECTED_PASSWORD']
  CREDENTIALS = [PROTECTED_USER, PROTECTED_PASSWORD]
  DATADOG_TRACER = ENV['DATADOG_TRACER']
  PORT = ENV['PORT'] || 3000

  set :port, PORT
  set :bind, '0.0.0.0'

  configure do
    Sidekiq.configure_client do |config|
      host = ENV['REDIS_HOST'] || 'localhost'.freeze
      port = ENV['REDIS_PORT'] || 6379
      config.redis = { url: "redis://#{host}:#{port}" }
    end

    Datadog.configure do |c|
      c.tracer hostname: DATADOG_TRACER
      c.use :sinatra, service: SERVICE
    end
  end

  helpers do
    def authorized?
      @auth ||= Rack::Auth::Basic::Request.new(request.env)

      return false unless @auth.provided? && @auth.basic?
      return false if @auth.credentials.nil?
      return false unless @auth.credentials == CREDENTIALS

      true
    end

    def protected!
      return if authorized?
      headers['WWW-Authenticate'] = 'Basic realm="Restricted Area"'
      halt 401, "Not authorized\n"
    end
  end

  get '/' do
    erb :jobs
  end

  get '/api/jobs', provides: :json do
    jobs = Job.all

    job_data = jobs.map { |job| job.marshal}
    json job_data
  end

  get '/api/jobs', provides: :html do
    jobs = Job.all

    erb :jobs_fragment, layout: nil, locals: { jobs: jobs }
  end

  post '/api/jobs' do
    protected!

    data = JSON.parse(request.body.read)
    job = Job.unmarshal(data)
    halt 400, 'invalid job data' unless job.valid?

    job.schedule

    status 204
  end
end

App.run!
