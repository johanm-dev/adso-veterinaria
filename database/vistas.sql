-- ==============================================================
-- VISTAS DE NEGOCIO: ALERTAS AUTOMÁTICAS (database/vistas.sql)
-- Funcionalidad Innovadora de Veterinaria Digital
-- ==============================================================

-- Vista 1: Alertas de Vacunación (Vencidas, Por vencer y Al día)
-- Utiliza DISTINCT ON de PostgreSQL para tomar únicamente la última aplicación de cada vacuna por mascota
CREATE OR REPLACE VIEW v_alertas_vacunas AS
SELECT
    sub.id AS aplicacion_id,
    sub.mascota_id,
    sub.mascota,
    sub.propietario,
    sub.telefono,
    sub.vacuna,
    sub.fecha_aplicacion,
    sub.proxima_dosis,
    sub.dias_restantes,
    CASE 
        WHEN sub.dias_restantes < 0 THEN 'Vencida'
        WHEN sub.dias_restantes <= 7 THEN 'Por vencer'
        ELSE 'Al día'
    END AS estado
FROM (
    SELECT DISTINCT ON (av.mascota_id, av.vacuna_id)
        av.id,
        m.id AS mascota_id,
        m.nombre AS mascota,
        p.nombres || ' ' || p.apellidos AS propietario,
        p.telefono,
        va.nombre AS vacuna,
        av.fecha_aplicacion,
        av.proxima_dosis,
        (av.proxima_dosis - CURRENT_DATE) AS dias_restantes
    FROM aplicacion_vacuna av
    JOIN mascota m     ON m.id = av.mascota_id
    JOIN propietario p ON p.id = m.propietario_id
    JOIN vacuna va     ON va.id = av.vacuna_id
    WHERE m.activo = TRUE
    ORDER BY av.mascota_id, av.vacuna_id, av.fecha_aplicacion DESC
) sub;

-- Vista 2: Alertas de Controles Médicos Pendientes
-- Controles próximos en 7 días o ya vencidos que no hayan tenido una consulta posterior
CREATE OR REPLACE VIEW v_alertas_controles AS
SELECT 
    c.id AS consulta_id,
    m.id AS mascota_id,
    m.nombre AS mascota,
    p.nombres || ' ' || p.apellidos AS propietario,
    p.telefono,
    c.proximo_control,
    (c.proximo_control - CURRENT_DATE) AS dias_restantes,
    CASE 
        WHEN (c.proximo_control - CURRENT_DATE) < 0 THEN 'Vencido'
        ELSE 'Por asistir'
    END AS estado
FROM consulta c
JOIN mascota m     ON m.id = c.mascota_id
JOIN propietario p ON p.id = m.propietario_id
WHERE c.proximo_control IS NOT NULL
  AND c.proximo_control <= CURRENT_DATE + 7
  AND NOT EXISTS (
      SELECT 1 FROM consulta c2
      WHERE c2.mascota_id = c.mascota_id 
        AND c2.fecha::date >= c.proximo_control
  );
