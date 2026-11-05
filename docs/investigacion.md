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

| Campo | Registro / Detalle |
|---|---|
| **Fecha de observación:** | 4 de Noviembre de 2026 (Horario: 9:00 a. m. a 12:30 p. m. - Jornada pico) |
| **Lugar:** | Recepción y Sala de Espera del Centro Veterinario San Roque |
| **Observador:** | Johan Muñoz |
| **Actividad observada 1:** | Ingreso de paciente canino para consulta general de urgencia por vómito. |
| **Problema detectado 1:** | La auxiliar tardó 7 minutos buscando la carpeta física en el archivo rodante. Mientras tanto, el cliente se impacientaba y la sala de espera se congestionó con dos pacientes más. |
| **Oportunidad 1:** | Implementar un buscador digital en tiempo real que permita ubicar el paciente en menos de 2 segundos mediante su nombre o el documento de identidad de su propietario. |
| **Actividad observada 2:** | Propietario solicita información sobre la vacuna antirrábica que le aplicaron hace un año porque perdió el carné. |
| **Problema detectado 2:** | No hubo forma rápida de certificar la fecha ni el lote de la vacuna aplicada sin revisar hoja por hoja las notas manuscritas del año anterior. |
| **Oportunidad 2:** | Creación de un historial digital de vacunación que calcule de forma automática la próxima dosis requerida y conserve el registro del lote. |
| **Actividad observada 3:** | Cruce de citas a las 11:30 a. m. entre dos clientes citados con el mismo médico veterinario. |
| **Problema detectado 3:** | Ambos clientes tenían agendada cita a la misma hora en la libreta física de recepción. Uno de ellos tuvo que esperar 45 minutos adicionales. |
| **Oportunidad 3:** | Restricción lógica en base de datos que impida asignar dos consultas médicas al mismo profesional en idéntico horario. |

---

## 6. Encuesta a Clientes / Propietarios de Mascotas

### Ficha Técnica de la Encuesta:
- **Población objetivo:** Propietarios de caninos y felinos que asisten al centro veterinario.
- **Muestra aplicada:** 25 propietarios encuestados de forma presencial y mediante formulario virtual.
- **Tipo de preguntas:** Cerradas (opción múltiple y dicotómicas Sí/No).

### Cuestionario y Tabulación de Resultados (25 encuestados = 100%):

1. **¿Ha olvidado o retrasado alguna vez la fecha de vacunación o desparasitación de su mascota?**
   - Sí: 19 personas (**76%**)
   - No: 6 personas (**24%**)
   *Interpretación:* Más de 3 cuartas partes de los usuarios olvidan las fechas críticas de inmunización por falta de recordación activa.

2. **¿Conserva usted en buen estado el carné de vacunación físico de su mascota?**
   - Sí, siempre: 8 personas (**32%**)
   - Lo pierdo o daño con frecuencia: 11 personas (**44%**)
   - No sé dónde está actualmente: 6 personas (**24%**)
   *Interpretación:* El 68% de los propietarios no cuenta con el soporte físico confiable en el momento de una consulta o viaje.

3. **¿Le gustaría recibir notificaciones o recordatorios automáticos (ej. vía WhatsApp) días antes del vencimiento de una vacuna o control médico?**
   - Sí, definitivamente: 24 personas (**96%**)
   - No me interesa: 1 persona (**4%**)
   *Interpretación:* Existe una necesidad y aprobación casi unánime (96%) para la funcionalidad de alertas automáticas.

4. **¿Cuál considera que es el canal más cómodo y efectivo para recibir dichos avisos?**
   - Mensaje de WhatsApp: 21 personas (**84%**)
   - Correo electrónico: 3 personas (**12%**)
   - Llamada telefónica tradicional: 1 persona (**4%**)
   *Interpretación:* WhatsApp es el canal preferido por los dueños de mascotas.

5. **Cuando acude a la veterinaria, ¿cuánto tiempo considera que tarda la atención en recepción para ubicar el historial de su mascota?**
   - Menos de 2 minutos: 4 personas (**16%**)
   - Entre 5 y 10 minutos: 16 personas (**64%**)
   - Más de 10 minutos: 5 personas (**20%**)
   *Interpretación:* El 84% percibe tiempos de espera superiores a 5 minutos únicamente en la fase de localización de registros.

6. **¿Le gustaría que su médico veterinario tenga acceso inmediato al historial clínico completo de su mascota en pantalla?**
   - Totalmente de acuerdo: 25 personas (**100%**)
   - En desacuerdo: 0 personas (**0%**)
   *Interpretación:* Unanimidad total respecto a la importancia de la digitalización de la historia clínica.

---

## 7. Oportunidades de Mejora Identificadas

Con base en la triangulación de la entrevista al director, la observación directa y la encuesta a los 25 clientes, se definen las siguientes **5 oportunidades de mejora prioritarias** que justifican y delimitan el sistema **Veterinaria Digital**:

1. **Centralización y Digitalización del Historial Clínico:**  
   Reemplazar el archivo físico en papel por un repositorio digital bajo arquitectura MVC con PostgreSQL, permitiendo consultar antecedentes, diagnósticos y tratamientos de cualquier mascota en menos de 3 segundos mediante búsqueda por nombre o documento del dueño.
2. **Sistema Automatizado de Alertas de Vacunación y Controles (Funcionalidad Innovadora):**  
   Implementar una lógica de negocio y vistas en base de datos (`v_alertas_vacunas` y `v_alertas_controles`) que detecten automáticamente las vacunas y revisiones que vencen en los próximos 7 días, habilitando un enlace con un solo clic para notificar al propietario a través de WhatsApp.
3. **Cálculo Preciso de Próxima Dosis según Intervalo Farmacológico:**  
   Eliminar el error humano en el agendamiento calculando la próxima fecha de aplicación automáticamente a partir del catálogo de vacunas (ej. 365 días para rabia/moquillo, 90 días para desparasitaciones), asegurando la integridad del calendario preventivo.
4. **Control de Restricciones y Reglas de Integridad Médica:**  
   Establecer reglas a nivel de base de datos que impidan registrar dos consultas al mismo médico en idéntica fecha y hora (`UNIQUE`), así como evitar la eliminación accidental de dueños que tengan mascotas con historial activo (`ON DELETE RESTRICT`).
5. **Dashboard Gerencial y Reportes Automatizados en Tiempo Real:**  
   Sustituir el conteo manual de recibos a fin de mes por un panel con indicadores clave (KPIs), gráficas de consultas por mes y reportes con filtro de fechas exportables a formato CSV (Excel) para el control transparente de ingresos y rendimiento veterinario.
