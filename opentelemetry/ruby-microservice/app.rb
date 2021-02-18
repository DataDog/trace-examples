# frozen_string_literal: true

require 'opentelemetry/sdk'
require 'sinatra'
require 'sinatra/contrib'
require 'sinatra/custom_logger'
require 'logger'
require 'spacex'
require 'redis-sinatra'
require 'json'
require 'yaml'

class Multivac < Sinatra::Base
  helpers Sinatra::CustomLogger

  configure do
    redis_config = YAML.load(File.open('redis.yml'))
    dflt = redis_config['default']
    set :cache, Sinatra::Cache::RedisStore.new(dflt)

    logger = Logger.new(STDOUT)
    logger.progname = 'multivac'
    original_formatter = Logger::Formatter.new
    logger.formatter  = proc do |severity, datetime, progname, msg|
      current_span = OpenTelemetry::Trace.current_span(OpenTelemetry::Context.current).context
      
      dd_trace_id = current_span.trace_id.unpack1('H*')[16, 16].to_i(16).to_s
      dd_span_id = current_span.span_id.unpack1('H*').to_i(16).to_s
      
      if current_span
        "#{{datetime: datetime, progname: progname, severity: severity, msg: msg, 'dd.trace_id': dd_trace_id, 'dd.span_id': dd_span_id}.to_json}\n"
      else
        "#{{datetime: datetime, progname: progname, severity: severity, msg: msg}.to_json}\n"
      end
    end

    set :logger, logger
  end

  get '/' do
    'Hello World!'
  end

  get '/_health' do
    'Hello Heath!'
  end

  get '/next_launch' do
    begin
      logger.info('checking next_launch')
      cached_next_launch = settings.cache.read('next_launch')

      if cached_next_launch
        logger.info('cached next_launch')
        next_launch = JSON.parse(cached_next_launch)
      else
        logger.info('cached next_launch miss')
        next_launch = SPACEX::Launches.next
        settings.cache.write('next_launch', next_launch.to_json, expire_after: 15)
      end

      logger.info({
        'next_launch_name' => next_launch['mission_name'],
        'next_launch_date' => next_launch['launch_date_utc']
      }.to_json)

      erb :next_launch, locals: {
        name: next_launch['mission_name'],
        date: next_launch['launch_date_utc'],
        data: JSON.pretty_generate(next_launch)
      }
    rescue StandardError => e
      logger.error("Error when making next_launch check: #{e.message} #{e.backtrace}")

      status 500
    end
  end

  get '/location_info' do
    begin
      logger.info('checking location_info')

      cached_location_info = settings.cache.read('location_info')

      if cached_location_info
        logger.info('cached location_info')
        location_info = JSON.parse(cached_location_info)
      else
        logger.info('cached location_info miss')
        location_info = SPACEX::Roadster.info
        settings.cache.write('location_info', location_info.to_json, expire_after: 15)
      end

      logger.info(
        'roadster_name' => location_info['name'],
        'roadster_distance_earth' => location_info['earth_distance_mi'],
        'roadster_distance_mars' => location_info['mars_distance_mi']
      )

      content_type :json
      location_info.to_json
    rescue StandardError => e
      logger.error("Error when making location_info check: #{e.message} #{e.backtrace}")

      status 500
    end
  end
end
