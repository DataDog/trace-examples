require 'test_helper'
require 'rails/performance_test_help'

# Currently broken in 0.0.7 of rails-perftest.
# See https://github.com/rails/rails-perftest/issues/38#issuecomment-365991394
#     https://github.com/rails/rails-perftest/issues/43

# class BrowsingTest < ActionDispatch::PerformanceTest
#   # Workaround for https://github.com/rails/rails-perftest/issues/38#issuecomment-365991394
#   # self.profile_options = { runs: 5, formats: [:flat], metrics: [:wall_time, :memory] }

#   # Refer to the documentation for all available options
#   # self.profile_options = { :runs => 5, :metrics => [:wall_time, :memory]
#   #                          :output => 'tmp/performance', :formats => [:flat] }

#   def test_homepage
#     get '/'
#   end
# end
