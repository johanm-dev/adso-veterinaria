-- ==============================================================
-- CONSULTAS SQL DEL NEGOCIO (database/consultas.sql)
-- Cumple con las 11 cláusulas obligatorias de la Sesión 6:
-- SELECT, INSERT, UPDATE, DELETE, WHERE, ORDER BY,
-- JOIN, COUNT, SUM, GROUP BY, HAVING
-- Motor: PostgreSQL 14+ / 16+ / 17+
-- ==============================================================

-- 1. INSERT con RETURNING
-- Registra un nuevo propietario y devuelve de inmediato el identificador generado
INSERT INTO propietario (documento, nombres, apellidos, telefono, correo, direccion)
VALUES ('1099888', 'Diana', 'Mora Rincón', '3120001122', 'diana.mora@correo.com', 'Calle 100 # 15-20')
RETURNING id;

-- 2. SELECT con WHERE y ORDER BY (búsqueda insensible con ILIKE)
-- Filtra mascotas activas que coincidan con un criterio de búsqueda
SELECT id, nombre, especie, raza, peso_kg
FROM mascota
WHERE nombre ILIKE '%ma%' AND activo = TRUE
ORDER BY nombre ASC;

-- 3. UPDATE con WHERE
-- Modificación controlada del peso de una mascota
UPDATE mascota
SET peso_kg = 29.20
WHERE id = 1;

-- 4. DELETE con WHERE
-- Eliminación segura de un tratamiento farmacológico
DELETE FROM tratamiento
WHERE id = 1 AND estado = 'Finalizado';

-- 5. JOIN múltiple (INNER JOIN entre consulta, mascota, propietario y veterinario)
-- Consulta del historial clínico de un paciente específico
SELECT 
    c.id AS consulta_id,
    c.fecha,
    m.nombre AS mascota,
    p.nombres || ' ' || p.apellidos AS propietario,
    v.nombre AS veterinario,
    c.motivo,
    c.diagnostico,
    c.costo
FROM consulta c
JOIN mascota m     ON m.id = c.mascota_id
JOIN propietario p ON p.id = m.propietario_id
JOIN veterinario v ON v.id = c.veterinario_id
WHERE m.id = 1
ORDER BY c.fecha DESC;

-- 6. COUNT + GROUP BY
-- Distribución de pacientes activos agrupados por especie
SELECT especie, COUNT(*) AS total_mascotas
FROM mascota
WHERE activo = TRUE
GROUP BY especie
ORDER BY total_mascotas DESC;

-- 7. SUM + GROUP BY (con TO_CHAR para agrupar por mes)
-- Reporte de recaudación y facturación acumulada mensual
SELECT 
    TO_CHAR(fecha, 'YYYY-MM') AS mes,
    COUNT(id) AS total_consultas,
    SUM(costo) AS ingresos_totales
FROM consulta
GROUP BY TO_CHAR(fecha, 'YYYY-MM')
ORDER BY mes DESC;

-- 8. HAVING (filtrado sobre funciones agregadas)
-- Identificar propietarios que tienen más de una mascota registrada
SELECT 
    p.id,
    p.documento,
    p.nombres || ' ' || p.apellidos AS propietario,
    p.telefono,
    COUNT(m.id) AS total_mascotas
FROM propietario p
JOIN mascota m ON m.propietario_id = p.id
WHERE m.activo = TRUE
GROUP BY p.id, p.documento, p.nombres, p.apellidos, p.telefono
HAVING COUNT(m.id) > 1
ORDER BY total_mascotas DESC;

-- 9. LEFT JOIN + COALESCE + SUM + GROUP BY
-- Rendimiento asistencial y financiero discriminado por médico veterinario
SELECT 
    v.id,
    v.nombre AS veterinario,
    v.tarjeta_profesional,
    COUNT(c.id) AS consultas_atendidas,
    COALESCE(SUM(c.costo), 0.00) AS total_recaudado
FROM veterinario v
LEFT JOIN consulta c ON c.veterinario_id = v.id
GROUP BY v.id, v.nombre, v.tarjeta_profesional
ORDER BY total_recaudado DESC;

-- 10. Consulta de Alertas Activas (Alimentación del Dashboard)
SELECT * FROM v_alertas_vacunas
WHERE estado IN ('Vencida', 'Por vencer')
ORDER BY dias_restantes ASC;
