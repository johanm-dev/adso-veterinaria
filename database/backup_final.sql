--
-- PostgreSQL database dump
--

\restrict 2h5dVaLALtUvfKdSmNNQSv41lcNGJke7WPrYw3Tq2ubQzTq8fX9RpI5N9aIEgV0

-- Dumped from database version 17.11
-- Dumped by pg_dump version 17.11

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
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
-- Name: aplicacion_vacuna; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aplicacion_vacuna (
    id integer NOT NULL,
    mascota_id integer NOT NULL,
    vacuna_id integer NOT NULL,
    veterinario_id integer NOT NULL,
    fecha_aplicacion date NOT NULL,
    proxima_dosis date NOT NULL,
    lote character varying(30),
    CONSTRAINT aplicacion_vacuna_check CHECK ((proxima_dosis > fecha_aplicacion))
);


ALTER TABLE public.aplicacion_vacuna OWNER TO postgres;

--
-- Name: aplicacion_vacuna_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.aplicacion_vacuna ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.aplicacion_vacuna_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: consulta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.consulta (
    id integer NOT NULL,
    mascota_id integer NOT NULL,
    veterinario_id integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    motivo character varying(200) NOT NULL,
    diagnostico text,
    costo numeric(10,2) DEFAULT 0.00 NOT NULL,
    proximo_control date,
    CONSTRAINT consulta_costo_check CHECK ((costo >= (0)::numeric))
);


ALTER TABLE public.consulta OWNER TO postgres;

--
-- Name: consulta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.consulta ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.consulta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: mascota; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mascota (
    id integer NOT NULL,
    propietario_id integer NOT NULL,
    nombre character varying(60) NOT NULL,
    especie character varying(30) NOT NULL,
    raza character varying(60),
    sexo character(1) NOT NULL,
    fecha_nacimiento date,
    peso_kg numeric(6,2),
    activo boolean DEFAULT true NOT NULL,
    CONSTRAINT mascota_especie_check CHECK (((especie)::text = ANY ((ARRAY['Perro'::character varying, 'Gato'::character varying, 'Ave'::character varying, 'Conejo'::character varying, 'Otro'::character varying])::text[]))),
    CONSTRAINT mascota_peso_kg_check CHECK ((peso_kg > (0)::numeric)),
    CONSTRAINT mascota_sexo_check CHECK ((sexo = ANY (ARRAY['M'::bpchar, 'H'::bpchar])))
);


ALTER TABLE public.mascota OWNER TO postgres;

--
-- Name: mascota_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.mascota ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.mascota_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: propietario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.propietario (
    id integer NOT NULL,
    documento character varying(20) NOT NULL,
    nombres character varying(80) NOT NULL,
    apellidos character varying(80) NOT NULL,
    telefono character varying(20) NOT NULL,
    correo character varying(120),
    direccion character varying(150),
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.propietario OWNER TO postgres;

--
-- Name: propietario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.propietario ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.propietario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: tratamiento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tratamiento (
    id integer NOT NULL,
    consulta_id integer NOT NULL,
    medicamento character varying(100) NOT NULL,
    dosis character varying(60) NOT NULL,
    frecuencia character varying(60) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date,
    estado character varying(20) DEFAULT 'En curso'::character varying NOT NULL,
    CONSTRAINT tratamiento_check CHECK (((fecha_fin IS NULL) OR (fecha_fin >= fecha_inicio))),
    CONSTRAINT tratamiento_estado_check CHECK (((estado)::text = ANY ((ARRAY['En curso'::character varying, 'Finalizado'::character varying, 'Suspendido'::character varying])::text[])))
);


ALTER TABLE public.tratamiento OWNER TO postgres;

--
-- Name: tratamiento_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.tratamiento ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.tratamiento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    correo character varying(120) NOT NULL,
    password_hash character varying(255) NOT NULL,
    rol character varying(20) NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT usuario_rol_check CHECK (((rol)::text = ANY ((ARRAY['admin'::character varying, 'veterinario'::character varying, 'recepcion'::character varying])::text[])))
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.usuario ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: v_alertas_controles; Type: VIEW; Schema: public; Owner: app_veterinaria
--

CREATE VIEW public.v_alertas_controles AS
 SELECT c.id AS consulta_id,
    m.id AS mascota_id,
    m.nombre AS mascota,
    (((p.nombres)::text || ' '::text) || (p.apellidos)::text) AS propietario,
    p.telefono,
    c.proximo_control,
    (c.proximo_control - CURRENT_DATE) AS dias_restantes,
        CASE
            WHEN ((c.proximo_control - CURRENT_DATE) < 0) THEN 'Vencido'::text
            ELSE 'Por asistir'::text
        END AS estado
   FROM ((public.consulta c
     JOIN public.mascota m ON ((m.id = c.mascota_id)))
     JOIN public.propietario p ON ((p.id = m.propietario_id)))
  WHERE ((c.proximo_control IS NOT NULL) AND (c.proximo_control <= (CURRENT_DATE + 7)) AND (NOT (EXISTS ( SELECT 1
           FROM public.consulta c2
          WHERE ((c2.mascota_id = c.mascota_id) AND ((c2.fecha)::date >= c.proximo_control))))));


ALTER VIEW public.v_alertas_controles OWNER TO app_veterinaria;

--
-- Name: vacuna; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacuna (
    id integer NOT NULL,
    nombre character varying(80) NOT NULL,
    especie character varying(30) NOT NULL,
    intervalo_dias integer NOT NULL,
    CONSTRAINT vacuna_intervalo_dias_check CHECK ((intervalo_dias > 0))
);


ALTER TABLE public.vacuna OWNER TO postgres;

--
-- Name: v_alertas_vacunas; Type: VIEW; Schema: public; Owner: app_veterinaria
--

CREATE VIEW public.v_alertas_vacunas AS
 SELECT id AS aplicacion_id,
    mascota_id,
    mascota,
    propietario,
    telefono,
    vacuna,
    fecha_aplicacion,
    proxima_dosis,
    dias_restantes,
        CASE
            WHEN (dias_restantes < 0) THEN 'Vencida'::text
            WHEN (dias_restantes <= 7) THEN 'Por vencer'::text
            ELSE 'Al día'::text
        END AS estado
   FROM ( SELECT DISTINCT ON (av.mascota_id, av.vacuna_id) av.id,
            m.id AS mascota_id,
            m.nombre AS mascota,
            (((p.nombres)::text || ' '::text) || (p.apellidos)::text) AS propietario,
            p.telefono,
            va.nombre AS vacuna,
            av.fecha_aplicacion,
            av.proxima_dosis,
            (av.proxima_dosis - CURRENT_DATE) AS dias_restantes
           FROM (((public.aplicacion_vacuna av
             JOIN public.mascota m ON ((m.id = av.mascota_id)))
             JOIN public.propietario p ON ((p.id = m.propietario_id)))
             JOIN public.vacuna va ON ((va.id = av.vacuna_id)))
          WHERE (m.activo = true)
          ORDER BY av.mascota_id, av.vacuna_id, av.fecha_aplicacion DESC) sub;


ALTER VIEW public.v_alertas_vacunas OWNER TO app_veterinaria;

--
-- Name: vacuna_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.vacuna ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.vacuna_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: veterinario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.veterinario (
    id integer NOT NULL,
    usuario_id integer,
    nombre character varying(100) NOT NULL,
    tarjeta_profesional character varying(30) NOT NULL,
    especialidad character varying(60),
    telefono character varying(20)
);


ALTER TABLE public.veterinario OWNER TO postgres;

--
-- Name: veterinario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.veterinario ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.veterinario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Data for Name: aplicacion_vacuna; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.aplicacion_vacuna (id, mascota_id, vacuna_id, veterinario_id, fecha_aplicacion, proxima_dosis, lote) FROM stdin;
1	1	1	1	2025-11-14	2026-11-14	L-RAB-2025
2	3	2	2	2025-10-30	2026-10-30	L-PAR-2024
3	2	3	1	2026-10-10	2027-10-10	L-TRI-2026
4	5	5	2	2026-08-13	2026-11-11	L-DES-2026
5	4	4	1	2026-08-01	2027-08-01	L-LEU-2025
\.


--
-- Data for Name: consulta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.consulta (id, mascota_id, veterinario_id, fecha, motivo, diagnostico, costo, proximo_control) FROM stdin;
1	1	1	2026-10-10 15:52:59.523087	Control postoperatorio	Recuperación favorable de sutura	60000.00	2026-11-13
2	2	1	2026-10-25 15:52:59.523087	Episodio de vómito	Gastritis aguda alimentaria	85000.00	2026-11-07
3	3	2	2026-11-01 15:52:59.523087	Cojera pata trasera	Esguince leve de ligamento	120000.00	\N
4	4	1	2026-11-06 15:52:59.523087	Revisión general piel	Sin ectoparásitos, dermis sana	50000.00	\N
5	5	2	2026-11-08 15:52:59.523087	Alergia e irritación	Dermatitis atópica estacional	95000.00	2026-11-19
\.


--
-- Data for Name: mascota; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mascota (id, propietario_id, nombre, especie, raza, sexo, fecha_nacimiento, peso_kg, activo) FROM stdin;
1	1	Max	Perro	Labrador Retriever	M	2021-03-10	28.50	t
2	1	Luna	Gato	Criollo común	H	2022-07-01	4.20	t
3	2	Rocky	Perro	Bulldog Francés	M	2020-11-20	12.00	t
4	3	Mia	Gato	Siamés	H	2023-01-15	3.80	t
5	4	Toby	Perro	Beagle	M	2019-05-05	14.30	t
\.


--
-- Data for Name: propietario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.propietario (id, documento, nombres, apellidos, telefono, correo, direccion, creado_en) FROM stdin;
1	1010111	Carlos	Pérez Moreno	3001234567	carlos.perez@correo.com	Calle 10 # 20-30	2026-11-09 15:52:59.475021
2	1020222	María	López Salazar	3109876543	maria.lopez@correo.com	Carrera 15 # 45-12	2026-11-09 15:52:59.475021
3	1030333	Jorge	Ramírez Castro	3205554433	jorge.ramirez@correo.com	Diagonal 8 # 11-40	2026-11-09 15:52:59.475021
4	1040444	Ana	Torres Gaitán	3157778899	ana.torres@correo.com	Avenida 6 # 30-15	2026-11-09 15:52:59.475021
5	1050555	Luis	Castro Martínez	3014445566	luis.castro@correo.com	Calle 50 # 14-08	2026-11-09 15:52:59.475021
\.


--
-- Data for Name: tratamiento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tratamiento (id, consulta_id, medicamento, dosis, frecuencia, fecha_inicio, fecha_fin, estado) FROM stdin;
1	2	Omeprazol suspensión	10 mg	Cada 24 horas vía oral	2026-10-25	2026-11-04	Finalizado
2	3	Meloxicam gotas	1.5 mg	Cada 24 horas con comida	2026-11-01	2026-11-08	Finalizado
3	5	Prednisolona tabletas	5 mg	Cada 12 horas por 7 días	2026-11-08	2026-11-15	En curso
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario (id, nombre, correo, password_hash, rol, activo, creado_en) FROM stdin;
1	Administrador General	admin@vet.com	$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK	admin	t	2026-11-09 15:52:59.439006
2	Dra. Laura Gómez	laura@vet.com	$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK	veterinario	t	2026-11-09 15:52:59.439006
3	Dr. Andrés Ruiz	andres@vet.com	$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK	veterinario	t	2026-11-09 15:52:59.439006
4	Sara Díaz (Recepción)	sara@vet.com	$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK	recepcion	t	2026-11-09 15:52:59.439006
5	Pedro León (Recepción)	pedro@vet.com	$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK	recepcion	t	2026-11-09 15:52:59.439006
\.


--
-- Data for Name: vacuna; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vacuna (id, nombre, especie, intervalo_dias) FROM stdin;
1	Rabia	Perro	365
2	Parvovirus / Moquillo	Perro	365
3	Triple Felina	Gato	365
4	Leucemia Felina	Gato	365
5	Desparasitación Plus	Perro	90
\.


--
-- Data for Name: veterinario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.veterinario (id, usuario_id, nombre, tarjeta_profesional, especialidad, telefono) FROM stdin;
1	2	Laura Gómez	TP-1001	Medicina Felina y Canina	3001112233
2	3	Andrés Ruiz	TP-1002	Cirugía y Traumatología	3002223344
\.


--
-- Name: aplicacion_vacuna_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.aplicacion_vacuna_id_seq', 7, true);


--
-- Name: consulta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.consulta_id_seq', 7, true);


--
-- Name: mascota_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mascota_id_seq', 5, true);


--
-- Name: propietario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propietario_id_seq', 5, true);


--
-- Name: tratamiento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tratamiento_id_seq', 3, true);


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_id_seq', 5, true);


--
-- Name: vacuna_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vacuna_id_seq', 5, true);


--
-- Name: veterinario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.veterinario_id_seq', 2, true);


--
-- Name: aplicacion_vacuna aplicacion_vacuna_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aplicacion_vacuna
    ADD CONSTRAINT aplicacion_vacuna_pkey PRIMARY KEY (id);


--
-- Name: consulta consulta_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consulta
    ADD CONSTRAINT consulta_pkey PRIMARY KEY (id);


--
-- Name: mascota mascota_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mascota
    ADD CONSTRAINT mascota_pkey PRIMARY KEY (id);


--
-- Name: propietario propietario_documento_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propietario
    ADD CONSTRAINT propietario_documento_key UNIQUE (documento);


--
-- Name: propietario propietario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propietario
    ADD CONSTRAINT propietario_pkey PRIMARY KEY (id);


--
-- Name: tratamiento tratamiento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tratamiento
    ADD CONSTRAINT tratamiento_pkey PRIMARY KEY (id);


--
-- Name: vacuna uq_vacuna; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacuna
    ADD CONSTRAINT uq_vacuna UNIQUE (nombre, especie);


--
-- Name: consulta uq_veterinario_fecha; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consulta
    ADD CONSTRAINT uq_veterinario_fecha UNIQUE (veterinario_id, fecha);


--
-- Name: usuario usuario_correo_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_correo_key UNIQUE (correo);


--
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id);


--
-- Name: vacuna vacuna_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacuna
    ADD CONSTRAINT vacuna_pkey PRIMARY KEY (id);


--
-- Name: veterinario veterinario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinario
    ADD CONSTRAINT veterinario_pkey PRIMARY KEY (id);


--
-- Name: veterinario veterinario_tarjeta_profesional_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinario
    ADD CONSTRAINT veterinario_tarjeta_profesional_key UNIQUE (tarjeta_profesional);


--
-- Name: veterinario veterinario_usuario_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinario
    ADD CONSTRAINT veterinario_usuario_id_key UNIQUE (usuario_id);


--
-- Name: idx_aplicacion_proxima; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_aplicacion_proxima ON public.aplicacion_vacuna USING btree (proxima_dosis);


--
-- Name: idx_consulta_fecha; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_consulta_fecha ON public.consulta USING btree (fecha);


--
-- Name: idx_consulta_mascota; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_consulta_mascota ON public.consulta USING btree (mascota_id);


--
-- Name: idx_mascota_propietario; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_mascota_propietario ON public.mascota USING btree (propietario_id);


--
-- Name: aplicacion_vacuna aplicacion_vacuna_mascota_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aplicacion_vacuna
    ADD CONSTRAINT aplicacion_vacuna_mascota_id_fkey FOREIGN KEY (mascota_id) REFERENCES public.mascota(id) ON DELETE CASCADE;


--
-- Name: aplicacion_vacuna aplicacion_vacuna_vacuna_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aplicacion_vacuna
    ADD CONSTRAINT aplicacion_vacuna_vacuna_id_fkey FOREIGN KEY (vacuna_id) REFERENCES public.vacuna(id) ON DELETE RESTRICT;


--
-- Name: aplicacion_vacuna aplicacion_vacuna_veterinario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aplicacion_vacuna
    ADD CONSTRAINT aplicacion_vacuna_veterinario_id_fkey FOREIGN KEY (veterinario_id) REFERENCES public.veterinario(id) ON DELETE RESTRICT;


--
-- Name: consulta consulta_mascota_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consulta
    ADD CONSTRAINT consulta_mascota_id_fkey FOREIGN KEY (mascota_id) REFERENCES public.mascota(id) ON DELETE RESTRICT;


--
-- Name: consulta consulta_veterinario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consulta
    ADD CONSTRAINT consulta_veterinario_id_fkey FOREIGN KEY (veterinario_id) REFERENCES public.veterinario(id) ON DELETE RESTRICT;


--
-- Name: mascota mascota_propietario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mascota
    ADD CONSTRAINT mascota_propietario_id_fkey FOREIGN KEY (propietario_id) REFERENCES public.propietario(id) ON DELETE RESTRICT;


--
-- Name: tratamiento tratamiento_consulta_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tratamiento
    ADD CONSTRAINT tratamiento_consulta_id_fkey FOREIGN KEY (consulta_id) REFERENCES public.consulta(id) ON DELETE CASCADE;


--
-- Name: veterinario veterinario_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinario
    ADD CONSTRAINT veterinario_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuario(id) ON DELETE SET NULL;


--
-- Name: TABLE aplicacion_vacuna; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.aplicacion_vacuna TO app_veterinaria;


--
-- Name: SEQUENCE aplicacion_vacuna_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.aplicacion_vacuna_id_seq TO app_veterinaria;


--
-- Name: TABLE consulta; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.consulta TO app_veterinaria;


--
-- Name: SEQUENCE consulta_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.consulta_id_seq TO app_veterinaria;


--
-- Name: TABLE mascota; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mascota TO app_veterinaria;


--
-- Name: SEQUENCE mascota_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.mascota_id_seq TO app_veterinaria;


--
-- Name: TABLE propietario; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.propietario TO app_veterinaria;


--
-- Name: SEQUENCE propietario_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.propietario_id_seq TO app_veterinaria;


--
-- Name: TABLE tratamiento; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tratamiento TO app_veterinaria;


--
-- Name: SEQUENCE tratamiento_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tratamiento_id_seq TO app_veterinaria;


--
-- Name: TABLE usuario; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.usuario TO app_veterinaria;


--
-- Name: SEQUENCE usuario_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.usuario_id_seq TO app_veterinaria;


--
-- Name: TABLE vacuna; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacuna TO app_veterinaria;


--
-- Name: SEQUENCE vacuna_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vacuna_id_seq TO app_veterinaria;


--
-- Name: TABLE veterinario; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.veterinario TO app_veterinaria;


--
-- Name: SEQUENCE veterinario_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.veterinario_id_seq TO app_veterinaria;


--
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: public; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON SEQUENCES TO app_veterinaria;


--
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: public; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON TABLES TO app_veterinaria;


--
-- PostgreSQL database dump complete
--

\unrestrict 2h5dVaLALtUvfKdSmNNQSv41lcNGJke7WPrYw3Tq2ubQzTq8fX9RpI5N9aIEgV0

