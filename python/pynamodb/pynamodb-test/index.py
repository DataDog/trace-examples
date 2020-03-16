from blog import Blog
import time

time.sleep(3)

if not Blog.exists():
  print("No Blog table. Creating..")
  Blog.create_table(wait=True)
  print("Table created")


# Create records
blog_record = Blog('hello', 'test', memo='1')
blog_record.save()
blog_record2 = Blog('world', 'test2', memo='2')
blog_record2.save()
blog_record3 = Blog('world', 'test3', memo='3')
blog_record3.save()

# Get a record's memo
print(Blog.get('hello','test').memo)
  
# Update a record
blog_record.update(actions=[Blog.memo.set('3')])

# Querying a record
print('Number of records', Blog.count())

# Delete table when the script is done
Blog.delete_table()