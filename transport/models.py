# transport/models.py
from django.db import models
from django.contrib.auth.models import User
from django.utils import timezone

class AbandonTrips(models.Model):
    id = models.BigAutoField(primary_key=True)
    abandon_date = models.DateTimeField(blank=True, null=True)
    notes = models.TextField()
    reservation = models.ForeignKey('Reservations', on_delete=models.CASCADE)
    user = models.ForeignKey(User, on_delete=models.SET_NULL, blank=True, null=True)
    mile_charges = models.FloatField()
    calculate_fine = models.BooleanField(default=False)
    miles = models.SmallIntegerField(default=25)

    def __str__(self):
        return f"Trip {self.id} by {self.user}"

    class Meta:
        managed = True
        db_table = 'transport_abandon_trips'
        verbose_name = "Abandon Trip"
        verbose_name_plural = "Abandon Trips"


class GlobalSettings(models.Model):
    id = models.BigIntegerField(primary_key=True)
    leader_code = models.CharField(max_length=20)

    def __str__(self):
        return self.leader_code

    class Meta:
        managed = True
        db_table = 'transport_global_settings'
        verbose_name = "Global Setting"
        verbose_name_plural = "Global Settings"


class InfoLinks(models.Model):
    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=100)
    text = models.TextField()
    link_date = models.DateTimeField(blank=True, null=True)
    position = models.SmallIntegerField(default=1)
    display_page = models.CharField(max_length=25)
    display_flag = models.CharField(max_length=1, blank=True, null=True)

    def __str__(self):
        return self.title

    class Meta:
        managed = True
        db_table = 'transport_info_links'
        verbose_name = "Info Link"
        verbose_name_plural = "Info Links"


class RestrictedCharges(models.Model):
    id = models.BigAutoField(primary_key=True)
    vehicle = models.ForeignKey('Vehicles', on_delete=models.RESTRICT, db_column='vehicle_id')
    charge_month = models.CharField(max_length=2)
    charge_year = models.CharField(max_length=4)
    department = models.ForeignKey('Departments', on_delete=models.RESTRICT)
    calculation_method = models.CharField(max_length=50)
    total_charge = models.FloatField()
    begin_mileage = models.CharField(max_length=7, blank=True, null=True)
    end_mileage = models.CharField(max_length=7, blank=True, null=True)
    rate = models.FloatField(blank=True, null=True)
    reg_date = models.DateTimeField(blank=True, null=True)

    def __str__(self):
        return f"Charge {self.id} for {self.vehicle}"

    class Meta:
        managed = True
        db_table = 'transport_restricted_charges'
        verbose_name = "Restricted Charge"
        verbose_name_plural = "Restricted Charges"


class Vehicles(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.RESTRICT)
    vehicle_no = models.CharField(max_length=12)
    vin_no = models.CharField(max_length=20)
    oil_filter = models.CharField(max_length=20)
    safety_date = models.DateField(blank=True, null=True)
    registration_date = models.DateField(blank=True, null=True)
    license_plate_no = models.CharField(max_length=50)
    make = models.ForeignKey('VehicleBrand', on_delete=models.RESTRICT)
    model = models.ForeignKey('VehicleType', on_delete=models.RESTRICT)
    manufacture_year = models.CharField(max_length=4)
    mileage_at_takeover = models.CharField(max_length=7)
    date_at_takeover = models.DateField(blank=True, null=True)
    cost_at_takeover = models.FloatField()
    cost_rate = models.FloatField()
    passenger_capacity = models.CharField(max_length=2)
    condition = models.CharField(max_length=50)
    restriction = models.CharField(max_length=150)
    issues = models.CharField(max_length=255)
    active = models.BooleanField()
    restricted = models.BooleanField()
    revised_date = models.DateTimeField(blank=True, null=True)
    sold = models.BooleanField(default=False)
    sold_date = models.DateField(blank=True, null=True)
    admin_issues = models.TextField()

    def __str__(self):
        return self.vehicle_no

    class Meta:
        managed = True
        db_table = 'transport_vehicles'
        verbose_name = "Vehicle"
        verbose_name_plural = "Vehicles"


class InfoLinksPosition(models.Model):
    id = models.AutoField(primary_key=True)
    link = models.ForeignKey(InfoLinks, on_delete=models.CASCADE)
    position = models.IntegerField()
    driver_login = models.BooleanField()

    def __str__(self):
        return f"Position {self.position} for {self.link.title}"

    class Meta:
        managed = True
        db_table = 'transport_info_links_position'
        verbose_name = "Info Link Position"
        verbose_name_plural = "Info Links Positions"


class TempMassEmails(models.Model):
    id = models.AutoField(primary_key=True)
    email = models.CharField(max_length=250)
    driver_name = models.CharField(max_length=50)

    def __str__(self):
        return self.email

    class Meta:
        managed = True
        db_table = 'transport_temp_mass_emails'
        verbose_name = "Temp Mass Email"
        verbose_name_plural = "Temp Mass Emails"


class Driver(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    department = models.ForeignKey('Departments', on_delete=models.RESTRICT)
    phone = models.CharField(max_length=25)
    birth_date = models.DateField(blank=True, null=True)
    license_no = models.CharField(max_length=20)
    license_state = models.CharField(max_length=15)
    license_country = models.CharField(max_length=60)
    license_expire = models.DateField(blank=True, null=True)
    drive_tested = models.CharField(max_length=30)
    test_date = models.DateField(blank=True, null=True)
    end_permit = models.DateField(blank=True, null=True)
    home_country = models.CharField(max_length=30)
    status_date = models.DateTimeField(blank=True, null=True)
    user_type = models.CharField(max_length=20, choices=[('Other', 'Other'), ('Mission Bldr.', 'Mission Bldr.'), ('Staff', 'Staff'), ('Student', 'Student')])  # Enum field
    photo = models.CharField(max_length=255)
    photo_link = models.CharField(max_length=255)
    comment = models.CharField(max_length=300)
    permit_type = models.CharField(max_length=20, choices=[('Renew', 'Renew'), ('First', 'First')], default='First')  # Enum field
    renew_date = models.DateField(blank=True, null=True)
    renew_text = models.CharField(max_length=200, blank=True, null=True)
    new_user = models.BooleanField(default=False)
    driver_permission = models.BooleanField(default=False)
    max_passengers = models.BigIntegerField(default=15)

    def __str__(self):
        return self.user.username

    class Meta:
        managed = True
        db_table = 'transport_driver'
        verbose_name = "Driver"
        verbose_name_plural = "Drivers"


class Departments(models.Model):
    id = models.CharField(primary_key=True, max_length=4)
    name = models.CharField(max_length=50)
    leader_first_name = models.CharField(max_length=15)
    leader_last_name = models.CharField(max_length=15)
    leader_phone = models.CharField(max_length=25)
    leader_email = models.CharField(max_length=150)
    reg_date = models.DateTimeField(default=timezone.now)
    active = models.BooleanField(default=True)
    deactive_date = models.DateField(blank=True, null=True)
    info = models.CharField(max_length=200)

    def __str__(self):
        return self.name

    class Meta:
        managed = True
        db_table = 'transport_departments'
        verbose_name = "Department"
        verbose_name_plural = "Departments"


class VehicleLimit(models.Model):
    id = models.BigAutoField(primary_key=True)
    option = models.SmallIntegerField()
    department = models.ForeignKey(Departments, on_delete=models.RESTRICT)
    user = models.ForeignKey(User, on_delete=models.RESTRICT)
    from_date = models.DateField(blank=True, null=True)
    to_date = models.DateField(blank=True, null=True)
    limit_value = models.SmallIntegerField(default=3)

    def __str__(self):
        return f"Limit {self.id} for {self.department.name}"

    class Meta:
        managed = True
        db_table = 'transport_vehicle_limit'
        verbose_name = "Vehicle Limit"
        verbose_name_plural = "Vehicle Limits"


class Reservations(models.Model):
    id = models.BigAutoField(primary_key=True)
    vehicle = models.ForeignKey(Vehicles, on_delete=models.RESTRICT, db_column='vehicle_id')
    user = models.ForeignKey(User, on_delete=models.RESTRICT)
    planned_passenger_no = models.CharField(max_length=2)
    coordinator_approval = models.CharField(max_length=15, default='Approved')
    planned_departure_datetime = models.DateTimeField(blank=True, null=True)
    planned_return_datetime = models.DateTimeField(blank=True, null=True)
    overnight = models.BooleanField()
    child_seat = models.BooleanField()
    destination = models.CharField(max_length=100)
    reservation_cancelled = models.BooleanField(default=False)
    reg_date = models.DateTimeField(default=timezone.now)
    cancelled_by_driver = models.BooleanField(default=False)
    driver_cancelled_time = models.DateTimeField(blank=True, null=True)
    key_no = models.CharField(max_length=4, blank=True, null=True)
    card_no = models.CharField(max_length=8, blank=True, null=True)
    billing_department = models.ForeignKey(Departments, on_delete=models.RESTRICT, db_column='billing_department')
    assigned_driver = models.ForeignKey(Driver, on_delete=models.RESTRICT, db_column='assigned_driver', related_name='assigned_reservations')
    repeating = models.BooleanField(default=False)
    deleted_by_driver = models.ForeignKey(Driver, on_delete=models.SET_NULL, db_column='deleted_by_driver', blank=True, null=True, related_name='deleted_reservations')
    deleted_datetime = models.DateTimeField(blank=True, null=True)
    no_cost = models.BooleanField(default=False)

    def __str__(self):
        return f"Reservation {self.id} for {self.vehicle}"

    class Meta:
        managed = True
        db_table = 'transport_reservations'
        verbose_name = "Reservation"
        verbose_name_plural = "Reservations"


class CommentLog(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    comments = models.TextField()
    comment_time = models.DateTimeField(default=timezone.now)

    def __str__(self):
        return f"Comment by {self.user}"

    class Meta:
        managed = True
        db_table = 'transport_comment_log'
        verbose_name = "Comment Log"
        verbose_name_plural = "Comment Logs"


class Log(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    login_datetime = models.DateTimeField(blank=True, null=True)
    logout_datetime = models.DateTimeField(blank=True, null=True)
    ip_address = models.CharField(max_length=16)

    def __str__(self):
        return f"Log {self.id} by {self.user}"

    class Meta:
        managed = True
        db_table = 'transport_log'
        verbose_name = "Log"
        verbose_name_plural = "Logs"


class ShopTasks(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.RESTRICT)
    vehicle = models.ForeignKey(Vehicles, on_delete=models.RESTRICT)
    mileage_reading = models.CharField(max_length=7)
    last_mileage = models.CharField(max_length=7)
    work_type = models.ForeignKey('WorkType', on_delete=models.RESTRICT)
    work_start_date = models.DateField(blank=True, null=True)
    next_oil_change = models.DateField(blank=True, null=True)
    total_cost = models.FloatField()
    parts_source = models.CharField(max_length=255)
    drive_test_done = models.BooleanField()
    task_complete = models.BooleanField()
    technician_comments = models.TextField()
    reg_date = models.DateTimeField(default=timezone.now)
    invoice_no = models.CharField(max_length=50)
    vendor_name = models.CharField(max_length=50)

    def __str__(self):
        return f"Task {self.id} for {self.vehicle}"

    class Meta:
        managed = True
        db_table = 'transport_shop_tasks'
        verbose_name = "Shop Task"
        verbose_name_plural = "Shop Tasks"


class WorkType(models.Model):
    id = models.AutoField(primary_key=True)
    type = models.CharField(max_length=255)

    def __str__(self):
        return self.type

    class Meta:
        managed = True
        db_table = 'transport_work_type'
        verbose_name = "Work Type"
        verbose_name_plural = "Work Types"


class SpecialNotice(models.Model):
    id = models.BigAutoField(primary_key=True)
    notice_date = models.DateTimeField(blank=True, null=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    title = models.CharField(max_length=255)
    notice = models.TextField()

    def __str__(self):
        return self.title

    class Meta:
        managed = True
        db_table = 'transport_special_notice'
        verbose_name = "Special Notice"
        verbose_name_plural = "Special Notices"


class ServiceReservations(models.Model):
    id = models.BigAutoField(primary_key=True)
    reg_date = models.DateTimeField(default=timezone.now)
    vehicle = models.ForeignKey(Vehicles, on_delete=models.RESTRICT, db_column='vehicle_id')
    from_datetime = models.DateTimeField(blank=True, null=True)
    to_datetime = models.DateTimeField(blank=True, null=True)
    is_cancelled = models.BooleanField(default=False)
    service_type = models.CharField(max_length=15)

    def __str__(self):
        return f"Service Reservation {self.id} for {self.vehicle}"

    class Meta:
        managed = True
        db_table = 'transport_service_reservations'
        verbose_name = "Service Reservation"
        verbose_name_plural = "Service Reservations"


class ServiceReservationsDetails(models.Model):
    id = models.AutoField(primary_key=True)
    service_reservation = models.ForeignKey(ServiceReservations, on_delete=models.CASCADE)
    reservation = models.ForeignKey(Reservations, on_delete=models.CASCADE)

    def __str__(self):
        return f"Detail {self.id} for {self.service_reservation}"

    class Meta:
        managed = True
        db_table = 'transport_service_reservations_details'
        verbose_name = "Service Reservation Detail"
        verbose_name_plural = "Service Reservation Details"


class TripDetails(models.Model):
    id = models.BigAutoField(primary_key=True)
    reservation = models.ForeignKey(Reservations, on_delete=models.CASCADE)
    begin_mileage = models.CharField(max_length=7)
    end_mileage = models.CharField(max_length=7)
    end_gas_percent = models.CharField(max_length=4)
    problem = models.BooleanField()
    problem_description = models.TextField()
    reg_date = models.DateTimeField(default=timezone.now)
    mile_charges = models.FloatField()
    user = models.ForeignKey(User, on_delete=models.SET_NULL, blank=True, null=True)

    def __str__(self):
        return f"Trip {self.id} for {self.reservation}"

    class Meta:
        managed = True
        db_table = 'transport_trip_details'
        verbose_name = "Trip Detail"
        verbose_name_plural = "Trip Details"


class DriverComments(models.Model):
    id = models.BigAutoField(primary_key=True)
    posting_user = models.ForeignKey(Driver, on_delete=models.CASCADE, related_name='comments_posting')
    about_user = models.ForeignKey(Driver, on_delete=models.CASCADE, related_name='comments_about')
    comments_date = models.DateTimeField(default=timezone.now)
    comments = models.TextField()
    trip = models.ForeignKey(TripDetails, on_delete=models.SET_NULL, blank=True, null=True)

    def __str__(self):
        return f"Comment by {self.posting_user} about {self.about_user}"

    class Meta:
        managed = True
        db_table = 'transport_driver_comments'
        verbose_name = "Driver Comment"
        verbose_name_plural = "Driver Comments"


class VehicleBrand(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=255)

    def __str__(self):
        return self.name

    class Meta:
        managed = True
        db_table = 'transport_vehicle_brand'
        verbose_name = "Vehicle Brand"
        verbose_name_plural = "Vehicle Brands"


class VehicleType(models.Model):
    id = models.AutoField(primary_key=True)
    type = models.CharField(max_length=255)
    capacity = models.SmallIntegerField()

    def __str__(self):
        return self.type

    class Meta:
        managed = True
        db_table = 'transport_vehicle_type'
        verbose_name = "Vehicle Type"
        verbose_name_plural = "Vehicle Types"


class VehicleComments(models.Model):
    id = models.BigAutoField(primary_key=True)
    posting_user = models.ForeignKey(Driver, on_delete=models.CASCADE)
    vehicle = models.ForeignKey(Vehicles, on_delete=models.CASCADE)
    comment_date = models.BigIntegerField()
    type = models.CharField(max_length=25)
    comments = models.CharField(max_length=300)

    def __str__(self):
        return f"Comment {self.id} for {self.vehicle}"

    class Meta:
        managed = True
        db_table = 'transport_vehicle_comments'
        verbose_name = "Vehicle Comment"
        verbose_name_plural = "Vehicle Comments"


from django.db import models

class EmailTemplate(models.Model):
    class Event(models.TextChoices):
        DRIVER_REGISTERED = 'driver_registered', 'Driver Registered'
        DRIVER_ACTIVATED = 'driver_activated', 'Driver Activated'
        DEPT_CROSS_CHARGE_NOTICE = 'dept_cross_charge_notice', 'Dept. Cross Charge Notice'
        VEHICLE_REMOVED_FOR_SERVICE = 'vehicle_removed_for_service', 'Vehicle Removed for Service'
        DRIVER_DEACTIVATION_NOTICE = 'driver_deactivation_notice', 'Driver Deactivation Notice'
        RESTORE_SUSPENDED_DRIVER = 'restore_suspended_driver', 'Restore Suspended Driver'
        ACTIVATE_RESTORE_VEHICLE = 'activate_restore_vehicle', 'Activate - Restore Vehicle'
        PERMIT_RENEWAL = 'permit_renewal', 'Permit Renewal'
        LATE_TRIP_CANCELLATION = 'late_trip_cancellation', 'Late Trip Cancellation'
        ABANDON_NOTICE_WITH_CHARGE = 'abandon_notice_with_charge', 'Abandon Notice with Charge'

    id = models.AutoField(primary_key=True)
    event = models.CharField(max_length=50, choices=Event.choices, unique=True)
    subject = models.CharField(max_length=255)
    body = models.TextField()
    variables = models.JSONField()

    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    def __str__(self):
        return f"{self.event} {self.subject}"

    class Meta:
        managed = True
        db_table = 'transport_email_template'
        verbose_name = "Email Template"
        verbose_name_plural = "Email Templates"
