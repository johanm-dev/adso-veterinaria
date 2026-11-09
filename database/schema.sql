-- ==============================================================
-- SISTEMA VETERINARIA DIGITAL - ESQUEMA RELACIONAL POSTGRESQL
-- Archivo: database/schema.sql
-- Motor: PostgreSQL 14 / 16 / 17 (ANSI SQL)
-- Aprendiz: Johan Muñoz - ADSO SENA
-- ==============================================================

-- Limpieza previa en orden referencial inverso
DROP TABLE IF EXISTS tratamiento CASCADE;
DROP TABLE IF EXISTS aplicacion_vacuna CASCADE;
DROP TABLE IF EXISTS consulta CASCADE;
DROP TABLE IF EXISTS vacuna CASCADE;
DROP TABLE IF EXISTS mascota CASCADE;
DROP TABLE IF EXISTS propietario CASCADE;
DROP TABLE IF EXISTS veterinario CASCADE;
DROP TABLE IF EXISTS usuario CASCADE;

-- --------------------------------------------------------------
-- 1. TABLA: usuario (Seguridad, Autenticación y RBAC)
-- --------------------------------------------------------------
CREATE TABLE usuario (
  id            INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nombre        VARCHAR(100) NOT NULL,
  correo        VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol           VARCHAR(20)  NOT NULL CHECK (rol IN ('admin', 'veterinario', 'recepcion')),
  activo        BOOLEAN      NOT NULL DEFAULT TRUE,
  creado_en     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------------
-- 2. TABLA: veterinario (Personal Asistencial)
-- --------------------------------------------------------------
CREATE TABLE veterinario (
  id                  INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  usuario_id          INT UNIQUE REFERENCES usuario(id) ON DELETE SET NULL,
  nombre              VARCHAR(100) NOT NULL,
  tarjeta_profesional VARCHAR(30)  NOT NULL UNIQUE,
  especialidad        VARCHAR(60),
  telefono            VARCHAR(20)
);

-- --------------------------------------------------------------
-- 3. TABLA: propietario (Clientes / Dueños)
-- --------------------------------------------------------------
CREATE TABLE propietario (
  id        INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  documento VARCHAR(20)  NOT NULL UNIQUE,
  nombres   VARCHAR(80)  NOT NULL,
  apellidos VARCHAR(80)  NOT NULL,
  telefono  VARCHAR(20)  NOT NULL,
  correo    VARCHAR(120),
  direccion VARCHAR(150),
  creado_en TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------------
-- 4. TABLA: mascota (Pacientes)
-- Regla RN-02: ON DELETE RESTRICT (no se borra propietario si tiene mascotas)
-- --------------------------------------------------------------
CREATE TABLE mascota (
  id               INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  propietario_id   INT NOT NULL REFERENCES propietario(id) ON DELETE RESTRICT,
  nombre           VARCHAR(60) NOT NULL,
  especie          VARCHAR(30) NOT NULL CHECK (especie IN ('Perro', 'Gato', 'Ave', 'Conejo', 'Otro')),
  raza             VARCHAR(60),
  sexo             CHAR(1)     NOT NULL CHECK (sexo IN ('M', 'H')),
  fecha_nacimiento DATE,
  peso_kg          NUMERIC(6,2) CHECK (peso_kg > 0),
  activo           BOOLEAN NOT NULL DEFAULT TRUE
);

-- --------------------------------------------------------------
-- 5. TABLA: vacuna (Catálogo Maestro de Biológicos e Intervalos)
-- --------------------------------------------------------------
CREATE TABLE vacuna (
  id             INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nombre         VARCHAR(80) NOT NULL,
  especie        VARCHAR(30) NOT NULL,
  intervalo_dias INT NOT NULL CHECK (intervalo_dias > 0),
  CONSTRAINT uq_vacuna UNIQUE (nombre, especie)
);

-- --------------------------------------------------------------
-- 6. TABLA: consulta (Acto Médico y Seguimiento Clínico)
-- Regla RN-03: uq_veterinario_fecha evita que un médico tenga dos citas a la vez
-- --------------------------------------------------------------
CREATE TABLE consulta (
  id              INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  mascota_id      INT NOT NULL REFERENCES mascota(id) ON DELETE RESTRICT,
  veterinario_id  INT NOT NULL REFERENCES veterinario(id) ON DELETE RESTRICT,
  fecha           TIMESTAMP NOT NULL,
  motivo          VARCHAR(200) NOT NULL,
  diagnostico     TEXT,
  costo           NUMERIC(10,2) NOT NULL DEFAULT 0.00 CHECK (costo >= 0),
  proximo_control DATE,
  CONSTRAINT uq_veterinario_fecha UNIQUE (veterinario_id, fecha)
);

-- --------------------------------------------------------------
-- 7. TABLA: aplicacion_vacuna (Resuelve relación N:M mascota <-> vacuna)
-- Regla RN-01: CHECK (proxima_dosis > fecha_aplicacion)
-- --------------------------------------------------------------
CREATE TABLE aplicacion_vacuna (
  id               INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  mascota_id       INT NOT NULL REFERENCES mascota(id) ON DELETE CASCADE,
  vacuna_id        INT NOT NULL REFERENCES vacuna(id) ON DELETE RESTRICT,
  veterinario_id   INT NOT NULL REFERENCES veterinario(id) ON DELETE RESTRICT,
  fecha_aplicacion DATE NOT NULL,
  proxima_dosis    DATE NOT NULL,
  lote             VARCHAR(30),
  CHECK (proxima_dosis > fecha_aplicacion)
);

-- --------------------------------------------------------------
-- 8. TABLA: tratamiento (Prescripción Médica)
-- --------------------------------------------------------------
CREATE TABLE tratamiento (
  id           INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  consulta_id  INT NOT NULL REFERENCES consulta(id) ON DELETE CASCADE,
  medicamento  VARCHAR(100) NOT NULL,
  dosis        VARCHAR(60)  NOT NULL,
  frecuencia   VARCHAR(60)  NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin    DATE,
  estado       VARCHAR(20) NOT NULL DEFAULT 'En curso'
               CHECK (estado IN ('En curso', 'Finalizado', 'Suspendido')),
  CHECK (fecha_fin IS NULL OR fecha_fin >= fecha_inicio)
);

-- --------------------------------------------------------------
-- ÍNDICES PARA OPTIMIZACIÓN DE BÚSQUEDAS Y ALERTAS
-- --------------------------------------------------------------
CREATE INDEX idx_mascota_propietario ON mascota(propietario_id);
CREATE INDEX idx_consulta_mascota    ON consulta(mascota_id);
CREATE INDEX idx_consulta_fecha      ON consulta(fecha);
CREATE INDEX idx_aplicacion_proxima  ON aplicacion_vacuna(proxima_dosis);
