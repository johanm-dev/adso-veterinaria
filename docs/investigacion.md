# Informe de Investigación de Negocio (Sesión 1)
**Programa:** Tecnólogo en Análisis y Desarrollo de Software (ADSO) · SENA  
**Proyecto N.º 2:** Veterinaria Digital  
**Aprendiz:** Johan Muñoz  
**Instructora:** Joan Castillo Celis  
**Fecha:** Noviembre 2026  

---

## 1. Identificación del Negocio Real
- **Nombre comercial:** Centro Veterinario & Pet Shop San Roque
- **Ubicación:** Barrio La Flora / Tequendama, Cali, Colombia
- **Tipo de entidad:** Pequeña y Mediana Empresa (Pyme) de salud animal y bienestar de animales de compañía (caninos y felinos).
- **Servicios ofrecidos:** Consulta médica general, urgencias, planes de vacunación, desparasitación interna/externa, profilaxis dental, cirugía de tejidos blandos y venta de fármacos y alimentos concentrados.
- **Planta de personal:**
  - 1 Administrador / Propietario (Médico Veterinario Zootecnista Principal).
  - 2 Médicos Veterinarios de planta.
  - 2 Auxiliares veterinarias / Recepcionistas.

---

## 2. Identificación de Actores y Usuarios del Sistema

| Tipo de Usuario | Rol en el Negocio | Responsabilidades / Acciones |
|---|---|---|
| **Administrador** | Director del establecimiento / Gerente | Supervisa la operación médica y financiera, gestiona el personal (altas/bajas), define tarifas de servicios y analiza reportes mensuales de productividad e ingresos. |
| **Personal Asistencial (Veterinarios)** | Médico Veterinario tratante | Atiende las consultas clínicas, registra la anamnesis, constantes vitales, sintomatología, diagnósticos, formula tratamientos y aplica biológicos (vacunas). |
| **Personal de Atención (Recepcionista / Auxiliar)** | Recepción y servicio al cliente | Registra el ingreso de propietarios y mascotas, atiende llamadas, cobra servicios y valida recordatorios de citas o vacunas. |
| **Cliente / Propietario (Usuario externo)** | Propietario o tenedor responsable del paciente | Solicita citas, acude con el paciente a revisiones médicas y esquemas de vacunación, paga las consultas y recibe las recomendaciones de salud animal. |

---

## 3. Descripción del Proceso Actual (As-Is / Cómo se hace hoy)

Actualmente, el Centro Veterinario opera bajo una metodología híbrida semi-manual con los siguientes pasos críticos:

1. **Recepción y Registro de Clientes y Mascotas:**  
   Cuando un propietario llega a la clínica por primera vez, la auxiliar en recepción toma los datos básicos (nombre, cédula, teléfono, dirección, nombre de la mascota, raza, edad aproximada) y los anota a mano en una libreta de registro diario, o los transcribe a una hoja de cálculo en Excel si la recepción no está congestionada.
2. **Historial Clínico:**  
   Cada paciente cuenta con una carpeta de cartulina física en un archivador clasificado por orden alfabético del apellido del dueño. En cada consulta, el veterinario busca la carpeta manualmente y redacta el diagnóstico y prescripción en hojas impresas con membrete. Si el propietario es atendido por otro veterinario, con frecuencia la carpeta no se encuentra en su lugar o la letra resulta difícil de entender.
3. **Agendamiento y Control de Vacunas:**  
   Al aplicar una vacuna, se registra la fecha y el sticker del lote en un carné físico de cartón que se entrega al dueño de la mascota. La clínica no guarda una copia digital centralizada con fechas de vencimiento; dependen 100% de que el dueño conserve el carné y recuerde cuándo volver. Solo ocasionalmente la recepcionista revisa el archivador para enviar mensajes manuales por WhatsApp personal.
4. **Cobro y Facturación:**  
   Al concluir la consulta, el veterinario anota el valor en una papeleta de cobro que el cliente entrega en caja. El pago se registra en un recibo manual y en un libro de caja diario.
5. **Generación de Reportes:**  
   Al final de cada mes, el administrador debe dedicar de 2 a 3 días hábiles a sumar manualmente los recibos de caja para calcular ingresos, comisiones médicas y estimar qué medicamentos se agotaron.

---

## 4. Guion de Entrevista (Preguntas Abiertas)
**Entrevistado:** Dr. Camilo Martínez (Director Médico y Propietario)  
**Entrevistador:** Johan Muñoz (Aprendiz ADSO)  

### Cuestionario aplicado:
1. **¿Cómo registra y almacena hoy la información de los propietarios y sus mascotas?**  
   *Respuesta resumen:* Usamos carpetas físicas archivadas por orden alfabético y una hoja de Excel general. Sin embargo, muchas veces la hoja no está actualizada porque en horas pico no da tiempo de digitar.
2. **¿Cuál es el procedimiento cuando un paciente regresa a consulta después de varios meses?**  
   *Respuesta resumen:* La recepcionista va al archivador físico a buscar la carpeta. A veces toma de 5 a 10 minutos encontrarla si fue archivada con el nombre de la mascota y no con el apellido del propietario, lo que genera demoras en la sala de espera.
3. **¿Cómo lleva el control del esquema de vacunación y desparasitación de cada mascota?**  
   *Respuesta resumen:* Pegamos el sticker del lote en el carné de vacunación que se lleva el dueño. En la carpeta queda anotada la fecha en la hoja de evolución médica, pero no tenemos un calendario unificado que nos alerte cuándo vence.
4. **¿Cómo le avisan a los clientes sobre vacunas o revisiones médicas pendientes?**  
   *Respuesta resumen:* La auxiliar intenta revisar carpetas cuando hay tiempo libre y escribe por WhatsApp desde el teléfono de la clínica. Pero es una tarea muy desgastante y solo alcanzamos a avisarle a un 20% o 30% de los pacientes.
5. **¿Qué tareas o procesos administrativos le consumen la mayor cantidad de tiempo en el día a día?**  
   *Respuesta resumen:* La búsqueda de fichas clínicas en el archivador, transcribir las notas del día al Excel y cuadrar manualmente la caja cada noche.
6. **¿Qué tipo de errores o inconvenientes se repiten con mayor frecuencia en la operación?**  
   *Respuesta resumen:* Pérdida temporal o traspapeleo de fichas clínicas, propietarios que pierden el carné de vacunación y no saben qué vacuna le falta al perro, y confusiones de horarios entre los veterinarios para revisiones.
7. **¿Cómo se asegura de que un veterinario no se solape con otro en la atención de una consulta médica?**  
   *Respuesta resumen:* Nos guiamos por un cuaderno de citas en el mostrador. Si alguien anota una cita sobre la misma hora sin avisar, se cruzan las consultas y los pacientes deben esperar afuera.
8. **¿Cómo registra y hace seguimiento a los tratamientos farmacológicos que formula?**  
   *Respuesta resumen:* Queda escrito a mano en la fórmula que se lleva el propietario y una copia de carbón en la carpeta física. No hay seguimiento posterior a menos que el cliente vuelva por complicaciones.
9. **¿Qué reportes o métricas gerenciales necesita con urgencia para tomar decisiones en la clínica?**  
   *Respuesta resumen:* Necesito saber con exactitud cuánto dinero se factura al mes por consultas frente a vacunación, cuántas consultas atendió cada veterinario para calcular comisiones, y cuántas mascotas activas tenemos por especie.
10. **¿Quiénes dentro de la veterinaria deberían tener acceso a un nuevo sistema y con qué nivel de privacidad?**  
    *Respuesta resumen:* Recepción solo debe ver datos de clientes, registrar mascotas y ver alertas para avisar. Los veterinarios deben poder ingresar diagnósticos, tratamientos y aplicar vacunas. Y yo como administrador debo ser el único con acceso a los reportes financieros e ingresos totales.

---

## 5. Formato de Observación Directa

| Fecha | Lugar | Actividad observada | Problema detectado | Oportunidad |
|---|---|---|---|---|
| 04/11/2026 | Recepción | Búsqueda de historial de paciente canino para consulta de urgencia. | La recepcionista tardó 7 minutos buscando la carpeta física en el archivador rodante, retrasando la atención y congestionando la sala de espera. | Implementar un buscador digital en tiempo real por documento del dueño o nombre de la mascota con respuesta menor a 3 segundos. |
| 04/11/2026 | Mostrador | Propietario solicitando duplicado de fecha de vacuna antirrábica por extravío de carné. | Imposible certificar fecha y lote sin revisar manualmente página por página las hojas de consulta manuscritas del año anterior. | Módulo digital de vacunación que mantenga el histórico de aplicaciones con sus lotes y calcule la próxima dosis de forma automática. |
| 04/11/2026 | Consultorio 1 | Llegada de dos clientes citados a las 11:30 a. m. con el mismo médico veterinario. | Cruce de citas por registro simultáneo en la libreta física de agendamiento; un cliente esperó 45 minutos adicionales. | Validación a nivel de base de datos que impida asignar dos citas al mismo veterinario en idéntica fecha y hora (`UNIQUE`). |

---

## 6. Encuesta a Clientes / Propietarios de Mascotas (Datos Reales)

### Ficha Técnica del Instrumento:
- **Población / Muestra:** 18 propietarios de mascotas encuestados mediante formulario estructurado.
- **Fecha de aplicación:** 5 de Noviembre de 2026 (14:49:04 a 14:55:33).
- **Tipo de preguntas:** Cerradas (opción múltiple, dicotómicas y escalas Likert).

### Tabulación de Respuestas (18 encuestados = 100%):

#### Pregunta 1: ¿Ha olvidado o retrasado alguna vez la fecha de vacunación o desparasitación de su mascota?
| Opción de Respuesta | Frecuencia Absoluta | Frecuencia Relativa (%) |
|---|:---:|:---:|
| **Sí** | 14 | **77.8%** |
| **No** | 4 | **22.2%** |
| **Total** | **18** | **100.0%** |
*Análisis:* Más de tres cuartas partes (77.8%) de los dueños admite haber retrasado esquemas preventivos por falta de recordación activa.

#### Pregunta 2: ¿Conserva usted en buen estado el carné de vacunación físico de su mascota?
| Opción de Respuesta | Frecuencia Absoluta | Frecuencia Relativa (%) |
|---|:---:|:---:|
| **Lo pierdo o daño con frecuencia** | 5 | **27.8%** |
| **No sé dónde está** | 5 | **27.8%** |
| **Sí, siempre** | 5 | **27.8%** |
| **No, nunca** | 3 | **16.7%** |
| **Total** | **18** | **100.0%** |
*Análisis:* El **72.2%** de los clientes (13 de 18) no conserva o extravía el carné físico, dejando desprotegido el historial de vacunación ante emergencias o viajes.

#### Pregunta 3: ¿Le gustaría recibir recordatorios automáticos días antes del vencimiento de una vacuna o control médico?
| Opción de Respuesta | Frecuencia Absoluta | Frecuencia Relativa (%) |
|---|:---:|:---:|
| **Definitivamente** | 9 | **50.0%** |
| **Sí** | 7 | **38.9%** |
| **No** | 2 | **11.1%** |
| **Total** | **18** | **100.0%** |
*Análisis:* El **88.9%** de los encuestados demanda un sistema automatizado de notificaciones previas para controles y vacunas.

#### Pregunta 4: ¿Por cuál canal preferiría recibir dichos recordatorios?
| Opción de Respuesta | Frecuencia Absoluta | Frecuencia Relativa (%) |
|---|:---:|:---:|
| **Correo electrónico** | 11 | **61.1%** |
| **Mensaje de WhatsApp** | 5 | **27.8%** |
| **Llamada telefónica** | 2 | **11.1%** |
| **Total** | **18** | **100.0%** |
*Análisis:* El correo electrónico lidera las preferencias de notificación formal con 61.1%, seguido por mensajería instantánea de WhatsApp con 27.8%.

#### Pregunta 5: En recepción, ¿cuánto tiempo considera que tardan en ubicar los datos requeridos para su mascota?
| Opción de Respuesta | Frecuencia Absoluta | Frecuencia Relativa (%) |
|---|:---:|:---:|
| **Más de 10 minutos** | 8 | **44.4%** |
| **Entre 5 y 10 minutos** | 7 | **38.9%** |
| **Menos de 2 minutos** | 3 | **16.7%** |
| **Total** | **18** | **100.0%** |
*Análisis:* El **83.3%** de los usuarios experimenta demoras críticas superiores a 5 minutos (con casi la mitad superando los 10 minutos) sólo en la localización de expedientes.

#### Pregunta 6: ¿Considera importante que su veterinario pueda ver el historial médico completo de su mascota en una pantalla?
| Opción de Respuesta | Frecuencia Absoluta | Frecuencia Relativa (%) |
|---|:---:|:---:|
| **Totalmente de acuerdo** | 16 | **88.9%** |
| **En desacuerdo** | 2 | **11.1%** |
| **Total** | **18** | **100.0%** |
*Análisis:* Amplio consenso positivo (88.9%) que respalda la necesidad de contar con historial clínico digital durante el acto médico.

---

## 7. Oportunidades de Mejora del Negocio (Sustentadas en Datos Reales)

1. **Reducción radical de tiempos de espera en recepción (Acceso digital en < 3 s):**  
   Frente al **83.3%** de propietarios que reporta esperas de más de 5 a 10 minutos para ubicar datos, el software debe incorporar un motor de búsqueda instantáneo por cédula del dueño o nombre de la mascota, reemplazando de raíz el archivo físico.

2. **Módulo automatizado de alertas de vacunación y controles pendientes:**  
   Dado que el **77.8%** olvida las fechas de vacunación y el **88.9%** exige recordatorios previos, el sistema implementará alertas visuales automáticas calculadas según el calendario de dosis (7 días antes y vencidas), facilitando el contacto multicanal (correo y WhatsApp).

3. **Digitalización integral del carné de vacunación y trazabilidad de biológicos:**  
   El **72.2%** de los clientes extravía o deteriora el carné de papel. El sistema garantizará un registro histórico permanente de cada aplicación, veterinario responsable, lote del biológico y cálculo automático de la próxima dosis según el intervalo en días (Regla RN-01).

4. **Expediente clínico unificado en pantalla para el profesional tratante:**  
   Respaldado por el **88.9%** de aprobación, el veterinario dispondrá en pantalla de antecedentes, consultas pasadas, diagnósticos y tratamientos vigentes en tiempo real, mejorando la calidad del diagnóstico clínico.
