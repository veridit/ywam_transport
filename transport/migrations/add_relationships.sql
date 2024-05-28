-- add_relationships.sql
BEGIN;

-- Add foreign key relationships

DELETE FROM vehicle_limit WHERE department_id NOT IN (SELECT id FROM departments);
DELETE FROM vehicle_limit WHERE driver_id NOT IN (SELECT id FROM driver);

ALTER TABLE vehicle_limit
    ADD CONSTRAINT fk_vehicle_limit_department
    FOREIGN KEY (department_id) REFERENCES departments(id),
    ADD CONSTRAINT fk_vehicle_limit_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id);

DELETE FROM abandon_trips WHERE reservation_id NOT IN (SELECT id FROM reservations);
DELETE FROM abandon_trips WHERE driver_id NOT IN (SELECT id FROM driver);

ALTER TABLE abandon_trips
    ADD CONSTRAINT fk_abandon_trips_reservation
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    ADD CONSTRAINT fk_abandon_trips_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id);

DELETE FROM comment_log WHERE driver_id NOT IN (SELECT id FROM driver);
ALTER TABLE comment_log
    ADD CONSTRAINT fk_comment_log_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id);

DELETE FROM log WHERE driver_id NOT IN (SELECT id FROM driver);
ALTER TABLE log
    ADD CONSTRAINT fk_log_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id);

-- Resolve TODO: Deletion process for reservations table
-- Step 1: Create a temporary table to store IDs of rows to be deleted
CREATE TEMPORARY TABLE temp_deleted_reservations AS
SELECT id FROM reservations
WHERE vehicle_id NOT IN (SELECT id FROM vehicles)
   OR driver_id NOT IN (SELECT id FROM driver)
   OR billing_department NOT IN (SELECT id FROM departments)
   OR assigned_driver NOT IN (SELECT id FROM driver)
   OR deleted_by_driver NOT IN (SELECT id FROM driver);

-- Step 2: Delete related rows from linked tables
DELETE FROM abandon_trips WHERE reservation_id IN (SELECT id FROM temp_deleted_reservations);
DELETE FROM service_reservations_details WHERE reservation_id IN (SELECT id FROM temp_deleted_reservations);
DELETE FROM trip_details WHERE reservation_id IN (SELECT id FROM temp_deleted_reservations);
DELETE FROM driver_comments WHERE trip_id IN (SELECT id FROM temp_deleted_reservations);

-- Step 3: Delete rows from the reservations table
DELETE FROM reservations WHERE id IN (SELECT id FROM temp_deleted_reservations);

-- Drop the temporary table
DROP TABLE temp_deleted_reservations;

ALTER TABLE reservations
    ADD CONSTRAINT fk_reservations_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    ADD CONSTRAINT fk_reservations_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id),
    ADD CONSTRAINT fk_reservations_billing_department
    FOREIGN KEY (billing_department) REFERENCES departments(id),
    ADD CONSTRAINT fk_reservations_assigned_driver
    FOREIGN KEY (assigned_driver) REFERENCES driver(id),
    ADD CONSTRAINT fk_reservations_deleted_by_driver
    FOREIGN KEY (deleted_by_driver) REFERENCES driver(id);

DELETE FROM restricted_charges WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
DELETE FROM restricted_charges WHERE department_id NOT IN (SELECT id FROM departments);
ALTER TABLE restricted_charges
    ADD CONSTRAINT fk_restricted_charges_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    ADD CONSTRAINT fk_restricted_charges_department
    FOREIGN KEY (department_id) REFERENCES departments(id);

DELETE FROM shop_tasks WHERE driver_id NOT IN (SELECT id FROM driver);
DELETE FROM shop_tasks WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
DELETE FROM shop_tasks WHERE work_type_id NOT IN (SELECT id FROM work_type);
ALTER TABLE shop_tasks
    ADD CONSTRAINT fk_shop_tasks_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id),
    ADD CONSTRAINT fk_shop_tasks_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    ADD CONSTRAINT fk_shop_tasks_work_type
    FOREIGN KEY (work_type_id) REFERENCES work_type(id);

DELETE FROM special_notice WHERE driver_id NOT IN (SELECT id FROM driver);
ALTER TABLE special_notice
    ADD CONSTRAINT fk_special_notice_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id);

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
DELETE FROM trip_details WHERE driver_id NOT IN (SELECT id FROM driver);
ALTER TABLE trip_details
    ADD CONSTRAINT fk_trip_details_reservation
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    ADD CONSTRAINT fk_trip_details_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id);

DELETE FROM driver_comments WHERE posting_driver_id NOT IN (SELECT id FROM driver);
DELETE FROM driver_comments WHERE about_driver_id NOT IN (SELECT id FROM driver);
DELETE FROM driver_comments WHERE trip_id NOT IN (SELECT id FROM trip_details);
ALTER TABLE driver_comments
    ADD CONSTRAINT fk_driver_comments_posting_driver
    FOREIGN KEY (posting_driver_id) REFERENCES driver(id),
    ADD CONSTRAINT fk_driver_comments_about_driver
    FOREIGN KEY (about_driver_id) REFERENCES driver(id),
    ADD CONSTRAINT fk_driver_comments_trip
    FOREIGN KEY (trip_id) REFERENCES trip_details(id);

DELETE FROM vehicles WHERE driver_id NOT IN (SELECT id FROM driver);
DELETE FROM vehicles WHERE make_id NOT IN (SELECT id FROM vehicle_brand);
DELETE FROM vehicles WHERE model_id NOT IN (SELECT id FROM vehicle_type);
ALTER TABLE vehicles
    ADD CONSTRAINT fk_vehicles_driver
    FOREIGN KEY (driver_id) REFERENCES driver(id),
    ADD CONSTRAINT fk_vehicles_make
    FOREIGN KEY (make_id) REFERENCES vehicle_brand(id),
    ADD CONSTRAINT fk_vehicles_model
    FOREIGN KEY (model_id) REFERENCES vehicle_type(id);

DELETE FROM vehicle_comments WHERE posting_driver_id NOT IN (SELECT id FROM driver);
DELETE FROM vehicle_comments WHERE vehicle_id NOT IN (SELECT id FROM vehicles);
ALTER TABLE vehicle_comments
    ADD CONSTRAINT fk_vehicle_comments_posting_driver
    FOREIGN KEY (posting_driver_id) REFERENCES driver(id),
    ADD CONSTRAINT fk_vehicle_comments_vehicle
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id);

END;
