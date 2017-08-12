class DocumentController < ApplicationController
  def index
    # fill the context
    @people = Person.where(first_name: 'Willy')
  end

  def add
    # create after each call
    Person.create([
      { first_name: 'Heinrich', last_name: 'Heine' },
      { first_name: 'Willy', last_name: 'Brandt' }
    ])
  end
end
