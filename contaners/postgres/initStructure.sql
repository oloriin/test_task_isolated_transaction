CREATE TABLE public.collector
(
  id SERIAL PRIMARY KEY,
  microtime TIMESTAMP,
  data JSONB
);

CREATE TABLE public.events
(
  token UUID PRIMARY KEY,
  microtime TIMESTAMP,
  numbers JSONB,
  row_count INT
);

CREATE OR REPLACE FUNCTION log_event() RETURNS TRIGGER AS $events$
BEGIN

  INSERT INTO events (token, microtime, numbers, row_count)
    SELECT
      (NEW.data ->> 'token')::UUID as token,
      NEW.microtime as microtime,
      (NEW.data -> 'numbers') as numbers,
      (NEW.data ->> 'row_count')::INT as row_count
  ;
  RETURN NULL;

END;
$events$ LANGUAGE plpgsql;

CREATE TRIGGER events
AFTER INSERT ON collector
FOR EACH ROW EXECUTE PROCEDURE log_event();


