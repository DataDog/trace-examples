
import logging

from django.db import models
from django.db import connection

log = logging.getLogger()

# Create your models here.
class Greeting(models.Model):
    when = models.DateTimeField('date created', auto_now_add=True)


def explicit_query_error():
    log.info("making err query")
    cursor = connection.cursor()
    return cursor.execute("select error from non_existant_table").fetchall()

def explicit_query_pass():
    cursor = connection.cursor()
    cursor.execute("select 1 from hello_greeting")


