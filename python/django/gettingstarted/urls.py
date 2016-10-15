from django.contrib import admin
from django.conf.urls import include, url

from hello.views import GreetingsView, index_page, error_page


admin.autodiscover()

urlpatterns = [
    url(r'^$', index_page, name='index'),
    url(r'^error/$', error_page, name='error'),
    url(r'^index/$', GreetingsView.as_view(), name='index-cbv'),
    url(r'^admin/', include(admin.site.urls)),
]
