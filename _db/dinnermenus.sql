--
-- PostgreSQL database dump
--

-- Dumped from database version 14.17
-- Dumped by pg_dump version 15.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: sp808
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO sp808;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: dinnermenus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dinnermenus (
    id integer,
    code integer,
    category character varying(50),
    title character varying(80),
    description character varying(50),
    weight character varying(30),
    price character varying(10)
);


ALTER TABLE public.dinnermenus OWNER TO postgres;

--
-- Data for Name: dinnermenus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dinnermenus (id, code, category, title, description, weight, price) FROM stdin;
1	1032	Cold dishes	Cold dishes menu 1	secondary description	100	100
2	1034	Cold dishes	Cold dishes menu 2	secondary description	100	120
3	1066	Side dishes	Side dishes 1 extra	secondary description	150	55
4	1067	Side dishes	Side dishes 1 extra	secondary description	150	65
5	2015	First courses	Soup with meat 1	secondary description	100	90
6	2006	First courses	Soup with meat 2	secondary description	250	80
7	3046	Second courses	Delicious fish 1	secondary description	75	75
8	3040	Second courses	Delicious fish 2	secondary description	50	20
9	3047	Drinks	Coca-Cola Light 1	secondary description	75	80
10	3041	Drinks	Coca-Cola Light 2	secondary description	50	100
11	3048	Bakery	Rug roll ban 1	secondary description	75	110
12	3049	Bakery	Rug roll ban 2	secondary description	50	150
13	3050	Bread	Bread 1	secondary description	54	70
14	3051	Bread	Bread 2	secondary description	40	65
\.


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: sp808
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

