# transport/urls.py
from django.urls import path
from .views import pending_trips_chart, load_pending_trips, previous_day, next_day
from django.shortcuts import redirect

urlpatterns = [
    path('', lambda request: redirect('pending_trips_chart')),
    path('pending_trips_chart', pending_trips_chart, name='pending_trips_chart'),
    path('load_pending_trips/<int:res_id>/', load_pending_trips, name='load_pending_trips'),
    path('previous_day/', previous_day, name='previous_day'),
    path('next_day/', next_day, name='next_day'),
]
