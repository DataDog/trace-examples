
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
  REDIS_HOST = ENV['SINATRA_REDIS_HOST'] || '127.0.0.1'.freeze()
  REDIS_PORT = ENV['SINATRA_REDIS_PORT'] || 6379
  SERVICE = ENV['SIDEKIQ_SERVICE'] || 'sidekiq-demo'.freeze()
  PROTECTED_USER = ENV['PROTECTED_USER']
  PROTECTED_PASSWORD = ENV['PROTECTED_PASSWORD']
  CREDENTIALS = [PROTECTED_USER, PROTECTED_PASSWORD]
  DATADOG_TRACER = ENV['DATADOG_TRACER']

  configure do
    settings.datadog_tracer.configure(default_service: SERVICE,
                                      trace_agent_hostname: DATADOG_TRACER)
  end

  helpers do
    def authorized?
      @auth ||=  Rack::Auth::Basic::Request.new(request.env)

      return false unless @auth.provided? && @auth.basic?
      return false if @auth.credentials.nil?
      return false unless @auth.credentials == [CREDENTIALS]

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
    jobs = Job.all()

    job_data = jobs.map {|job| job.marshal()}
    json job_data
  end

  get '/api/jobs', provides: :html do
    jobs = Job.all()

    erb :jobs_fragment, layout: nil, locals: {jobs: jobs}
  end

  post '/api/jobs' do
    protected!

    data = JSON.parse(request.body.read)
    job = Job.unmarshal(data)
    halt 400, 'invalid job data' unless job.valid?

    job.schedule()

    status 204
  end
end

App.run!
