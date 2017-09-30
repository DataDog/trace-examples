module FastAPI
  class Ping < Grape::API
    namespace :example_namespace
    desc 'Returns a pong'
    get :ping do
      { ping: params[:pong] || 'pong' }
    end
  end
end

module FastAPI
  class Cache < Grape::API
    desc 'Returns a value from the Rails cache'
    before do
      sleep 0.001
    end

    get :cache do
      { value: Rails.cache.fetch('custom_cache_key') }
    end
  end
end

class API < Grape::API
  prefix 'api'
  format :json
  mount FastAPI::Ping
  mount FastAPI::Cache
end
