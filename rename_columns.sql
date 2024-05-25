-- rename_columns.sql
BEGIN;

-- Clean up column names for vehicle_limit
ALTER TABLE vehicle_limit RENAME COLUMN limit_id TO id;
ALTER TABLE vehicle_limit RENAME COLUMN soption TO option;
ALTER TABLE vehicle_limit RENAME COLUMN dept_id TO department_id;
ALTER TABLE vehicle_limit RENAME COLUMN user_id TO user_profile_id;
--ALTER TABLE vehicle_limit RENAME COLUMN from_date TO from_date;
--ALTER TABLE vehicle_limit RENAME COLUMN to_date TO to_date;

-- Clean up column names for abandon_trips
ALTER TABLE abandon_trips RENAME COLUMN abandon_id TO id;
--ALTER TABLE abandon_trips RENAME COLUMN abandon_date TO abandon_date;
--ALTER TABLE abandon_trips RENAME COLUMN notes TO notes;
ALTER TABLE abandon_trips RENAME COLUMN res_id TO reservation_id;
ALTER TABLE abandon_trips RENAME COLUMN user_id TO user_profile_id;
--ALTER TABLE abandon_trips RENAME COLUMN mile_charges TO mile_charges;
--ALTER TABLE abandon_trips RENAME COLUMN calculate_fine TO calculate_fine;
--ALTER TABLE abandon_trips RENAME COLUMN miles TO miles;

-- Clean up column names for comment_log
ALTER TABLE comment_log RENAME COLUMN comment_id TO id;
ALTER TABLE comment_log RENAME COLUMN user_id TO user_profile_id;
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
ALTER TABLE log RENAME COLUMN user_id TO user_profile_id;
--ALTER TABLE log RENAME COLUMN login_datetime TO login_datetime;
--ALTER TABLE log RENAME COLUMN logout_datetime TO logout_datetime;
--ALTER TABLE log RENAME COLUMN ip_address TO ip_address;

-- Clean up column names for reservations
ALTER TABLE reservations RENAME COLUMN res_id TO id;
--ALTER TABLE reservations RENAME COLUMN vehicle_id TO vehicle_id;
ALTER TABLE reservations RENAME COLUMN user_id TO user_profile_id;
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
ALTER TABLE reservations RENAME COLUMN res_delete_user TO deleted_by_user;
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
ALTER TABLE shop_tasks RENAME COLUMN user_id TO user_profile_id;
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
ALTER TABLE special_notice RENAME COLUMN user_id TO user_profile_id;
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
ALTER TABLE trip_details RENAME COLUMN user_id TO user_profile_id;

-- Clean up column names for user
ALTER TABLE "user" RENAME COLUMN user_id TO id;
ALTER TABLE "user" RENAME COLUMN f_name TO first_name;
ALTER TABLE "user" RENAME COLUMN l_name TO last_name;
ALTER TABLE "user" RENAME COLUMN dept_id TO department_id;
--ALTER TABLE "user" RENAME COLUMN phone TO phone;
--ALTER TABLE "user" RENAME COLUMN birth_date TO birth_date;
--ALTER TABLE "user" RENAME COLUMN license_no TO license_no;
--ALTER TABLE "user" RENAME COLUMN license_state TO license_state;
--ALTER TABLE "user" RENAME COLUMN license_expire TO license_expire;
--ALTER TABLE "user" RENAME COLUMN email TO email;
--ALTER TABLE "user" RENAME COLUMN password TO password;
--ALTER TABLE "user" RENAME COLUMN drive_tested TO drive_tested;
--ALTER TABLE "user" RENAME COLUMN test_date TO test_date;
--ALTER TABLE "user" RENAME COLUMN end_permit TO end_permit;
ALTER TABLE "user" RENAME COLUMN home_st_country TO home_state_country;
--ALTER TABLE "user" RENAME COLUMN active TO active;
--ALTER TABLE "user" RENAME COLUMN status_date TO status_date;
--ALTER TABLE "user" RENAME COLUMN user_group TO user_group;
--ALTER TABLE "user" RENAME COLUMN user_type TO user_type;
--ALTER TABLE "user" RENAME COLUMN photo TO photo;
--ALTER TABLE "user" RENAME COLUMN photo_link TO photo_link;
--ALTER TABLE "user" RENAME COLUMN comment TO comment;
--ALTER TABLE "user" RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE "user" RENAME COLUMN permit_type TO permit_type;
--ALTER TABLE "user" RENAME COLUMN renew_date TO renew_date;
--ALTER TABLE "user" RENAME COLUMN renew_text TO renew_text;
--ALTER TABLE "user" RENAME COLUMN new_user TO new_user;
--ALTER TABLE "user" RENAME COLUMN driver_permission TO driver_permission;
--ALTER TABLE "user" RENAME COLUMN license_country TO license_country;
--ALTER TABLE "user" RENAME COLUMN max_passengers TO max_passengers;

-- Clean up column names for user_comments
--ALTER TABLE user_comments RENAME COLUMN id TO id;
ALTER TABLE user_comments RENAME COLUMN posting_user_id TO posting_user_profile_id;
ALTER TABLE user_comments RENAME COLUMN about_user_id TO about_user_profile_id;
--ALTER TABLE user_comments RENAME COLUMN comments_date TO comments_date;
--ALTER TABLE user_comments RENAME COLUMN comments TO comments;
--ALTER TABLE user_comments RENAME COLUMN trip_id TO trip_id;

-- Clean up column names for user_group
ALTER TABLE user_group RENAME COLUMN group_id TO id;
ALTER TABLE user_group RENAME COLUMN group_name TO name;

-- Clean up column names for vehicle_brand
ALTER TABLE vehicle_brand RENAME COLUMN brand_id TO id;
ALTER TABLE vehicle_brand RENAME COLUMN brand_name TO name;

-- Clean up column names for vehicle_comments
--ALTER TABLE vehicle_comments RENAME COLUMN id TO id;
ALTER TABLE vehicle_comments RENAME COLUMN posting_user_id TO posting_user_profile_id;
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
ALTER TABLE vehicles RENAME COLUMN user_id TO user_profile_id;
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
