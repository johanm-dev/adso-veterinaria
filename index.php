<?php
/**
 * Front Controller & Enrutador Principal
 * Veterinaria Digital - ADSO SENA
 */

declare(strict_types=1);

// Configuración de Sesión Segura
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

// Autoloader PSR-4 Básico
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Cargar Helpers Globales
require_once __DIR__ . '/app/helpers/funciones.php';

// Enrutador de Acciones
$action = $_GET['action'] ?? (isset($_SESSION['usuario_id']) ? 'dashboard' : 'login');

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PropietarioController;
use App\Controllers\MascotaController;
use App\Controllers\ConsultaController;
use App\Controllers\VacunaController;
use App\Controllers\AlertaController;
use App\Controllers\ReporteController;
use App\Controllers\UsuarioController;

switch ($action) {
    // Autenticación
    case 'login':
        (new AuthController())->showLogin();
        break;
    case 'login_submit':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    // Panel Principal
    case 'dashboard':
        (new DashboardController())->index();
        break;

    // Propietarios
    case 'propietarios':
        (new PropietarioController())->index();
        break;
    case 'propietarios_create':
        (new PropietarioController())->create();
        break;
    case 'propietarios_store':
        (new PropietarioController())->store();
        break;
    case 'propietarios_edit':
        (new PropietarioController())->edit();
        break;
    case 'propietarios_update':
        (new PropietarioController())->update();
        break;
    case 'propietarios_delete':
        (new PropietarioController())->delete();
        break;

    // Mascotas
    case 'mascotas':
        (new MascotaController())->index();
        break;
    case 'mascotas_create':
        (new MascotaController())->create();
        break;
    case 'mascotas_store':
        (new MascotaController())->store();
        break;
    case 'mascotas_edit':
        (new MascotaController())->edit();
        break;
    case 'mascotas_update':
        (new MascotaController())->update();
        break;
    case 'mascotas_delete':
        (new MascotaController())->delete();
        break;
    case 'mascotas_historial':
        (new MascotaController())->historial();
        break;

    // Consultas Médicas
    case 'consultas':
        (new ConsultaController())->index();
        break;
    case 'consultas_create':
        (new ConsultaController())->create();
        break;
    case 'consultas_store':
        (new ConsultaController())->store();
        break;
    case 'consultas_show':
        (new ConsultaController())->show();
        break;

    // Vacunación
    case 'vacunas':
        (new VacunaController())->index();
        break;
    case 'vacunas_create':
        (new VacunaController())->create();
        break;
    case 'vacunas_store':
        (new VacunaController())->store();
        break;

    // Alertas y WhatsApp
    case 'alertas':
        (new AlertaController())->index();
        break;

    // Reportes CSV
    case 'reportes':
        (new ReporteController())->index();
        break;
    case 'reportes_exportar':
        (new ReporteController())->exportarCsv();
        break;

    // Gestión de Usuarios (Solo Administrador)
    case 'usuarios':
        (new UsuarioController())->index();
        break;
    case 'usuarios_create':
        (new UsuarioController())->create();
        break;
    case 'usuarios_store':
        (new UsuarioController())->store();
        break;
    case 'usuarios_toggle':
        (new UsuarioController())->toggleActivo();
        break;

    default:
        http_response_code(404);
        die("Error 404: Ruta no encontrada en el sistema.");
}
