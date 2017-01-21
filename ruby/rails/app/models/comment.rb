class Comment < ActiveRecord::Base
  belongs_to :post
  attr_accessible :body, :commenter
end
