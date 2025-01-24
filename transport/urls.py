# transport/urls.py
from django.urls import path
from .views import pending_trips_chart
from django.shortcuts import redirect

urlpatterns = [
    path('', lambda request: redirect('pending_trips_chart')),
    path('pending_trips_chart', pending_trips_chart, name='pending_trips_chart'),
]
