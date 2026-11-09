# Especificación de Diseño UX/UI - Sistema Veterinaria Digital

**Proyecto:** Veterinaria Digital - Software Integral de Gestión Veterinaria  
**Institución:** SENA - Centro de Servicios Financieros / ADSO  
**Aprendiz:** Johan Muñoz  
**Instructora:** Joan Castillo Celis  
**Herramienta de Maquetación y Prototipado:** Figma / Wireframes Bootstrap 5  
**Estándar de Diseño:** Mobile First y Responsive Desktop (1440px / 390px)

---

## 1. Guía de Estilos y Sistema de Diseño (Design System)

### 1.1 Paleta Cromática
- **Primario (Azul Veterinario / Confianza):** `#0d6efd` / `#0a58ca` (Botones de acción, enlaces activos, branding).
- **Secundario (Gris Neutro / Estructura):** `#6c757d` / `#495057` (Subtítulos, bordes, estados secundarios).
- **Éxito (Verde Salud / Al día / Completado):** `#198754` (Badges de estado "Al día", tratamientos finalizados).
- **Alerta / Precaución (Amarillo Ámbar / Próximo a Vencer):** `#ffc107` (Alertas de vacunas en menos de 5 días).
- **Peligro / Crítico (Rojo Alerta / Vencido / Cancelado):** `#dc3545` (Vacunas vencidas, citas canceladas, eliminación).
- **Fondo General (Background Canvas):** `#f8f9fa` (Fondo suave para evitar fatiga visual en clínicas).
- **Superficie de Tarjetas (Card Surface):** `#ffffff` con sombra sutil `box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075)`.

### 1.2 Tipografía
- **Fuente Principal:** Inter / Roboto / System Sans-serif.
- **Títulos (H1):** 1.75rem (28px), Bold 700.
- **Subtítulos (H2-H3):** 1.25rem (20px), Semi-bold 600.
- **Cuerpo de Texto:** 0.95rem (15px), Regular 400, interlineado 1.5.
- **Micro-textos y Badges:** 0.75rem (12px), Medium 500 / Bold 600.

---

## 2. Inventario de Pantallas y Wireframes (10 Pantallas)

### Pantalla 1: Login / Inicio de Sesión (`/login`)
- **Objetivo:** Autenticación segura de usuarios (Administrador, Veterinario, Recepcionista).
- **Componentes:**
  - Card central flotante con logotipo de Veterinaria Digital.
  - Campo Email con validación de formato.
  - Campo Contraseña con botón mostrar/ocultar.
  - Token de protección CSRF oculto.
  - Botón "Iniciar Sesión" con efecto de carga.
  - Alerta de error para credenciales inválidas o cuenta inactiva.

### Pantalla 2: Dashboard General (`/dashboard`)
- **Objetivo:** Panel de control con métricas clave y resúmenes ejecutivos en tiempo real.
- **Componentes:**
  - 4 Cards KPI superiores: Total Pacientes Registrados, Consultas de la Semana, Alertas Críticas de Vacunación, Total Facturado / Atendido.
  - Gráfico interactivo (Chart.js): Consultas por mes y distribución de especies (Canino, Felino, etc.).
  - Tabla de "Próximos Controles y Vacunas por Vencer" con accesos directos de notificación.
  - Botones de acceso rápido: "Nueva Consulta", "Registrar Mascota", "Ver Alertas".

### Pantalla 3: Gestión de Propietarios (`/propietarios`)
- **Objetivo:** Directorio y administración integral de clientes/dueños de mascotas.
- **Componentes:**
  - Barra de búsqueda reactiva (filtro por nombre, documento o teléfono).
  - Botón modal "Registrar Propietario".
  - Tabla con columnas: Documento, Nombre Completo, Teléfono WhatsApp, Correo, Mascotas Vinculadas, Acciones (Editar, Detalle, Eliminar).
  - Modal de validación de regla de negocio RN-02 (impide eliminación si tiene mascotas activas).

### Pantalla 4: Gestión de Mascotas / Pacientes (`/mascotas`)
- **Objetivo:** Control del censo de animales atendidos en la clínica.
- **Componentes:**
  - Buscador predictivo por nombre de mascota o documento del dueño.
  - Filtro por especie (Canino, Felino, Ave, Roedor, Otro) y estado (Activo / Inactivo).
  - Listado en tarjetas o tabla con foto/icono de raza, edad calculada, peso y dueño responsable.
  - Botón directo para "Abrir Historial Clínico".

### Pantalla 5: Historial Clínico y Ficha del Paciente (`/mascotas/historial?id=X`)
- **Objetivo:** Expediente médico consolidado único de la mascota.
- **Componentes:**
  - Encabezado con datos generales (Nombre, Especie, Raza, Sexo, Fecha Nacimiento, Peso Actual, Datos del Propietario).
  - Pestaña 1: Consultas Médicas cronológicas (Motivo, Síntomas, Diagnóstico, Veterinario a cargo).
  - Pestaña 2: Carnet de Vacunación Digital (Vacuna, Lote, Fecha de aplicación, Próxima dosis, Estado).
  - Pestaña 3: Tratamientos y Fórmulas Médicas (Medicamentos, Dosis, Frecuencia, Duración).
  - Botón de impresión / exportación rápida del carnet.

### Pantalla 6: Registro y Gestión de Consultas (`/consultas`)
- **Objetivo:** Agenda y consignación de actos médicos veterinarios.
- **Componentes:**
  - Formulario de nueva consulta: Selección de mascota (autocomplete), veterinario asignado, fecha y hora, motivo, peso, temperatura, frecuencia cardíaca, diagnóstico y observaciones.
  - Validación de regla de negocio RN-03: Alerta en pantalla si el veterinario ya tiene una consulta programada en la misma fecha y hora.
  - Listado de consultas recientes con badge de estado (Completada, En progreso, Cancelada).

### Pantalla 7: Módulo de Vacunación (`/vacunas`)
- **Objetivo:** Aplicación de biológicos y calendarización automática de refuerzos.
- **Componentes:**
  - Formulario de aplicación: Mascota, Vacuna del catálogo, Fecha de aplicación, Número de Lote.
  - Aplicación automática de regla RN-01: Cálculo automático en tiempo real de la fecha para la próxima dosis según la periodicidad en días de la vacuna.
  - Listado de biológicos disponibles con stock y periodicidad estándar.

### Pantalla 8: Centro de Alertas y Notificaciones (`/alertas`)
- **Objetivo:** Monitor de vencimientos y disparador de mensajería preventiva a propietarios.
- **Componentes:**
  - Segmentador: "Vacunas por vencer (≤ 5 días)", "Vacunas vencidas", "Controles médicos programados".
  - Tabla informativa con cálculo de días restantes o días de retraso.
  - Botón de acción directa "Notificar por WhatsApp": genera el enlace universal `https://api.whatsapp.com/send?phone=...&text=...` con el mensaje pre-redactado personalizado.

### Pantalla 9: Módulo de Reportes y Exportación (`/reportes`)
- **Objetivo:** Generación de información gerencial y respaldo plano.
- **Componentes:**
  - Selector de rango de fechas (Fecha Inicio - Fecha Fin).
  - Selector de tipo de reporte: Consultas atendidas, Vacunas aplicadas, Nuevos pacientes registrados.
  - Vista previa de datos en tabla responsiva.
  - Botón de descarga en formato CSV codificado en UTF-8 con BOM para apertura nativa en Microsoft Excel sin problemas de tildes ni caracteres especiales.

### Pantalla 10: Modales del Sistema y Retroalimentación
- **Objetivo:** Diálogos modales y componentes emergentes de confirmación y feedback.
- **Componentes:**
  - Modal de Confirmación de Borrado Lógico con mensaje explicativo.
  - Mensajes Flash flotantes (Toast / Alerts): Éxito (verde), Error (rojo), Advertencia (amarillo).
  - Modal de Detalle Rápido de Mascota sin abandonar la pantalla actual.

---

## 3. Matriz de Adaptabilidad Responsiva (Responsive Grid)

| Componente | Desktop (≥ 992px) | Tablet (768px - 991px) | Móvil (≤ 767px) |
|---|---|---|---|
| Menú de Navegación | Sidebar lateral fijo / Navbar superior completo | Navbar colapsable con hamburguesa | Menú hamburguesa superior compacto |
| Cards de KPIs | 4 columnas (`col-lg-3`) | 2 columnas (`col-md-6`) | 1 columna apilada (`col-12`) |
| Tablas de Datos | Tabla completa con todas las columnas | Tabla con scroll horizontal `table-responsive` | Tarjetas compactas o scroll horizontal |
| Formularios | 2 o 3 columnas agrupadas lógicamente | 2 columnas | 1 columna única para fácil digitación |
