--
-- PostgreSQL database dump
--

-- Dumped from database version 15.10 (Debian 15.10-1.pgdg120+1)
-- Dumped by pg_dump version 15.10 (Debian 15.10-1.pgdg120+1)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: templates; Type: TABLE; Schema: public; Owner: icarusadmin
--

CREATE TABLE public.templates (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    content text NOT NULL,
    type character varying(50) DEFAULT 'page'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    is_active boolean DEFAULT true,
    parent_id character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.templates OWNER TO icarusadmin;

--
-- Name: list templates; Type: VIEW; Schema: public; Owner: icarusadmin
--

CREATE VIEW public."list templates" AS
 SELECT count(*) AS count
   FROM public.templates;


ALTER TABLE public."list templates" OWNER TO icarusadmin;

--
-- Name: template_variables; Type: TABLE; Schema: public; Owner: icarusadmin
--

CREATE TABLE public.template_variables (
    id character varying(36) NOT NULL,
    template_id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    default_value text,
    tag_type character varying(50) DEFAULT 'string'::character varying NOT NULL,
    is_required boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    data_type character varying(50),
    helper_name character varying(255),
    arguments jsonb
);


ALTER TABLE public.template_variables OWNER TO icarusadmin;

--
-- Name: template_versions; Type: TABLE; Schema: public; Owner: icarusadmin
--

CREATE TABLE public.template_versions (
    id character varying(36) NOT NULL,
    template_id character varying(36) NOT NULL,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    created_by character varying(36) NOT NULL
);


ALTER TABLE public.template_versions OWNER TO icarusadmin;

--
-- Name: template_variables template_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: icarusadmin
--

ALTER TABLE ONLY public.template_variables
    ADD CONSTRAINT template_variables_pkey PRIMARY KEY (id);


--
-- Name: template_versions template_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: icarusadmin
--

ALTER TABLE ONLY public.template_versions
    ADD CONSTRAINT template_versions_pkey PRIMARY KEY (id);


--
-- Name: templates templates_pkey; Type: CONSTRAINT; Schema: public; Owner: icarusadmin
--

ALTER TABLE ONLY public.templates
    ADD CONSTRAINT templates_pkey PRIMARY KEY (id);


--
-- Name: template_variables template_variables_template_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: icarusadmin
--

ALTER TABLE ONLY public.template_variables
    ADD CONSTRAINT template_variables_template_id_fkey FOREIGN KEY (template_id) REFERENCES public.templates(id);


--
-- Name: template_versions template_versions_template_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: icarusadmin
--

ALTER TABLE ONLY public.template_versions
    ADD CONSTRAINT template_versions_template_id_fkey FOREIGN KEY (template_id) REFERENCES public.templates(id);


--
-- Name: templates templates_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: icarusadmin
--

ALTER TABLE ONLY public.templates
    ADD CONSTRAINT templates_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES public.templates(id);


--
-- PostgreSQL database dump complete
--

