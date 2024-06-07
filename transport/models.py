from django.db import models
from django.contrib.auth.models import User


class AbandonTrips(models.Model):
    id = models.BigAutoField(primary_key=True)
    abandon_date = models.DateTimeField(blank=True, null=True)
    notes = models.TextField()
    reservation = models.ForeignKey('Reservations', models.DO_NOTHING)
    user = models.ForeignKey(User, models.DO_NOTHING, blank=True, null=True)
    mile_charges = models.FloatField()
    calculate_fine = models.BooleanField()
    miles = models.SmallIntegerField()

    class Meta:
        managed = False
        db_table = 'transport_abandon_trips'


class GlobalSettings(models.Model):
    id = models.BigIntegerField(primary_key=True)
    leader_code = models.CharField(max_length=20)

    class Meta:
        managed = False
        db_table = 'transport_global_settings'


class InfoLinks(models.Model):
    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=100)
    text = models.TextField()
    link_date = models.DateTimeField(blank=True, null=True)
    position = models.SmallIntegerField()
    display_page = models.CharField(max_length=25)
    display_flag = models.CharField(max_length=1, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'transport_info_links'


class RestrictedCharges(models.Model):
    id = models.BigAutoField(primary_key=True)
    vehicle = models.ForeignKey('Vehicles', models.DO_NOTHING)
    charge_month = models.CharField(max_length=2)
    charge_year = models.CharField(max_length=4)
    department = models.ForeignKey('Departments', models.DO_NOTHING)
    calculation_method = models.CharField(max_length=50)
    total_charge = models.FloatField()
    begin_mileage = models.CharField(max_length=7, blank=True, null=True)
    end_mileage = models.CharField(max_length=7, blank=True, null=True)
    rate = models.FloatField(blank=True, null=True)
    reg_date = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'transport_restricted_charges'


class Vehicles(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, models.DO_NOTHING)
    vehicle_no = models.CharField(max_length=12)
    vin_no = models.CharField(max_length=20)
    oil_filter = models.CharField(max_length=20)
    safety_date = models.DateField(blank=True, null=True)
    registration_date = models.DateField(blank=True, null=True)
    license_plate_no = models.CharField(max_length=50)
    make = models.ForeignKey('VehicleBrand', models.DO_NOTHING)
    model = models.ForeignKey('VehicleType', models.DO_NOTHING)
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
    sold = models.BooleanField()
    sold_date = models.DateField(blank=True, null=True)
    admin_issues = models.TextField()

    class Meta:
        managed = False
        db_table = 'transport_vehicles'


class InfoLinksPosition(models.Model):
    id = models.AutoField(primary_key=True)
    link = models.ForeignKey(InfoLinks, models.DO_NOTHING)
    position = models.IntegerField()
    driver_login = models.BooleanField()
    id = models.IntegerField(primary_key=True)

    class Meta:
        managed = False
        db_table = 'transport_info_links_position'


class TempMassEmails(models.Model):
    id = models.AutoField(primary_key=True)
    email = models.CharField(max_length=250)
    driver_name = models.CharField(max_length=50)

    class Meta:
        managed = False
        db_table = 'transport_temp_mass_emails'


class Driver(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.OneToOneField(User, models.DO_NOTHING)
    department = models.ForeignKey('Departments', models.DO_NOTHING)
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
    user_type = models.TextField()  # This field type is a guess.
    photo = models.CharField(max_length=255)
    photo_link = models.CharField(max_length=255)
    comment = models.CharField(max_length=300)
    permit_type = models.TextField()  # This field type is a guess.
    renew_date = models.DateField(blank=True, null=True)
    renew_text = models.CharField(max_length=200, blank=True, null=True)
    new_user = models.BooleanField()
    driver_permission = models.BooleanField()
    max_passengers = models.BigIntegerField()

    class Meta:
        managed = False
        db_table = 'transport_driver'


class Departments(models.Model):
    id = models.CharField(primary_key=True, max_length=4)
    name = models.CharField(max_length=50)
    leader_first_name = models.CharField(max_length=15)
    leader_last_name = models.CharField(max_length=15)
    leader_phone = models.CharField(max_length=25)
    leader_email = models.CharField(max_length=150)
    reg_date = models.DateTimeField()
    active = models.BooleanField()
    deactive_date = models.DateField(blank=True, null=True)
    info = models.CharField(max_length=200)

    class Meta:
        managed = False
        db_table = 'transport_departments'


class VehicleLimit(models.Model):
    id = models.BigAutoField(primary_key=True)
    option = models.SmallIntegerField()
    department = models.ForeignKey(Departments, models.DO_NOTHING)
    user = models.ForeignKey(User, models.DO_NOTHING)
    from_date = models.DateField(blank=True, null=True)
    to_date = models.DateField(blank=True, null=True)
    limit_value = models.SmallIntegerField()

    class Meta:
        managed = False
        db_table = 'transport_vehicle_limit'


class Reservations(models.Model):
    id = models.BigAutoField(primary_key=True)
    vehicle = models.ForeignKey(Vehicles, models.DO_NOTHING)
    user = models.ForeignKey(User, models.DO_NOTHING)
    planned_passenger_no = models.CharField(max_length=2)
    coordinator_approval = models.CharField(max_length=15)
    planned_departure_datetime = models.DateTimeField(blank=True, null=True)
    planned_return_datetime = models.DateTimeField(blank=True, null=True)
    overnight = models.BooleanField()
    child_seat = models.BooleanField()
    destination = models.CharField(max_length=100)
    reservation_cancelled = models.BooleanField()
    reg_date = models.DateTimeField()
    cancelled_by_driver = models.BooleanField()
    driver_cancelled_time = models.DateTimeField(blank=True, null=True)
    key_no = models.CharField(max_length=4, blank=True, null=True)
    card_no = models.CharField(max_length=8, blank=True, null=True)
    billing_department = models.ForeignKey(Departments, models.DO_NOTHING, db_column='billing_department')
    assigned_driver = models.ForeignKey(Driver, models.DO_NOTHING, db_column='assigned_driver')
    repeating = models.BooleanField()
    deleted_by_driver = models.ForeignKey(Driver, models.DO_NOTHING, db_column='deleted_by_driver', blank=True, null=True)
    deleted_datetime = models.DateTimeField(blank=True, null=True)
    no_cost = models.BooleanField()

    class Meta:
        managed = False
        db_table = 'transport_reservations'


class CommentLog(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, models.DO_NOTHING)
    comments = models.TextField()
    comment_time = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'transport_comment_log'


class Log(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, models.DO_NOTHING)
    login_datetime = models.DateTimeField(blank=True, null=True)
    logout_datetime = models.DateTimeField(blank=True, null=True)
    ip_address = models.CharField(max_length=16)

    class Meta:
        managed = False
        db_table = 'transport_log'


class ShopTasks(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(User, models.DO_NOTHING)
    vehicle = models.ForeignKey(Vehicles, models.DO_NOTHING)
    mileage_reading = models.CharField(max_length=7)
    last_mileage = models.CharField(max_length=7)
    work_type = models.ForeignKey('WorkType', models.DO_NOTHING)
    work_start_date = models.DateField(blank=True, null=True)
    next_oil_change = models.DateField(blank=True, null=True)
    total_cost = models.FloatField()
    parts_source = models.CharField(max_length=255)
    drive_test_done = models.BooleanField()
    task_complete = models.BooleanField()
    technician_comments = models.TextField()
    reg_date = models.DateTimeField()
    invoice_no = models.CharField(max_length=50)
    vendor_name = models.CharField(max_length=50)

    class Meta:
        managed = False
        db_table = 'transport_shop_tasks'


class WorkType(models.Model):
    id = models.AutoField(primary_key=True)
    type = models.CharField(max_length=255)

    class Meta:
        managed = False
        db_table = 'transport_work_type'


class SpecialNotice(models.Model):
    id = models.BigAutoField(primary_key=True)
    notice_date = models.DateTimeField(blank=True, null=True)
    user = models.ForeignKey(User, models.DO_NOTHING)
    title = models.CharField(max_length=255)
    notice = models.TextField()

    class Meta:
        managed = False
        db_table = 'transport_special_notice'


class ServiceReservations(models.Model):
    id = models.BigAutoField(primary_key=True)
    reg_date = models.DateTimeField()
    vehicle = models.ForeignKey(Vehicles, models.DO_NOTHING)
    from_datetime = models.DateTimeField(blank=True, null=True)
    to_datetime = models.DateTimeField(blank=True, null=True)
    is_cancelled = models.BooleanField()
    service_type = models.CharField(max_length=15)

    class Meta:
        managed = False
        db_table = 'transport_service_reservations'


class ServiceReservationsDetails(models.Model):
    id = models.AutoField(primary_key=True)
    service_reservation = models.ForeignKey(ServiceReservations, models.DO_NOTHING)
    reservation = models.ForeignKey(Reservations, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'transport_service_reservations_details'


class TripDetails(models.Model):
    id = models.BigAutoField(primary_key=True)
    reservation = models.ForeignKey(Reservations, models.DO_NOTHING)
    begin_mileage = models.CharField(max_length=7)
    end_mileage = models.CharField(max_length=7)
    end_gas_percent = models.CharField(max_length=4)
    problem = models.BooleanField()
    problem_description = models.TextField()
    reg_date = models.DateTimeField()
    mile_charges = models.FloatField()
    user = models.ForeignKey(User, models.DO_NOTHING, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'transport_trip_details'


class DriverComments(models.Model):
    id = models.BigAutoField(primary_key=True)
    posting_user = models.ForeignKey(Driver, models.DO_NOTHING)
    about_user = models.ForeignKey(Driver, models.DO_NOTHING)
    comments_date = models.DateTimeField()
    comments = models.TextField()
    trip = models.ForeignKey(TripDetails, models.DO_NOTHING, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'transport_driver_comments'


class VehicleBrand(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=255)

    class Meta:
        managed = False
        db_table = 'transport_vehicle_brand'


class VehicleType(models.Model):
    id = models.AutoField(primary_key=True)
    type = models.CharField(max_length=255)
    capacity = models.SmallIntegerField()

    class Meta:
        managed = False
        db_table = 'transport_vehicle_type'


class VehicleComments(models.Model):
    id = models.BigAutoField(primary_key=True)
    posting_user = models.ForeignKey(Driver, models.DO_NOTHING)
    vehicle = models.ForeignKey(Vehicles, models.DO_NOTHING)
    comment_date = models.BigIntegerField()
    type = models.CharField(max_length=25)
    comments = models.CharField(max_length=300)

    class Meta:
        managed = False
        db_table = 'transport_vehicle_comments'
