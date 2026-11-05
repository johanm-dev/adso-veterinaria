# Documento de Especificación de Requisitos de Software
**Referencia:** ISO/IEC/IEEE 29148:2018 (Ingeniería de Requisitos)  
**Proyecto N.º 2:** Veterinaria Digital  
**Aprendiz:** Johan Muñoz  
**Tecnólogo en Análisis y Desarrollo de Software (ADSO)** · SENA  
**Instructora:** Joan Castillo Celis  
**Fecha:** Noviembre 2026  

---

## 1. Definición del Problema

### 1.1 Descripción del Problema
En el *Centro Veterinario & Pet Shop San Roque*, la administración operativa y clínica se fundamenta en un modelo analógico y semi-manual sustentado en carpetas de cartulina almacenadas en un archivador físico rodante, carnés de vacunación en papel y libros contables manuscritos.

A partir del estudio empírico realizado con 18 propietarios y el personal de la clínica, se identificaron fallas críticas que impactan negativamente la calidad asistencial y la rentabilidad del negocio:
1. **Tiempos de espera excesivos en recepción:** El **83.3%** de los clientes experimenta demoras superiores a los 5 minutos (y un **44.4%** espera más de 10 minutos) únicamente mientras la recepcionista localiza manualmente la ficha física del paciente, generando congestión en sala de espera.
2. **Pérdida de trazabilidad médica y extravío de carnés:** El **72.2%** de los dueños admite haber extraviado, dañado o no saber dónde está el carné de vacunación físico. Esto impide certificar dosis previas, marcas y lotes de biológicos aplicados.
3. **Falta de continuidad en esquemas preventivos:** El **77.8%** de los propietarios ha olvidado o retrasado la fecha de vacunación o desparasitación de su mascota por ausencia de un canal activo de recordación, pese a que el **88.9%** manifiesta su deseo explícito de recibir recordatorios automáticos.
4. **Conflictos de agendamiento:** Cruces de citas donde dos pacientes resultan programados con el mismo médico veterinario en idéntica franja horaria debido al registro desarticulado en libretas de papel.
5. **Opacidad financiera:** Dificultad para consolidar ingresos mensuales y liquidar el rendimiento asistencial por profesional sin recurrir al conteo manual de recibos.

### 1.2 Pregunta Problema
> ¿De qué manera la implementación de un sistema web integral bajo arquitectura MVC con base de datos relacional PostgreSQL puede optimizar la gestión del historial clínico, el control de consultas y la puntualidad en los esquemas de vacunación en el Centro Veterinario San Roque?

### 1.3 Justificación
Desde la perspectiva operativa, la transición de expedientes en papel a un repositorio digital centralizado reduce los tiempos de consulta a menos de 3 segundos, aumentando la capacidad de atención y disminuyendo el estrés en sala de espera.

Desde el punto de vista clínico y de salud pública veterinaria, garantizar el cumplimiento oportuno de biológicos esenciales (rabia, parvovirus, moquillo, leucemia felina) previene brotes epidemiológicos y zoonosis. El **88.9%** de los usuarios respalda el acceso en pantalla a la historia clínica durante el acto médico, permitiendo diagnósticos comparativos fundamentados en antecedentes exactos.

Desde el ángulo técnico, el uso del motor relacional PostgreSQL y arquitectura Modelo-Vista-Controlador (MVC) en PHP 8 nativo asegura escalabilidad, consistencia transaccional y robustez mediante reglas de negocio forzadas directamente en el esquema de base de datos (`UNIQUE`, `CHECK`, `RESTRICT`).

### 1.4 Objetivo General
Desarrollar un sistema de información web para la gestión integral de pacientes (mascotas), propietarios, consultas médicas, tratamientos y esquemas de vacunación con un módulo de alertas automáticas proactivas para el Centro Veterinario San Roque.

### 1.5 Objetivos Específicos
1. Levantar, clasificar y documentar los requisitos funcionales y no funcionales del establecimiento bajo el estándar **ISO/IEC/IEEE 29148:2018**.
2. Diseñar el modelo de datos relacional normalizado (3FN) en PostgreSQL con restricciones de integridad que impidan colisiones de horario y borrado accidental de registros vinculados.
3. Construir la aplicación web bajo arquitectura MVC en PHP 8+ nativo (sin frameworks) con persistencia segura mediante PDO y vistas responsivas adaptadas con Bootstrap 5 y JavaScript.
4. Implementar un módulo innovador de alertas automáticas que identifique controles y vacunas vencidas o próximas a vencer (umbral de 7 días) con enlaces rápidos de notificación.
5. Desarrollar un tablero gerencial (Dashboard) con indicadores visuales clave (KPIs, gráficas analíticas) y generación de reportes con filtros por fecha exportables a formato CSV (Excel).
6. Validar el sistema mediante un banco de casos de prueba integrales que verifiquen el cumplimiento del 100% de los criterios de aceptación y directrices de seguridad (inyección SQL, XSS, CSRF).

### 1.6 Alcance del Sistema
El sistema contempla los siguientes módulos y capacidades:
- **Módulo de Seguridad y Autenticación:** Inicio y cierre de sesión con control de acceso basado en roles (*Administrador*, *Veterinario*, *Recepción*) y contraseñas cifradas en Bcrypt.
- **Módulo de Propietarios y Mascotas:** Gestión completa (CRUD), asociación 1:N, y buscador en tiempo real por documento o nombre.
- **Módulo de Consulta Médica e Historia Clínica:** Registro asistencial, diagnóstico, costos, próximo control programado y vista detallada del expediente del paciente.
- **Módulo de Vacunación:** Catálogo de biológicos con intervalos en días, registro de dosis aplicadas con lote y cálculo automatizado de la próxima fecha de aplicación.
- **Módulo de Tratamientos:** Prescripción farmacológica (dosis, frecuencia y duración) ligada a consultas.
- **Módulo de Alertas Automáticas (Innovación):** Vistas SQL dedicadas (`v_alertas_vacunas`, `v_alertas_controles`) con badges de estado y disparadores de notificación directa.
- **Módulo de Dashboard e Informes:** Tarjetas métricas, gráficas por especie y mes (Chart.js), y reportes financieros con exportación a CSV.

### 1.7 Limitaciones del Proyecto
1. **Infraestructura de despliegue:** El prototipo funcional está diseñado para ejecutarse en entorno local de servidor (Apache + PostgreSQL sobre Windows/Linux).
2. **Notificaciones externas:** La comunicación por WhatsApp se implementa mediante generación dinámica de hipervínculos `wa.me` (URI scheme) sin requerir integración con la API de pago de WhatsApp Cloud / Meta Business.
3. **Facturación legal:** El sistema gestiona el control interno de costos asistenciales e ingresos; no sustituye el software de facturación electrónica validado por la DIAN.
4. **Pasarelas de pago:** No incluye cobro en línea con tarjetas de crédito o PSE.

---

## 2. Identificación de Actores del Sistema

| Actor | Descripción / Responsabilidad | Interacción con el Sistema |
|---|---|---|
| **Administrador** | Director médico o administrador del establecimiento. Responsable de la gestión financiera, auditoría de atenciones, administración de cuentas de usuario y políticas de precios. | Acceso irrestricto a todos los módulos: CRUD de usuarios, catálogos, Dashboard gerencial, informes asistenciales y exportación de datos a CSV. |
| **Veterinario** | Profesional de la salud animal responsable de la atención médica, valoración diagnóstica, prescripción de fármacos y aplicación de biológicos. | Acceso al catálogo de mascotas y propietarios, registro de consultas médicas, emisión de diagnósticos/tratamientos, aplicación de vacunas y visualización del historial clínico. |
| **Recepción** | Personal auxiliar encargado del primer contacto con el cliente, ingreso de fichas y programación de atenciones. | Registro y actualización de propietarios y mascotas, asignación de turnos/citas, consulta rápida de expedientes y visualización del panel de alertas para gestión de recordatorios. |

---

## 3. Matriz de Requisitos del Sistema (ISO/IEC/IEEE 29148:2018)

### 3.1 Requisitos Funcionales (RF)

| ID | Nombre del Requisito | Descripción Detallada | Prioridad | Criterio de Aceptación (Condición de Verificación) |
|:---:|---|---|:---:|---|
| **RF-01** | Autenticación de Usuarios | El sistema debe validar las credenciales de acceso (correo electrónico y contraseña cifrada) y crear la sesión del usuario según su rol asignado. | Alta | Dado un usuario registrado activo, al ingresar credenciales válidas, el sistema redirige al Dashboard y muestra su nombre y rol; si falla, emite mensaje de error sin revelar cuál dato es incorrecto. |
| **RF-02** | Control de Acceso por Roles (RBAC) | El sistema debe restringir el acceso a los módulos y acciones según el rol del usuario (*admin*, *veterinario*, *recepcion*). | Alta | Si un usuario con rol *recepcion* intenta ingresar directamente por URL a `c=reporte`, el sistema bloquea la petición y responde con código HTTP 403 (Acceso Denegado). |
| **RF-03** | Gestión de Propietarios (CRUD) | El sistema debe permitir registrar, consultar, editar y eliminar los datos de los propietarios (cédula, nombres, apellidos, teléfono, correo, dirección). | Alta | Dado un formulario con datos válidos y cédula única, el sistema almacena el cliente en la tabla `propietario` y lo lista inmediatamente. |
| **RF-04** | Protección de Integridad de Propietarios (RN-02) | El sistema debe impedir la eliminación de cualquier propietario que posea una o más mascotas asociadas en la base de datos. | Alta | Al intentar eliminar un propietario con mascotas registradas, la base de datos emite error de llave foránea (`23503`) y la aplicación presenta un mensaje informativo de restricción. |
| **RF-05** | Gestión de Mascotas (CRUD) | El sistema debe permitir registrar, editar y consultar mascotas vinculadas a un propietario (nombre, especie, raza, sexo, fecha nacimiento, peso). | Alta | Al registrar una mascota, se debe seleccionar obligatoriamente un propietario existente y seleccionar una especie válida ('Perro', 'Gato', 'Ave', 'Conejo', 'Otro'). |
| **RF-06** | Baja Lógica de Pacientes | El sistema debe implementar eliminación lógica para las mascotas (marcador `activo = FALSE`) para no truncar la integridad del expediente médico. | Media | Al pulsar "Dar de baja" en una mascota, su estado cambia a inactivo y deja de figurar en el listado general, pero sus registros de consultas y vacunas se conservan en la base de datos. |
| **RF-07** | Búsqueda Rápida en Tiempo Real | El sistema debe permitir filtrar el listado de mascotas mediante el nombre del paciente o el número de documento de identidad de su dueño. | Alta | Al ingresar texto en el campo de búsqueda, el sistema ejecuta una coincidencia parcial (`ILIKE`) devolviendo los resultados en menos de 1 segundo. |
| **RF-08** | Registro de Consulta Médica | El sistema debe permitir registrar la fecha, hora, motivo, diagnóstico, costo y próximo control de una consulta asociándola a una mascota y a un veterinario. | Alta | La consulta queda registrada en la tabla `consulta` y el costo se valida como valor numérico igual o mayor a cero. |
| **RF-09** | Restricción de Solapamiento Médico (RN-03) | El sistema debe evitar que un mismo veterinario tenga dos consultas registradas en la misma fecha y hora exacta. | Alta | Si se intenta agendar una consulta para un veterinario a una hora en la que ya tiene otra registrada, el sistema rechaza la transacción informando la duplicidad. |
| **RF-10** | Visualización del Historial Clínico | El sistema debe presentar una vista unificada y cronológica de todo el historial clínico de un paciente (consultas, diagnósticos y vacunas aplicadas). | Alta | Al ingresar al historial de una mascota, se despliega en una sola pantalla la ficha del paciente, la tabla de consultas previas y el registro de biológicos aplicados. |
| **RF-11** | Catálogo de Vacunas y Frecuencias | El sistema debe mantener un catálogo de biológicos clasificados por especie con su respectivo intervalo de revacunación en días. | Media | Cada vacuna cuenta con un nombre, especie destino y un valor entero positivo correspondiente a los días de inmunidad (`intervalo_dias > 0`). |
| **RF-12** | Registro de Vacunación y Cálculo Automático (RN-01) | El sistema debe registrar la vacuna aplicada, lote y calcular automáticamente la fecha de la próxima dosis sumando el intervalo de la vacuna a la fecha de aplicación. | Alta | Dada una vacuna con intervalo de 365 días aplicada el 10/01/2026, el sistema calcula y almacena de forma desatendida la próxima dosis en la fecha 10/01/2027. |
| **RF-13** | Módulo de Alertas Preventivas (Innovación) | El sistema debe listar automáticamente todas las vacunas y controles médicos que estén vencidos o que venzan en los próximos 7 días calendario. | Alta | La vista de alertas agrupa y resalta en rojo las citas/vacunas vencidas (`dias_restantes < 0`) y en amarillo las próximas a vencer (`0 <= dias_restantes <= 7`). |
| **RF-14** | Enlace Rápido de Notificación (WhatsApp) | El sistema debe generar hipervínculos dinámicos con mensajes pre-redactados para avisar al dueño sobre sus alertas pendientes vía WhatsApp Web. | Media | Al presionar el botón "Notificar", se abre una pestaña con el enlace `wa.me/57[telefono]?text=[mensaje]` personalizado con el nombre del dueño, mascota y vacuna. |
| **RF-15** | Dashboard Operativo con Indicadores y Gráficos | El sistema debe calcular y mostrar en la pantalla principal el total de mascotas activas, propietarios, consultas del mes, ingresos mensuales y gráficas estadísticas. | Media | El dashboard carga las métricas agregadas mediante consultas SQL de agregación y dibuja gráficas interactivas con la librería Chart.js. |
| **RF-16** | Generación de Reportes Financieros y Asistenciales | El sistema debe permitir al Administrador filtrar las consultas atendidas y el rendimiento de los veterinarios por rango de fechas (desde/hasta). | Media | El reporte lista las atenciones entre las fechas seleccionadas, totaliza los ingresos del periodo y detalla el recaudo atribuido a cada profesional. |
| **RF-17** | Exportación de Datos a CSV (Excel) | El sistema debe permitir descargar los registros del reporte asistencial en un archivo con formato `.csv` compatible con hojas de cálculo. | Media | Al pulsar "Exportar a Excel", el navegador descarga un archivo CSV con codificación UTF-8 con BOM y delimitador de punto y coma (`;`). |

---

### 3.2 Requisitos No Funcionales (RNF)

| ID | Clasificación | Requisito No Funcional | Métrica / Criterio de Medición |
|:---:|---|---|---|
| **RNF-01** | Rendimiento y Eficiencia | El tiempo de respuesta del sistema para la consulta de información de pacientes y carga de pantallas no debe superar los 2 segundos bajo carga estándar local. | Tiempo de respuesta < 2.0 s verificado en DevTools de Chrome. |
| **RNF-02** | Seguridad (Almacenamiento de Credenciales) | Las contraseñas de los usuarios deben ser almacenadas de forma irreversible mediante algoritmos robustos de derivación de claves (Bcrypt). | Uso de `password_hash()` con constante `PASSWORD_BCRYPT` (costo por defecto). |
| **RNF-03** | Seguridad (Mitigación de Inyección SQL) | Todas las interacciones con la base de datos PostgreSQL deben ejecutarse mediante sentencias preparadas con enlace de parámetros a través de PDO. | 100% de consultas parametrizadas (`PDO::prepare`); cero concatenación de cadenas en cláusulas SQL. |
| **RNF-04** | Seguridad (Mitigación de XSS y CSRF) | El sistema debe neutralizar la inyección de código script malicioso en las vistas y validar tokens anti-falsificación en todos los formularios de petición POST. | Escapado global con `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` y validación estricta de token `$_SESSION['csrf']`. |
| **RNF-05** | Usabilidad y Diseño Responsivo | La interfaz de usuario debe adaptarse fluidamente a dispositivos de escritorio (1440 px) y pantallas móviles (390 px) manteniendo legibilidad sin pérdida de funcionalidad. | Implementación de Bootstrap 5.3 con diseño 'Mobile-First' y tipografías fluidas. |
| **RNF-06** | Compatibilidad de Navegadores | La aplicación web debe operar sin distorsiones de estilo o fallos en ejecución de scripts en las versiones modernas de Google Chrome, Mozilla Firefox y Microsoft Edge. | Renderizado validado al 100% en los 3 navegadores principales. |
| **RNF-07** | Arquitectura y Mantenibilidad | El código fuente debe estar estructurado estrictamente bajo el patrón arquitectónico Modelo-Vista-Controlador (MVC), separando la lógica de negocio, acceso a datos y presentación. | Ausencia de lógica de acceso a datos en las vistas y ausencia de marcado HTML en los modelos. |
| **RNF-08** | Control de Versiones | El ciclo de desarrollo debe registrarse de manera incremental y atómica en un repositorio público de Git/GitHub siguiendo la convención semántica de commits. | Historial con prefijos estándar (`feat:`, `fix:`, `docs:`, `test:`, `refactor:`). |

---

## 4. Historias de Usuario (HU)

1. **HU-01: Autenticación en el sistema**  
   *Como* usuario de la veterinaria (*administrador*, *veterinario* o *recepción*),  
   *quiero* iniciar sesión con mi correo y clave personal,  
   *para* ingresar al sistema con los privilegios asignados a mi rol laboral.
2. **HU-02: Búsqueda rápida de pacientes**  
   *Como* recepcionista,  
   *quiero* buscar mascotas ingresando el nombre o la cédula de su dueño,  
   *para* ubicar los datos del paciente en menos de 3 segundos y evitar esperas en mostrador.
3. **HU-03: Registro de nuevos pacientes**  
   *Como* recepcionista,  
   *quiero* registrar una nueva mascota asociándola a su propietario responsable,  
   *para* abrir su expediente en el sistema antes de que pase a consulta médica.
4. **HU-04: Consulta de historia clínica**  
   *Como* médico veterinario,  
   *quiero* visualizar el historial cronológico completo de consultas, vacunas y cirugías de una mascota,  
   *para* contar con antecedentes confiables que orienten el diagnóstico del paciente.
5. **HU-05: Registro de atención clínica**  
   *Como* médico veterinario,  
   *quiero* ingresar el motivo, diagnóstico clínico, costo y fecha de próximo control de la consulta,  
   *para* dejar constancia legal y técnica de la atención brindada.
6. **HU-06: Aplicación y cálculo de biológicos**  
   *Como* médico veterinario,  
   *quiero* registrar una vacuna aplicada indicando el lote y dejar que el sistema calcule la próxima dosis,  
   *para* garantizar la exactitud en el calendario de inmunización del animal.
7. **HU-07: Monitoreo de alertas de vencimiento**  
   *Como* auxiliar de recepción,  
   *quiero* visualizar una lista filtrada con las vacunas y controles que vencen en los próximos 7 días,  
   *para* coordinar las citas de refuerzo antes de que el paciente quede desprotegido.
8. **HU-08: Notificación directa al propietario**  
   *Como* auxiliar de recepción,  
   *quiero* enviar un mensaje con un clic al WhatsApp del dueño con los datos de su cita pendiente,  
   *para* agilizar la comunicación sin tener que redactar el mensaje manualmente.
9. **HU-09: Análisis gerencial en Dashboard**  
   *Como* administrador de la clínica,  
   *quiero* observar en gráficos y tarjetas los ingresos del mes y el total de pacientes activos,  
   *para* evaluar el crecimiento operativo del establecimiento de un vistazo.
10. **HU-10: Auditoría y reporte por profesional**  
    *Como* administrador de la clínica,  
    *quiero* filtrar las consultas atendidas por médico y exportar los resultados a Excel (CSV),  
    *para* liquidar honorarios y presentar informes contables de fin de mes.

---

## 5. Reglas de Negocio (RN)

- **RN-01 (Cálculo del Intervalo de Inmunización):**  
  La fecha de la próxima dosis de cualquier biológico es igual a la fecha de aplicación más el número exacto de días estipulado en el catálogo de vacunas (`proxima_dosis = fecha_aplicacion + intervalo_dias`). La fecha de aplicación nunca podrá ser posterior a la fecha actual (`fecha_aplicacion <= CURRENT_DATE`).
- **RN-02 (Integridad Referencial de Propietarios):**  
  Un propietario no puede ser eliminado del sistema si mantiene una o más mascotas asociadas a su nombre en la base de datos (restringido a nivel de motor mediante `ON DELETE RESTRICT`).
- **RN-03 (Exclusividad de Agenda Médica):**  
  Un médico veterinario no puede tener asignadas dos consultas con idéntica fecha y hora exacta (`CONSTRAINT uq_veterinario_fecha UNIQUE(veterinario_id, fecha)`), previniendo traslapes en los consultorios.
- **RN-04 (Umbral y Estados de Alerta Preventiva):**  
  Toda vacuna o control médico cuya fecha programada reste entre 0 y 7 días respecto al día de hoy se clasifica automáticamente con el estado `'Por vencer'`. Si la fecha es estrictamente menor a la actual (`dias_restantes < 0`), se clasifica como `'Vencida'`. Si la fecha supera los 7 días, se cataloga como `'Al día'`.
