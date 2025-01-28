# transport/views.py
from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from .models import Vehicles, Reservations, ServiceReservations
from django.utils import timezone
import datetime

def pending_trips_chart(request, partial=False):
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

    context = _get_chart_data(current_date)
    context['message'] = request.session.pop('message', None)
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

def _format_hours():
    """Helper function to format hours with AM/PM"""
    hours = []
    for hour in range(4, 23):  # 4 AM to 10 PM
        ampm = 'AM' if hour < 12 else 'PM'
        display_hour = hour if hour <= 12 else hour - 12
        hours.append({
            'hour': hour,
            'display': f"{display_hour}{ampm}"
        })
    return hours

def _get_chart_data(current_date):
    """Helper function to get all data needed for the chart"""
    days = [current_date + datetime.timedelta(days=i) for i in range(3)]
    hours = _format_hours()

    # Fetch active vehicles
    vehicles = Vehicles.objects.filter(
        sold=False,
        active=True
    ).order_by('vehicle_no')

    vehicle_data = []
    for vehicle in vehicles:
        trips = check_pending_trips(
            vehicle.id,
            days[0],
            days[-1] + datetime.timedelta(days=1)
        )
        vehicle_data.append({
            'vehicle_no': vehicle.vehicle_no,
            'trips': trips
        })

    return {
        'vehicles': vehicle_data,
        'current_date': current_date,
        'days': days,
        'hours': hours
    }

def _handle_day_change(request, delta):
    """Helper function to handle day navigation with proper HTMX support"""
    current_date = request.session.get('current_date', timezone.now().date().isoformat())
    current_date = datetime.datetime.fromisoformat(current_date).date()

    new_date = current_date + datetime.timedelta(days=delta)
    request.session['current_date'] = new_date.isoformat()

    context = _get_chart_data(new_date)
    context['message'] = request.session.pop('message', None)

    # Return partial template for HTMX requests
    return render(request, 'transport/partials/trips_table.html', context)

def previous_day(request):
    """Navigate to previous day with HTMX support"""
    return _handle_day_change(request, -1)

def next_day(request):
    """Navigate to next day with HTMX support"""
    return _handle_day_change(request, 1)
