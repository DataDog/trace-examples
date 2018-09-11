from django.conf.urls import url

from . import views

urlpatterns = [
    url(r"^$", views.client_index, name="index"),
    url(r"^simple", views.client_simple, name="simple"),
    url(r"^log", views.client_log, name="log"),
    url(r"^childspan", views.client_child_span, name="childspan"),
]
