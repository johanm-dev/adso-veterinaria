# Diccionario de Datos - Sistema Veterinaria Digital

**Proyecto:** Veterinaria Digital  
**Institución:** SENA - Centro de Servicios Financieros / ADSO  
**Aprendiz:** Johan Muñoz  
**Instructora:** Joan Castillo Celis  
**Motor de Base de Datos:** PostgreSQL 17  
**Esquema:** `public`  
**Base de Datos:** `veterinaria`  

---

## 1. Tabla: `usuario`
Almacena las cuentas de acceso al sistema con roles y contraseñas cifradas en Bcrypt.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador autoincremental de la cuenta |
| `nombre` | `VARCHAR(100)` | NO | — | Nombre completo del usuario |
| `correo` | `VARCHAR(120)` | NO | `UNIQUE` | Correo electrónico institucional para inicio de sesión |
| `password_hash` | `VARCHAR(255)` | NO | — | Hash de contraseña generado con `PASSWORD_BCRYPT` |
| `rol` | `VARCHAR(20)` | NO | `CHECK (rol IN ('admin', 'veterinario', 'recepcion'))` | Perfil de permisos de control de acceso (RBAC) |
| `activo` | `BOOLEAN` | NO | `DEFAULT TRUE` | Estado del usuario para permitir o bloquear el acceso |
| `creado_en` | `TIMESTAMP` | NO | `DEFAULT CURRENT_TIMESTAMP` | Fecha y hora de creación de la cuenta |

---

## 2. Tabla: `veterinario`
Contiene la información profesional y asistencial del cuerpo médico.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador único del médico veterinario |
| `usuario_id` | `INT` | SÍ | `FOREIGN KEY (usuario.id) ON DELETE SET NULL UNIQUE` | Cuenta de sistema vinculada al profesional |
| `nombre` | `VARCHAR(100)` | NO | — | Nombre del profesional de la salud animal |
| `tarjeta_profesional` | `VARCHAR(30)` | NO | `UNIQUE` | Matrícula / Registro ante COMVEZCOL |
| `especialidad` | `VARCHAR(60)` | SÍ | — | Rama de especialidad médica (Ej: Cirugía, Felinos) |
| `telefono` | `VARCHAR(20)` | SÍ | — | Línea de contacto directo del profesional |

---

## 3. Tabla: `propietario`
Registra la información de contacto y legal de los clientes y dueños de mascotas.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador autoincremental del cliente |
| `documento` | `VARCHAR(20)` | NO | `UNIQUE` | Cédula de ciudadanía o identificación legal única |
| `nombres` | `VARCHAR(80)` | NO | — | Nombres del propietario |
| `apellidos` | `VARCHAR(80)` | NO | — | Apellidos del propietario |
| `telefono` | `VARCHAR(20)` | NO | — | Línea celular para llamadas y notificaciones WhatsApp |
| `correo` | `VARCHAR(120)` | SÍ | — | Correo electrónico de notificación |
| `direccion` | `VARCHAR(150)` | SÍ | — | Dirección física de residencia |
| `creado_en` | `TIMESTAMP` | NO | `DEFAULT CURRENT_TIMESTAMP` | Fecha de afiliación o primer registro |

---

## 4. Tabla: `mascota`
Censo de pacientes atendidos en la clínica veterinaria.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador de la historia médica / paciente |
| `propietario_id` | `INT` | NO | `FOREIGN KEY (propietario.id) ON DELETE RESTRICT` | Propietario legal (RN-02: Integridad estricta) |
| `nombre` | `VARCHAR(60)` | NO | — | Nombre del animal |
| `especie` | `VARCHAR(30)` | NO | `CHECK (especie IN ('Perro', 'Gato', 'Ave', 'Conejo', 'Otro'))` | Especie biológica del animal |
| `raza` | `VARCHAR(60)` | SÍ | — | Raza zootécnica o 'Mestizo' |
| `sexo` | `CHAR(1)` | NO | `CHECK (sexo IN ('M', 'H'))` | Sexo biológico (M: Macho, H: Hembra) |
| `fecha_nacimiento` | `DATE` | SÍ | — | Fecha aproximada o exacta de nacimiento |
| `peso_kg` | `NUMERIC(6,2)` | SÍ | `CHECK (peso_kg > 0)` | Último peso registrado en kilogramos |
| `activo` | `BOOLEAN` | NO | `DEFAULT TRUE` | Estado de registro (Baja lógica) |

---

## 5. Tabla: `vacuna`
Catálogo maestro de biológicos oficiales e intervalos sanitarios.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador del biológico |
| `nombre` | `VARCHAR(80)` | NO | — | Nombre comercial y farmacológico de la vacuna |
| `especie` | `VARCHAR(30)` | NO | — | Especie animal a la que aplica el biológico |
| `intervalo_dias` | `INT` | NO | `CHECK (intervalo_dias > 0)` | Días reglamentarios para el refuerzo (RN-01) |
| *(Restricción)* | — | — | `UNIQUE (nombre, especie)` | Evita duplicidad de nombres en la misma especie |

---

## 6. Tabla: `consulta`
Registro detallado del acto médico asistencial veterinario.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Número del acto médico / folio |
| `mascota_id` | `INT` | NO | `FOREIGN KEY (mascota.id) ON DELETE RESTRICT` | Mascota examinada |
| `veterinario_id` | `INT` | NO | `FOREIGN KEY (veterinario.id) ON DELETE RESTRICT` | Profesional que atendió la consulta |
| `fecha` | `TIMESTAMP` | NO | — | Fecha y hora exacta de la cita |
| `motivo` | `VARCHAR(200)` | NO | — | Causa reportada por el propietario o cuadro inicial |
| `diagnostico` | `TEXT` | SÍ | — | Impresión diagnóstica y hallazgos clínicos |
| `costo` | `NUMERIC(10,2)` | NO | `DEFAULT 0.00 CHECK (costo >= 0)` | Valor liquidado de la atención médica (COP) |
| `proximo_control` | `DATE` | SÍ | — | Fecha agendada para revisión posconsulta |
| *(Restricción)* | — | — | `UNIQUE (veterinario_id, fecha)` | RN-03: Evita colisiones de horario por médico |

---

## 7. Tabla: `aplicacion_vacuna`
Consignación de dosis administradas a pacientes caninos, felinos y otras especies.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador del registro de vacunación |
| `mascota_id` | `INT` | NO | `FOREIGN KEY (mascota.id) ON DELETE CASCADE` | Mascota inmunizada |
| `vacuna_id` | `INT` | NO | `FOREIGN KEY (vacuna.id) ON DELETE RESTRICT` | Biológico administrado |
| `veterinario_id` | `INT` | NO | `FOREIGN KEY (veterinario.id) ON DELETE RESTRICT` | Profesional que aplicó la dosis |
| `fecha_aplicacion` | `DATE` | NO | — | Fecha en que se aplicó el fármaco |
| `proxima_dosis` | `DATE` | NO | — | Fecha de refuerzo (RN-01: fecha + intervalo) |
| `lote` | `VARCHAR(30)` | SÍ | — | Código de lote de fabricación del laboratorio |
| *(Restricción)* | — | — | `CHECK (proxima_dosis > fecha_aplicacion)` | Garantiza coherencia temporal |

---

## 8. Tabla: `tratamiento`
Prescripción farmacológica derivada de una consulta médica.

| Columna | Tipo de Dato | Nulo | Clave / Restricción | Descripción |
|---|---|---|---|---|
| `id` | `INT` (Identity) | NO | `PRIMARY KEY` | Identificador de la fórmula médica |
| `consulta_id` | `INT` | NO | `FOREIGN KEY (consulta.id) ON DELETE CASCADE` | Consulta de la cual se deriva la fórmula |
| `medicamento` | `VARCHAR(100)` | NO | — | Nombre comercial y principio activo prescrito |
| `dosis` | `VARCHAR(60)` | NO | — | Cantidad o concentración por toma |
| `frecuencia` | `VARCHAR(60)` | NO | — | Intervalo de administración (Ej: Cada 12 horas) |
| `fecha_inicio` | `DATE` | NO | — | Fecha inicial de tratamiento |
| `fecha_fin` | `DATE` | SÍ | — | Fecha de culminación del tratamiento |
| `estado` | `VARCHAR(20)` | NO | `CHECK (estado IN ('En curso', 'Finalizado', 'Suspendido'))` | Situación actual de la terapia |
