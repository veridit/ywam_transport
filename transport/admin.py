# transport/admin.py
from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from django.contrib.auth.models import User
from unfold.admin import ModelAdmin
from .models import (
    AbandonTrips, GlobalSettings, InfoLinks, RestrictedCharges, Vehicles,
    InfoLinksPosition, TempMassEmails, Driver, Departments, VehicleLimit,
    Reservations, CommentLog, Log, ShopTasks, WorkType, SpecialNotice,
    ServiceReservations, ServiceReservationsDetails, TripDetails,
    DriverComments, VehicleBrand, VehicleType, VehicleComments
)

admin.site.unregister(User)

@admin.register(User)
class UserAdmin(BaseUserAdmin, ModelAdmin):
    pass

@admin.register(AbandonTrips)
class AbandonTripsAdmin(ModelAdmin):
    list_display = ('id', 'abandon_date', 'reservation', 'user', 'mile_charges', 'calculate_fine', 'miles')
    search_fields = ('notes', 'reservation__id', 'user__username')
    list_filter = ('calculate_fine', 'miles')
    autocomplete_fields = ['reservation', 'user']

@admin.register(GlobalSettings)
class GlobalSettingsAdmin(ModelAdmin):
    list_display = ('id', 'leader_code')

@admin.register(InfoLinks)
class InfoLinksAdmin(ModelAdmin):
    list_display = ('id', 'title', 'link_date', 'position', 'display_page', 'display_flag')
    search_fields = ('title', 'display_page')
    list_filter = ('display_page', 'display_flag')

@admin.register(RestrictedCharges)
class RestrictedChargesAdmin(ModelAdmin):
    list_display = ('id', 'vehicle', 'charge_month', 'charge_year', 'department', 'total_charge')
    search_fields = ('vehicle__vehicle_no', 'department__name')
    list_filter = ('charge_month', 'charge_year', 'department')
    autocomplete_fields = ['vehicle', 'department']

@admin.register(Vehicles)
class VehiclesAdmin(ModelAdmin):
    list_display = ('id', 'user', 'vehicle_no', 'vin_no', 'make', 'model', 'manufacture_year', 'active', 'restricted', 'sold')
    search_fields = ('vehicle_no', 'vin_no', 'license_plate_no', 'make__name', 'model__type')
    list_filter = ('make', 'model', 'active', 'restricted', 'sold')
    autocomplete_fields = ['user', 'make', 'model']

@admin.register(InfoLinksPosition)
class InfoLinksPositionAdmin(ModelAdmin):
    list_display = ('id', 'link', 'position', 'driver_login')
    search_fields = ('link__title',)
    list_filter = ('driver_login',)
    autocomplete_fields = ['link']

@admin.register(TempMassEmails)
class TempMassEmailsAdmin(ModelAdmin):
    list_display = ('id', 'email', 'driver_name')
    search_fields = ('email', 'driver_name')

@admin.register(Driver)
class DriverAdmin(ModelAdmin):
    list_display = ('id', 'user', 'department', 'phone', 'license_no', 'user_type', 'permit_type', 'new_user', 'driver_permission')
    search_fields = ('user__username', 'department__name', 'phone', 'license_no')
    list_filter = ('user_type', 'permit_type', 'new_user', 'driver_permission')
    autocomplete_fields = ['user', 'department']

@admin.register(Departments)
class DepartmentsAdmin(ModelAdmin):
    list_display = ('id', 'name', 'leader_first_name', 'leader_last_name', 'leader_phone', 'leader_email', 'active')
    search_fields = ('name', 'leader_first_name', 'leader_last_name', 'leader_email')
    list_filter = ('active',)

@admin.register(VehicleLimit)
class VehicleLimitAdmin(ModelAdmin):
    list_display = ('id', 'option', 'department', 'user', 'from_date', 'to_date', 'limit_value')
    search_fields = ('department__name', 'user__username')
    list_filter = ('department',)
    autocomplete_fields = ['department', 'user']

@admin.register(Reservations)
class ReservationsAdmin(ModelAdmin):
    list_display = ('id', 'vehicle', 'user', 'planned_passenger_no', 'coordinator_approval', 'planned_departure_datetime', 'planned_return_datetime', 'overnight', 'destination', 'reservation_cancelled')
    search_fields = ('vehicle__vehicle_no', 'user__username', 'destination')
    list_filter = ('coordinator_approval', 'overnight', 'reservation_cancelled', 'repeating', 'no_cost')
    autocomplete_fields = ['vehicle', 'user']

@admin.register(CommentLog)
class CommentLogAdmin(ModelAdmin):
    list_display = ('id', 'user', 'comment_time')
    search_fields = ('user__username', 'comments')
    list_filter = ('comment_time',)
    autocomplete_fields = ['user']

@admin.register(Log)
class LogAdmin(ModelAdmin):
    list_display = ('id', 'user', 'login_datetime', 'logout_datetime', 'ip_address')
    search_fields = ('user__username', 'ip_address')
    list_filter = ('login_datetime', 'logout_datetime')
    autocomplete_fields = ['user']

@admin.register(ShopTasks)
class ShopTasksAdmin(ModelAdmin):
    list_display = ('id', 'user', 'vehicle', 'mileage_reading', 'last_mileage', 'work_type', 'work_start_date', 'task_complete', 'reg_date')
    search_fields = ('user__username', 'vehicle__vehicle_no', 'work_type__type')
    list_filter = ('work_type', 'task_complete', 'reg_date')
    autocomplete_fields = ['user', 'vehicle', 'work_type']

@admin.register(WorkType)
class WorkTypeAdmin(ModelAdmin):
    list_display = ('id', 'type')
    search_fields = ('type',)

@admin.register(SpecialNotice)
class SpecialNoticeAdmin(ModelAdmin):
    list_display = ('id', 'notice_date', 'user', 'title')
    search_fields = ('user__username', 'title', 'notice')
    list_filter = ('notice_date',)
    autocomplete_fields = ['user']

@admin.register(ServiceReservations)
class ServiceReservationsAdmin(ModelAdmin):
    list_display = ('id', 'reg_date', 'vehicle', 'from_datetime', 'to_datetime', 'is_cancelled', 'service_type')
    search_fields = ('vehicle__vehicle_no', 'service_type')
    list_filter = ('is_cancelled', 'service_type')
    autocomplete_fields = ['vehicle']

@admin.register(ServiceReservationsDetails)
class ServiceReservationsDetailsAdmin(ModelAdmin):
    list_display = ('id', 'service_reservation', 'reservation')
    search_fields = ('service_reservation__id', 'reservation__id')
    autocomplete_fields = ['service_reservation', 'reservation']

@admin.register(TripDetails)
class TripDetailsAdmin(ModelAdmin):
    list_display = ('id', 'reservation', 'begin_mileage', 'end_mileage', 'problem', 'reg_date')
    search_fields = ('reservation__id', 'user__username', 'problem_description')
    list_filter = ('problem', 'reg_date')
    autocomplete_fields = ['reservation', 'user']

@admin.register(DriverComments)
class DriverCommentsAdmin(ModelAdmin):
    list_display = ('id', 'posting_user', 'about_user', 'comments_date', 'trip')
    search_fields = ('posting_user__user__username', 'about_user__user__username', 'comments')
    list_filter = ('comments_date',)
    autocomplete_fields = ['posting_user', 'about_user', 'trip']

@admin.register(VehicleBrand)
class VehicleBrandAdmin(ModelAdmin):
    list_display = ('id', 'name')
    search_fields = ('name',)

@admin.register(VehicleType)
class VehicleTypeAdmin(ModelAdmin):
    list_display = ('id', 'type', 'capacity')
    search_fields = ('type', 'capacity')

@admin.register(VehicleComments)
class VehicleCommentsAdmin(ModelAdmin):
    list_display = ('id', 'posting_user', 'vehicle', 'comment_date', 'type')
    search_fields = ('posting_user__user__username', 'vehicle__vehicle_no', 'comments')
    list_filter = ('comment_date', 'type')
    autocomplete_fields = ['posting_user', 'vehicle']
