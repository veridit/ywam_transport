-- rename_columns.sql
BEGIN;

-- Clean up table names
ALTER TABLE tbl_3_vehicle_limit RENAME TO vehicle_limit;
ALTER TABLE tbl_abandon_trips RENAME TO abandon_trips;
ALTER TABLE tbl_comment_log RENAME TO comment_log;
ALTER TABLE tbl_departments RENAME TO departments;
ALTER TABLE tbl_driver_links_order RENAME TO driver_links_position;
ALTER TABLE tbl_global_settings RENAME TO global_settings;
ALTER TABLE tbl_info_links RENAME TO info_links;
ALTER TABLE tbl_log RENAME TO log;
ALTER TABLE tbl_reservations RENAME TO reservations;
ALTER TABLE tbl_restricted_charges RENAME TO restricted_charges;
ALTER TABLE tbl_shop_tasks RENAME TO shop_tasks;
ALTER TABLE tbl_special_notice RENAME TO special_notice;
ALTER TABLE tbl_srvc_resvs RENAME TO service_reservations;
ALTER TABLE tbl_srvc_resvs_details RENAME TO service_reservations_details;
ALTER TABLE tbl_temp_mass_emails RENAME TO temp_mass_emails;
ALTER TABLE tbl_trip_details RENAME TO trip_details;
ALTER TABLE tbl_user RENAME TO driver;
ALTER TABLE tbl_user_comments RENAME TO driver_comments;
ALTER TABLE tbl_user_group RENAME TO driver_group;
ALTER TABLE tbl_vehicle_brand RENAME TO vehicle_brand;
ALTER TABLE tbl_vehicle_comments RENAME TO vehicle_comments;
ALTER TABLE tbl_vehicle_type RENAME TO vehicle_type;
ALTER TABLE tbl_vehicles RENAME TO vehicles;
ALTER TABLE tbl_work_type RENAME TO work_type;

-- Clean up column names for vehicle_limit
ALTER TABLE vehicle_limit RENAME COLUMN limit_id TO id;
ALTER TABLE vehicle_limit RENAME COLUMN soption TO option;
ALTER TABLE vehicle_limit RENAME COLUMN dept_id TO department_id;
ALTER TABLE vehicle_limit RENAME COLUMN user_id TO driver_id;
--ALTER TABLE vehicle_limit RENAME COLUMN from_date TO from_date;
--ALTER TABLE vehicle_limit RENAME COLUMN to_date TO to_date;

-- Clean up column names for abandon_trips
ALTER TABLE abandon_trips RENAME COLUMN abandon_id TO id;
--ALTER TABLE abandon_trips RENAME COLUMN abandon_date TO abandon_date;
--ALTER TABLE abandon_trips RENAME COLUMN notes TO notes;
ALTER TABLE abandon_trips RENAME COLUMN res_id TO reservation_id;
ALTER TABLE abandon_trips RENAME COLUMN user_id TO driver_id;
--ALTER TABLE abandon_trips RENAME COLUMN mile_charges TO mile_charges;
--ALTER TABLE abandon_trips RENAME COLUMN calculate_fine TO calculate_fine;
--ALTER TABLE abandon_trips RENAME COLUMN miles TO miles;

-- Clean up column names for comment_log
ALTER TABLE comment_log RENAME COLUMN comment_id TO id;
ALTER TABLE comment_log RENAME COLUMN user_id TO driver_id;
--ALTER TABLE comment_log RENAME COLUMN comments TO comments;
--ALTER TABLE comment_log RENAME COLUMN comment_time TO comment_time;

-- Clean up column names for departments
ALTER TABLE departments RENAME COLUMN dept_id TO id;
ALTER TABLE departments RENAME COLUMN dept_name TO name;
ALTER TABLE departments RENAME COLUMN leader_f_name TO leader_first_name;
ALTER TABLE departments RENAME COLUMN leader_l_name TO leader_last_name;
--ALTER TABLE departments RENAME COLUMN leader_phone TO leader_phone;
--ALTER TABLE departments RENAME COLUMN leader_email TO leader_email;
--ALTER TABLE departments RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE departments RENAME COLUMN active TO active;
--ALTER TABLE departments RENAME COLUMN deactive_date TO deactive_date;
ALTER TABLE departments RENAME COLUMN dept_info TO info;

-- Clean up column names for driver_links_position
ALTER TABLE driver_links_position RENAME COLUMN link_id TO id;
ALTER TABLE driver_links_position RENAME COLUMN link_order TO position;
--ALTER TABLE driver_links_position RENAME COLUMN driver_login TO driver_login;

-- Clean up column names for global_settings
--ALTER TABLE global_settings RENAME COLUMN id TO id;
--ALTER TABLE global_settings RENAME COLUMN leader_code TO leader_code;

-- Clean up column names for info_links
ALTER TABLE info_links RENAME COLUMN link_id TO id;
ALTER TABLE info_links RENAME COLUMN link_title TO title;
ALTER TABLE info_links RENAME COLUMN link_text TO text;
--ALTER TABLE info_links RENAME COLUMN link_date TO link_date;
ALTER TABLE info_links RENAME COLUMN link_order TO position;
ALTER TABLE info_links RENAME COLUMN link_display_page TO display_page;
ALTER TABLE info_links RENAME COLUMN link_display_flag TO display_flag;

-- Clean up column names for log
ALTER TABLE log RENAME COLUMN log_id TO id;
ALTER TABLE log RENAME COLUMN user_id TO driver_id;
--ALTER TABLE log RENAME COLUMN login_datetime TO login_datetime;
--ALTER TABLE log RENAME COLUMN logout_datetime TO logout_datetime;
--ALTER TABLE log RENAME COLUMN ip_address TO ip_address;

-- Clean up column names for reservations
ALTER TABLE reservations RENAME COLUMN res_id TO id;
--ALTER TABLE reservations RENAME COLUMN vehicle_id TO vehicle_id;
ALTER TABLE reservations RENAME COLUMN user_id TO driver_id;
ALTER TABLE reservations RENAME COLUMN planned_passngr_no TO planned_passenger_no;
ALTER TABLE reservations RENAME COLUMN coord_approval TO coordinator_approval;
ALTER TABLE reservations RENAME COLUMN planned_depart_day_time TO planned_departure_datetime;
ALTER TABLE reservations RENAME COLUMN planned_return_day_time TO planned_return_datetime;
--ALTER TABLE reservations RENAME COLUMN overnight TO overnight;
ALTER TABLE reservations RENAME COLUMN childseat TO child_seat;
--ALTER TABLE reservations RENAME COLUMN destination TO destination;
--ALTER TABLE reservations RENAME COLUMN reservation_cancelled TO reservation_cancelled;
--ALTER TABLE reservations RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE reservations RENAME COLUMN cancelled_by_driver TO cancelled_by_driver;
--ALTER TABLE reservations RENAME COLUMN driver_cancelled_time TO driver_cancelled_time;
--ALTER TABLE reservations RENAME COLUMN key_no TO key_no;
--ALTER TABLE reservations RENAME COLUMN card_no TO card_no;
ALTER TABLE reservations RENAME COLUMN billing_dept TO billing_department;
--ALTER TABLE reservations RENAME COLUMN assigned_driver TO assigned_driver;
--ALTER TABLE reservations RENAME COLUMN repeating TO repeating;
ALTER TABLE reservations RENAME COLUMN res_delete_user TO deleted_by_driver;
ALTER TABLE reservations RENAME COLUMN res_delete_datetime TO deleted_datetime;
--ALTER TABLE reservations RENAME COLUMN no_cost TO no_cost;

-- Clean up column names for restricted_charges
ALTER TABLE restricted_charges RENAME COLUMN charge_id TO id;
--ALTER TABLE restricted_charges RENAME COLUMN vehicle_id TO vehicle_id;
--ALTER TABLE restricted_charges RENAME COLUMN charge_month TO charge_month;
--ALTER TABLE restricted_charges RENAME COLUMN charge_year TO charge_year;
ALTER TABLE restricted_charges RENAME COLUMN dept_id TO department_id;
ALTER TABLE restricted_charges RENAME COLUMN calc_method TO calculation_method;
--ALTER TABLE restricted_charges RENAME COLUMN total_charge TO total_charge;
--ALTER TABLE restricted_charges RENAME COLUMN begin_mileage TO begin_mileage;
--ALTER TABLE restricted_charges RENAME COLUMN end_mileage TO end_mileage;
--ALTER TABLE restricted_charges RENAME COLUMN rate TO rate;
--ALTER TABLE restricted_charges RENAME COLUMN reg_date TO reg_date;

-- Clean up column names for shop_tasks
ALTER TABLE shop_tasks RENAME COLUMN task_id TO id;
ALTER TABLE shop_tasks RENAME COLUMN user_id TO driver_id;
--ALTER TABLE shop_tasks RENAME COLUMN vehicle_id TO vehicle_id;
ALTER TABLE shop_tasks RENAME COLUMN miles_reading_tech TO mileage_reading;
--ALTER TABLE shop_tasks RENAME COLUMN last_mileage TO last_mileage;
--ALTER TABLE shop_tasks RENAME COLUMN work_type_id TO work_type_id;
--ALTER TABLE shop_tasks RENAME COLUMN work_start_date TO work_start_date;
ALTER TABLE shop_tasks RENAME COLUMN next_oil TO next_oil_change;
--ALTER TABLE shop_tasks RENAME COLUMN total_cost TO total_cost;
--ALTER TABLE shop_tasks RENAME COLUMN parts_source TO parts_source;
--ALTER TABLE shop_tasks RENAME COLUMN drive_test_done TO drive_test_done;
--ALTER TABLE shop_tasks RENAME COLUMN task_complete TO task_complete;
ALTER TABLE shop_tasks RENAME COLUMN tech_comments TO technician_comments;
--ALTER TABLE shop_tasks RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE shop_tasks RENAME COLUMN invoice_no TO invoice_no;
--ALTER TABLE shop_tasks RENAME COLUMN vendor_name TO vendor_name;

-- Clean up column names for special_notice
ALTER TABLE special_notice RENAME COLUMN notice_id TO id;
--ALTER TABLE special_notice RENAME COLUMN notice_date TO notice_date;
ALTER TABLE special_notice RENAME COLUMN user_id TO driver_id;
ALTER TABLE special_notice RENAME COLUMN notice_title TO title;
--ALTER TABLE special_notice RENAME COLUMN notice TO notice;

-- Clean up column names for service_reservations
ALTER TABLE service_reservations RENAME COLUMN srvc_id TO id;
--ALTER TABLE service_reservations RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE service_reservations RENAME COLUMN vehicle_id TO vehicle_id;
ALTER TABLE service_reservations RENAME COLUMN from_date TO from_datetime;
ALTER TABLE service_reservations RENAME COLUMN to_date TO to_datetime;
--ALTER TABLE service_reservations RENAME COLUMN is_cancelled TO is_cancelled;
--ALTER TABLE service_reservations RENAME COLUMN service_type TO service_type;

-- Clean up column names for service_reservations_details
ALTER TABLE service_reservations_details RENAME COLUMN srvc_id TO service_reservation_id;
ALTER TABLE service_reservations_details RENAME COLUMN res_id TO reservation_id;

-- Clean up column names for temp_mass_emails
ALTER TABLE temp_mass_emails RENAME COLUMN email_id TO id;
--ALTER TABLE temp_mass_emails RENAME COLUMN driver_name TO driver_name;

-- Clean up column names for trip_details
ALTER TABLE trip_details RENAME COLUMN trip_id TO id;
ALTER TABLE trip_details RENAME COLUMN res_id TO reservation_id;
--ALTER TABLE trip_details RENAME COLUMN begin_mileage TO begin_mileage;
--ALTER TABLE trip_details RENAME COLUMN end_mileage TO end_mileage;
--ALTER TABLE trip_details RENAME COLUMN end_gas_percent TO end_gas_percent;
--ALTER TABLE trip_details RENAME COLUMN problem TO problem;
ALTER TABLE trip_details RENAME COLUMN desc_problem TO problem_description;
--ALTER TABLE trip_details RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE trip_details RENAME COLUMN mile_charges TO mile_charges;
ALTER TABLE trip_details RENAME COLUMN user_id TO driver_id;

-- Clean up column names for driver and make it compatible with django auth_user.
-- Make sure this migration is run after a custom migration that creates the driver table.
ALTER TABLE driver RENAME COLUMN user_id TO id;
ALTER TABLE driver RENAME COLUMN f_name TO first_name;
ALTER TABLE driver RENAME COLUMN l_name TO last_name;
ALTER TABLE driver RENAME COLUMN dept_id TO department_id;
ALTER TABLE driver RENAME COLUMN home_st_country TO home_state_country;

-- Make sure first_name and last_name are not null
ALTER TABLE driver ALTER COLUMN first_name SET NOT NULL;
ALTER TABLE driver ALTER COLUMN last_name SET NOT NULL;

-- Adjust the password field to match auth_user's password field
ALTER TABLE driver ALTER COLUMN password TYPE varchar(128);
ALTER TABLE driver ALTER COLUMN password SET NOT NULL;

-- Add missing fields from auth_user table
ALTER TABLE driver ADD COLUMN last_login timestamp with time zone;
ALTER TABLE driver ADD COLUMN is_superuser boolean NOT NULL DEFAULT FALSE;
ALTER TABLE driver ADD COLUMN username varchar(150) NOT NULL UNIQUE;
ALTER TABLE driver ADD COLUMN is_staff boolean NOT NULL DEFAULT FALSE;
ALTER TABLE driver ADD COLUMN date_joined timestamp with time zone NOT NULL DEFAULT now();

-- Modify existing email field to match auth_user's constraints
ALTER TABLE driver ALTER COLUMN email TYPE varchar(254);
ALTER TABLE driver ADD CONSTRAINT driver_email_key UNIQUE (email);

-- Modify existing active field to match auth_user's is_active field
ALTER TABLE driver RENAME COLUMN active TO is_active;
ALTER TABLE driver ALTER COLUMN is_active SET DEFAULT TRUE;

-- Create a function to clear username and email on deactivation
CREATE OR REPLACE FUNCTION deactivate_user() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.is_active = FALSE THEN
        NEW.username := 'deactivated_' || NEW.id;
        NEW.email := 'deactivated_' || NEW.id || '@example.com';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create a trigger to call the function before update on driver
CREATE TRIGGER before_update_driver
BEFORE UPDATE ON driver
FOR EACH ROW
WHEN (OLD.is_active IS TRUE AND NEW.is_active IS FALSE)
EXECUTE FUNCTION deactivate_user();


-- Clean up column names for driver_comments
--ALTER TABLE driver_comments RENAME COLUMN id TO id;
ALTER TABLE driver_comments RENAME COLUMN posting_user_id TO posting_driver_id;
ALTER TABLE driver_comments RENAME COLUMN about_user_id TO about_driver_id;
--ALTER TABLE driver_comments RENAME COLUMN comments_date TO comments_date;
--ALTER TABLE driver_comments RENAME COLUMN comments TO comments;
--ALTER TABLE driver_comments RENAME COLUMN trip_id TO trip_id;

-- Clean up column names for driver_group
ALTER TABLE driver_group RENAME COLUMN group_id TO id;
ALTER TABLE driver_group RENAME COLUMN group_name TO name;

-- Clean up column names for vehicle_brand
ALTER TABLE vehicle_brand RENAME COLUMN brand_id TO id;
ALTER TABLE vehicle_brand RENAME COLUMN brand_name TO name;

-- Clean up column names for vehicle_comments
--ALTER TABLE vehicle_comments RENAME COLUMN id TO id;
ALTER TABLE vehicle_comments RENAME COLUMN posting_user_id TO posting_driver_id;
--ALTER TABLE vehicle_comments RENAME COLUMN vehicle_id TO vehicle_id;
--ALTER TABLE vehicle_comments RENAME COLUMN comment_date TO comment_date;
ALTER TABLE vehicle_comments RENAME COLUMN comment_type TO type;
--ALTER TABLE vehicle_comments RENAME COLUMN comments TO comments;

-- Clean up column names for vehicle_type
ALTER TABLE vehicle_type RENAME COLUMN v_type_id TO id;
ALTER TABLE vehicle_type RENAME COLUMN v_type TO type;
--ALTER TABLE vehicle_type RENAME COLUMN capacity TO capacity;

-- Clean up column names for vehicles
ALTER TABLE vehicles RENAME COLUMN vehicle_id TO id;
ALTER TABLE vehicles RENAME COLUMN user_id TO driver_id;
--ALTER TABLE vehicles RENAME COLUMN vehicle_no TO vehicle_no;
--ALTER TABLE vehicles RENAME COLUMN vin_no TO vin_no;
--ALTER TABLE vehicles RENAME COLUMN oil_filter TO oil_filter;
--ALTER TABLE vehicles RENAME COLUMN safety_date TO safety_date;
--ALTER TABLE vehicles RENAME COLUMN registration_date TO registration_date;
ALTER TABLE vehicles RENAME COLUMN lic_plate_no TO license_plate_no;
--ALTER TABLE vehicles RENAME COLUMN make_id TO make_id;
ALTER TABLE vehicles RENAME COLUMN model TO model_id;
ALTER TABLE vehicles RENAME COLUMN year_manuf TO manufacture_year;
ALTER TABLE vehicles RENAME COLUMN mileage_un TO mileage_at_takeover;
ALTER TABLE vehicles RENAME COLUMN date_to_un TO date_at_takeover;
ALTER TABLE vehicles RENAME COLUMN cost_to_un TO cost_at_takeover;
--ALTER TABLE vehicles RENAME COLUMN cost_rate TO cost_rate;
ALTER TABLE vehicles RENAME COLUMN passenger_cap TO passenger_capacity;
ALTER TABLE vehicles RENAME COLUMN condition_tech TO condition;
--ALTER TABLE vehicles RENAME COLUMN restriction TO restriction;
--ALTER TABLE vehicles RENAME COLUMN issues TO issues;
--ALTER TABLE vehicles RENAME COLUMN active TO active;
--ALTER TABLE vehicles RENAME COLUMN restricted TO restricted;
ALTER TABLE vehicles RENAME COLUMN date_revised TO revised_date;
--ALTER TABLE vehicles RENAME COLUMN sold TO sold;
--ALTER TABLE vehicles RENAME COLUMN sold_date TO sold_date;
--ALTER TABLE vehicles RENAME COLUMN admin_issues TO admin_issues;

-- Clean up column names for work_type
ALTER TABLE work_type RENAME COLUMN work_type_id TO id;
ALTER TABLE work_type RENAME COLUMN work_type TO type;

END;
