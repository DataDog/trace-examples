from django.conf.urls import include, url

from django.contrib import admin
admin.autodiscover()

import hello.views

app_name = "hello"

urlpatterns = [
    url(r'^$', hello.views.index, name='index'),
    url(r'^error', hello.views.error, name='error'),
    url(r'^admin/', include(admin.site.urls)),
]
