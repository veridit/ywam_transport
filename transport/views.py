# transport/views.py
from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from .models import Vehicles, Reservations, ServiceReservations
from django.utils import timezone
import datetime

def pending_trips_chart(request):
    # Handling form data (Only for authenticated users)
    if request.method == "POST" and request.user.is_authenticated:
        action = request.POST.get('action', '')
        txtdeptdatetime = request.POST.get('txtdeptdatetime', '')

        if action == 'viewreservations':
            # Handle viewing reservations or other actions that require authentication
            pass

    # Set default date
    today_date = timezone.now().date()
    sTimePickerDate1 = (today_date + datetime.timedelta(days=1)).strftime('%m/%d/%Y')

    # Calculate the next three days
    days = [today_date + datetime.timedelta(days=i) for i in range(3)]
    hours = list(range(4, 23))

    # Fetch vehicles and their pending trips
    vehicles = Vehicles.objects.filter(sold=False)
    vehicle_data = []

    for vehicle in vehicles:
        # TODO: Shouldn't there be a consistent `days=days.length`?
        trips = check_pending_trips(vehicle.id, today_date, today_date + datetime.timedelta(days=2))
        vehicle_data.append({
            'vehicle_no': vehicle.vehicle_no,
            'trips': trips
        })

    context = {
        'vehicles': vehicle_data,
        'today_date': today_date,
        'sTimePickerDate1': sTimePickerDate1,
        'days': days,
        'hours': hours
    }
    return render(request, 'transport/pending_trips_chart.html', context)

def check_pending_trips(vehicle_id, start_date, end_date):
    pending_trips = []
    reservations = Reservations.objects.filter(
        vehicle_id=vehicle_id,
        planned_departure_datetime__range=(start_date, end_date),
        reservation_cancelled=False,
        cancelled_by_driver=False,
        coordinator_approval='Approved'
    )
    service_reservations = ServiceReservations.objects.filter(
        vehicle_id=vehicle_id,
        from_datetime__lte=end_date,
        to_datetime__gte=start_date,
        is_cancelled=False,
        service_type='temporary'
    )

    for res in reservations:
        pending_trips.append({
            'res_id': res.id,
            'pending_trips': res.planned_departure_datetime,
            'res_type': 'normal'
        })
    for sr in service_reservations:
        pending_trips.append({
            'res_id': sr.id,
            'pending_trips': sr.from_datetime,
            'res_type': 'pulled'
        })

    return pending_trips
