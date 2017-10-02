require 'ddtrace/contrib/resque/resque_job'
require 'ddtrace'

class PseudoWorker
  extend Datadog::Contrib::ResqueJob
  @queue = :meow
  def self.perform(params)
    sleep(3)
    return true
  end
end

class ResqueController < ApplicationController
  def index
    return true
  end

  def create 
    Resque.enqueue(PseudoWorker, params)
    render html: 'creating job'
  end
end
