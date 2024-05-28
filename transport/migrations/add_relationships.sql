-- add_relationships.sql
BEGIN;

-- Add foreign key relationships

DELETE FROM vehicle_limit WHERE department_id NOT IN (SELECT id FROM departments);
DELETE FROM vehicle_limit WHERE user_profile_id NOT IN (SELECT id FROM user_profile);

ALTER TABLE vehicle_limit
    ADD CONSTRAINT fk_vehicle_limit_department
    FOREIGN KEY (department_id) REFERENCES departments(id),
    ADD CONSTRAINT fk_vehicle_limit_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id);

DELETE FROM abandon_trips WHERE reservation_id NOT IN (SELECT id FROM reservations);
DELETE FROM abandon_trips WHERE user_profile_id NOT IN (SELECT id FROM user_profile);

ALTER TABLE abandon_trips
    ADD CONSTRAINT fk_abandon_trips_reservation
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    ADD CONSTRAINT fk_abandon_trips_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id);

DELETE FROM comment_log WHERE user_profile_id NOT IN (SELECT id FROM user_profile);
ALTER TABLE comment_log
    ADD CONSTRAINT fk_comment_log_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id);

DELETE FROM log WHERE user_profile_id NOT IN (SELECT id FROM user_profile);
ALTER TABLE log
    ADD CONSTRAINT fk_log_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id);

-- Resolve TODO: Deletion process for reservations table
-- Step 1: Create a temporary table to store IDs of rows to be deleted
CREATE TEMPORARY TABLE temp_deleted_reservations AS
SELECT id FROM reservations
WHERE vehicle_id NOT IN (SELECT id FROM vehicles)
   OR user_profile_id NOT IN (SELECT id FROM user_profile)
   OR billing_department NOT IN (SELECT id FROM departments)
   OR assigned_driver NOT IN (SELECT id FROM user_profile)
   OR deleted_by_user NOT IN (SELECT id FROM user_profile);

-- Step 2: Delete related rows from linked tables
DELETE FROM abandon_trips WHERE reservation_id IN (SELECT id FROM temp_deleted_reservations);
DELETE FROM service_reservations_details WHERE reservation_id IN (SELECT id FROM temp_deleted_reservations);
DELETE FROM trip_details WHERE reservation_id IN (SELECT id FROM temp_deleted_reservations);
DELETE FROM user_comments WHERE trip_id IN (SELECT id FROM temp_deleted_reservations);

-- Step 3: Delete rows from the reservations table
DELETE FROM reservations WHERE id IN (SELECT id FROM temp_deleted_reservations);

-- Drop the temporary table
DROP TABLE temp_deleted_reservations;

ALTER TABLE reservations
    ADD CONSTRAINT fk_reservations_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    ADD CONSTRAINT fk_reservations_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_reservations_billing_department
    FOREIGN KEY (billing_department) REFERENCES departments(id),
    ADD CONSTRAINT fk_reservations_assigned_driver
    FOREIGN KEY (assigned_driver) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_reservations_deleted_by_user
    FOREIGN KEY (deleted_by_user) REFERENCES user_profile(id);

DELETE FROM restricted_charges WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
DELETE FROM restricted_charges WHERE department_id NOT IN (SELECT id FROM departments);
ALTER TABLE restricted_charges
    ADD CONSTRAINT fk_restricted_charges_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    ADD CONSTRAINT fk_restricted_charges_department
    FOREIGN KEY (department_id) REFERENCES departments(id);

DELETE FROM shop_tasks WHERE user_profile_id NOT IN (SELECT id FROM user_profile);
DELETE FROM shop_tasks WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
DELETE FROM shop_tasks WHERE work_type_id NOT IN (SELECT id FROM work_type);
ALTER TABLE shop_tasks
    ADD CONSTRAINT fk_shop_tasks_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_shop_tasks_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    ADD CONSTRAINT fk_shop_tasks_work_type
    FOREIGN KEY (work_type_id) REFERENCES work_type(id);

DELETE FROM special_notice WHERE user_profile_id NOT IN (SELECT id FROM user_profile);
ALTER TABLE special_notice
    ADD CONSTRAINT fk_special_notice_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id);

DELETE FROM service_reservations WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
ALTER TABLE service_reservations
    ADD CONSTRAINT fk_service_reservations_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id);

DELETE FROM service_reservations_details WHERE service_reservation_id NOT IN (SELECT id FROM service_reservations);
DELETE FROM service_reservations_details WHERE reservation_id NOT IN (SELECT id FROM reservations);
ALTER TABLE service_reservations_details
    ADD CONSTRAINT fk_service_reservations_details_service
    FOREIGN KEY (service_reservation_id) REFERENCES service_reservations(id),
    ADD CONSTRAINT fk_service_reservations_details_reservation
    FOREIGN KEY (reservation_id) REFERENCES reservations(id);

DELETE FROM trip_details WHERE reservation_id NOT IN (SELECT id FROM reservations);
DELETE FROM trip_details WHERE user_profile_id NOT IN (SELECT id FROM user_profile);
ALTER TABLE trip_details
    ADD CONSTRAINT fk_trip_details_reservation
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    ADD CONSTRAINT fk_trip_details_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id);

DELETE FROM user_comments WHERE posting_user_profile_id NOT IN (SELECT id FROM user_profile);
DELETE FROM user_comments WHERE about_user_profile_id NOT IN (SELECT id FROM user_profile);
DELETE FROM user_comments WHERE trip_id NOT IN (SELECT id FROM trip_details);
ALTER TABLE user_comments
    ADD CONSTRAINT fk_user_comments_posting_user_profile
    FOREIGN KEY (posting_user_profile_id) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_user_comments_about_user_profile
    FOREIGN KEY (about_user_profile_id) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_user_comments_trip
    FOREIGN KEY (trip_id) REFERENCES trip_details(id);

DELETE FROM vehicles WHERE user_profile_id NOT IN (SELECT id FROM user_profile);
DELETE FROM vehicles WHERE make_id NOT IN (SELECT id FROM vehicle_brand);
DELETE FROM vehicles WHERE model_id NOT IN (SELECT id FROM vehicle_type);
ALTER TABLE vehicles
    ADD CONSTRAINT fk_vehicles_user_profile
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_vehicles_make
    FOREIGN KEY (make_id) REFERENCES vehicle_brand(id),
    ADD CONSTRAINT fk_vehicles_model
    FOREIGN KEY (model_id) REFERENCES vehicle_type(id);

DELETE FROM vehicle_comments WHERE posting_user_profile_id NOT IN (SELECT id FROM user_profile);
DELETE FROM vehicle_comments WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
ALTER TABLE vehicle_comments
    ADD CONSTRAINT fk_vehicle_comments_posting_user_profile
    FOREIGN KEY (posting_user_profile_id) REFERENCES user_profile(id),
    ADD CONSTRAINT fk_vehicle_comments_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id);

END;
