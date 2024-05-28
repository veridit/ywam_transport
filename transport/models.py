# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#   * Rearrange models' order
#   * Make sure each model has one field with primary_key=True
#   * Make sure each ForeignKey and OneToOneField has `on_delete` set to the desired behavior
#   * Remove `managed = False` lines if you wish to allow Django to create, modify, and delete the table
# Feel free to rename the models, but don't rename db_table values or field names.
from django.db import models


class AbandonTrips(models.Model):
    id = models.BigAutoField(primary_key=True)
    abandon_date = models.DateTimeField(blank=True, null=True)
    notes = models.TextField()
    reservation = models.ForeignKey('Reservations', models.DO_NOTHING)
    driver = models.ForeignKey('Driver', models.DO_NOTHING, blank=True, null=True)
    mile_charges = models.FloatField()
    calculate_fine = models.BooleanField()
    miles = models.SmallIntegerField()

    class Meta:
        managed = False
        db_table = 'abandon_trips'


class AuthGroup(models.Model):
    name = models.CharField(unique=True, max_length=150)

    class Meta:
        managed = False
        db_table = 'auth_group'


class AuthGroupPermissions(models.Model):
    id = models.BigAutoField(primary_key=True)
    group = models.ForeignKey(AuthGroup, models.DO_NOTHING)
    permission = models.ForeignKey('AuthPermission', models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_group_permissions'
        unique_together = (('group', 'permission'),)


class AuthPermission(models.Model):
    name = models.CharField(max_length=255)
    content_type = models.ForeignKey('DjangoContentType', models.DO_NOTHING)
    codename = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'auth_permission'
        unique_together = (('content_type', 'codename'),)


class AuthUser(models.Model):
    password = models.CharField(max_length=128)
    last_login = models.DateTimeField(blank=True, null=True)
    is_superuser = models.BooleanField()
    username = models.CharField(unique=True, max_length=150)
    first_name = models.CharField(max_length=150)
    last_name = models.CharField(max_length=150)
    email = models.CharField(max_length=254)
    is_staff = models.BooleanField()
    is_active = models.BooleanField()
    date_joined = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'auth_user'


class AuthUserGroups(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)
    group = models.ForeignKey(AuthGroup, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_user_groups'
        unique_together = (('user', 'group'),)


class AuthUserUserPermissions(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)
    permission = models.ForeignKey(AuthPermission, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_user_user_permissions'
        unique_together = (('user', 'permission'),)


class CommentLog(models.Model):
    id = models.BigAutoField(primary_key=True)
    driver = models.ForeignKey('Driver', models.DO_NOTHING)
    comments = models.TextField()
    comment_time = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'comment_log'


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
        db_table = 'departments'


class DjangoAdminLog(models.Model):
    action_time = models.DateTimeField()
    object_id = models.TextField(blank=True, null=True)
    object_repr = models.CharField(max_length=200)
    action_flag = models.SmallIntegerField()
    change_message = models.TextField()
    content_type = models.ForeignKey('DjangoContentType', models.DO_NOTHING, blank=True, null=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'django_admin_log'


class DjangoContentType(models.Model):
    app_label = models.CharField(max_length=100)
    model = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'django_content_type'
        unique_together = (('app_label', 'model'),)


class DjangoMigrations(models.Model):
    id = models.BigAutoField(primary_key=True)
    app = models.CharField(max_length=255)
    name = models.CharField(max_length=255)
    applied = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'django_migrations'


class DjangoSession(models.Model):
    session_key = models.CharField(primary_key=True, max_length=40)
    session_data = models.TextField()
    expire_date = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'django_session'


class Driver(models.Model):
    id = models.BigAutoField(primary_key=True)
    first_name = models.CharField(max_length=20)
    last_name = models.CharField(max_length=20)
    department_id = models.CharField(max_length=4)
    phone = models.CharField(max_length=25)
    birth_date = models.DateField(blank=True, null=True)
    license_no = models.CharField(max_length=20)
    license_state = models.CharField(max_length=15)
    license_expire = models.DateField(blank=True, null=True)
    email = models.CharField(unique=True, max_length=254)
    password = models.CharField(max_length=128)
    drive_tested = models.CharField(max_length=30)
    test_date = models.DateField(blank=True, null=True)
    end_permit = models.DateField(blank=True, null=True)
    home_state_country = models.CharField(max_length=30)
    is_active = models.BooleanField()
    status_date = models.DateTimeField(blank=True, null=True)
    user_group = models.SmallIntegerField()
    user_type = models.CharField(max_length=20)
    photo = models.CharField(max_length=255)
    photo_link = models.CharField(max_length=255)
    comment = models.CharField(max_length=300)
    date_joined = models.DateTimeField()
    permit_type = models.CharField(max_length=5)
    renew_date = models.DateField(blank=True, null=True)
    renew_text = models.CharField(max_length=200, blank=True, null=True)
    new_user = models.BooleanField()
    driver_permission = models.BooleanField()
    license_country = models.CharField(max_length=60)
    max_passengers = models.BigIntegerField()
    last_login = models.DateTimeField(blank=True, null=True)
    is_superuser = models.BooleanField()
    username = models.CharField(unique=True, max_length=150)
    is_staff = models.BooleanField()

    class Meta:
        managed = False
        db_table = 'driver'


class DriverComments(models.Model):
    id = models.BigAutoField(primary_key=True)
    posting_driver = models.ForeignKey(Driver, models.DO_NOTHING)
    about_driver = models.ForeignKey(Driver, models.DO_NOTHING)
    comments_date = models.DateTimeField()
    comments = models.TextField()
    trip = models.ForeignKey('TripDetails', models.DO_NOTHING, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'driver_comments'


class DriverGroup(models.Model):
    name = models.CharField(max_length=50)

    class Meta:
        managed = False
        db_table = 'driver_group'


class DriverLinksPosition(models.Model):
    id = models.IntegerField()
    position = models.IntegerField()
    driver_login = models.BooleanField()

    class Meta:
        managed = False
        db_table = 'driver_links_position'


class GlobalSettings(models.Model):
    id = models.BigIntegerField(primary_key=True)
    leader_code = models.CharField(max_length=20)

    class Meta:
        managed = False
        db_table = 'global_settings'


class InfoLinks(models.Model):
    title = models.CharField(max_length=100)
    text = models.TextField()
    link_date = models.DateTimeField(blank=True, null=True)
    position = models.SmallIntegerField()
    display_page = models.CharField(max_length=25)
    display_flag = models.CharField(max_length=1, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'info_links'


class Log(models.Model):
    id = models.BigAutoField(primary_key=True)
    driver = models.ForeignKey(Driver, models.DO_NOTHING)
    login_datetime = models.DateTimeField(blank=True, null=True)
    logout_datetime = models.DateTimeField(blank=True, null=True)
    ip_address = models.CharField(max_length=16)

    class Meta:
        managed = False
        db_table = 'log'


class Reservations(models.Model):
    id = models.BigAutoField(primary_key=True)
    vehicle = models.ForeignKey('Vehicles', models.DO_NOTHING)
    driver = models.ForeignKey(Driver, models.DO_NOTHING)
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
        db_table = 'reservations'


class RestrictedCharges(models.Model):
    id = models.BigAutoField(primary_key=True)
    vehicle = models.ForeignKey('Vehicles', models.DO_NOTHING)
    charge_month = models.CharField(max_length=2)
    charge_year = models.CharField(max_length=4)
    department = models.ForeignKey(Departments, models.DO_NOTHING)
    calculation_method = models.CharField(max_length=50)
    total_charge = models.FloatField()
    begin_mileage = models.CharField(max_length=7, blank=True, null=True)
    end_mileage = models.CharField(max_length=7, blank=True, null=True)
    rate = models.FloatField(blank=True, null=True)
    reg_date = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'restricted_charges'


class ServiceReservations(models.Model):
    id = models.BigAutoField(primary_key=True)
    reg_date = models.DateTimeField()
    vehicle = models.ForeignKey('Vehicles', models.DO_NOTHING)
    from_datetime = models.DateTimeField(blank=True, null=True)
    to_datetime = models.DateTimeField(blank=True, null=True)
    is_cancelled = models.BooleanField()
    service_type = models.CharField(max_length=15)

    class Meta:
        managed = False
        db_table = 'service_reservations'


class ServiceReservationsDetails(models.Model):
    service_reservation = models.ForeignKey(ServiceReservations, models.DO_NOTHING)
    reservation = models.ForeignKey(Reservations, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'service_reservations_details'


class ShopTasks(models.Model):
    id = models.BigAutoField(primary_key=True)
    driver = models.ForeignKey(Driver, models.DO_NOTHING)
    vehicle = models.ForeignKey('Vehicles', models.DO_NOTHING)
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
        db_table = 'shop_tasks'


class SpecialNotice(models.Model):
    id = models.BigAutoField(primary_key=True)
    notice_date = models.DateTimeField(blank=True, null=True)
    driver = models.ForeignKey(Driver, models.DO_NOTHING)
    title = models.CharField(max_length=255)
    notice = models.TextField()

    class Meta:
        managed = False
        db_table = 'special_notice'


class TempMassEmails(models.Model):
    id = models.CharField(max_length=250)
    driver_name = models.CharField(max_length=50)

    class Meta:
        managed = False
        db_table = 'temp_mass_emails'


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
    driver = models.ForeignKey(Driver, models.DO_NOTHING, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'trip_details'


class VehicleBrand(models.Model):
    name = models.CharField(max_length=255)

    class Meta:
        managed = False
        db_table = 'vehicle_brand'


class VehicleComments(models.Model):
    id = models.BigAutoField(primary_key=True)
    posting_driver = models.ForeignKey(Driver, models.DO_NOTHING)
    vehicle = models.ForeignKey('Vehicles', models.DO_NOTHING)
    comment_date = models.BigIntegerField()
    type = models.CharField(max_length=25)
    comments = models.CharField(max_length=300)

    class Meta:
        managed = False
        db_table = 'vehicle_comments'


class VehicleLimit(models.Model):
    id = models.BigAutoField(primary_key=True)
    option = models.SmallIntegerField()
    department = models.ForeignKey(Departments, models.DO_NOTHING)
    driver = models.ForeignKey(Driver, models.DO_NOTHING)
    from_date = models.DateField(blank=True, null=True)
    to_date = models.DateField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'vehicle_limit'


class VehicleType(models.Model):
    type = models.CharField(max_length=255)
    capacity = models.SmallIntegerField()

    class Meta:
        managed = False
        db_table = 'vehicle_type'


class Vehicles(models.Model):
    id = models.BigAutoField(primary_key=True)
    driver = models.ForeignKey(Driver, models.DO_NOTHING)
    vehicle_no = models.CharField(max_length=12)
    vin_no = models.CharField(max_length=20)
    oil_filter = models.CharField(max_length=20)
    safety_date = models.DateField(blank=True, null=True)
    registration_date = models.DateField(blank=True, null=True)
    license_plate_no = models.CharField(max_length=50)
    make = models.ForeignKey(VehicleBrand, models.DO_NOTHING)
    model = models.ForeignKey(VehicleType, models.DO_NOTHING)
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
        db_table = 'vehicles'


class WorkType(models.Model):
    type = models.CharField(max_length=255)

    class Meta:
        managed = False
        db_table = 'work_type'
