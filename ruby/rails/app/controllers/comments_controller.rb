class CommentsController < ApplicationController
  # ridiculous but good enough
  http_basic_authenticate_with :name => ENV['PROTECTED_USER'], :password => ENV['PROTECTED_PASSWORD'], :except => [:index, :show]

  def create
    @post = Post.find(params[:post_id])
    @comment = @post.comments.create(params[:comment])
    redirect_to post_path(@post)
  end

  def destroy
    @post = Post.find(params[:post_id])
    @comment = @post.comments.find(params[:id])
    @comment.destroy
    redirect_to post_path(@post)
  end
end
