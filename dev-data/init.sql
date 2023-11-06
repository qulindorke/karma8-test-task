-- Creating users table
CREATE TABLE users
(
    id        SERIAL PRIMARY KEY,
    username  VARCHAR(255) NOT NULL,
    email     VARCHAR(255) NOT NULL,
    validts   BIGINT NOT NULL,
    confirmed BOOLEAN NOT NULL DEFAULT FALSE,
    checked   BOOLEAN NOT NULL DEFAULT FALSE,
    valid     BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE INDEX idx_partial_confirmed_validts ON users(confirmed, validts)
    WHERE confirmed IS TRUE AND validts <> 0;

-- Creating queue table
CREATE TYPE job_status AS ENUM ('pending', 'processing', 'failed');

CREATE TABLE job_queue
(
    id           SERIAL PRIMARY KEY,
    action_name VARCHAR(255) NOT NULL,
    parameters   JSON         NOT NULL,
    status       job_status   NOT NULL,
    created_at   TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    started_at   TIMESTAMP WITH TIME ZONE DEFAULT NULL
);

CREATE INDEX idx_job_queue_status_created_at ON job_queue (status, created_at);

-- Function to generate a random subscription expiration date
CREATE OR REPLACE FUNCTION random_unixtimestamp_around_now()
RETURNS BIGINT AS $$
DECLARE
range_seconds INT := 14 * 24 * 60 * 60; -- Define the range in seconds (14 days)
    random_offset INT := FLOOR(random() * (range_seconds * 2 + 1))::INT - range_seconds;
BEGIN
    -- Return a random Unix timestamp within the +/- 14 days range
RETURN EXTRACT(EPOCH FROM NOW() + (random_offset || ' seconds')::INTERVAL)::BIGINT;
END;
$$ LANGUAGE plpgsql;

-- Function to generate a random string of a given length
CREATE OR REPLACE FUNCTION generate_random_string(length INTEGER)
RETURNS TEXT AS $$
DECLARE
chars TEXT[] := ARRAY[
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
        'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z'
    ];
    result TEXT := '';
    i INTEGER;
BEGIN
FOR i IN 1..length LOOP
        result := result || chars[1 + (RANDOM() * (array_length(chars, 1) - 1))::int];
END LOOP;
RETURN result;
END;
$$ LANGUAGE plpgsql VOLATILE;

-- Insert random data into the users table
DO $$
DECLARE
i INTEGER;
    v_username TEXT;
    v_email TEXT;
    v_validts BIGINT;
    v_confirmed BOOLEAN;
    v_checked BOOLEAN;
    v_valid BOOLEAN;
    num_users CONSTANT INTEGER := 10000; -- The number of users to generate
BEGIN
    RAISE NOTICE 'Generating % users', num_users;

FOR i IN 1..num_users LOOP
        -- Generate random data
        v_username := generate_random_string(10);
        v_email := generate_random_string(8) || '@example.com';

        -- Set validts to zero 80% of the time, otherwise get a random Unix timestamp
        IF RANDOM() < 0.8 THEN
            v_validts := 0;
ELSE
            v_validts := random_unixtimestamp_around_now();
END IF;

        -- Generate confirmed, checked, and valid according to the specified conditions
        v_confirmed := RANDOM() <= 0.15;
        IF v_confirmed THEN
            v_checked := RANDOM() <= 0.5;
ELSE
            v_checked := FALSE;
END IF;

        IF v_checked THEN
            v_valid := RANDOM() <= 0.5;
ELSE
            v_valid := FALSE;
END IF;

INSERT INTO users (username, email, validts, confirmed, checked, valid)
VALUES (v_username, v_email, v_validts, v_confirmed, v_checked, v_valid);
END LOOP;

    RAISE NOTICE 'Finished generating % users', num_users;
END;
$$;
