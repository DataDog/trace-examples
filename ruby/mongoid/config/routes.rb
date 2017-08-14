Rails.application.routes.draw do
  get "document/index"
  get "document/add"
  root :to => "document#index"
end
