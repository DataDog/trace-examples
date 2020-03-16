from pynamodb.models import Model
from pynamodb.attributes import UnicodeAttribute

class Blog(Model):
  class Meta:
    table_name='Blog'
    region = 'us-west-1'
    write_capacity_units = 1
    read_capacity_units = 1
    host = "http://dynamodb:8000"

  title = UnicodeAttribute(hash_key=True)
  content = UnicodeAttribute(range_key=True)
  memo = UnicodeAttribute()