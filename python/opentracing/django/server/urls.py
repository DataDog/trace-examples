from django.conf.urls import url

from . import views

urlpatterns = [
	url(r'^$', views.server_index, name='index'),
	url(r'^simple', views.server_simple, name='simple'),
	url(r'^log', views.server_log, name='log'),
	url(r'^childspan', views.server_child_span, name='childspan')
]