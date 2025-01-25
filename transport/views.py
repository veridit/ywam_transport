# transport/views.py
from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from .models import Vehicles, Reservations, ServiceReservations
from django.utils import timezone
import datetime

def pending_trips_chart(request):
    """Display chart of pending trips"""
    # Get the current view date from session or POST
    current_date = None
    if request.method == "POST" and request.user.is_authenticated:
        posted_date = request.POST.get('txtdeptdatetime')
        if posted_date:
            current_date = datetime.datetime.strptime(posted_date, '%Y-%m-%d').date()

    if not current_date:
        current_date = request.session.get('current_date')
        if current_date:
            current_date = datetime.datetime.fromisoformat(current_date).date()
        else:
            current_date = timezone.now().date()

    # Store current date in session
    request.session['current_date'] = current_date.isoformat()

    # Calculate the date range to show (3 days)
    days = [current_date + datetime.timedelta(days=i) for i in range(3)]
    hours = list(range(4, 23))  # 4 AM to 11 PM

    # Fetch active vehicles
    vehicles = Vehicles.objects.filter(
        sold=False,
        active=False
    ).order_by('vehicle_no')

    vehicle_data = []
    for vehicle in vehicles:
        trips = check_pending_trips(
            vehicle.id,
            days[0],  # Start of range
            days[-1] + datetime.timedelta(days=1)  # End of range
        )
        vehicle_data.append({
            'vehicle_no': vehicle.vehicle_no,
            'trips': trips
        })

    context = {
        'vehicles': vehicle_data,
        'current_date': current_date,
        'days': days,
        'hours': hours,
        'message': request.session.pop('message', None)
    }
    return render(request, 'transport/pending_trips_chart.html', context)

def check_pending_trips(vehicle_id, start_date, end_date):
    """Check for pending trips in the given date range"""
    pending_trips = []

    # Get normal reservations
    reservations = Reservations.objects.filter(
        vehicle_id=vehicle_id,
        planned_departure_datetime__range=(start_date, end_date),
        planned_return_datetime__range=(start_date, end_date),
        reservation_cancelled=False,
        cancelled_by_driver=False,
        coordinator_approval='Approved'
    ).select_related('vehicle', 'assigned_driver', 'billing_department')

    # Get service reservations
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
            'vehicle_no': res.vehicle.vehicle_no,
            'driver_name': f"{res.assigned_driver.first_name} {res.assigned_driver.last_name}",
            'passenger_count': res.planned_passenger_count,
            'departure': res.planned_departure_datetime,
            'return': res.planned_return_datetime,
            'destination': res.destination,
            'department': res.billing_department.name,
            'key_no': res.key_no,
            'card_no': res.card_no,
            'res_type': 'normal'
        })

    for sr in service_reservations:
        pending_trips.append({
            'res_id': sr.id,
            'vehicle_no': sr.vehicle.vehicle_no,
            'departure': sr.from_datetime,
            'return': sr.to_datetime,
            'res_type': 'pulled'
        })

    # Sort trips by datetime
    return sorted(pending_trips, key=lambda x: x['departure'])

def load_pending_trips(request, res_id):
    """Load details for a specific pending trip"""
    try:
        # Try to get reservation details
        reservation = Reservations.objects.select_related(
            'vehicle',
            'assigned_driver',
            'billing_department'
        ).get(
            id=res_id,
            reservation_cancelled=False,
            cancelled_by_driver=False,
            coordinator_approval='Approved'
        )

        context = {
            'res_id': res_id,
            'vehicle_no': reservation.vehicle.vehicle_no,
            'driver_name': f"{reservation.assigned_driver.first_name} {reservation.assigned_driver.last_name}",
            'passenger_count': reservation.planned_passenger_count,
            'departure': reservation.planned_departure_datetime,
            'return': reservation.planned_return_datetime,
            'destination': reservation.destination,
            'department': reservation.billing_department.name,
            'key_no': reservation.key_no,
            'card_no': reservation.card_no
        }
    except Reservations.DoesNotExist:
        # Check if it's a service reservation
        try:
            service = ServiceReservations.objects.select_related('vehicle').get(
                id=res_id,
                is_cancelled=False,
                service_type='temporary'
            )
            context = {
                'res_id': res_id,
                'vehicle_no': service.vehicle.vehicle_no,
                'service_type': service.service_type,
                'from_date': service.from_datetime,
                'to_date': service.to_datetime
            }
        except ServiceReservations.DoesNotExist:
            context = {'error': 'Reservation not found'}

    return render(request, 'transport/trip_details.html', context)

def previous_day(request):
    # Get current date from session or use today
    current_date = request.session.get('current_date', timezone.now().date().isoformat())
    current_date = datetime.datetime.fromisoformat(current_date).date()

    # Move one day back
    new_date = current_date - datetime.timedelta(days=1)
    request.session['current_date'] = new_date.isoformat()

    # Recalculate the view with new date
    return pending_trips_chart(request)

def next_day(request):
    # Get current date from session or use today
    current_date = request.session.get('current_date', timezone.now().date().isoformat())
    current_date = datetime.datetime.fromisoformat(current_date).date()

    # Move one day forward
    new_date = current_date + datetime.timedelta(days=1)
    request.session['current_date'] = new_date.isoformat()

    # Recalculate the view with new date
    return pending_trips_chart(request)
