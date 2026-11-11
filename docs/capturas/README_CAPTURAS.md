# Índice de Capturas y Evidencias — Veterinaria Digital (ADSO SENA)

Todas las capturas fueron generadas directamente sobre la ejecución real del sistema (Apache + PHP 8 + PostgreSQL 17), consultas SQL reales, resultados de Jira Cloud (`VET`) y pruebas automatizadas.

## Ubicación de Evidencias
- **Carpeta de imágenes:** `docs/capturas/`
- **Prototipo UX/UI en PDF (Sesión 4):** `docs/figma.pdf`

---

## Guía para Pegar en el Documento por Sesión

### Sesión 3 — Historias de usuario, backlog en Jira y trazabilidad
- `sesion3_jira_backlog_sprint.png` — Resumen de Épicas (`VET-1` a `VET-8`), Product Backlog, Sprint 1 (`105`) y 59 subtareas técnicas extraídas de Jira Cloud.
- `sesion3_matriz_trazabilidad.png` — Matriz completa de trazabilidad (Requisitos RF → Historias HU → Módulos → Casos de prueba CP).

### Sesión 4 — Diseño UX/UI y Prototipo Responsive
- `sesion4_ui_desktop_dashboard_1440px.png` — Vista Desktop (1440px).
- `sesion4_ui_movil_login_390px.png` — Login Responsive Móvil (390px).
- `sesion4_ui_movil_dashboard_390px.png` — Dashboard Responsive Móvil (390px).
- `../figma.pdf` — Documento PDF consolidado de diseño UX/UI (paleta de colores, tipografía y pantallas Desktop/Móvil).

### Sesión 5 — Modelo de datos en PostgreSQL
- `sesion5_diagrama_er.png` — Diagrama Entidad-Relación (8 tablas en 3FN con PK, FK, CHECK y UNIQUE).
- `sesion5_postgresql_tablas.png` — Verificación de tablas, vistas y conteo real de filas en PostgreSQL 17.

### Sesión 6 — SQL aplicado al negocio (11 cláusulas y vistas)
- `sesion6_sql_consultas_1_3.png` — Consultas `SELECT ... WHERE ILIKE ORDER BY`, `JOIN` múltiple y agregación `COUNT + SUM + GROUP BY`.
- `sesion6_sql_consultas_4_6.png` — Consulta `HAVING` y ejecución de vistas automáticas `v_alertas_vacunas` y `v_alertas_controles`.

### Sesiones 7 a 10 — Aplicación Web Full Stack MVC (PHP 8 + PostgreSQL)
- `sesion7_10_01_login.png` — Inicio de sesión seguro con CSRF y Bcrypt.
- `sesion7_10_02_dashboard.png` — Panel de control con KPIs, gráfico Chart.js y alertas críticas.
- `sesion7_10_03_propietarios.png` — Directorio de propietarios y protección RN-02.
- `sesion7_10_04_mascotas.png` — Directorio y filtros de mascotas.
- `sesion7_10_05_historial_clinico.png` — Expediente clínico unificado (Consultas + Carnet de vacunación).
- `sesion7_10_06_consultas.png` — Gestión de consultas médicas (con control de agenda RN-03).
- `sesion7_10_07_vacunas.png` — Registro de vacunación con cálculo automático de refuerzo (RN-01).
- `sesion7_10_08_alertas_whatsapp.png` — Centro de alertas automáticas y botones de notificación por WhatsApp.
- `sesion7_10_09_reportes_csv.png` — Módulo de reportes por rango de fechas y descarga CSV (UTF-8 BOM).
- `sesion7_10_10_usuarios_admin.png` — Gestión de usuarios y roles RBAC exclusiva del Administrador.

### Sesión 11 — Pruebas y Aseguramiento de Calidad (QA)
- `sesion11_pruebas_automatizadas.png` — Ejecución CLI de `tests/ReglasTest.php` con 6/6 pruebas aprobadas (RN-01, RN-02, RN-03, XSS, WhatsApp y Usuarios).

### Sesión 12 — GitHub y Respaldo Final de Base de Datos
- `sesion12_git_commits_y_backup.png` — Historial completo de commits en GitHub (`johanm-dev/adso-veterinaria`) y respaldo `database/backup_final.sql` (`pg_dump`).
