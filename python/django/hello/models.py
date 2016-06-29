
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
    out = cursor.execute("select * from sqlite_master").fetchall()
    log.info("made pass query")
    return out


