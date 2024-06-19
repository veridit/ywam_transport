BEGIN;

WITH prepared AS (
    SELECT
        id,
        title,
        text,
        (SELECT regexp_replace(string_agg(fixed_part,''),'\r', '', 'g') AS clean_text
           FROM
          (
          SELECT *
               , CASE WHEN variable
                 THEN trim('_' FROM lower(regexp_replace(replace(part, ' ', '_'), '([a-z]+)([A-Z]+)', '\1_\2', 'g')))
                 ELSE part
                 END AS fixed_part
          FROM
          ( SELECT *
                 , CASE WHEN (i - 1) % 2 = 1 THEN true ELSE false END AS variable
              FROM regexp_split_to_table(
                regexp_replace(text, '#(\w+(?:\s+\w+)*)#', '{{\1}}', 'g')
                , '(?<={{)|(?=}})') with ordinality as v(part, i)
          ) AS parts
          ) AS fixed_parts) AS cleaned_text_with_replaced_vars
    FROM transport_info_links
    WHERE text ILIKE '%--SUBJECT--%'
    ORDER BY id
),
extracted AS (
    SELECT
        id,
        title,
        text,
        cleaned_text_with_replaced_vars,
        regexp_replace(
            regexp_replace(
                regexp_replace(
                    (regexp_matches(cleaned_text_with_replaced_vars, '--SUBJECT--.*?\n\s*((?:.|\n)+?)\s*\n.*?--END SUBJECT--','n'))[1],
                    '<[^>]*>', '', 'g'
                ),
                '&nbsp;', ' ', 'g'
            ),
            '\n', '', 'g'
        ) AS subject_match,
        (regexp_matches(cleaned_text_with_replaced_vars, '--MESSAGE BODY--.*?\n\s*((?:.|\n)+?)\s*\n.*?--END MESSAGE BODY--','n'))[1]
        AS body_match,
        (SELECT ARRAY_AGG(DISTINCT matches[1]) FROM regexp_matches(cleaned_text_with_replaced_vars, '{{(\w+)}}', 'g') AS matches) AS variable_matches
    FROM prepared
), mapped AS (
    SELECT
        extracted.*,
        COALESCE(subject_match, '') AS subject,
        COALESCE(body_match, '') AS body,
        COALESCE(ARRAY_TO_JSON(variable_matches),'[]'::JSON) AS variables,
        CASE
            WHEN title = 'New Driver Msg # 1 (acknowledge registration)' THEN 'driver_registered'
            WHEN title = 'New Driver Msg # 2 (activation)' THEN 'driver_activated'
            WHEN title = 'Dept. Cross Charge Notice' THEN 'dept_cross_charge_notice'
            WHEN title = 'Vehicle Removed for Service Work' THEN 'vehicle_removed_for_service'
            WHEN title = 'Driver Deactivation Notice When Dept. Deactivated' THEN 'driver_deactivation_notice'
            WHEN title = 'Restore Suspended Driver - Message' THEN 'restore_suspended_driver'
            WHEN title = 'Activate - Restore Vehicle to Service from permanent removal' THEN 'activate_restore_vehicle'
            WHEN title = 'PERMIT RENEWAL - approval message to driver' THEN 'permit_renewal'
            WHEN title = 'Late Trip Cancellation -Abandon warning by system' THEN 'late_trip_cancellation'
            WHEN title = 'Abandon Notice W / Charge of 25 Miles' THEN 'abandon_notice_with_charge'
            ELSE 'unknown'
        END AS event
    FROM extracted
)
--SELECT id, event, subject, variables, body FROM mapped;--WHERE subject_match IS NULL;
--SELECT DISTINCT json_array_elements_text(variables) AS variable FROM mapped;--WHERE subject_match IS NULL;
--SELECT cleaned_text_with_replaced_vars FROM prepared WHERE id = 25;
--SELECT * FROM prepared; --WHERE id = 25;
--SELECT * FROM extracted; -- WHERE id = 25;
INSERT INTO transport_email_template (event, subject, body, variables, created_at, updated_at)
SELECT
    event,
    subject,
    body,
    variables,
    NOW(),
    NOW()
FROM mapped
WHERE event != 'unknown';

DELETE FROM transport_info_links WHERE text ILIKE '%--SUBJECT--%';

-- Function to update variables in the 'variables' JSONB field
-- Function to update variables in the 'variables' JSONB array
CREATE OR REPLACE FUNCTION update_jsonb_array(variables JSONB) RETURNS JSONB AS $$
DECLARE
    replacements JSONB := '{
        "password": "activation_link",
        "dept_name": "department_name",
        "resv_no": "reservation_id",
        "reservation": "reservation_id",
        "departdate": "planned_departure_datetime",
        "permit_end_date": "end_permit",
        "testing_person": "reserving_user_full_name",
        "on_date": "planned_departure_datetime",
        "user_level": "auth_group_name",
        "resvno": "reservation_id"
    }';
    var TEXT;
    new_variables JSONB := '[]';
BEGIN
    FOR var IN SELECT jsonb_array_elements_text(variables)
    LOOP
        IF replacements ? var THEN
            new_variables := jsonb_insert(new_variables, '{-1}', to_jsonb(replacements->>var), true);
        ELSE
            new_variables := jsonb_insert(new_variables, '{-1}', to_jsonb(var), true);
        END IF;
    END LOOP;
    RETURN new_variables;
END;
$$ LANGUAGE plpgsql;

-- Update variables in the 'variables' JSONB field
UPDATE transport_email_template
SET variables = update_jsonb_array(variables);

-- Clean up by removing the function after use
DROP FUNCTION update_jsonb_array(JSONB);

-- Function to replace variables in the 'body' template
CREATE OR REPLACE FUNCTION replace_variables(body TEXT, variables JSONB) RETURNS TEXT AS $$
DECLARE
    replacements JSONB := '{
        "password": "activation_link",
        "dept_name": "department_name",
        "resv_no": "reservation_id",
        "reservation": "reservation_id",
        "departdate": "planned_departure_datetime",
        "permit_end_date": "end_permit",
        "testing_person": "reserving_user_full_name",
        "on_date": "planned_departure_datetime",
        "user_level": "auth_group_name",
        "resvno": "reservation_id"
    }';
    var_name TEXT;
    new_var_name TEXT;
BEGIN
    FOR var_name, new_var_name IN SELECT * FROM jsonb_each_text(replacements)
    LOOP
        --RAISE NOTICE 'Checking for % in %', var_name, variables;
        IF variables ? var_name OR variables ? new_var_name THEN
            --RAISE NOTICE 'Changing % to %', var_name, new_var_name;
            body := replace(body, '{{' || var_name || '}}', '{{' || new_var_name || '}}');
        END IF;
    END LOOP;
    RETURN body;
END;
$$ LANGUAGE plpgsql;

-- Update the 'body' field using the replace_variables function
UPDATE transport_email_template
SET body = replace_variables(body, variables);

-- Clean up by removing the function after use
DROP FUNCTION replace_variables(TEXT, JSONB);

END;
