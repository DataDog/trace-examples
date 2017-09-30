Blog::Application.routes.draw do
  resources :posts do
    resources :comments
  end

  get "home/index"
  mount API => '/'
  root :to => "home#index"

  get "error", to: "home#error"
end
