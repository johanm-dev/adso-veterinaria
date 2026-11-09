# Matriz de Pruebas y Aseguramiento de Calidad (QA)

**Proyecto:** Veterinaria Digital  
**Institución:** SENA - Centro de Servicios Financieros / ADSO  
**Aprendiz:** Johan Muñoz  
**Instructora:** Joan Castillo Celis  
**Tipo de Pruebas:** Funcionales, Reglas de Negocio, Seguridad, Integración y Regresión  
**Entorno de Ejecución:** PHP 8+ Native, PostgreSQL 17, Apache / CLI  
**Total Casos de Prueba:** 22 Casos

---

## 1. Resumen Ejecutivo de Ejecución

| Categoría | Casos Diseñados | Pasaron | Fallaron | % Cobertura / Éxito |
|---|---|---|---|---|
| **Seguridad y Control de Acceso** | 4 | 4 | 0 | 100% |
| **Reglas de Negocio (RN-01 a RN-04)** | 6 | 6 | 0 | 100% |
| **Gestión de Propietarios y Pacientes** | 4 | 4 | 0 | 100% |
| **Consultas Médicas y Agenda** | 3 | 3 | 0 | 100% |
| **Vacunación y Motor de Alertas** | 3 | 3 | 0 | 100% |
| **Reportes y Exportación** | 2 | 2 | 0 | 100% |
| **TOTAL** | **22** | **22** | **0** | **100%** |

---

## 2. Matriz Detallada de Casos de Prueba

| ID Caso | Módulo | Requisito / Regla | Descripción del Escenario | Datos de Entrada | Resultado Esperado | Resultado Obtenido | Estado |
|---|---|---|---|---|---|---|---|
| **CP-01** | Autenticación | RF-01 / RNF-01 | Login con credenciales válidas de Administrador | `admin@veterinaria.com`, `Admin123*` | Autenticación exitosa, regeneración de Session ID, redirección a `/dashboard` con mensaje de bienvenida. | Acceso concedido al Dashboard con sesión activa. | **APROBADO** |
| **CP-02** | Autenticación | RF-01 / RNF-01 | Login con contraseña errónea | `admin@veterinaria.com`, `ClaveInvalida999` | Rechazo de login, mensaje flash "Credenciales inválidas", permanencia en vista login. | Mensaje de error visible, no se genera sesión. | **APROBADO** |
| **CP-03** | Seguridad | RNF-02 | Verificación de protección contra ataques CSRF | Petición POST sin token `csrf_token` o token alterado | HTTP 403 Forbidden y detención inmediata del procesamiento. | Solicitud bloqueada con código 403. | **APROBADO** |
| **CP-04** | Seguridad | RNF-03 | Sanitización de entradas contra Cross-Site Scripting (XSS) | `<script>alert('pwned');</script>` | Helper `e()` transforma caracteres especiales a entidades HTML seguras. | Caracteres neutralizados sin ejecución de scripts. | **APROBADO** |
| **CP-05** | Reglas Negocio | RN-01 | Cálculo automático de próxima dosis de vacunación | Fecha: `2026-06-01`, Vacuna: Rabia (intervalo 365 días) | Próxima dosis calculada en `2027-06-01` automáticamente sin intervención manual. | Próxima dosis registrada exactamente en `2027-06-01`. | **APROBADO** |
| **CP-06** | Reglas Negocio | RN-01 | Coherencia cronológica de vacunas | Fecha aplicación posterior a próxima dosis | El motor PostgreSQL o la app arroja violación de restricción `CHECK (proxima_dosis > fecha_aplicacion)`. | Inserción rechazada si las fechas son ilógicas. | **APROBADO** |
| **CP-07** | Reglas Negocio | RN-02 | Eliminación de propietario con mascotas activas | Propietario ID 1 con mascotas asociadas | Bloqueo por regla RN-02 (`DomainException` / `ON DELETE RESTRICT`), alerta explicativa. | Bloqueo exitoso; el propietario no se elimina. | **APROBADO** |
| **CP-08** | Reglas Negocio | RN-02 | Eliminación de propietario sin mascotas | Propietario recién creado sin animales | Eliminación exitosa y confirmación al usuario. | Registro eliminado de la base de datos sin errores. | **APROBADO** |
| **CP-09** | Reglas Negocio | RN-03 | Conflicto de horario médico en consulta veterinaria | Veterinario ID 1, Fecha `2026-11-20 10:00:00` (ya ocupada) | Bloqueo por regla RN-03 (`DomainException` / `uq_veterinario_fecha`), mensaje de agenda ocupada. | Conflicto bloqueado exitosamente; no se duplica la cita. | **APROBADO** |
| **CP-10** | Reglas Negocio | RN-03 | Asignación de cita en horario disponible para el médico | Veterinario ID 1, Fecha `2026-11-20 11:30:00` (libre) | Registro exitoso de la consulta. | Consulta médica guardada con folio e ID. | **APROBADO** |
| **CP-11** | Propietarios | RF-02 | Registro de propietario con datos completos | Doc: `1020304050`, Nombres: Carolina, Tel: `3001234567` | Registro exitoso en tabla `propietario` y redirección a listado. | Creado exitosamente en PostgreSQL. | **APROBADO** |
| **CP-12** | Propietarios | RF-02 | Intento de registrar propietario con documento duplicado | Mismo número de documento ya existente | Error por violación de clave única `UNIQUE`, aviso en pantalla. | Excepción capturada, aviso preventivo. | **APROBADO** |
| **CP-13** | Mascotas | RF-03 | Registro de mascota asociada a propietario | Propietario ID 1, Rocky, Perro, Macho, Peso 14.5 kg | Registro en tabla `mascota`, vinculación referencial correcta. | Mascota creada y visible en historial clínico. | **APROBADO** |
| **CP-14** | Mascotas | RF-03 | Inactivación lógica de mascota | Clic en inactivar mascota activa | Campo `activo` cambia a `FALSE`, mascota no aparece en búsquedas generales pero historial se conserva. | Inactivación confirmada sin borrar registros. | **APROBADO** |
| **CP-15** | Historial | RF-04 | Consulta de expediente clínico consolidado | Mascota ID 1 | Despliegue de datos generales, historial de consultas y carnet de vacunas. | Visualización íntegra en interfaz unificada. | **APROBADO** |
| **CP-16** | Consultas | RF-05 | Registro de acto clínico con costo y diagnóstico | Motivo: 'Control anual', Costo: 50000 COP | Consulta registrada con médico tratante y costo liquidado. | Datos almacenados y reflejados en KPIs de Dashboard. | **APROBADO** |
| **CP-17** | Vacunación | RF-06 | Aplicación de biológico con lote y dosis | Mascota ID 2, Vacuna Triple Felina, Lote `TF-99` | Inserción en `aplicacion_vacuna`, próxima fecha calculada automáticamente. | Registro correcto en carnet digital. | **APROBADO** |
| **CP-18** | Alertas | RF-07 | Vista de alertas de vacunas por vencer (≤ 7 días) | Vista `v_alertas_vacunas` en PostgreSQL | Retorna únicamente mascotas con vacunas en rango crítico o vencidas. | Filtro preciso con badges 'Por vencer' / 'Vencida'. | **APROBADO** |
| **CP-19** | Alertas | RF-07 | Generación de enlace directo a WhatsApp | Teléfono: `3001234567`, Paciente: Firulais | URL estructurada con prefijo país 57 y texto codificado listo para envío. | Enlace funcional que abre chat de WhatsApp Web/App. | **APROBADO** |
| **CP-20** | Dashboard | RF-08 | Cálculo dinámico de KPIs y gráfico de especies | Carga inicial del panel | Totales de pacientes, propietarios, consultas y desglose Chart.js. | Indicadores calculados en tiempo real. | **APROBADO** |
| **CP-21** | Reportes | RF-09 | Filtrado de consultas por rango de fechas | Rango: mes en curso | Presentación en tabla de consultas correspondientes al período. | Registros coincidentes mostrados. | **APROBADO** |
| **CP-22** | Reportes | RF-09 | Exportación a CSV con formato compatible Excel | Clic en "Descargar CSV" | Archivo descargado con encabezados, delimitador `;` y codificación UTF-8 BOM. | Apertura nativa en Excel con tildes y eñes legibles. | **APROBADO** |
