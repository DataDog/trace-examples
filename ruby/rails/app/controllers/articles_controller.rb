class ArticlesController < ApplicationController
  def index
    @articles = Article.all
    @counter = Rails.cache.fetch('custom_cache_key') do
       @counter = 100
    end
  end

  def show
    @article = Article.find(params[:id])
  end
  def new
  end

  def create
    @article = Article.new(article_params)

    if @article.save
      redirect_to @article
    else
      render 'new'
    end
  end

  def edit
    @article = Article.find(params[:id])
  end

  def update
    @article = Article.find(params[:id])

    if @article.update(article_params)
      redirect_to @article
    else
      render 'edit'
    end
  end

  def destroy
    @article = Article.find(params[:id])
    @article.destroy

    redirect_to articles_path
  end

  def article_params
    params.require(:article).permit(:title, :text)
  end

  private :article_params
end
