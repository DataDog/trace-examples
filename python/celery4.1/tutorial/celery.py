from ddtrace import patch
from celery import Celery
import os

patch(celery=True)

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'tutorial.settings')

app = Celery('tutorial', broker='redis://localhost')
app.config_from_object('django.conf:settings', namespace='CELERY')
app.autodiscover_tasks()

from demoapp.tasks import add
import time
while True:
    print('test')
    add.delay(4, 4)
    time.sleep(0.2)
