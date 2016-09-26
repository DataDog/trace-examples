from __future__ import unicode_literals

import logging

from django.db import models, connection
from django.utils.encoding import python_2_unicode_compatible


log = logging.getLogger()


@python_2_unicode_compatible
class Greeting(models.Model):
    when = models.DateTimeField('date created', auto_now_add=True)

    def __str__(self):
        return str(self.when)


def explicit_query_error():
    log.info("making err query")
    cursor = connection.cursor()
    return cursor.execute("select error from non_existant_table").fetchall()


def explicit_query_pass():
    cursor = connection.cursor()
    cursor.execute("select 1 from hello_greeting")
