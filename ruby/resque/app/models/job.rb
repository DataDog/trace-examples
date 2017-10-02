require 'resque'
require 'ddtrace/contrib/resque/resque_job'

class Job
	extend Datadog::Contrib::ResqueJob
  @queue = :default
  def self.perform(params)
     ActiveRecord::Base.verify_active_connections!
		sleep(2) 
		Resque.logger.info "Processed a job!"
  end
end
