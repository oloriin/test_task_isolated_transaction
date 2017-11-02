CREATE TABLE public.collector
(
  id SERIAL PRIMARY KEY,
  data JSONB
);

CREATE TABLE public.events
(
  id SERIAL PRIMARY KEY,
  token UUID UNIQUE,
  numbers JSONB,
  previous_count INT
);

CREATE OR REPLACE FUNCTION log_event() RETURNS TRIGGER AS $events$
BEGIN

  INSERT INTO events (token, numbers, previous_count)
    SELECT
      (NEW.data ->> 'token')::UUID as token,
      (NEW.data -> 'numbers') as numbers,
      (NEW.data ->> 'previous_count')::INT as previous_count
  ;
  RETURN NULL;

END;
$events$ LANGUAGE plpgsql;

CREATE TRIGGER events
AFTER INSERT ON collector
FOR EACH ROW EXECUTE PROCEDURE log_event();


