from django.urls import path

from . import views

urlpatterns = [
    path('simple', views.simple_view, name='simple'),
    path('templated', views.templated_view, name='templated'),
    path('cached', views.cached_view, name='cached'),
    path('db', views.db_view, name='db'),
    path('all', views.all_the_things, name='all'),
]
