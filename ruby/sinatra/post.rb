require 'date'
require 'securerandom'

class Post < Object
  attr_accessor :id, :cdate, :title, :body

  def self.generate_id
    SecureRandom.uuid
  end

  def initialize(id:, cdate:, title:, body:)
    @id = id
    @cdate = cdate || DateTime.now
    @title = title || ''
    @body = body || ''
  end

  def store(conn)
    raise "post already stored with id #{@id}" unless @id.nil?
    @id = Post.generate_id
    conn.set(redis_key, marshal)
  end

  def self.all(conn)
    keys = conn.keys("sinatra-demo:post:*")

    values = keys.empty? ? [] : conn.mget(*keys)
    values.map! do |value|
      data = JSON.parse(value)
      Post.unmarshal(data)
    end

    values.sort_by! {|v| v.cdate }
    values.reverse
  end

  def self.get(conn, id)
    value = conn.get("sinatra-demo:post:#{id}")
    return unless value

    data = JSON.parse(value)
    Post.unmarshal(data)
  end

  def marshal
    {id: @id, cdate: @cdate, title: @title, body: @body}.to_json
  end

  def self.unmarshal(data)
    cdate = nil
    if data.key? 'cdate'
      cdate = DateTime.parse(data['cdate'])
    end

    Post.new(id: data['id'],
             cdate: cdate,
             title: data['title'],
             body: data['body'])
  end

  def valid?
    !@title.empty? && !@body.empty?
  end

  def redis_key
    "sinatra-demo:post:#{@id}"
  end
end
