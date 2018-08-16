require 'date'

require 'sidekiq/api'

require 'ddtrace'
require 'ddtrace/contrib/sidekiq/tracer'

Datadog.configure do |c|
  c.tracer hostname: ENV['DATADOG_TRACER'] || 'localhost'.freeze
  c.use :sidekiq, service: ENV['SIDEKIQ_SERVICE'] || 'sidekiq-demo'.freeze
end


Sidekiq.configure_server do |config|
  host = ENV['REDIS_HOST'] || 'localhost'.freeze
  port = ENV['REDIS_PORT'] || 6379
  config.redis = { url: "redis://#{host}:#{port}" }
end

class SleepWorker
  include Sidekiq::Worker

  def perform(cdate, duration)
    sleep(duration)
  end
end

class Job
  attr_accessor :id, :cdate, :duration

  def initialize(id: nil, cdate: nil, duration: 5.0, running: false)
    @id = id
    @cdate = cdate || DateTime.now()
    @duration = duration
    @running = running
  end

  def self.all()
    jobs = []

    queue = Sidekiq::Queue.new
    queue.each do |qjob|
      next unless qjob.klass == 'SleepWorker'

      cdate = DateTime.parse(qjob.args[0])
      duration = qjob.args[1]

      job = Job.new(id: qjob.jid, cdate: cdate, duration: duration)
      jobs << job
    end

    workers = Sidekiq::Workers.new
    workers.each do |pid, tid, work|
      msg = work['payload']
      next unless msg['class'] == 'SleepWorker'

      args = msg['args']
      cdate = DateTime.parse(args[0])
      duration = args[1]

      job = Job.new(id: msg['jid'], cdate: cdate, duration: duration, running: true)
      jobs << job
    end

    workers = Sidekiq::Workers.new
    workers.each do |pid, tid, work|
      msg = work['payload']
      next unless msg['class'] == 'SleepWorker'

      args = msg['args']
      cdate = DateTime.parse(args[0])
      duration = args[1]
    end

    jobs.sort {|j1, j2| j2.cdate <=> j1.cdate}
  end

  def schedule
    SleepWorker.perform_async(@cdate, @duration)
  end

  def marshal
    {id: @id, cdate: @cdate, duration: @duration, running: @running}
  end

  def self.unmarshal(data)
    Job.new(duration: data['duration'])
  end

  def valid?
    return false unless @duration.is_a?(Integer) || @duration.is_a?(Float)
    return false if @duration < 0

    true
  end

  def running?
    @running
  end
end
