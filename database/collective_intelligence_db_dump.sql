--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET search_path = public, pg_catalog;

--
-- Name: ci_commit_transaction(uuid, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ci_commit_transaction(token_uuid uuid, end_node integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
  init_node INTEGER;
  init_val DOUBLE PRECISION;
  end_val DOUBLE PRECISION;
  avg_val DOUBLE PRECISION;
BEGIN
  SELECT init_node_id INTO init_node FROM token WHERE token_id = token_uuid;
  SELECT value INTO init_val FROM node WHERE node_id = init_node;
  SELECT value INTO end_val FROM node WHERE node_id = end_node;
  SELECT AVG(value) INTO avg_val FROM node WHERE node_id IN (init_node, end_node);
  UPDATE node SET value = avg_val WHERE node_id IN (init_node, end_node);
  UPDATE token SET end_node_id = end_node, init_node_value = init_val, end_node_value = end_val, final_init_node_value = avg_val, final_end_node_value = avg_val WHERE token_id = token_uuid;
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

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: account; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE account (
    account_id integer NOT NULL,
    node_id integer NOT NULL,
    identifier text,
    meta text,
    social smallint DEFAULT 0 NOT NULL,
    photo_url text,
    display_name character varying(255)
);


ALTER TABLE public.account OWNER TO postgres;

--
-- Name: account_acount_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE account_acount_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.account_acount_id_seq OWNER TO postgres;

--
-- Name: account_acount_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE account_acount_id_seq OWNED BY account.account_id;


--
-- Name: node; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE node (
    node_id integer NOT NULL,
    value double precision NOT NULL,
    last_change timestamp without time zone
);


ALTER TABLE public.node OWNER TO postgres;

--
-- Name: node_node_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE node_node_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.node_node_id_seq OWNER TO postgres;

--
-- Name: node_node_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE node_node_id_seq OWNED BY node.node_id;


--
-- Name: token; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE token (
    token_id uuid DEFAULT uuid_generate_v4() NOT NULL,
    init_node_id integer NOT NULL,
    created timestamp without time zone DEFAULT statement_timestamp() NOT NULL,
    end_node_id integer,
    ended timestamp without time zone,
    init_node_value double precision,
    end_node_value double precision,
    final_init_node_value double precision,
    final_end_node_value double precision
);


ALTER TABLE public.token OWNER TO postgres;

--
-- Name: account_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY account ALTER COLUMN account_id SET DEFAULT nextval('account_acount_id_seq'::regclass);


--
-- Name: node_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY node ALTER COLUMN node_id SET DEFAULT nextval('node_node_id_seq'::regclass);


--
-- Data for Name: account; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY account (account_id, node_id, identifier, meta, social, photo_url, display_name) FROM stdin;
25	20	https://open.login.yahooapis.com/openid20/user_profile/xrds	{"identifier":"https:\\/\\/open.login.yahooapis.com\\/openid20\\/user_profile\\/xrds","webSiteURL":null,"profileURL":null,"photoURL":"https:\\/\\/s.yimg.com\\/dh\\/ap\\/social\\/profile\\/profile_b48.png","displayName":"Gigi marzullo","description":null,"firstName":"","lastName":"","gender":"M","language":"en-US","age":null,"birthDay":"","birthMonth":"","birthYear":"","email":"xavdelp@yahoo.it","emailVerified":null,"phone":null,"address":null,"country":"","region":null,"city":null,"zip":""}	6	https://s.yimg.com/dh/ap/social/profile/profile_b48.png	Gigi marzullo
26	20	114626824471062532579	{"identifier":"114626824471062532579","webSiteURL":null,"profileURL":"https:\\/\\/plus.google.com\\/+SaverioDelpriori","photoURL":"https:\\/\\/lh5.googleusercontent.com\\/-5ojOQ6jFmF0\\/AAAAAAAAAAI\\/AAAAAAAAEn4\\/7FQK0bZbckw\\/photo.jpg?sz=200","displayName":"Saverio Delpriori","description":"","firstName":"Saverio","lastName":"Delpriori","gender":"male","language":"","age":"> 21","birthDay":0,"birthMonth":0,"birthYear":0,"email":"saveriodelpriori@gmail.com","emailVerified":"saveriodelpriori@gmail.com","phone":"","address":"Urbino","country":"","region":"","city":"Urbino","zip":""}	4	https://lh5.googleusercontent.com/-5ojOQ6jFmF0/AAAAAAAAAAI/AAAAAAAAEn4/7FQK0bZbckw/photo.jpg?sz=200	Saverio Delpriori
27	20	10153218744756439	{"identifier":"10153218744756439","webSiteURL":"","profileURL":"https:\\/\\/www.facebook.com\\/app_scoped_user_id\\/10153218744756439\\/","photoURL":"https:\\/\\/graph.facebook.com\\/10153218744756439\\/picture?width=150&height=150","displayName":"Robin Saverio Duca d'Angi\\u00f2","description":"","firstName":"Robin Saverio","lastName":"Duca d'Angi\\u00f2","gender":"male","language":"en_US","age":null,"birthDay":18,"birthMonth":4,"birthYear":1985,"email":"saveriodelpriori@gmail.com","emailVerified":"saveriodelpriori@gmail.com","phone":null,"address":null,"country":null,"region":"","city":null,"zip":null,"username":"","coverInfoURL":"https:\\/\\/graph.facebook.com\\/10153218744756439?fields=cover&access_token=CAAGtB3ZBM7RUBAG9RhNRUZA9c7AC8zPSJswHLOZBrFkOmLXr6IMfcMINQtsVdTi9dWOawUQ3GjoFJRHkDkGr2qioQO7ARAHW1cwmfxbywKiS4zlKIZBK8Qr5tQiKBjjmZCsyQfs75vBDJcGAMa3wsZCGp7FBn6MZB12tWAEP6RbMXJ9WxKyR5Kk"}	1	https://graph.facebook.com/10153218744756439/picture?width=150&height=150	Robin Saverio Duca d'Angiò
29	20	Zgmowubz0R	{"identifier":"Zgmowubz0R","webSiteURL":null,"profileURL":"https:\\/\\/www.linkedin.com\\/in\\/saveriodelpriori","photoURL":"https:\\/\\/media.licdn.com\\/mpr\\/mprx\\/0_ndxXK8ys5tRYLe3-9WjUKi0BFqplbm8-qawUKiY64AH8ZoBtVf7HO_oHdsy7XWTOBogVxk2Suipd","displayName":"Saverio Delpriori","description":"Mobile, server and web application developer. Interested in context aware applications and multi-tiered software architectures.\\n\\nMy research focuses on WSNs, Smart cities and Ubiquitous computing.\\n\\nI carry out some parallel activities about K\\u201312 science education.","firstName":"Saverio","lastName":"Delpriori","gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":"","phone":null,"address":null,"country":null,"region":null,"city":null,"zip":null}	5	https://media.licdn.com/mpr/mprx/0_ndxXK8ys5tRYLe3-9WjUKi0BFqplbm8-qawUKiY64AH8ZoBtVf7HO_oHdsy7XWTOBogVxk2Suipd	Saverio Delpriori
30	20	4751819	{"identifier":"4751819","webSiteURL":"http:\\/\\/www.s-delpriori.it","profileURL":null,"photoURL":"https:\\/\\/scontent.cdninstagram.com\\/t51.2885-19\\/s150x150\\/11909343_112610222426493_697219197_a.jpg","displayName":"Saverio  Delpriori","description":"I can quote Aladdin better than you. \\nDeal with that.","firstName":null,"lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":null,"emailVerified":null,"phone":null,"address":null,"country":null,"region":null,"city":null,"zip":null,"username":"dlpswr"}	3	https://scontent.cdninstagram.com/t51.2885-19/s150x150/11909343_112610222426493_697219197_a.jpg	Saverio  Delpriori
31	21	1207467817	{"identifier":1207467817,"webSiteURL":"http:\\/\\/t.co\\/gk1qaUPK8q","profileURL":"http:\\/\\/twitter.com\\/ScoutMatelica1","photoURL":"http:\\/\\/pbs.twimg.com\\/profile_images\\/3290923654\\/4f1512f883e62a07f7d5cd93b6ef3693.png","displayName":"ScoutMatelica1","description":"","firstName":"Scout Matelica1","lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":null,"phone":null,"address":null,"country":null,"region":"Matelica, Italy","city":null,"zip":null}	2	http://pbs.twimg.com/profile_images/3290923654/4f1512f883e62a07f7d5cd93b6ef3693.png	ScoutMatelica1
32	22	2401312003	{"identifier":2401312003,"webSiteURL":"http:\\/\\/t.co\\/OuWsNIR29E","profileURL":"http:\\/\\/twitter.com\\/PerMatelica","photoURL":"http:\\/\\/pbs.twimg.com\\/profile_images\\/474580979508453377\\/e9dThxaC.png","displayName":"PerMatelica","description":"Pagina Twitter ufficiale di Per Matelica, gruppo consiliare di maggioranza del Comune di Matelica. Sindaco Alessandro Delpriori.","firstName":"Per Matelica","lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":null,"phone":null,"address":null,"country":null,"region":"Matelica (MC), IT","city":null,"zip":null}	2	http://pbs.twimg.com/profile_images/474580979508453377/e9dThxaC.png	PerMatelica
34	24	423346032	{"identifier":423346032,"webSiteURL":"http:\\/\\/t.co\\/jMgkkqVWTd","profileURL":"http:\\/\\/twitter.com\\/DummyChef","photoURL":"http:\\/\\/pbs.twimg.com\\/profile_images\\/1662736136\\/cbd-logo.png","displayName":"DummyChef","description":"Cooking by Dummies \\u00e8 un blog che cerca di unire l\\u2019amore per la gastronomia con la pratica del masochismo ed il rifiuto di qualsiasi principio d\\u2019igiene.","firstName":"Cooking by Dummies","lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":null,"phone":null,"address":null,"country":null,"region":"","city":null,"zip":null}	2	http://pbs.twimg.com/profile_images/1662736136/cbd-logo.png	DummyChef
35	25	27646219	{"identifier":27646219,"webSiteURL":"http:\\/\\/t.co\\/zjiTTDgyWm","profileURL":"http:\\/\\/twitter.com\\/dlpswr","photoURL":"http:\\/\\/pbs.twimg.com\\/profile_images\\/3307911200\\/6110d41684e8cadf223f933db22ac41d.jpeg","displayName":"dlpswr","description":"I can quote Aladdin better than you. Deal with that.","firstName":"Sawerio Delpriory","lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":null,"phone":null,"address":null,"country":null,"region":"","city":null,"zip":null}	2	http://pbs.twimg.com/profile_images/3307911200/6110d41684e8cadf223f933db22ac41d.jpeg	dlpswr
36	26	10153997984488147	{"identifier":"10153997984488147","webSiteURL":"","profileURL":"https:\\/\\/www.facebook.com\\/app_scoped_user_id\\/10153997984488147\\/","photoURL":"https:\\/\\/graph.facebook.com\\/10153997984488147\\/picture?width=150&height=150","displayName":"Fabio Giglietto","description":"","firstName":"Fabio","lastName":"Giglietto","gender":"male","language":"en_US","age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"fabio.giglietto+facebook@gmail.com","emailVerified":"fabio.giglietto+facebook@gmail.com","phone":null,"address":null,"country":null,"region":"","city":null,"zip":null,"username":"","coverInfoURL":"https:\\/\\/graph.facebook.com\\/10153997984488147?fields=cover&access_token=CAAGtB3ZBM7RUBALPueRZBkuU7FZCGBuOHzgaimZCZAekYJ5knklDl9gtbB0YjCn37qOhGyodfW2LX60nRwgxjdv49o3KFM8nemIfxy3TJOMO4QaXcAZBsJ1VwrHzZBxW8SrSEHLAsfSmUHvChgDeCGDZCg3Qz1kyc8VFLbo5FAv3ixrLIHokl1Bcsw7C5tCk19CY0c8kEdgH8gZDZD"}	1	https://graph.facebook.com/10153997984488147/picture?width=150&height=150	Fabio Giglietto
37	27	10208360326583773	{"identifier":"10208360326583773","webSiteURL":"","profileURL":"https:\\/\\/www.facebook.com\\/app_scoped_user_id\\/10208360326583773\\/","photoURL":"https:\\/\\/graph.facebook.com\\/10208360326583773\\/picture?width=150&height=150","displayName":"Lorenz Cuno Klopfenstein","description":"","firstName":"Lorenz","lastName":"Klopfenstein","gender":"male","language":"en_US","age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"lck@klopfenstein.net","emailVerified":"lck@klopfenstein.net","phone":null,"address":null,"country":null,"region":"","city":null,"zip":null,"username":"","coverInfoURL":"https:\\/\\/graph.facebook.com\\/10208360326583773?fields=cover&access_token=CAAGtB3ZBM7RUBAET8rq8tMOJteGDAEtLCbIAV28pg7a348Klq7wrEp6F4PcYXgyUF1NecQ4OdNlmZAd70WeModuUdeX1uYkW2L4Db4WxyNU48c91Hf0mN6D2lYCwe9M8jJ8QTLmxnTVd7G2fZB3qfZBuR6aLX2jqEskqLSQ2U9gh5Hqdl8WllAX777JcEoQZD"}	1	https://graph.facebook.com/10208360326583773/picture?width=150&height=150	Lorenz Cuno Klopfenstein
38	27	546822022	{"identifier":546822022,"webSiteURL":"http:\\/\\/t.co\\/8AI2tV2hme","profileURL":"http:\\/\\/twitter.com\\/LorenzCK","photoURL":"http:\\/\\/pbs.twimg.com\\/profile_images\\/419057911063908352\\/LZ40FIkY.jpeg","displayName":"LorenzCK","description":"Good software developer, decent designer, awesome human.","firstName":"Lorenz Klopfenstein","lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":null,"phone":null,"address":null,"country":null,"region":"Italy","city":null,"zip":null}	2	http://pbs.twimg.com/profile_images/419057911063908352/LZ40FIkY.jpeg	LorenzCK
39	27	109071405868995913585	{"identifier":"109071405868995913585","webSiteURL":null,"profileURL":"https:\\/\\/plus.google.com\\/+LorenzCunoKlopfenstein","photoURL":"https:\\/\\/lh4.googleusercontent.com\\/-rytBYI_spfA\\/AAAAAAAAAAI\\/AAAAAAAABZg\\/kca0F5Htoes\\/photo.jpg?sz=200","displayName":"Lorenz Cuno Klopfenstein","description":"","firstName":"Lorenz Cuno","lastName":"Klopfenstein","gender":"male","language":"","age":"> 21","birthDay":12,"birthMonth":11,"birthYear":1984,"email":"lorenz.klopfenstein@gmail.com","emailVerified":"lorenz.klopfenstein@gmail.com","phone":"","address":"Z\\u00fcrich, Switzerland","country":"","region":"","city":"Z\\u00fcrich, Switzerland","zip":""}	4	https://lh4.googleusercontent.com/-rytBYI_spfA/AAAAAAAAAAI/AAAAAAAABZg/kca0F5Htoes/photo.jpg?sz=200	Lorenz Cuno Klopfenstein
40	28	7773732	{"identifier":7773732,"webSiteURL":"http:\\/\\/t.co\\/YXboraNFxe","profileURL":"http:\\/\\/twitter.com\\/gba_mm","photoURL":"http:\\/\\/pbs.twimg.com\\/profile_images\\/667359413213138944\\/NYaaNONm.jpg","displayName":"gba_mm","description":"Academic activist on the social and cultural implications of technology http:\\/\\/t.co\\/5CFuB5wsKa","firstName":"Gio. Boccia Artieri","lastName":null,"gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"","emailVerified":null,"phone":null,"address":null,"country":null,"region":"","city":null,"zip":null}	2	http://pbs.twimg.com/profile_images/667359413213138944/NYaaNONm.jpg	gba_mm
41	29	117179390184655293924	{"identifier":"117179390184655293924","webSiteURL":"","profileURL":"https:\\/\\/plus.google.com\\/117179390184655293924","photoURL":"https:\\/\\/lh5.googleusercontent.com\\/-awipM5A6t-Y\\/AAAAAAAAAAI\\/AAAAAAAADTA\\/1ZGF8Abo7F0\\/photo.jpg?sz=200","displayName":"Emanuele Lattanzi","description":"","firstName":"Emanuele","lastName":"Lattanzi","gender":"male","language":"","age":"> 21","birthDay":0,"birthMonth":0,"birthYear":0,"email":"lattanzi.emanuele2@gmail.com","emailVerified":"lattanzi.emanuele2@gmail.com","phone":"","address":"Monte Cerignone","country":"","region":"","city":"Monte Cerignone","zip":""}	4	https://lh5.googleusercontent.com/-awipM5A6t-Y/AAAAAAAAAAI/AAAAAAAADTA/1ZGF8Abo7F0/photo.jpg?sz=200	Emanuele Lattanzi
42	30	113237290949462181987	{"identifier":"113237290949462181987","webSiteURL":"","profileURL":"https:\\/\\/plus.google.com\\/113237290949462181987","photoURL":"https:\\/\\/lh3.googleusercontent.com\\/-wJ0ZUj9SAtM\\/AAAAAAAAAAI\\/AAAAAAAAAjw\\/NiUklxfTRrk\\/photo.jpg?sz=200","displayName":"Alessandro Bogliolo","description":"","firstName":"Alessandro","lastName":"Bogliolo","gender":"male","language":"","age":"> 21","birthDay":0,"birthMonth":0,"birthYear":0,"email":"alessandro.bogliolo@uniurb.it","emailVerified":"alessandro.bogliolo@uniurb.it","phone":"","address":null,"country":"","region":"","city":null,"zip":""}	4	https://lh3.googleusercontent.com/-wJ0ZUj9SAtM/AAAAAAAAAAI/AAAAAAAAAjw/NiUklxfTRrk/photo.jpg?sz=200	Alessandro Bogliolo
43	31	116087079248598034438	{"identifier":"116087079248598034438","webSiteURL":null,"profileURL":"https:\\/\\/plus.google.com\\/+BrendanPaolini","photoURL":"https:\\/\\/lh5.googleusercontent.com\\/-wrRXAlaE8ac\\/AAAAAAAAAAI\\/AAAAAAAAAEg\\/WXmtkJ9QzXA\\/photo.jpg?sz=200","displayName":"Brendan Paolini","description":"","firstName":"Brendan","lastName":"Paolini","gender":"male","language":"","age":"> 21","birthDay":0,"birthMonth":0,"birthYear":0,"email":"brendan.paolini@gmail.com","emailVerified":"brendan.paolini@gmail.com","phone":"","address":"Fano","country":"","region":"","city":"Fano","zip":""}	4	https://lh5.googleusercontent.com/-wrRXAlaE8ac/AAAAAAAAAAI/AAAAAAAAAEg/WXmtkJ9QzXA/photo.jpg?sz=200	Brendan Paolini
\.


--
-- Name: account_acount_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('account_acount_id_seq', 43, true);


--
-- Data for Name: node; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY node (node_id, value, last_change) FROM stdin;
18	44	2016-02-26 15:26:38.33608
19	14	2016-02-26 15:26:56.671253
21	77	2016-03-03 16:49:32.201598
22	73	2016-03-03 16:53:05.019842
20	61	2016-03-03 17:21:47.35431
24	61	2016-03-03 17:21:47.35431
25	10.5	2016-03-04 15:55:23.451495
26	10.5	2016-03-04 15:55:23.451495
27	22	2016-03-04 16:10:05.743925
28	99	2016-03-04 17:38:23.541477
29	57	2016-03-07 15:44:03.265411
30	40	2016-03-08 12:02:37.240373
31	89	2016-03-14 11:55:03.716658
\.


--
-- Name: node_node_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('node_node_id_seq', 31, true);


--
-- Data for Name: token; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY token (token_id, init_node_id, created, end_node_id, ended, init_node_value, end_node_value, final_init_node_value, final_end_node_value) FROM stdin;
74eaea13-902d-4fe0-a7fa-a31e189610d4	21	2016-03-03 15:53:39.251837	20	2016-03-03 15:57:32.557375	78	76	77	77
518e3fdb-3f90-4574-91a0-6c16aa7510d0	21	2016-03-03 16:49:22.855959	20	2016-03-03 16:49:32.201598	77	77	77	77
5819343d-4a33-4f54-9714-9ce00fb7b9c1	20	2016-02-29 10:56:05.656416	24	2016-03-03 17:21:47.35431	77	45	61	61
e4f89c06-dc5a-4edb-b88a-64e5cf97350a	25	2016-03-04 15:49:48.308826	26	2016-03-04 15:51:25.582456	3	18	10.5	10.5
46eda36f-a37f-4cd0-bb7d-1ea3e56eb715	26	2016-03-04 15:53:06.145919	25	2016-03-04 15:55:23.451495	10.5	10.5	10.5	10.5
71d5e0b8-24e3-451d-a521-e646aaaf2f12	27	2016-03-04 16:10:43.447328	\N	\N	\N	\N	\N	\N
95186c77-1a47-4a20-8e56-58d248920c59	28	2016-03-04 17:38:58.880706	\N	\N	\N	\N	\N	\N
30af1c65-42cd-4c0b-8442-6ae0216e1a2c	30	2016-03-08 12:06:44.771957	\N	\N	\N	\N	\N	\N
d9f978a2-75ab-438d-b4a4-b4a2d6ffb819	20	2016-03-08 16:05:10.094866	\N	\N	\N	\N	\N	\N
ad437c2f-ecf2-4b60-8112-a9529c2a716c	26	2016-03-10 08:58:20.297101	\N	\N	\N	\N	\N	\N
ce73e457-d580-47d3-8dc2-b383d230c07c	31	2016-03-14 11:55:30.335957	\N	\N	\N	\N	\N	\N
\.


--
-- Name: account_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY account
    ADD CONSTRAINT account_pkey PRIMARY KEY (account_id);


--
-- Name: node_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY node
    ADD CONSTRAINT node_pkey PRIMARY KEY (node_id);


--
-- Name: ci_insert_node_last_change; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER ci_insert_node_last_change BEFORE INSERT ON node FOR EACH ROW EXECUTE PROCEDURE ci_update_modified_column();


--
-- Name: ci_update_node_last_change; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER ci_update_node_last_change BEFORE UPDATE ON node FOR EACH ROW EXECUTE PROCEDURE ci_update_modified_column();


--
-- Name: ci_update_token_end; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER ci_update_token_end BEFORE UPDATE OF end_node_id ON token FOR EACH ROW EXECUTE PROCEDURE ci_update_ended_column();


--
-- Name: account_node_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY account
    ADD CONSTRAINT account_node_id_fkey FOREIGN KEY (node_id) REFERENCES node(node_id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: init_node_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY token
    ADD CONSTRAINT init_node_id_fk FOREIGN KEY (init_node_id) REFERENCES node(node_id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: token_end_node_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY token
    ADD CONSTRAINT token_end_node_id_fkey FOREIGN KEY (end_node_id) REFERENCES node(node_id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

