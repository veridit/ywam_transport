# Table auth_group
```
                                    Table "public.auth_group"
 Column |          Type          | Collation | Nullable |                Default                 
--------+------------------------+-----------+----------+----------------------------------------
 id     | integer                |           | not null | nextval('auth_group_id_seq'::regclass)
 name   | character varying(150) |           | not null | 
Indexes:
    "auth_group_pkey" PRIMARY KEY, btree (id)
    "auth_group_name_a6ea08ec_like" btree (name varchar_pattern_ops)
    "auth_group_name_key" UNIQUE CONSTRAINT, btree (name)
Referenced by:
    TABLE "auth_group_permissions" CONSTRAINT "auth_group_permissions_group_id_b120cbf9_fk_auth_group_id" FOREIGN KEY (group_id) REFERENCES auth_group(id) DEFERRABLE INITIALLY DEFERRED
    TABLE "auth_user_groups" CONSTRAINT "auth_user_groups_group_id_97559544_fk_auth_group_id" FOREIGN KEY (group_id) REFERENCES auth_group(id) DEFERRABLE INITIALLY DEFERRED

```
# Table auth_group_permissions
```
                                Table "public.auth_group_permissions"
    Column     |  Type   | Collation | Nullable |                      Default                       
---------------+---------+-----------+----------+----------------------------------------------------
 id            | bigint  |           | not null | nextval('auth_group_permissions_id_seq'::regclass)
 group_id      | integer |           | not null | 
 permission_id | integer |           | not null | 
Indexes:
    "auth_group_permissions_pkey" PRIMARY KEY, btree (id)
    "auth_group_permissions_group_id_b120cbf9" btree (group_id)
    "auth_group_permissions_group_id_permission_id_0cd325b0_uniq" UNIQUE CONSTRAINT, btree (group_id, permission_id)
    "auth_group_permissions_permission_id_84c5c92e" btree (permission_id)
Foreign-key constraints:
    "auth_group_permissio_permission_id_84c5c92e_fk_auth_perm" FOREIGN KEY (permission_id) REFERENCES auth_permission(id) DEFERRABLE INITIALLY DEFERRED
    "auth_group_permissions_group_id_b120cbf9_fk_auth_group_id" FOREIGN KEY (group_id) REFERENCES auth_group(id) DEFERRABLE INITIALLY DEFERRED

```
# Table auth_permission
```
                                        Table "public.auth_permission"
     Column      |          Type          | Collation | Nullable |                   Default                   
-----------------+------------------------+-----------+----------+---------------------------------------------
 id              | integer                |           | not null | nextval('auth_permission_id_seq'::regclass)
 name            | character varying(255) |           | not null | 
 content_type_id | integer                |           | not null | 
 codename        | character varying(100) |           | not null | 
Indexes:
    "auth_permission_pkey" PRIMARY KEY, btree (id)
    "auth_permission_content_type_id_2f476e4b" btree (content_type_id)
    "auth_permission_content_type_id_codename_01ab375a_uniq" UNIQUE CONSTRAINT, btree (content_type_id, codename)
Foreign-key constraints:
    "auth_permission_content_type_id_2f476e4b_fk_django_co" FOREIGN KEY (content_type_id) REFERENCES django_content_type(id) DEFERRABLE INITIALLY DEFERRED
Referenced by:
    TABLE "auth_group_permissions" CONSTRAINT "auth_group_permissio_permission_id_84c5c92e_fk_auth_perm" FOREIGN KEY (permission_id) REFERENCES auth_permission(id) DEFERRABLE INITIALLY DEFERRED
    TABLE "auth_user_user_permissions" CONSTRAINT "auth_user_user_permi_permission_id_1fbb5f2c_fk_auth_perm" FOREIGN KEY (permission_id) REFERENCES auth_permission(id) DEFERRABLE INITIALLY DEFERRED

```
# Table auth_user
```
                                        Table "public.auth_user"
    Column    |           Type           | Collation | Nullable |                Default                
--------------+--------------------------+-----------+----------+---------------------------------------
 id           | integer                  |           | not null | nextval('auth_user_id_seq'::regclass)
 password     | character varying(128)   |           | not null | 
 last_login   | timestamp with time zone |           |          | 
 is_superuser | boolean                  |           | not null | 
 username     | character varying(150)   |           | not null | 
 first_name   | character varying(150)   |           | not null | 
 last_name    | character varying(150)   |           | not null | 
 email        | character varying(254)   |           | not null | 
 is_staff     | boolean                  |           | not null | 
 is_active    | boolean                  |           | not null | 
 date_joined  | timestamp with time zone |           | not null | 
Indexes:
    "auth_user_pkey" PRIMARY KEY, btree (id)
    "auth_user_username_6821ab7c_like" btree (username varchar_pattern_ops)
    "auth_user_username_key" UNIQUE CONSTRAINT, btree (username)
Referenced by:
    TABLE "auth_user_groups" CONSTRAINT "auth_user_groups_user_id_6a12ed8b_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) DEFERRABLE INITIALLY DEFERRED
    TABLE "auth_user_user_permissions" CONSTRAINT "auth_user_user_permissions_user_id_a95ead1b_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) DEFERRABLE INITIALLY DEFERRED
    TABLE "django_admin_log" CONSTRAINT "django_admin_log_user_id_c564eba6_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) DEFERRABLE INITIALLY DEFERRED
    TABLE "transport_abandon_trips" CONSTRAINT "transport_abandon_trips_user_id_88d1e9ec_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE SET NULL DEFERRABLE
    TABLE "transport_comment_log" CONSTRAINT "transport_comment_log_user_id_7449557a_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_driver" CONSTRAINT "transport_driver_user_id_29ac0108_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_log" CONSTRAINT "transport_log_user_id_368ca736_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_reservations" CONSTRAINT "transport_reservations_user_id_3a33be73_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_shop_tasks" CONSTRAINT "transport_shop_tasks_user_id_45df4e1d_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_special_notice" CONSTRAINT "transport_special_notice_user_id_77c44512_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_trip_details" CONSTRAINT "transport_trip_details_user_id_51026fff_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE SET NULL DEFERRABLE
    TABLE "transport_vehicle_limit" CONSTRAINT "transport_vehicle_limit_user_id_2f698599_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_vehicles" CONSTRAINT "transport_vehicles_user_id_084f10a6_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE

```
# Table auth_user_groups
```
                             Table "public.auth_user_groups"
  Column  |  Type   | Collation | Nullable |                   Default                    
----------+---------+-----------+----------+----------------------------------------------
 id       | bigint  |           | not null | nextval('auth_user_groups_id_seq'::regclass)
 user_id  | integer |           | not null | 
 group_id | integer |           | not null | 
Indexes:
    "auth_user_groups_pkey" PRIMARY KEY, btree (id)
    "auth_user_groups_group_id_97559544" btree (group_id)
    "auth_user_groups_user_id_6a12ed8b" btree (user_id)
    "auth_user_groups_user_id_group_id_94350c0c_uniq" UNIQUE CONSTRAINT, btree (user_id, group_id)
Foreign-key constraints:
    "auth_user_groups_group_id_97559544_fk_auth_group_id" FOREIGN KEY (group_id) REFERENCES auth_group(id) DEFERRABLE INITIALLY DEFERRED
    "auth_user_groups_user_id_6a12ed8b_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) DEFERRABLE INITIALLY DEFERRED

```
# Table auth_user_user_permissions
```
                                Table "public.auth_user_user_permissions"
    Column     |  Type   | Collation | Nullable |                        Default                         
---------------+---------+-----------+----------+--------------------------------------------------------
 id            | bigint  |           | not null | nextval('auth_user_user_permissions_id_seq'::regclass)
 user_id       | integer |           | not null | 
 permission_id | integer |           | not null | 
Indexes:
    "auth_user_user_permissions_pkey" PRIMARY KEY, btree (id)
    "auth_user_user_permissions_permission_id_1fbb5f2c" btree (permission_id)
    "auth_user_user_permissions_user_id_a95ead1b" btree (user_id)
    "auth_user_user_permissions_user_id_permission_id_14a6b632_uniq" UNIQUE CONSTRAINT, btree (user_id, permission_id)
Foreign-key constraints:
    "auth_user_user_permi_permission_id_1fbb5f2c_fk_auth_perm" FOREIGN KEY (permission_id) REFERENCES auth_permission(id) DEFERRABLE INITIALLY DEFERRED
    "auth_user_user_permissions_user_id_a95ead1b_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) DEFERRABLE INITIALLY DEFERRED

```
# Table django_admin_log
```
                                         Table "public.django_admin_log"
     Column      |           Type           | Collation | Nullable |                   Default                    
-----------------+--------------------------+-----------+----------+----------------------------------------------
 id              | integer                  |           | not null | nextval('django_admin_log_id_seq'::regclass)
 action_time     | timestamp with time zone |           | not null | 
 object_id       | text                     |           |          | 
 object_repr     | character varying(200)   |           | not null | 
 action_flag     | smallint                 |           | not null | 
 change_message  | text                     |           | not null | 
 content_type_id | integer                  |           |          | 
 user_id         | integer                  |           | not null | 
Indexes:
    "django_admin_log_pkey" PRIMARY KEY, btree (id)
    "django_admin_log_content_type_id_c4bce8eb" btree (content_type_id)
    "django_admin_log_user_id_c564eba6" btree (user_id)
Check constraints:
    "django_admin_log_action_flag_check" CHECK (action_flag >= 0)
Foreign-key constraints:
    "django_admin_log_content_type_id_c4bce8eb_fk_django_co" FOREIGN KEY (content_type_id) REFERENCES django_content_type(id) DEFERRABLE INITIALLY DEFERRED
    "django_admin_log_user_id_c564eba6_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) DEFERRABLE INITIALLY DEFERRED

```
# Table django_content_type
```
                                     Table "public.django_content_type"
  Column   |          Type          | Collation | Nullable |                     Default                     
-----------+------------------------+-----------+----------+-------------------------------------------------
 id        | integer                |           | not null | nextval('django_content_type_id_seq'::regclass)
 app_label | character varying(100) |           | not null | 
 model     | character varying(100) |           | not null | 
Indexes:
    "django_content_type_pkey" PRIMARY KEY, btree (id)
    "django_content_type_app_label_model_76bd3d3b_uniq" UNIQUE CONSTRAINT, btree (app_label, model)
Referenced by:
    TABLE "auth_permission" CONSTRAINT "auth_permission_content_type_id_2f476e4b_fk_django_co" FOREIGN KEY (content_type_id) REFERENCES django_content_type(id) DEFERRABLE INITIALLY DEFERRED
    TABLE "django_admin_log" CONSTRAINT "django_admin_log_content_type_id_c4bce8eb_fk_django_co" FOREIGN KEY (content_type_id) REFERENCES django_content_type(id) DEFERRABLE INITIALLY DEFERRED

```
# Table django_migrations
```
                                     Table "public.django_migrations"
 Column  |           Type           | Collation | Nullable |                    Default                    
---------+--------------------------+-----------+----------+-----------------------------------------------
 id      | bigint                   |           | not null | nextval('django_migrations_id_seq'::regclass)
 app     | character varying(255)   |           | not null | 
 name    | character varying(255)   |           | not null | 
 applied | timestamp with time zone |           | not null | 
Indexes:
    "django_migrations_pkey" PRIMARY KEY, btree (id)

```
# Table django_session
```
                      Table "public.django_session"
    Column    |           Type           | Collation | Nullable | Default 
--------------+--------------------------+-----------+----------+---------
 session_key  | character varying(40)    |           | not null | 
 session_data | text                     |           | not null | 
 expire_date  | timestamp with time zone |           | not null | 
Indexes:
    "django_session_pkey" PRIMARY KEY, btree (session_key)
    "django_session_expire_date_a5c62663" btree (expire_date)
    "django_session_session_key_c0390e0f_like" btree (session_key varchar_pattern_ops)

```
# Table transport_abandon_trips
```
                                         Table "public.transport_abandon_trips"
     Column     |           Type           | Collation | Nullable |                       Default                       
----------------+--------------------------+-----------+----------+-----------------------------------------------------
 id             | bigint                   |           | not null | nextval('transport_abandon_trips_id_seq'::regclass)
 abandon_date   | timestamp with time zone |           |          | 
 notes          | text                     |           | not null | 
 reservation_id | bigint                   |           | not null | 
 user_id        | bigint                   |           |          | 
 mile_charges   | double precision         |           | not null | 
 calculate_fine | boolean                  |           | not null | false
 miles          | smallint                 |           | not null | '25'::smallint
Indexes:
    "idx_16393_primary" PRIMARY KEY, btree (id)
    "transport_abandon_trips_reservation_id_1cf30d87" btree (reservation_id)
    "transport_abandon_trips_user_id_88d1e9ec" btree (user_id)
Foreign-key constraints:
    "transport_abandon_tr_reservation_id_1cf30d87_fk_transport" FOREIGN KEY (reservation_id) REFERENCES transport_reservations(id) ON DELETE CASCADE DEFERRABLE
    "transport_abandon_trips_user_id_88d1e9ec_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE SET NULL DEFERRABLE
Triggers:
    on_update_current_timestamp BEFORE UPDATE ON transport_abandon_trips FOR EACH ROW EXECUTE FUNCTION on_update_current_timestamp_tbl_abandon_trips()

```
# Table transport_comment_log
```
                                        Table "public.transport_comment_log"
    Column    |           Type           | Collation | Nullable |                      Default                      
--------------+--------------------------+-----------+----------+---------------------------------------------------
 id           | bigint                   |           | not null | nextval('transport_comment_log_id_seq'::regclass)
 user_id      | bigint                   |           | not null | 
 comments     | text                     |           | not null | 
 comment_time | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
Indexes:
    "idx_16404_primary" PRIMARY KEY, btree (id)
    "transport_comment_log_user_id_7449557a" btree (user_id)
Foreign-key constraints:
    "transport_comment_log_user_id_7449557a_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_departments
```
                          Table "public.transport_departments"
      Column       |           Type           | Collation | Nullable |      Default      
-------------------+--------------------------+-----------+----------+-------------------
 id                | character varying(4)     |           | not null | 
 name              | character varying(50)    |           | not null | 
 leader_first_name | character varying(15)    |           | not null | 
 leader_last_name  | character varying(15)    |           | not null | 
 leader_phone      | character varying(25)    |           | not null | 
 leader_email      | character varying(150)   |           | not null | 
 reg_date          | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
 active            | boolean                  |           | not null | true
 deactive_date     | date                     |           |          | 
 info              | character varying(200)   |           | not null | 
Indexes:
    "idx_16412_primary" PRIMARY KEY, btree (id)
    "transport_departments_id_e6a036f1_like" btree (id varchar_pattern_ops)
Referenced by:
    TABLE "transport_driver" CONSTRAINT "transport_driver_department_id_2103ad31_fk_transport" FOREIGN KEY (department_id) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_reservations" CONSTRAINT "transport_reservatio_billing_department_649aba28_fk_transport" FOREIGN KEY (billing_department) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_restricted_charges" CONSTRAINT "transport_restricted_department_id_9c95448e_fk_transport" FOREIGN KEY (department_id) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_vehicle_limit" CONSTRAINT "transport_vehicle_li_department_id_66309f9d_fk_transport" FOREIGN KEY (department_id) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE

```
# Table transport_driver
```
                                          Table "public.transport_driver"
      Column       |           Type           | Collation | Nullable |                   Default                    
-------------------+--------------------------+-----------+----------+----------------------------------------------
 id                | integer                  |           | not null | nextval('transport_driver_id_seq'::regclass)
 user_id           | integer                  |           | not null | 
 department_id     | character varying(4)     |           | not null | 
 phone             | character varying(25)    |           | not null | 
 birth_date        | date                     |           |          | 
 license_no        | character varying(20)    |           | not null | 
 license_state     | character varying(15)    |           | not null | 
 license_country   | character varying(60)    |           | not null | 
 license_expire    | date                     |           |          | 
 drive_tested      | character varying(30)    |           | not null | 
 test_date         | date                     |           |          | 
 end_permit        | date                     |           |          | 
 home_country      | character varying(30)    |           | not null | 
 status_date       | timestamp with time zone |           |          | 
 user_type         | character varying(20)    |           | not null | 
 photo             | character varying(255)   |           | not null | 
 photo_link        | character varying(255)   |           | not null | 
 comment           | character varying(300)   |           | not null | 
 permit_type       | character varying(20)    |           | not null | 'First'::character varying
 renew_date        | date                     |           |          | 
 renew_text        | character varying(200)   |           |          | 
 new_user          | boolean                  |           | not null | false
 driver_permission | boolean                  |           | not null | false
 max_passengers    | bigint                   |           | not null | 15
Indexes:
    "transport_driver_pkey" PRIMARY KEY, btree (id)
    "transport_driver_department_id_2103ad31" btree (department_id)
    "transport_driver_department_id_2103ad31_like" btree (department_id varchar_pattern_ops)
    "transport_driver_user_id_key" UNIQUE CONSTRAINT, btree (user_id)
Foreign-key constraints:
    "transport_driver_department_id_2103ad31_fk_transport" FOREIGN KEY (department_id) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    "transport_driver_user_id_29ac0108_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE
Referenced by:
    TABLE "transport_driver_comments" CONSTRAINT "transport_driver_com_about_user_id_70fd68bb_fk_transport" FOREIGN KEY (about_user_id) REFERENCES transport_driver(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_driver_comments" CONSTRAINT "transport_driver_com_posting_user_id_7ebc044b_fk_transport" FOREIGN KEY (posting_user_id) REFERENCES transport_driver(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_reservations" CONSTRAINT "transport_reservatio_assigned_driver_7b0956f3_fk_transport" FOREIGN KEY (assigned_driver) REFERENCES transport_driver(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_reservations" CONSTRAINT "transport_reservatio_deleted_by_driver_c7405959_fk_transport" FOREIGN KEY (deleted_by_driver) REFERENCES transport_driver(id) ON DELETE SET NULL DEFERRABLE
    TABLE "transport_vehicle_comments" CONSTRAINT "transport_vehicle_co_posting_user_id_9c17d3f4_fk_transport" FOREIGN KEY (posting_user_id) REFERENCES transport_driver(id) ON DELETE RESTRICT DEFERRABLE

```
# Table transport_driver_comments
```
                                         Table "public.transport_driver_comments"
     Column      |           Type           | Collation | Nullable |                        Default                        
-----------------+--------------------------+-----------+----------+-------------------------------------------------------
 id              | bigint                   |           | not null | nextval('transport_driver_comments_id_seq'::regclass)
 posting_user_id | bigint                   |           | not null | 
 about_user_id   | bigint                   |           | not null | 
 comments_date   | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
 comments        | text                     |           | not null | 
 trip_id         | bigint                   |           |          | 
Indexes:
    "idx_16524_primary" PRIMARY KEY, btree (id)
    "transport_driver_comments_about_user_id_70fd68bb" btree (about_user_id)
    "transport_driver_comments_posting_user_id_7ebc044b" btree (posting_user_id)
    "transport_driver_comments_trip_id_7325ffae" btree (trip_id)
Foreign-key constraints:
    "transport_driver_com_about_user_id_70fd68bb_fk_transport" FOREIGN KEY (about_user_id) REFERENCES transport_driver(id) ON DELETE CASCADE DEFERRABLE
    "transport_driver_com_posting_user_id_7ebc044b_fk_transport" FOREIGN KEY (posting_user_id) REFERENCES transport_driver(id) ON DELETE CASCADE DEFERRABLE
    "transport_driver_com_trip_id_7325ffae_fk_transport" FOREIGN KEY (trip_id) REFERENCES transport_trip_details(id) ON DELETE SET NULL DEFERRABLE

```
# Table transport_email_template
```
                                       Table "public.transport_email_template"
   Column   |           Type           | Collation | Nullable |                       Default                        
------------+--------------------------+-----------+----------+------------------------------------------------------
 id         | integer                  |           | not null | nextval('transport_email_template_id_seq'::regclass)
 event      | character varying(50)    |           | not null | 
 subject    | character varying(255)   |           | not null | 
 body       | text                     |           | not null | 
 variables  | jsonb                    |           | not null | 
 created_at | timestamp with time zone |           | not null | 
 updated_at | timestamp with time zone |           | not null | 
Indexes:
    "transport_email_template_pkey" PRIMARY KEY, btree (id)
    "transport_email_template_event_b47ea311_like" btree (event varchar_pattern_ops)
    "transport_email_template_event_key" UNIQUE CONSTRAINT, btree (event)

```
# Table transport_global_settings
```
               Table "public.transport_global_settings"
   Column    |         Type          | Collation | Nullable | Default 
-------------+-----------------------+-----------+----------+---------
 id          | bigint                |           | not null | 
 leader_code | character varying(20) |           | not null | 
Indexes:
    "idx_16420_primary" PRIMARY KEY, btree (id)

```
# Table transport_info_links
```
                                        Table "public.transport_info_links"
    Column    |           Type           | Collation | Nullable |                     Default                      
--------------+--------------------------+-----------+----------+--------------------------------------------------
 id           | integer                  |           | not null | nextval('transport_info_links_id_seq'::regclass)
 title        | character varying(100)   |           | not null | 
 text         | text                     |           | not null | 
 link_date    | timestamp with time zone |           |          | 
 position     | smallint                 |           | not null | '1'::smallint
 display_page | character varying(25)    |           | not null | 
 display_flag | character(1)             |           |          | NULL::bpchar
Indexes:
    "idx_16425_primary" PRIMARY KEY, btree (id)
Referenced by:
    TABLE "transport_info_links_position" CONSTRAINT "transport_info_links_link_id_ec5b1832_fk_transport" FOREIGN KEY (link_id) REFERENCES transport_info_links(id) ON DELETE CASCADE DEFERRABLE
Triggers:
    on_update_current_timestamp BEFORE UPDATE ON transport_info_links FOR EACH ROW EXECUTE FUNCTION on_update_current_timestamp_tbl_info_links()

```
# Table transport_info_links_position
```
                               Table "public.transport_info_links_position"
    Column    |  Type   | Collation | Nullable |                          Default                          
--------------+---------+-----------+----------+-----------------------------------------------------------
 link_id      | integer |           | not null | 
 position     | integer |           | not null | 
 driver_login | boolean |           | not null | 
 id           | integer |           | not null | nextval('transport_info_links_position_id_seq'::regclass)
Indexes:
    "transport_info_links_position_pkey" PRIMARY KEY, btree (id)
    "transport_info_links_position_link_id_ec5b1832" btree (link_id)
Foreign-key constraints:
    "transport_info_links_link_id_ec5b1832_fk_transport" FOREIGN KEY (link_id) REFERENCES transport_info_links(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_log
```
                                         Table "public.transport_log"
     Column      |           Type           | Collation | Nullable |                  Default                  
-----------------+--------------------------+-----------+----------+-------------------------------------------
 id              | bigint                   |           | not null | nextval('transport_log_id_seq'::regclass)
 user_id         | bigint                   |           | not null | 
 login_datetime  | timestamp with time zone |           |          | 
 logout_datetime | timestamp with time zone |           |          | 
 ip_address      | character varying(16)    |           | not null | 
Indexes:
    "idx_16436_primary" PRIMARY KEY, btree (id)
    "transport_log_user_id_368ca736" btree (user_id)
Foreign-key constraints:
    "transport_log_user_id_368ca736_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_reservations
```
                                               Table "public.transport_reservations"
           Column           |           Type           | Collation | Nullable |                      Default                       
----------------------------+--------------------------+-----------+----------+----------------------------------------------------
 id                         | bigint                   |           | not null | nextval('transport_reservations_id_seq'::regclass)
 vehicle_id                 | bigint                   |           | not null | 
 user_id                    | bigint                   |           | not null | 
 planned_passenger_count    | character varying(2)     |           | not null | 
 coordinator_approval       | character varying(15)    |           | not null | 'Approved'::character varying
 planned_departure_datetime | timestamp with time zone |           |          | 
 planned_return_datetime    | timestamp with time zone |           |          | 
 overnight                  | boolean                  |           | not null | 
 child_seat                 | boolean                  |           | not null | 
 destination                | character varying(100)   |           | not null | 
 reservation_cancelled      | boolean                  |           | not null | false
 reg_date                   | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
 cancelled_by_driver        | boolean                  |           | not null | false
 driver_cancelled_time      | timestamp with time zone |           |          | 
 key_no                     | character varying(4)     |           |          | NULL::character varying
 card_no                    | character varying(8)     |           |          | NULL::character varying
 billing_department         | character varying(4)     |           | not null | 
 assigned_driver            | bigint                   |           | not null | 
 repeating                  | boolean                  |           | not null | false
 deleted_by_driver          | bigint                   |           |          | 
 deleted_datetime           | timestamp with time zone |           |          | 
 no_cost                    | boolean                  |           | not null | false
Indexes:
    "idx_16442_primary" PRIMARY KEY, btree (id)
    "transport_reservations_assigned_driver_7b0956f3" btree (assigned_driver)
    "transport_reservations_billing_department_649aba28" btree (billing_department)
    "transport_reservations_billing_department_649aba28_like" btree (billing_department varchar_pattern_ops)
    "transport_reservations_deleted_by_driver_c7405959" btree (deleted_by_driver)
    "transport_reservations_user_id_3a33be73" btree (user_id)
    "transport_reservations_vehicle_id_38940c0c" btree (vehicle_id)
Foreign-key constraints:
    "transport_reservatio_assigned_driver_7b0956f3_fk_transport" FOREIGN KEY (assigned_driver) REFERENCES transport_driver(id) ON DELETE RESTRICT DEFERRABLE
    "transport_reservatio_billing_department_649aba28_fk_transport" FOREIGN KEY (billing_department) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    "transport_reservatio_deleted_by_driver_c7405959_fk_transport" FOREIGN KEY (deleted_by_driver) REFERENCES transport_driver(id) ON DELETE SET NULL DEFERRABLE
    "transport_reservatio_vehicle_id_38940c0c_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
    "transport_reservations_user_id_3a33be73_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE
Referenced by:
    TABLE "transport_abandon_trips" CONSTRAINT "transport_abandon_tr_reservation_id_1cf30d87_fk_transport" FOREIGN KEY (reservation_id) REFERENCES transport_reservations(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_service_reservations_details" CONSTRAINT "transport_service_re_reservation_id_15059748_fk_transport" FOREIGN KEY (reservation_id) REFERENCES transport_reservations(id) ON DELETE CASCADE DEFERRABLE
    TABLE "transport_trip_details" CONSTRAINT "transport_trip_detai_reservation_id_8ee1caf4_fk_transport" FOREIGN KEY (reservation_id) REFERENCES transport_reservations(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_restricted_charges
```
                                           Table "public.transport_restricted_charges"
       Column       |           Type           | Collation | Nullable |                         Default                          
--------------------+--------------------------+-----------+----------+----------------------------------------------------------
 id                 | bigint                   |           | not null | nextval('transport_restricted_charges_id_seq'::regclass)
 vehicle_id         | bigint                   |           | not null | 
 charge_month       | character varying(2)     |           | not null | 
 charge_year        | character(4)             |           | not null | 
 department_id      | character varying(4)     |           | not null | 
 calculation_method | character varying(50)    |           | not null | 
 total_charge       | double precision         |           | not null | 
 begin_mileage      | character varying(7)     |           |          | NULL::character varying
 end_mileage        | character varying(7)     |           |          | NULL::character varying
 rate               | double precision         |           |          | 
 reg_date           | timestamp with time zone |           |          | 
Indexes:
    "idx_16456_primary" PRIMARY KEY, btree (id)
    "transport_restricted_charges_department_id_9c95448e" btree (department_id)
    "transport_restricted_charges_department_id_9c95448e_like" btree (department_id varchar_pattern_ops)
    "transport_restricted_charges_vehicle_id_608acb24" btree (vehicle_id)
Foreign-key constraints:
    "transport_restricted_department_id_9c95448e_fk_transport" FOREIGN KEY (department_id) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    "transport_restricted_vehicle_id_608acb24_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
Triggers:
    on_update_current_timestamp BEFORE UPDATE ON transport_restricted_charges FOR EACH ROW EXECUTE FUNCTION on_update_current_timestamp_tbl_restricted_charges()

```
# Table transport_service_reservations
```
                                        Table "public.transport_service_reservations"
    Column     |           Type           | Collation | Nullable |                          Default                           
---------------+--------------------------+-----------+----------+------------------------------------------------------------
 id            | bigint                   |           | not null | nextval('transport_service_reservations_id_seq'::regclass)
 reg_date      | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
 vehicle_id    | bigint                   |           | not null | 
 from_datetime | timestamp with time zone |           |          | 
 to_datetime   | timestamp with time zone |           |          | 
 is_cancelled  | boolean                  |           | not null | false
 service_type  | character varying(15)    |           | not null | 
Indexes:
    "idx_16483_primary" PRIMARY KEY, btree (id)
    "transport_service_reservations_vehicle_id_4d4d35f7" btree (vehicle_id)
Foreign-key constraints:
    "transport_service_re_vehicle_id_4d4d35f7_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
Referenced by:
    TABLE "transport_service_reservations_details" CONSTRAINT "transport_service_re_service_reservation__20e82c51_fk_transport" FOREIGN KEY (service_reservation_id) REFERENCES transport_service_reservations(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_service_reservations_details
```
                 Table "public.transport_service_reservations_details"
         Column         |  Type   | Collation | Nullable |           Default            
------------------------+---------+-----------+----------+------------------------------
 service_reservation_id | bigint  |           | not null | 
 reservation_id         | bigint  |           | not null | 
 id                     | integer |           | not null | generated always as identity
Indexes:
    "transport_service_reservations_details_pkey" PRIMARY KEY, btree (id)
    "transport_service_reservat_service_reservation_id_20e82c51" btree (service_reservation_id)
    "transport_service_reservations_details_reservation_id_15059748" btree (reservation_id)
Foreign-key constraints:
    "transport_service_re_reservation_id_15059748_fk_transport" FOREIGN KEY (reservation_id) REFERENCES transport_reservations(id) ON DELETE CASCADE DEFERRABLE
    "transport_service_re_service_reservation__20e82c51_fk_transport" FOREIGN KEY (service_reservation_id) REFERENCES transport_service_reservations(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_shop_tasks
```
                                           Table "public.transport_shop_tasks"
       Column        |           Type           | Collation | Nullable |                     Default                      
---------------------+--------------------------+-----------+----------+--------------------------------------------------
 id                  | bigint                   |           | not null | nextval('transport_shop_tasks_id_seq'::regclass)
 user_id             | bigint                   |           | not null | 
 vehicle_id          | bigint                   |           | not null | 
 mileage_reading     | character varying(7)     |           | not null | 
 last_mileage        | character varying(7)     |           | not null | 
 work_type_id        | smallint                 |           | not null | 
 work_start_date     | date                     |           |          | 
 next_oil_change     | date                     |           |          | 
 total_cost          | double precision         |           | not null | 
 parts_source        | character varying(255)   |           | not null | 
 drive_test_done     | boolean                  |           | not null | 
 task_complete       | boolean                  |           | not null | 
 technician_comments | text                     |           | not null | 
 reg_date            | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
 invoice_no          | character varying(50)    |           | not null | 
 vendor_name         | character varying(50)    |           | not null | 
Indexes:
    "idx_16464_primary" PRIMARY KEY, btree (id)
    "transport_shop_tasks_user_id_45df4e1d" btree (user_id)
    "transport_shop_tasks_vehicle_id_a0e004fa" btree (vehicle_id)
    "transport_shop_tasks_work_type_id_a5127d1e" btree (work_type_id)
Foreign-key constraints:
    "transport_shop_tasks_user_id_45df4e1d_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE
    "transport_shop_tasks_vehicle_id_a0e004fa_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
    "transport_shop_tasks_work_type_id_a5127d1e_fk_transport" FOREIGN KEY (work_type_id) REFERENCES transport_work_type(id) ON DELETE RESTRICT DEFERRABLE

```
# Table transport_special_notice
```
                                       Table "public.transport_special_notice"
   Column    |           Type           | Collation | Nullable |                       Default                        
-------------+--------------------------+-----------+----------+------------------------------------------------------
 id          | bigint                   |           | not null | nextval('transport_special_notice_id_seq'::regclass)
 notice_date | timestamp with time zone |           |          | 
 user_id     | bigint                   |           | not null | 
 title       | character varying(255)   |           | not null | 
 notice      | text                     |           | not null | 
Indexes:
    "idx_16474_primary" PRIMARY KEY, btree (id)
    "transport_special_notice_user_id_77c44512" btree (user_id)
Foreign-key constraints:
    "transport_special_notice_user_id_77c44512_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_temp_mass_emails
```
                         Table "public.transport_temp_mass_emails"
   Column    |          Type          | Collation | Nullable |           Default            
-------------+------------------------+-----------+----------+------------------------------
 email       | character varying(250) |           | not null | 
 driver_name | character varying(50)  |           | not null | 
 id          | integer                |           | not null | generated always as identity
Indexes:
    "transport_temp_mass_emails_pkey" PRIMARY KEY, btree (id)

```
# Table transport_trip_details
```
                                           Table "public.transport_trip_details"
       Column        |           Type           | Collation | Nullable |                      Default                       
---------------------+--------------------------+-----------+----------+----------------------------------------------------
 id                  | bigint                   |           | not null | nextval('transport_trip_details_id_seq'::regclass)
 reservation_id      | bigint                   |           | not null | 
 begin_mileage       | character varying(7)     |           | not null | 
 end_mileage         | character varying(7)     |           | not null | 
 end_gas_percent     | character varying(4)     |           | not null | 
 problem             | boolean                  |           | not null | 
 problem_description | text                     |           | not null | 
 reg_date            | timestamp with time zone |           | not null | CURRENT_TIMESTAMP
 mile_charges        | double precision         |           | not null | 
 user_id             | bigint                   |           |          | 
Indexes:
    "idx_16497_primary" PRIMARY KEY, btree (id)
    "transport_trip_details_reservation_id_8ee1caf4" btree (reservation_id)
    "transport_trip_details_user_id_51026fff" btree (user_id)
Foreign-key constraints:
    "transport_trip_detai_reservation_id_8ee1caf4_fk_transport" FOREIGN KEY (reservation_id) REFERENCES transport_reservations(id) ON DELETE CASCADE DEFERRABLE
    "transport_trip_details_user_id_51026fff_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE SET NULL DEFERRABLE
Referenced by:
    TABLE "transport_driver_comments" CONSTRAINT "transport_driver_com_trip_id_7325ffae_fk_transport" FOREIGN KEY (trip_id) REFERENCES transport_trip_details(id) ON DELETE SET NULL DEFERRABLE

```
# Table transport_vehicle_brand
```
                                    Table "public.transport_vehicle_brand"
 Column |          Type          | Collation | Nullable |                       Default                       
--------+------------------------+-----------+----------+-----------------------------------------------------
 id     | integer                |           | not null | nextval('transport_vehicle_brand_id_seq'::regclass)
 name   | character varying(255) |           | not null | 
Indexes:
    "idx_16550_primary" PRIMARY KEY, btree (id)
Referenced by:
    TABLE "transport_vehicles" CONSTRAINT "transport_vehicles_make_id_e017fa97_fk_transport" FOREIGN KEY (make_id) REFERENCES transport_vehicle_brand(id) ON DELETE RESTRICT DEFERRABLE

```
# Table transport_vehicle_comments
```
                                        Table "public.transport_vehicle_comments"
     Column      |          Type          | Collation | Nullable |                        Default                         
-----------------+------------------------+-----------+----------+--------------------------------------------------------
 id              | bigint                 |           | not null | nextval('transport_vehicle_comments_id_seq'::regclass)
 posting_user_id | bigint                 |           | not null | 
 vehicle_id      | bigint                 |           | not null | 
 comment_date    | bigint                 |           | not null | 
 type            | character varying(25)  |           | not null | 
 comments        | character varying(300) |           | not null | 
Indexes:
    "idx_16556_primary" PRIMARY KEY, btree (id)
    "transport_vehicle_comments_posting_user_id_9c17d3f4" btree (posting_user_id)
    "transport_vehicle_comments_vehicle_id_a7fe8bd9" btree (vehicle_id)
Foreign-key constraints:
    "transport_vehicle_co_posting_user_id_9c17d3f4_fk_transport" FOREIGN KEY (posting_user_id) REFERENCES transport_driver(id) ON DELETE RESTRICT DEFERRABLE
    "transport_vehicle_co_vehicle_id_a7fe8bd9_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE CASCADE DEFERRABLE

```
# Table transport_vehicle_limit
```
                                      Table "public.transport_vehicle_limit"
    Column     |         Type         | Collation | Nullable |                       Default                       
---------------+----------------------+-----------+----------+-----------------------------------------------------
 id            | bigint               |           | not null | nextval('transport_vehicle_limit_id_seq'::regclass)
 option        | smallint             |           | not null | 
 department_id | character varying(4) |           | not null | 
 user_id       | bigint               |           | not null | 
 from_date     | date                 |           |          | 
 to_date       | date                 |           |          | 
 limit_value   | smallint             |           | not null | 3
Indexes:
    "idx_16387_primary" PRIMARY KEY, btree (id)
    "transport_vehicle_limit_department_id_66309f9d" btree (department_id)
    "transport_vehicle_limit_department_id_66309f9d_like" btree (department_id varchar_pattern_ops)
    "transport_vehicle_limit_user_id_2f698599" btree (user_id)
Foreign-key constraints:
    "transport_vehicle_li_department_id_66309f9d_fk_transport" FOREIGN KEY (department_id) REFERENCES transport_departments(id) ON DELETE RESTRICT DEFERRABLE
    "transport_vehicle_limit_user_id_2f698599_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE

```
# Table transport_vehicle_type
```
                                     Table "public.transport_vehicle_type"
  Column  |          Type          | Collation | Nullable |                      Default                       
----------+------------------------+-----------+----------+----------------------------------------------------
 id       | integer                |           | not null | nextval('transport_vehicle_type_id_seq'::regclass)
 type     | character varying(255) |           | not null | 
 capacity | smallint               |           | not null | 
Indexes:
    "idx_16562_primary" PRIMARY KEY, btree (id)
Referenced by:
    TABLE "transport_vehicles" CONSTRAINT "transport_vehicles_model_id_4952d844_fk_transport" FOREIGN KEY (model_id) REFERENCES transport_vehicle_type(id) ON DELETE RESTRICT DEFERRABLE

```
# Table transport_vehicles
```
                                           Table "public.transport_vehicles"
       Column        |           Type           | Collation | Nullable |                    Default                     
---------------------+--------------------------+-----------+----------+------------------------------------------------
 id                  | bigint                   |           | not null | nextval('transport_vehicles_id_seq'::regclass)
 user_id             | bigint                   |           | not null | 
 vehicle_no          | character varying(12)    |           | not null | 
 vin_no              | character varying(20)    |           | not null | 
 oil_filter          | character varying(20)    |           | not null | 
 safety_date         | date                     |           |          | 
 registration_date   | date                     |           |          | 
 license_plate_no    | character varying(50)    |           | not null | 
 make_id             | smallint                 |           | not null | 
 model_id            | smallint                 |           | not null | 
 manufacture_year    | character(4)             |           | not null | 
 mileage_at_takeover | character varying(7)     |           | not null | 
 date_at_takeover    | date                     |           |          | 
 cost_at_takeover    | double precision         |           | not null | 
 cost_rate           | double precision         |           | not null | 
 passenger_capacity  | character varying(2)     |           | not null | 
 condition           | character varying(50)    |           | not null | 
 restriction         | character varying(150)   |           | not null | 
 issues              | character varying(255)   |           | not null | 
 active              | boolean                  |           | not null | 
 restricted          | boolean                  |           | not null | 
 revised_date        | timestamp with time zone |           |          | 
 sold                | boolean                  |           | not null | false
 sold_date           | date                     |           |          | 
 admin_issues        | text                     |           | not null | 
Indexes:
    "idx_16540_primary" PRIMARY KEY, btree (id)
    "transport_vehicles_make_id_e017fa97" btree (make_id)
    "transport_vehicles_model_id_4952d844" btree (model_id)
    "transport_vehicles_user_id_084f10a6" btree (user_id)
Check constraints:
    "transport_vehicles_check_sold_inactive" CHECK (NOT (active AND sold))
Foreign-key constraints:
    "transport_vehicles_make_id_e017fa97_fk_transport" FOREIGN KEY (make_id) REFERENCES transport_vehicle_brand(id) ON DELETE RESTRICT DEFERRABLE
    "transport_vehicles_model_id_4952d844_fk_transport" FOREIGN KEY (model_id) REFERENCES transport_vehicle_type(id) ON DELETE RESTRICT DEFERRABLE
    "transport_vehicles_user_id_084f10a6_fk_auth_user_id" FOREIGN KEY (user_id) REFERENCES auth_user(id) ON DELETE RESTRICT DEFERRABLE
Referenced by:
    TABLE "transport_reservations" CONSTRAINT "transport_reservatio_vehicle_id_38940c0c_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_restricted_charges" CONSTRAINT "transport_restricted_vehicle_id_608acb24_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_service_reservations" CONSTRAINT "transport_service_re_vehicle_id_4d4d35f7_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_shop_tasks" CONSTRAINT "transport_shop_tasks_vehicle_id_a0e004fa_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE RESTRICT DEFERRABLE
    TABLE "transport_vehicle_comments" CONSTRAINT "transport_vehicle_co_vehicle_id_a7fe8bd9_fk_transport" FOREIGN KEY (vehicle_id) REFERENCES transport_vehicles(id) ON DELETE CASCADE DEFERRABLE
Triggers:
    on_update_timestamp BEFORE UPDATE ON transport_vehicles FOR EACH ROW EXECUTE FUNCTION on_update_timestamp_vehicles()

```
# Table transport_work_type
```
                                    Table "public.transport_work_type"
 Column |          Type          | Collation | Nullable |                     Default                     
--------+------------------------+-----------+----------+-------------------------------------------------
 id     | integer                |           | not null | nextval('transport_work_type_id_seq'::regclass)
 type   | character varying(255) |           | not null | 
Indexes:
    "idx_16568_primary" PRIMARY KEY, btree (id)
Referenced by:
    TABLE "transport_shop_tasks" CONSTRAINT "transport_shop_tasks_work_type_id_a5127d1e_fk_transport" FOREIGN KEY (work_type_id) REFERENCES transport_work_type(id) ON DELETE RESTRICT DEFERRABLE

```
