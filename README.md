# Veterinaria Digital - Software Integral de Gestión Veterinaria

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17-336791?logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License: SENA](https://img.shields.io/badge/Proyecto-ADSO%20SENA-39A900)](https://www.sena.edu.co/)

**Proyecto Final Individual - Programa ADSO (Análisis y Desarrollo de Software)**  
**Centro de Servicios Financieros - SENA Regional Distrito Capital**  
**Aprendiz:** Johan Muñoz  
**Instructora Líder:** Joan Castillo Celis  
**Repositorio Oficial:** [johanm-dev/adso-veterinaria](https://github.com/johanm-dev/adso-veterinaria)  
**Jira Cloud:** `https://johanmunoz050.atlassian.net` (Proyecto: `VET`)

---

## 1. Descripción del Proyecto

**Veterinaria Digital** es una plataforma web integral desarrollada bajo arquitectura limpia **MVC (Modelo - Vista - Controlador)** en **PHP 8 nativo con Programación Orientada a Objetos (POO)** y **PDO**, conectada a un motor relacional **PostgreSQL 17**. 

El software resuelve de forma innovadora los problemas de pérdida de trazabilidad en historias clínicas, desorganización en agendas médicas y deserción en esquemas de vacunación mediante un **motor automático de alertas sanitarias y notificación directa por WhatsApp**.

---

## 2. Características Principales y Reglas de Negocio Implementadas

- **Autenticación y Seguridad (RBAC & CSRF):** Cifrado Bcrypt para credenciales, protección contra suplantación CSRF por tokens de un solo uso, mitigación XSS mediante saneamiento de entidades y sesiones blindadas.
- **Regla RN-01 (Cálculo Automático de Refuerzo de Vacunas):** Al aplicar un biológico, el sistema suma automáticamente el intervalo de días reglamentario a la fecha de aplicación para fijar la próxima dosis sin margen de error humano.
- **Regla RN-02 (Integridad Referencial Estricta de Propietarios):** Prohibición lógica y a nivel de base de datos (`ON DELETE RESTRICT`) de eliminar propietarios que tengan mascotas o expedientes clínicos activos.
- **Regla RN-03 (Restricción de Conflicto de Agenda Médica):** Detección e impedimento de traslapes en consultas para el mismo veterinario en una misma fecha y hora (`UNIQUE (veterinario_id, fecha)`).
- **Regla RN-04 (Baja Lógica de Pacientes):** Los animales dados de baja mantienen intacta su historia clínica acumulada y no se destruyen físicamente de la base de datos.
- **Centro de Alertas & Notificación WhatsApp:** Vistas SQL optimizadas (`DISTINCT ON`) para detectar biológicos próximos a vencer (≤ 7 días) o vencidos, con botones que disparan mensajes pre-redactados personalizados al WhatsApp del propietario.
- **Reportes Gerenciales CSV (Excel Compatible):** Exportación con prefijo UTF-8 BOM y delimitador `;` para visualización limpia de caracteres latinos en Microsoft Excel.

---

## 3. Arquitectura del Proyecto

```text
veterinaria/
├── app/
│   ├── config/
│   │   ├── config.example.php     # Plantilla de configuración
│   │   ├── config.local.php       # Credenciales locales activas
│   │   └── Database.php           # Patrón Singleton de conexión PDO a PostgreSQL
│   ├── controllers/
│   │   ├── Controller.php         # Controlador base con render, redirect y verificación CSRF
│   │   ├── AuthController.php     # Login, logout y sesiones
│   │   ├── DashboardController.php# KPIs y datasets para Chart.js
│   │   ├── PropietarioController.php # CRUD y validación de regla RN-02
│   │   ├── MascotaController.php  # Censo, búsqueda predictiva y baja lógica
│   │   ├── ConsultaController.php # Atención médica y regla RN-03
│   │   ├── VacunaController.php   # Aplicación y cálculo automático RN-01
│   │   ├── AlertaController.php   # Monitoreo y links de WhatsApp
│   │   └── ReporteController.php  # Filtros por fecha y exportación CSV con BOM
│   ├── helpers/
│   │   └── funciones.php          # e(), flash(), csrf_token(), csrf_field(), formato_pesos()
│   ├── models/
│   │   ├── Usuario.php, Propietario.php, Mascota.php
│   │   ├── Consulta.php, Vacuna.php, Alerta.php
│   └── views/
│       ├── layout/ (header.php, footer.php)
│       ├── auth/, dashboard/, propietarios/, mascotas/, consultas/, vacunas/, alertas/, reportes/
├── database/
│   ├── schema.sql                 # DDL con 8 tablas ANSI PostgreSQL, tipos, llaves e índices
│   ├── datos_prueba.sql           # Semillas con 5 usuarios Bcrypt, dueños, mascotas y vacunas
│   ├── consultas.sql              # Consultas de negocio con las 11 cláusulas obligatorias SQL
│   ├── vistas.sql                 # Vistas automáticas v_alertas_vacunas y v_alertas_controles
│   ├── modelo.pgerd               # Diagrama relacional para pgAdmin 4
│   └── backup_final.sql           # Respaldo generado mediante pg_dump
├── docs/
│   ├── investigacion.md           # Sesión 1: Estudio de campo y encuesta real
│   ├── requisitos.md              # Sesión 2: RF, RNF, RE, RN bajo ISO/IEC/IEEE 29148
│   ├── matriz_de_trazabilidad.docx# Sesión 3: Matriz HU vs RF vs Código vs Pruebas
│   ├── diseno_ui.md               # Sesión 4: Wireframes e inventario de 10 pantallas
│   ├── diccionario_datos.md       # Sesión 12: Diccionario formal de datos
│   └── pruebas.md                 # Sesión 11: Matriz de 22 casos de prueba
├── public/
│   ├── css/estilos.css            # Estilos personalizados y paleta cromática
│   └── js/validaciones.js         # Validaciones Bootstrap en cliente
├── tests/
│   └── ReglasTest.php             # Suite de pruebas automatizadas en consola CLI
├── index.php                      # Front Controller y Enrutador Principal
└── README.md                      # Documentación maestra del repositorio
```

---

## 4. Requisitos del Sistema

1. **Servidor Web:** Apache 2.4+ (incluido en XAMPP).
2. **PHP:** Versión 8.1 o superior con extensiones activas:
   - `pdo_pgsql`
   - `pgsql`
   - `session`, `filter`, `json`
3. **Base de Datos:** PostgreSQL 16 o 17.

---

## 5. Instrucciones de Instalación y Puesta en Marcha

### Paso 1: Clonar el Repositorio
```bash
cd C:\xampp\htdocs
git clone https://github.com/johanm-dev/adso-veterinaria.git veterinaria
```

### Paso 2: Configurar PostgreSQL
Abra una consola de PostgreSQL (`psql`) o pgAdmin 4 y ejecute:
```sql
CREATE DATABASE veterinaria;
CREATE USER app_veterinaria WITH PASSWORD 'TuClaveSegura123';
GRANT ALL PRIVILEGES ON DATABASE veterinaria TO app_veterinaria;
```

Cargue el esquema, las vistas y los datos iniciales:
```bash
psql -U app_veterinaria -h 127.0.0.1 -d veterinaria -f database/schema.sql
psql -U app_veterinaria -h 127.0.0.1 -d veterinaria -f database/vistas.sql
psql -U app_veterinaria -h 127.0.0.1 -d veterinaria -f database/datos_prueba.sql
```

### Paso 3: Configurar Credenciales de la Aplicación
Revise que el archivo `app/config/config.local.php` coincida con sus credenciales locales:
```php
return [
    'db' => [
        'driver'   => 'pgsql',
        'host'     => '127.0.0.1',
        'port'     => 5432,
        'dbname'   => 'veterinaria',
        'user'     => 'app_veterinaria',
        'password' => 'TuClaveSegura123'
    ],
    'app' => [
        'base_url' => 'http://localhost/veterinaria'
    ]
];
```

### Paso 4: Iniciar el Sistema en el Navegador
Inicie el módulo Apache en XAMPP y navegue a:
```text
http://localhost/veterinaria
```

---

## 6. Usuarios de Prueba del Sistema

Todas las cuentas tienen por contraseña predeterminada: **`Admin123*`**

| Nombre | Correo Electrónico | Rol | Permisos |
|---|---|---|---|
| **Administrador General** | `admin@vet.com` | `admin` | Acceso total a todos los módulos y administración |
| **Dra. Laura Gómez** | `laura@vet.com` | `veterinario` | Atención clínica, prescripción y vacunación |
| **Dr. Andrés Ruiz** | `andres@vet.com` | `veterinario` | Atención clínica y expedientes de pacientes |
| **Sara Díaz** | `sara@vet.com` | `recepcion` | Registro de propietarios, citas y alertas |
| **Pedro León** | `pedro@vet.com` | `recepcion` | Registro de propietarios, citas y alertas |

---

## 7. Ejecución de Pruebas Automatizadas

El proyecto incluye una suite de pruebas CLI que valida en milisegundos las reglas críticas del negocio (RN-01, RN-02, RN-03, XSS y generador WhatsApp):

```bash
C:\xampp\php\php.exe -d zend.assertions=1 tests/ReglasTest.php
```

**Salida esperada:**
```text
=== INICIANDO PRUEBAS DE REGLAS DE NEGOCIO Y CALIDAD ===

[TEST 1] Regla RN-01: Cálculo automático de próxima dosis... PASÓ
[TEST 2] Regla RN-02: Integridad referencial en borrado de propietario... PASÓ
[TEST 3] Regla RN-03: Restricción de agenda médica por médico/fecha/hora... PASÓ
[TEST 4] Seguridad XSS: Sanitización de entradas con e()... PASÓ
[TEST 5] Motor de Alertas: Enlace de mensajería WhatsApp... PASÓ

=== TODAS LAS PRUEBAS AUTOMATIZADAS PASARON CON ÉXITO (5/5) ===
```

---

## 8. Evidencias de Gestión en Jira Cloud

El ciclo de desarrollo ágil (Scrum) fue gestionado en **Jira Software Cloud**:
- **Tablero:** `Tablero VET` (Board ID: 67)
- **Sprint Activo:** `Sprint 1 - MVP Veterinaria Digital` (ID: 105)
- **Historias de Usuario:** `VET-9` a `VET-71` con estimación en Story Points y sub-tareas técnicas de BD, Backend, Frontend y Pruebas vinculadas.
- **Matriz de Trazabilidad:** Documentada en [`docs/matriz_de_trazabilidad.docx`](file:///C:/xampp/htdocs/veterinaria/docs/matriz_de_trazabilidad.docx).
