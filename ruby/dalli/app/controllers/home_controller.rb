# Item is a class with a delay() method that executes
# the called method in an async way. It relies in a database
# queue where this object is pickled.
class Item
  def initialize
    @store = ['a simple string', 'an \'escaped \' string', "another 'escaped' string", 42]
    @string = "a string with many \\\\\'escapes\\\\\'"
  end

  def show_store
    puts "Current store: #{@store}"
  end
end

class HomeController < ApplicationController
  def index
    item = Item.new()
    item.delay.show_store()
  end

  def error
    raise "this is an error, should trigger a meaningfull stack-trace"
  end
end
