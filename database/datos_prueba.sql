-- ==============================================================
-- DATOS DE PRUEBA: VETERINARIA DIGITAL
-- Archivo: database/datos_prueba.sql
-- Contraseña para todos los usuarios: Admin123*
-- Hash Bcrypt: $2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK
-- ==============================================================

-- 1. USUARIOS (Admin, Veterinarios y Recepcionistas)
INSERT INTO usuario (nombre, correo, password_hash, rol, activo) VALUES
 ('Administrador General', 'admin@vet.com',     '$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK', 'admin', true),
 ('Dra. Laura Gómez',      'laura@vet.com',     '$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK', 'veterinario', true),
 ('Dr. Andrés Ruiz',       'andres@vet.com',    '$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK', 'veterinario', true),
 ('Sara Díaz (Recepción)', 'sara@vet.com',      '$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK', 'recepcion', true),
 ('Pedro León (Recepción)','pedro@vet.com',     '$2y$10$PA75c/q8JKaEbZAjcKEPP.T7pOP7obGPRm4jKW8cfmaKgp.ZlLLgK', 'recepcion', true);

-- 2. VETERINARIOS
INSERT INTO veterinario (usuario_id, nombre, tarjeta_profesional, especialidad, telefono) VALUES
 (2, 'Laura Gómez', 'TP-1001', 'Medicina Felina y Canina', '3001112233'),
 (3, 'Andrés Ruiz', 'TP-1002', 'Cirugía y Traumatología',  '3002223344');

-- 3. PROPIETARIOS (Mínimo 5 registros)
INSERT INTO propietario (documento, nombres, apellidos, telefono, correo, direccion) VALUES
 ('1010111', 'Carlos', 'Pérez Moreno',    '3001234567', 'carlos.perez@correo.com',    'Calle 10 # 20-30'),
 ('1020222', 'María',  'López Salazar',   '3109876543', 'maria.lopez@correo.com',     'Carrera 15 # 45-12'),
 ('1030333', 'Jorge',  'Ramírez Castro',  '3205554433', 'jorge.ramirez@correo.com',   'Diagonal 8 # 11-40'),
 ('1040444', 'Ana',    'Torres Gaitán',   '3157778899', 'ana.torres@correo.com',      'Avenida 6 # 30-15'),
 ('1050555', 'Luis',   'Castro Martínez', '3014445566', 'luis.castro@correo.com',     'Calle 50 # 14-08');

-- 4. MASCOTAS (Mínimo 5 registros)
INSERT INTO mascota (propietario_id, nombre, especie, raza, sexo, fecha_nacimiento, peso_kg, activo) VALUES
 (1, 'Max',   'Perro', 'Labrador Retriever', 'M', '2021-03-10', 28.50, true),
 (1, 'Luna',  'Gato',  'Criollo común',      'H', '2022-07-01', 4.20, true),
 (2, 'Rocky', 'Perro', 'Bulldog Francés',    'M', '2020-11-20', 12.00, true),
 (3, 'Mia',   'Gato',  'Siamés',             'H', '2023-01-15', 3.80, true),
 (4, 'Toby',  'Perro', 'Beagle',             'M', '2019-05-05', 14.30, true);

-- 5. CATÁLOGO DE VACUNAS (Con intervalos en días)
INSERT INTO vacuna (nombre, especie, intervalo_dias) VALUES
 ('Rabia',                'Perro', 365),
 ('Parvovirus / Moquillo','Perro', 365),
 ('Triple Felina',        'Gato',  365),
 ('Leucemia Felina',      'Gato',  365),
 ('Desparasitación Plus', 'Perro', 90);

-- 6. APLICACIÓN DE VACUNAS (Fechas relativas para probar alertas activas)
INSERT INTO aplicacion_vacuna (mascota_id, vacuna_id, veterinario_id, fecha_aplicacion, proxima_dosis, lote) VALUES
 (1, 1, 1, CURRENT_DATE - 360, CURRENT_DATE + 5,   'L-RAB-2025'),  -- Estado: Por vencer (<= 7 días)
 (3, 2, 2, CURRENT_DATE - 375, CURRENT_DATE - 10,  'L-PAR-2024'),  -- Estado: Vencida (< 0 días)
 (2, 3, 1, CURRENT_DATE - 30,  CURRENT_DATE + 335, 'L-TRI-2026'),  -- Estado: Al día
 (5, 5, 2, CURRENT_DATE - 88,  CURRENT_DATE + 2,   'L-DES-2026'),  -- Estado: Por vencer
 (4, 4, 1, CURRENT_DATE - 100, CURRENT_DATE + 265, 'L-LEU-2025');  -- Estado: Al día

-- 7. CONSULTAS MÉDICAS (Mínimo 5 registros)
INSERT INTO consulta (mascota_id, veterinario_id, fecha, motivo, diagnostico, costo, proximo_control) VALUES
 (1, 1, CURRENT_TIMESTAMP - INTERVAL '30 days', 'Control postoperatorio', 'Recuperación favorable de sutura', 60000.00, CURRENT_DATE + 4),
 (2, 1, CURRENT_TIMESTAMP - INTERVAL '15 days', 'Episodio de vómito',    'Gastritis aguda alimentaria',        85000.00, CURRENT_DATE - 2),
 (3, 2, CURRENT_TIMESTAMP - INTERVAL '8 days',  'Cojera pata trasera',   'Esguince leve de ligamento',         120000.00, NULL),
 (4, 1, CURRENT_TIMESTAMP - INTERVAL '3 days',  'Revisión general piel', 'Sin ectoparásitos, dermis sana',    50000.00, NULL),
 (5, 2, CURRENT_TIMESTAMP - INTERVAL '1 day',   'Alergia e irritación',  'Dermatitis atópica estacional',      95000.00, CURRENT_DATE + 10);

-- 8. TRATAMIENTOS
INSERT INTO tratamiento (consulta_id, medicamento, dosis, frecuencia, fecha_inicio, fecha_fin, estado) VALUES
 (2, 'Omeprazol suspensión', '10 mg',  'Cada 24 horas vía oral', CURRENT_DATE - 15, CURRENT_DATE - 5, 'Finalizado'),
 (3, 'Meloxicam gotas',      '1.5 mg', 'Cada 24 horas con comida', CURRENT_DATE - 8,  CURRENT_DATE - 1, 'Finalizado'),
 (5, 'Prednisolona tabletas','5 mg',   'Cada 12 horas por 7 días', CURRENT_DATE - 1,  CURRENT_DATE + 6, 'En curso');
