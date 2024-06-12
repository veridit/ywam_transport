BEGIN;

-- Clean up table names
ALTER TABLE tbl_3_vehicle_limit RENAME TO transport_vehicle_limit;
ALTER TABLE tbl_abandon_trips RENAME TO transport_abandon_trips;
ALTER TABLE tbl_comment_log RENAME TO transport_comment_log;
ALTER TABLE tbl_departments RENAME TO transport_departments;
ALTER TABLE tbl_driver_links_order RENAME TO transport_info_links_position;
ALTER TABLE tbl_global_settings RENAME TO transport_global_settings;
ALTER TABLE tbl_info_links RENAME TO transport_info_links;
ALTER TABLE tbl_log RENAME TO transport_log;
ALTER TABLE tbl_reservations RENAME TO transport_reservations;
ALTER TABLE tbl_restricted_charges RENAME TO transport_restricted_charges;
ALTER TABLE tbl_shop_tasks RENAME TO transport_shop_tasks;
ALTER TABLE tbl_special_notice RENAME TO transport_special_notice;
ALTER TABLE tbl_srvc_resvs RENAME TO transport_service_reservations;
ALTER TABLE tbl_srvc_resvs_details RENAME TO transport_service_reservations_details;
ALTER TABLE tbl_temp_mass_emails RENAME TO transport_temp_mass_emails;
ALTER TABLE tbl_trip_details RENAME TO transport_trip_details;
ALTER TABLE tbl_user_comments RENAME TO transport_driver_comments;
ALTER TABLE tbl_vehicle_brand RENAME TO transport_vehicle_brand;
ALTER TABLE tbl_vehicle_comments RENAME TO transport_vehicle_comments;
ALTER TABLE tbl_vehicle_type RENAME TO transport_vehicle_type;
ALTER TABLE tbl_vehicles RENAME TO transport_vehicles;
ALTER TABLE tbl_work_type RENAME TO transport_work_type;

-- Clean up column names for vehicle_limit
ALTER TABLE transport_vehicle_limit RENAME COLUMN limit_id TO id;
ALTER TABLE transport_vehicle_limit RENAME COLUMN soption TO option;
ALTER TABLE transport_vehicle_limit RENAME COLUMN dept_id TO department_id;
--ALTER TABLE transport_vehicle_limit RENAME COLUMN user_id TO user_id;
--ALTER TABLE transport_vehicle_limit RENAME COLUMN from_date TO from_date;
--ALTER TABLE transport_vehicle_limit RENAME COLUMN to_date TO to_date;
ALTER TABLE transport_vehicle_limit ADD COLUMN limit_value smallint NOT NULL DEFAULT 3;

-- Clean up column names for abandon_trips
ALTER TABLE transport_abandon_trips RENAME COLUMN abandon_id TO id;
--ALTER TABLE transport_abandon_trips RENAME COLUMN abandon_date TO abandon_date;
--ALTER TABLE transport_abandon_trips RENAME COLUMN notes TO notes;
ALTER TABLE transport_abandon_trips RENAME COLUMN res_id TO reservation_id;
--ALTER TABLE transport_abandon_trips RENAME COLUMN user_id TO user_id;
--ALTER TABLE transport_abandon_trips RENAME COLUMN mile_charges TO mile_charges;
--ALTER TABLE transport_abandon_trips RENAME COLUMN calculate_fine TO calculate_fine;
--ALTER TABLE transport_abandon_trips RENAME COLUMN miles TO miles;

-- Clean up column names for comment_log
ALTER TABLE transport_comment_log RENAME COLUMN comment_id TO id;
--ALTER TABLE transport_comment_log RENAME COLUMN user_id TO user_id;
--ALTER TABLE transport_comment_log RENAME COLUMN comments TO comments;
--ALTER TABLE transport_comment_log RENAME COLUMN comment_time TO comment_time;

-- Clean up column names for departments
ALTER TABLE transport_departments RENAME COLUMN dept_id TO id;
ALTER TABLE transport_departments RENAME COLUMN dept_name TO name;
ALTER TABLE transport_departments RENAME COLUMN leader_f_name TO leader_first_name;
ALTER TABLE transport_departments RENAME COLUMN leader_l_name TO leader_last_name;
--ALTER TABLE transport_departments RENAME COLUMN leader_phone TO leader_phone;
--ALTER TABLE transport_departments RENAME COLUMN leader_email TO leader_email;
--ALTER TABLE transport_departments RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE transport_departments RENAME COLUMN active TO active;
--ALTER TABLE transport_departments RENAME COLUMN deactive_date TO deactive_date;
ALTER TABLE transport_departments RENAME COLUMN dept_info TO info;

-- Clean up column names for global_settings
--ALTER TABLE transport_global_settings RENAME COLUMN id TO id;
--ALTER TABLE transport_global_settings RENAME COLUMN leader_code TO leader_code;

-- Clean up column names for info_links
ALTER TABLE transport_info_links RENAME COLUMN link_id TO id;
ALTER TABLE transport_info_links RENAME COLUMN link_title TO title;
ALTER TABLE transport_info_links RENAME COLUMN link_text TO text;
--ALTER TABLE transport_info_links RENAME COLUMN link_date TO link_date;
ALTER TABLE transport_info_links RENAME COLUMN link_order TO position;
ALTER TABLE transport_info_links RENAME COLUMN link_display_page TO display_page;
ALTER TABLE transport_info_links RENAME COLUMN link_display_flag TO display_flag;

-- Clean up column names for driver_links_position

-- Add the new identity column 'id' to the table
ALTER TABLE transport_info_links_position ADD COLUMN id SERIAL PRIMARY KEY;
DELETE FROM transport_info_links_position WHERE link_id NOT IN (SELECT id FROM transport_info_links);
ALTER TABLE transport_info_links_position RENAME COLUMN link_order TO position;
--ALTER TABLE transport_info_links_position RENAME COLUMN driver_login TO driver_login;


-- Clean up column names for log
ALTER TABLE transport_log RENAME COLUMN log_id TO id;
--ALTER TABLE transport_log RENAME COLUMN user_id TO user_id;
--ALTER TABLE transport_log RENAME COLUMN login_datetime TO login_datetime;
--ALTER TABLE transport_log RENAME COLUMN logout_datetime TO logout_datetime;
--ALTER TABLE transport_log RENAME COLUMN ip_address TO ip_address;

-- Clean up column names for reservations
ALTER TABLE transport_reservations RENAME COLUMN res_id TO id;
--ALTER TABLE transport_reservations RENAME COLUMN vehicle_id TO vehicle_id;
--ALTER TABLE transport_reservations RENAME COLUMN user_id TO user_id;
ALTER TABLE transport_reservations RENAME COLUMN planned_passngr_no TO planned_passenger_no;
ALTER TABLE transport_reservations RENAME COLUMN coord_approval TO coordinator_approval;
ALTER TABLE transport_reservations RENAME COLUMN planned_depart_day_time TO planned_departure_datetime;
ALTER TABLE transport_reservations RENAME COLUMN planned_return_day_time TO planned_return_datetime;
--ALTER TABLE transport_reservations RENAME COLUMN overnight TO overnight;
ALTER TABLE transport_reservations RENAME COLUMN childseat TO child_seat;
--ALTER TABLE transport_reservations RENAME COLUMN destination TO destination;
--ALTER TABLE transport_reservations RENAME COLUMN reservation_cancelled TO reservation_cancelled;
--ALTER TABLE transport_reservations RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE transport_reservations RENAME COLUMN cancelled_by_driver TO cancelled_by_driver;
--ALTER TABLE transport_reservations RENAME COLUMN driver_cancelled_time TO driver_cancelled_time;
--ALTER TABLE transport_reservations RENAME COLUMN key_no TO key_no;
--ALTER TABLE transport_reservations RENAME COLUMN card_no TO card_no;
ALTER TABLE transport_reservations RENAME COLUMN billing_dept TO billing_department;
--ALTER TABLE transport_reservations RENAME COLUMN assigned_driver TO assigned_driver;
--ALTER TABLE transport_reservations RENAME COLUMN repeating TO repeating;
ALTER TABLE transport_reservations RENAME COLUMN res_delete_user TO deleted_by_driver;
ALTER TABLE transport_reservations RENAME COLUMN res_delete_datetime TO deleted_datetime;
--ALTER TABLE transport_reservations RENAME COLUMN no_cost TO no_cost;

-- Clean up column names for restricted_charges
ALTER TABLE transport_restricted_charges RENAME COLUMN charge_id TO id;
--ALTER TABLE transport_restricted_charges RENAME COLUMN vehicle_id TO vehicle_id;
--ALTER TABLE transport_restricted_charges RENAME COLUMN charge_month TO charge_month;
--ALTER TABLE transport_restricted_charges RENAME COLUMN charge_year TO charge_year;
ALTER TABLE transport_restricted_charges RENAME COLUMN dept_id TO department_id;
ALTER TABLE transport_restricted_charges RENAME COLUMN calc_method TO calculation_method;
--ALTER TABLE transport_restricted_charges RENAME COLUMN total_charge TO total_charge;
--ALTER TABLE transport_restricted_charges RENAME COLUMN begin_mileage TO begin_mileage;
--ALTER TABLE transport_restricted_charges RENAME COLUMN end_mileage TO end_mileage;
--ALTER TABLE transport_restricted_charges RENAME COLUMN rate TO rate;
--ALTER TABLE transport_restricted_charges RENAME COLUMN reg_date TO reg_date;

-- Clean up column names for shop_tasks
ALTER TABLE transport_shop_tasks RENAME COLUMN task_id TO id;
--ALTER TABLE transport_shop_tasks RENAME COLUMN user_id TO user_id;
--ALTER TABLE transport_shop_tasks RENAME COLUMN vehicle_id TO vehicle_id;
ALTER TABLE transport_shop_tasks RENAME COLUMN miles_reading_tech TO mileage_reading;
--ALTER TABLE transport_shop_tasks RENAME COLUMN last_mileage TO last_mileage;
--ALTER TABLE transport_shop_tasks RENAME COLUMN work_type_id TO work_type_id;
--ALTER TABLE transport_shop_tasks RENAME COLUMN work_start_date TO work_start_date;
ALTER TABLE transport_shop_tasks RENAME COLUMN next_oil TO next_oil_change;
--ALTER TABLE transport_shop_tasks RENAME COLUMN total_cost TO total_cost;
--ALTER TABLE transport_shop_tasks RENAME COLUMN parts_source TO parts_source;
--ALTER TABLE transport_shop_tasks RENAME COLUMN drive_test_done TO drive_test_done;
--ALTER TABLE transport_shop_tasks RENAME COLUMN task_complete TO task_complete;
ALTER TABLE transport_shop_tasks RENAME COLUMN tech_comments TO technician_comments;
--ALTER TABLE transport_shop_tasks RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE transport_shop_tasks RENAME COLUMN invoice_no TO invoice_no;
--ALTER TABLE transport_shop_tasks RENAME COLUMN vendor_name TO vendor_name;

-- Clean up column names for special_notice
ALTER TABLE transport_special_notice RENAME COLUMN notice_id TO id;
--ALTER TABLE transport_special_notice RENAME COLUMN notice_date TO notice_date;
--ALTER TABLE transport_special_notice RENAME COLUMN user_id TO user_id;
ALTER TABLE transport_special_notice RENAME COLUMN notice_title TO title;
--ALTER TABLE transport_special_notice RENAME COLUMN notice TO notice;

-- Clean up column names for service_reservations
ALTER TABLE transport_service_reservations RENAME COLUMN srvc_id TO id;
--ALTER TABLE transport_service_reservations RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE transport_service_reservations RENAME COLUMN vehicle_id TO vehicle_id;
ALTER TABLE transport_service_reservations RENAME COLUMN from_date TO from_datetime;
ALTER TABLE transport_service_reservations RENAME COLUMN to_date TO to_datetime;
--ALTER TABLE transport_service_reservations RENAME COLUMN is_cancelled TO is_cancelled;
--ALTER TABLE transport_service_reservations RENAME COLUMN service_type TO service_type;

-- Clean up column names for service_reservations_details
ALTER TABLE transport_service_reservations_details ADD COLUMN id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY;
ALTER TABLE transport_service_reservations_details RENAME COLUMN srvc_id TO service_reservation_id;
ALTER TABLE transport_service_reservations_details RENAME COLUMN res_id TO reservation_id;

-- Clean up column names for temp_mass_emails
ALTER TABLE transport_temp_mass_emails ADD COLUMN id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY;
ALTER TABLE transport_temp_mass_emails RENAME COLUMN email_id TO email;
--ALTER TABLE transport_temp_mass_emails RENAME COLUMN driver_name TO driver_name;

-- Clean up column names for trip_details
ALTER TABLE transport_trip_details RENAME COLUMN trip_id TO id;
ALTER TABLE transport_trip_details RENAME COLUMN res_id TO reservation_id;
--ALTER TABLE transport_trip_details RENAME COLUMN begin_mileage TO begin_mileage;
--ALTER TABLE transport_trip_details RENAME COLUMN end_mileage TO end_mileage;
--ALTER TABLE transport_trip_details RENAME COLUMN end_gas_percent TO end_gas_percent;
--ALTER TABLE transport_trip_details RENAME COLUMN problem TO problem;
ALTER TABLE transport_trip_details RENAME COLUMN desc_problem TO problem_description;
--ALTER TABLE transport_trip_details RENAME COLUMN reg_date TO reg_date;
--ALTER TABLE transport_trip_details RENAME COLUMN mile_charges TO mile_charges;
--ALTER TABLE transport_trip_details RENAME COLUMN user_id TO user_id;

--------------------------------------------------------------------------
UPDATE tbl_user
SET email = 'thor.stensby@uofnkona.org'
WHERE email = '0' AND f_name = 'Thor' AND l_name = 'Stensby';

UPDATE tbl_user
SET email = 'mark.han@uofnkona.org'
WHERE email = '0' AND f_name = 'Mark' AND l_name = 'Han';


CREATE TABLE transport_driver (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE,
    department_id VARCHAR(4) NOT NULL,
    phone VARCHAR(25) NOT NULL,
    birth_date DATE,
    license_no VARCHAR(20) NOT NULL,
    license_state VARCHAR(15) NOT NULL,
    license_country VARCHAR(60) NOT NULL,
    license_expire DATE,
    drive_tested VARCHAR(30) NOT NULL,
    test_date DATE,
    end_permit DATE,
    home_country VARCHAR(30) NOT NULL,
    status_date TIMESTAMPTZ,
    user_type VARCHAR(20) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    photo_link VARCHAR(255) NOT NULL,
    comment VARCHAR(300) NOT NULL,
    permit_type VARCHAR(20) NOT NULL DEFAULT 'First',
    renew_date DATE,
    renew_text VARCHAR(200),
    new_user BOOLEAN NOT NULL DEFAULT FALSE,
    driver_permission BOOLEAN NOT NULL DEFAULT FALSE,
    max_passengers BIGINT NOT NULL DEFAULT 15
);


INSERT INTO auth_user
  ( id
  , password
  , last_login
  , is_superuser
  , username
  , first_name
  , last_name
  , email
  , is_staff
  , is_active
  , date_joined
)
SELECT user_id
     , password
     , status_date AS last_login
     , CASE WHEN user_group IN (1, 2) THEN TRUE ELSE FALSE END AS is_superuser
     , email AS username
     , f_name AS first_name
     , l_name AS last_name
     , email
     , CASE WHEN user_group IN (1, 2) THEN TRUE ELSE FALSE END AS is_staff
     , active AS is_active
     , reg_date AS date_joined
FROM
    tbl_user;

SELECT setval('auth_user_id_seq', (SELECT MAX(id) FROM auth_user));

--\echo tbl_user WHERE dept_id NOT IN (SELECT id FROM transport_departments);
DELETE FROM tbl_user WHERE dept_id NOT IN (SELECT id FROM transport_departments);

INSERT INTO transport_driver
  ( user_id
  , department_id
  , phone
  , birth_date
  , license_no
  , license_state
  , license_expire
  , drive_tested
  , test_date
  , end_permit
  , home_country
  , status_date
  , user_type
  , photo
  , photo_link
  , comment
  , permit_type
  , renew_date
  , renew_text
  , new_user
  , driver_permission
  , license_country
  , max_passengers
)
SELECT user_id
     , dept_id
     , phone
     , birth_date
     , license_no
     , license_state
     , license_expire
     , drive_tested
     , test_date
     , end_permit
     , home_st_country
     , status_date
     , user_type
     , photo
     , photo_link
     , comment
     , permit_type
     , renew_date
     , renew_text
     , new_user
     , driver_permission
     , license_country
     , max_passengers
FROM
    tbl_user;

-- Migrate driver groups to auth_group
INSERT INTO auth_group (id, name)
SELECT group_id, group_name
FROM tbl_user_group;

SELECT setval('auth_group_id_seq', (SELECT MAX(id) FROM auth_group));

DROP TABLE tbl_user_group;

-- Migrate user group associations to auth_user_groups
INSERT INTO auth_user_groups (user_id, group_id)
SELECT
    user_id,
    user_group
FROM
    tbl_user
WHERE
    user_group IS NOT NULL
    AND user_group IN (SELECT id FROM auth_group);

DROP TABLE tbl_user;

----------------------------------------------------------------


-- Clean up column names for driver_comments
--ALTER TABLE transport_driver_comments RENAME COLUMN id TO id;
--ALTER TABLE transport_driver_comments RENAME COLUMN posting_user_id TO posting_user_id;
--ALTER TABLE transport_driver_comments RENAME COLUMN about_user_id TO about_user_id;
--ALTER TABLE transport_driver_comments RENAME COLUMN comments_date TO comments_date;
--ALTER TABLE transport_driver_comments RENAME COLUMN comments TO comments;
--ALTER TABLE transport_driver_comments RENAME COLUMN trip_id TO trip_id;

-- Clean up column names for vehicle_brand
ALTER TABLE transport_vehicle_brand RENAME COLUMN brand_id TO id;
ALTER TABLE transport_vehicle_brand RENAME COLUMN brand_name TO name;

-- Clean up column names for vehicle_comments
--ALTER TABLE transport_vehicle_comments RENAME COLUMN id TO id;
--ALTER TABLE transport_vehicle_comments RENAME COLUMN posting_user_id TO posting_user_id;
--ALTER TABLE transport_vehicle_comments RENAME COLUMN vehicle_id TO vehicle_id;
--ALTER TABLE transport_vehicle_comments RENAME COLUMN comment_date TO comment_date;
ALTER TABLE transport_vehicle_comments RENAME COLUMN comment_type TO type;
--ALTER TABLE transport_vehicle_comments RENAME COLUMN comments TO comments;

-- Clean up column names for vehicle_type
ALTER TABLE transport_vehicle_type RENAME COLUMN v_type_id TO id;
ALTER TABLE transport_vehicle_type RENAME COLUMN v_type TO type;
--ALTER TABLE transport_vehicle_type RENAME COLUMN capacity TO capacity;

-- Clean up column names for vehicles
ALTER TABLE transport_vehicles RENAME COLUMN vehicle_id TO id;
--ALTER TABLE transport_vehicles RENAME COLUMN user_id TO user_id;
--ALTER TABLE transport_vehicles RENAME COLUMN vehicle_no TO vehicle_no;
--ALTER TABLE transport_vehicles RENAME COLUMN vin_no TO vin_no;
--ALTER TABLE transport_vehicles RENAME COLUMN oil_filter TO oil_filter;
--ALTER TABLE transport_vehicles RENAME COLUMN safety_date TO safety_date;
--ALTER TABLE transport_vehicles RENAME COLUMN registration_date TO registration_date;
ALTER TABLE transport_vehicles RENAME COLUMN lic_plate_no TO license_plate_no;
--ALTER TABLE transport_vehicles RENAME COLUMN make_id TO make_id;
ALTER TABLE transport_vehicles RENAME COLUMN model TO model_id;
ALTER TABLE transport_vehicles RENAME COLUMN year_manuf TO manufacture_year;
ALTER TABLE transport_vehicles RENAME COLUMN mileage_un TO mileage_at_takeover;
ALTER TABLE transport_vehicles RENAME COLUMN date_to_un TO date_at_takeover;
ALTER TABLE transport_vehicles RENAME COLUMN cost_to_un TO cost_at_takeover;
--ALTER TABLE transport_vehicles RENAME COLUMN cost_rate TO cost_rate;
ALTER TABLE transport_vehicles RENAME COLUMN passenger_cap TO passenger_capacity;
ALTER TABLE transport_vehicles RENAME COLUMN condition_tech TO condition;
--ALTER TABLE transport_vehicles RENAME COLUMN restriction TO restriction;
--ALTER TABLE transport_vehicles RENAME COLUMN issues TO issues;
--ALTER TABLE transport_vehicles RENAME COLUMN active TO active;
--ALTER TABLE transport_vehicles RENAME COLUMN restricted TO restricted;
ALTER TABLE transport_vehicles RENAME COLUMN date_revised TO revised_date;
--ALTER TABLE transport_vehicles RENAME COLUMN sold TO sold;
--ALTER TABLE transport_vehicles RENAME COLUMN sold_date TO sold_date;
--ALTER TABLE transport_vehicles RENAME COLUMN admin_issues TO admin_issues;

-- Clean up column names for work_type
ALTER TABLE transport_work_type RENAME COLUMN work_type_id TO id;
ALTER TABLE transport_work_type RENAME COLUMN work_type TO type;


-- add_relationships.sql

-- Add foreign key relationships

--\echo vehicle_limit WHERE department_id NOT IN (SELECT id FROM transport_departments);
DELETE FROM transport_vehicle_limit WHERE department_id NOT IN (SELECT id FROM transport_departments);
--\echo vehicle_limit WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_vehicle_limit WHERE user_id NOT IN (SELECT id FROM auth_user);

--\echo abandon_trips WHERE reservation_id NOT IN (SELECT id FROM transport_reservations);
DELETE FROM transport_abandon_trips WHERE reservation_id NOT IN (SELECT id FROM transport_reservations);
--\echo abandon_trips WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_abandon_trips WHERE user_id NOT IN (SELECT id FROM auth_user);

--\echo comment_log WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_comment_log WHERE user_id NOT IN (SELECT id FROM auth_user);

--\echo log WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_log WHERE user_id NOT IN (SELECT id FROM auth_user);

-- Resolve TODO: Deletion process for reservations table
-- Step 1: Create a temporary table to store IDs of rows to be deleted
CREATE TEMPORARY TABLE transport_temp_deleted_reservations AS
SELECT id FROM transport_reservations
WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles)
   OR user_id NOT IN (SELECT id FROM auth_user)
   OR billing_department NOT IN (SELECT id FROM transport_departments)
   OR assigned_driver NOT IN (SELECT id FROM transport_driver)
   OR deleted_by_driver NOT IN (SELECT id FROM transport_driver);

-- Step 2: Delete related rows from linked tables
--\echo abandon_trips WHERE reservation_id IN (SELECT id FROM transport_temp_deleted_reservations);
DELETE FROM transport_abandon_trips WHERE reservation_id IN (SELECT id FROM transport_temp_deleted_reservations);
--\echo service_reservations_details WHERE reservation_id IN (SELECT id FROM transport_temp_deleted_reservations);
DELETE FROM transport_service_reservations_details WHERE reservation_id IN (SELECT id FROM transport_temp_deleted_reservations);
--\echo trip_details WHERE reservation_id IN (SELECT id FROM transport_temp_deleted_reservations);
DELETE FROM transport_trip_details WHERE reservation_id IN (SELECT id FROM transport_temp_deleted_reservations);
--\echo driver_comments WHERE trip_id IN (SELECT id FROM transport_temp_deleted_reservations);
DELETE FROM transport_driver_comments WHERE trip_id IN (SELECT id FROM transport_temp_deleted_reservations);

-- Step 3: Delete rows from the reservations table
--\echo reservations WHERE id IN (SELECT id FROM transport_temp_deleted_reservations);
DELETE FROM transport_reservations WHERE id IN (SELECT id FROM transport_temp_deleted_reservations);

-- Drop the temporary table
DROP TABLE transport_temp_deleted_reservations;

--\echo restricted_charges WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);
DELETE FROM transport_restricted_charges WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);
--\echo restricted_charges WHERE department_id NOT IN (SELECT id FROM transport_departments);
DELETE FROM transport_restricted_charges WHERE department_id NOT IN (SELECT id FROM transport_departments);

--\echo shop_tasks WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_shop_tasks WHERE user_id NOT IN (SELECT id FROM auth_user);
--\echo shop_tasks WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);
DELETE FROM transport_shop_tasks WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);
--\echo shop_tasks WHERE work_type_id NOT IN (SELECT id FROM transport_work_type);
DELETE FROM transport_shop_tasks WHERE work_type_id NOT IN (SELECT id FROM transport_work_type);

--\echo special_notice WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_special_notice WHERE user_id NOT IN (SELECT id FROM auth_user);

--\echo service_reservations WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);
DELETE FROM transport_service_reservations WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);

--\echo service_reservations_details WHERE service_reservation_id NOT IN (SELECT id FROM transport_service_reservations);
DELETE FROM transport_service_reservations_details WHERE service_reservation_id NOT IN (SELECT id FROM transport_service_reservations);

--\echo service_reservations_details WHERE reservation_id NOT IN (SELECT id FROM transport_reservations);
DELETE FROM transport_service_reservations_details WHERE reservation_id NOT IN (SELECT id FROM transport_reservations);

--\echo trip_details WHERE reservation_id NOT IN (SELECT id FROM transport_reservations);
DELETE FROM transport_trip_details WHERE reservation_id NOT IN (SELECT id FROM transport_reservations);
--\echo trip_details WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_trip_details WHERE user_id NOT IN (SELECT id FROM auth_user);

--\echo driver_comments WHERE posting_user_id NOT IN (SELECT id FROM transport_driver);
DELETE FROM transport_driver_comments WHERE posting_user_id NOT IN (SELECT id FROM transport_driver);
--\echo driver_comments WHERE about_user_id NOT IN (SELECT id FROM transport_driver);
DELETE FROM transport_driver_comments WHERE about_user_id NOT IN (SELECT id FROM transport_driver);
--\echo driver_comments WHERE trip_id NOT IN (SELECT id FROM transport_trip_details);
DELETE FROM transport_driver_comments WHERE trip_id NOT IN (SELECT id FROM transport_trip_details);

--\echo vehicles WHERE user_id NOT IN (SELECT id FROM auth_user);
DELETE FROM transport_vehicles WHERE user_id NOT IN (SELECT id FROM auth_user);
--\echo vehicles WHERE make_id NOT IN (SELECT id FROM transport_vehicle_brand);
DELETE FROM transport_vehicles WHERE make_id NOT IN (SELECT id FROM transport_vehicle_brand);
--\echo vehicles WHERE model_id NOT IN (SELECT id FROM transport_vehicle_type);
DELETE FROM transport_vehicles WHERE model_id NOT IN (SELECT id FROM transport_vehicle_type);

--\echo vehicle_comments WHERE posting_user_id NOT IN (SELECT id FROM transport_driver);
DELETE FROM transport_vehicle_comments WHERE posting_user_id NOT IN (SELECT id FROM transport_driver);
--\echo vehicle_comments WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);
DELETE FROM transport_vehicle_comments WHERE vehicle_id NOT IN (SELECT id FROM transport_vehicles);


ALTER TABLE "transport_service_reservations" ADD CONSTRAINT "transport_service_re_vehicle_id_4d4d35f7_fk_transport" FOREIGN KEY ("vehicle_id") REFERENCES "transport_vehicles"("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_driver" ADD CONSTRAINT "transport_driver_department_id_2103ad31_fk_transport" FOREIGN KEY ("department_id") REFERENCES "transport_departments" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_driver" ADD CONSTRAINT "transport_driver_user_id_29ac0108_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_reservations" ADD CONSTRAINT "transport_reservatio_vehicle_id_38940c0c_fk_transport" FOREIGN KEY ("vehicle_id") REFERENCES "transport_vehicles"("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_reservations" ADD CONSTRAINT "transport_reservatio_assigned_driver_7b0956f3_fk_transport" FOREIGN KEY ("assigned_driver") REFERENCES "transport_driver" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_reservations" ADD CONSTRAINT "transport_reservatio_billing_department_649aba28_fk_transport" FOREIGN KEY ("billing_department") REFERENCES "transport_departments" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_reservations" ADD CONSTRAINT "transport_reservatio_deleted_by_driver_c7405959_fk_transport" FOREIGN KEY ("deleted_by_driver") REFERENCES "transport_driver" ("id") ON DELETE SET NULL DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_reservations" ADD CONSTRAINT "transport_reservations_user_id_3a33be73_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_vehicles" ADD CONSTRAINT "transport_vehicles_make_id_e017fa97_fk_transport" FOREIGN KEY ("make_id") REFERENCES "transport_vehicle_brand" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_vehicles" ADD CONSTRAINT "transport_vehicles_model_id_4952d844_fk_transport" FOREIGN KEY ("model_id") REFERENCES "transport_vehicle_type" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_vehicles" ADD CONSTRAINT "transport_vehicles_user_id_084f10a6_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_vehicle_limit" ADD CONSTRAINT "transport_vehicle_li_department_id_66309f9d_fk_transport" FOREIGN KEY ("department_id") REFERENCES "transport_departments" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_vehicle_limit" ADD CONSTRAINT "transport_vehicle_limit_user_id_2f698599_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_vehicle_comments" ADD CONSTRAINT "transport_vehicle_co_posting_user_id_9c17d3f4_fk_transport" FOREIGN KEY ("posting_user_id") REFERENCES "transport_driver" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_vehicle_comments" ADD CONSTRAINT "transport_vehicle_co_vehicle_id_a7fe8bd9_fk_transport" FOREIGN KEY ("vehicle_id") REFERENCES "transport_vehicles" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_trip_details" ADD CONSTRAINT "transport_trip_detai_reservation_id_8ee1caf4_fk_transport" FOREIGN KEY ("reservation_id") REFERENCES "transport_reservations" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_trip_details" ADD CONSTRAINT "transport_trip_details_user_id_51026fff_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE SET NULL DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_special_notice" ADD CONSTRAINT "transport_special_notice_user_id_77c44512_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_shop_tasks" ADD CONSTRAINT "transport_shop_tasks_user_id_45df4e1d_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_shop_tasks" ADD CONSTRAINT "transport_shop_tasks_vehicle_id_a0e004fa_fk_transport" FOREIGN KEY ("vehicle_id") REFERENCES "transport_vehicles" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_shop_tasks" ADD CONSTRAINT "transport_shop_tasks_work_type_id_a5127d1e_fk_transport" FOREIGN KEY ("work_type_id") REFERENCES "transport_work_type" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_service_reservations_details" ADD CONSTRAINT "transport_service_re_reservation_id_15059748_fk_transport" FOREIGN KEY ("reservation_id") REFERENCES "transport_reservations" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_service_reservations_details" ADD CONSTRAINT "transport_service_re_service_reservation__20e82c51_fk_transport" FOREIGN KEY ("service_reservation_id") REFERENCES "transport_service_reservations" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_restricted_charges" ADD CONSTRAINT "transport_restricted_department_id_9c95448e_fk_transport" FOREIGN KEY ("department_id") REFERENCES "transport_departments" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_restricted_charges" ADD CONSTRAINT "transport_restricted_vehicle_id_608acb24_fk_transport" FOREIGN KEY ("vehicle_id") REFERENCES "transport_vehicles" ("id") ON DELETE RESTRICT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_log" ADD CONSTRAINT "transport_log_user_id_368ca736_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_info_links_position" ADD CONSTRAINT "transport_info_links_link_id_ec5b1832_fk_transport" FOREIGN KEY ("link_id") REFERENCES "transport_info_links" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_driver_comments" ADD CONSTRAINT "transport_driver_com_about_user_id_70fd68bb_fk_transport" FOREIGN KEY ("about_user_id") REFERENCES "transport_driver" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_driver_comments" ADD CONSTRAINT "transport_driver_com_posting_user_id_7ebc044b_fk_transport" FOREIGN KEY ("posting_user_id") REFERENCES "transport_driver" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_driver_comments" ADD CONSTRAINT "transport_driver_com_trip_id_7325ffae_fk_transport" FOREIGN KEY ("trip_id") REFERENCES "transport_trip_details" ("id") ON DELETE SET NULL DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transport_comment_log" ADD CONSTRAINT "transport_comment_log_user_id_7449557a_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_abandon_trips" ADD CONSTRAINT "transport_abandon_tr_reservation_id_1cf30d87_fk_transport" FOREIGN KEY ("reservation_id") REFERENCES "transport_reservations" ("id") ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "transport_abandon_trips" ADD CONSTRAINT "transport_abandon_trips_user_id_88d1e9ec_fk_auth_user_id" FOREIGN KEY ("user_id") REFERENCES "auth_user" ("id") ON DELETE SET NULL DEFERRABLE INITIALLY IMMEDIATE;



CREATE INDEX "transport_departments_id_e6a036f1_like" ON "transport_departments" ("id" varchar_pattern_ops);
CREATE INDEX "transport_driver_department_id_2103ad31" ON "transport_driver" ("department_id");
CREATE INDEX "transport_driver_department_id_2103ad31_like" ON "transport_driver" ("department_id" varchar_pattern_ops);
CREATE INDEX "transport_reservations_assigned_driver_7b0956f3" ON "transport_reservations" ("assigned_driver");
CREATE INDEX "transport_reservations_billing_department_649aba28" ON "transport_reservations" ("billing_department");
CREATE INDEX "transport_reservations_billing_department_649aba28_like" ON "transport_reservations" ("billing_department" varchar_pattern_ops);
CREATE INDEX "transport_reservations_deleted_by_driver_c7405959" ON "transport_reservations" ("deleted_by_driver");
CREATE INDEX "transport_reservations_user_id_3a33be73" ON "transport_reservations" ("user_id");
CREATE INDEX "transport_vehicles_make_id_e017fa97" ON "transport_vehicles" ("make_id");
CREATE INDEX "transport_vehicles_model_id_4952d844" ON "transport_vehicles" ("model_id");
CREATE INDEX "transport_vehicles_user_id_084f10a6" ON "transport_vehicles" ("user_id");
CREATE INDEX "transport_vehicle_limit_department_id_66309f9d" ON "transport_vehicle_limit" ("department_id");
CREATE INDEX "transport_vehicle_limit_department_id_66309f9d_like" ON "transport_vehicle_limit" ("department_id" varchar_pattern_ops);
CREATE INDEX "transport_vehicle_limit_user_id_2f698599" ON "transport_vehicle_limit" ("user_id");
CREATE INDEX "transport_vehicle_comments_posting_user_id_9c17d3f4" ON "transport_vehicle_comments" ("posting_user_id");
CREATE INDEX "transport_vehicle_comments_vehicle_id_a7fe8bd9" ON "transport_vehicle_comments" ("vehicle_id");
CREATE INDEX "transport_trip_details_reservation_id_8ee1caf4" ON "transport_trip_details" ("reservation_id");
CREATE INDEX "transport_trip_details_user_id_51026fff" ON "transport_trip_details" ("user_id");
CREATE INDEX "transport_special_notice_user_id_77c44512" ON "transport_special_notice" ("user_id");
CREATE INDEX "transport_shop_tasks_user_id_45df4e1d" ON "transport_shop_tasks" ("user_id");
CREATE INDEX "transport_shop_tasks_vehicle_id_a0e004fa" ON "transport_shop_tasks" ("vehicle_id");
CREATE INDEX "transport_shop_tasks_work_type_id_a5127d1e" ON "transport_shop_tasks" ("work_type_id");
CREATE INDEX "transport_service_reservations_details_reservation_id_15059748" ON "transport_service_reservations_details" ("reservation_id");
CREATE INDEX "transport_service_reservat_service_reservation_id_20e82c51" ON "transport_service_reservations_details" ("service_reservation_id");
CREATE INDEX "transport_service_reservations_vehicle_id_4d4d35f7" ON "transport_service_reservations" ("vehicle_id");
CREATE INDEX "transport_restricted_charges_department_id_9c95448e" ON "transport_restricted_charges" ("department_id");
CREATE INDEX "transport_restricted_charges_department_id_9c95448e_like" ON "transport_restricted_charges" ("department_id" varchar_pattern_ops);
CREATE INDEX "transport_restricted_charges_vehicle_id_608acb24" ON "transport_restricted_charges" ("vehicle_id");
CREATE INDEX "transport_reservations_vehicle_id_38940c0c" ON "transport_reservations" ("vehicle_id");
CREATE INDEX "transport_log_user_id_368ca736" ON "transport_log" ("user_id");
CREATE INDEX "transport_info_links_position_link_id_ec5b1832" ON "transport_info_links_position" ("link_id");
CREATE INDEX "transport_driver_comments_about_user_id_70fd68bb" ON "transport_driver_comments" ("about_user_id");
CREATE INDEX "transport_driver_comments_posting_user_id_7ebc044b" ON "transport_driver_comments" ("posting_user_id");
CREATE INDEX "transport_driver_comments_trip_id_7325ffae" ON "transport_driver_comments" ("trip_id");
CREATE INDEX "transport_comment_log_user_id_7449557a" ON "transport_comment_log" ("user_id");
CREATE INDEX "transport_abandon_trips_reservation_id_1cf30d87" ON "transport_abandon_trips" ("reservation_id");
CREATE INDEX "transport_abandon_trips_user_id_88d1e9ec" ON "transport_abandon_trips" ("user_id");

END;
