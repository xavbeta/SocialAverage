--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: ci_commit_transaction(uuid, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ci_commit_transaction(token_uuid uuid, end_node integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
    init_node INTEGER;
    avg_val DOUBLE PRECISION;
BEGIN
    SELECT init_node_id INTO init_node FROM token WHERE token_id = token_uuid;
    SELECT AVG(value) INTO avg_val FROM node WHERE node_id IN (init_node, end_node);
    UPDATE node SET value = avg_val WHERE node_id IN (init_node, end_node);
    UPDATE token SET end_node_id = end_node WHERE token_id = token_uuid;
    RETURN avg_val;
END;$$;


ALTER FUNCTION public.ci_commit_transaction(token_uuid uuid, end_node integer) OWNER TO postgres;

--
-- Name: ci_save_last_node_history_value(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ci_save_last_node_history_value() RETURNS trigger
    LANGUAGE plpgsql
    AS $$DECLARE
  node INTEGER;
  val DOUBLE PRECISION;
  token UUID;
BEGIN 
    select node_id, value, token_id INTO node, val, token FROM node INNER JOIN token ON token.init_node_id = node.node_id WHERE last_change IS NOT NULL ORDER BY token.ended DESC limit 1;
    insert into history (node_id, value, token_id) VALUES(node, val, token);
    RETURN NEW;	
END;
$$;


ALTER FUNCTION public.ci_save_last_node_history_value() OWNER TO postgres;

--
-- Name: ci_update_ended_column(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ci_update_ended_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.ended = now();
    RETURN NEW;	
END;
$$;


ALTER FUNCTION public.ci_update_ended_column() OWNER TO postgres;

--
-- Name: ci_update_modified_column(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ci_update_modified_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
    NEW.last_change= now();
    RETURN NEW;	
END;
$$;


ALTER FUNCTION public.ci_update_modified_column() OWNER TO postgres;

--
-- PostgreSQL database dump complete
--

