from django.conf.urls import include, url

from django.contrib import admin
admin.autodiscover()

import hello.views
from hello.views import IndexView

app_name = "hello"

urlpatterns = [
    url(r'^$', hello.views.index, name='index'),
    url(r'^error', hello.views.error, name='error'),
    url(r'^index', IndexView.as_view()),
    url(r'^admin/', include(admin.site.urls)),
]
