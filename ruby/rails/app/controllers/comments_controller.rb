class CommentsController < ApplicationController
  # ridiculous but good enough
  http_basic_authenticate_with :name => ENV['PROTECTED_USER'], :password => ENV['PROTECTED_PASSWORD'], :except => [:index, :show]

  def create
    @post = Post.find(post_params[:post_id])
    @comment = @post.comments.create(post_params[:comment])
    redirect_to post_path(@post)
  end

  def destroy
    @post = Post.find(post_params[:post_id])
    @comment = @post.comments.find(post_params[:id])
    @comment.destroy
    redirect_to post_path(@post)
  end

  private

  def post_params
    params.permit!
  end
end
