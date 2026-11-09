<?php
/**
 * Pruebas Automatizadas de Reglas de Negocio y Seguridad
 * Veterinaria Digital - ADSO SENA
 *
 * Ejecución:
 * php -d zend.assertions=1 tests/ReglasTest.php
 */

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require_once $file;
});

require_once dirname(__DIR__) . '/app/helpers/funciones.php';

use App\Config\Database;
use App\Models\Vacuna;
use App\Models\Propietario;
use App\Models\Consulta;
use App\Models\Alerta;

echo "=== INICIANDO PRUEBAS DE REGLAS DE NEGOCIO Y CALIDAD ===" . PHP_EOL . PHP_EOL;

$pdo = Database::getConnection();

// ---------------------------------------------------------
// PRUEBA 1: Regla RN-01 (Cálculo automático de próxima dosis)
// ---------------------------------------------------------
echo "[TEST 1] Regla RN-01: Cálculo automático de próxima dosis... ";
$fechaTest = '2026-06-01';
// Vacuna 1: Rabia (intervalo_dias = 365)
$vacunaId = 1;
$mascotaId = 1;
$vetId = 1;

$nuevaAplicacionId = Vacuna::aplicar([
    'mascota_id'       => $mascotaId,
    'vacuna_id'        => $vacunaId,
    'veterinario_id'   => $vetId,
    'fecha_aplicacion' => $fechaTest,
    'lote'             => 'TEST-RN01'
]);

$stmt = $pdo->prepare("SELECT fecha_aplicacion, proxima_dosis FROM aplicacion_vacuna WHERE id = :id");
$stmt->execute(['id' => $nuevaAplicacionId]);
$row = $stmt->fetch();

$fechaEsperada = '2027-06-01'; // 2026-06-01 + 365 días
if ($row['proxima_dosis'] === $fechaEsperada) {
    echo "PASÓ (Esperado: $fechaEsperada | Obtenido: {$row['proxima_dosis']})" . PHP_EOL;
} else {
    echo "FALLÓ (Esperado: $fechaEsperada | Obtenido: {$row['proxima_dosis']})" . PHP_EOL;
    exit(1);
}
// Limpieza de prueba
$pdo->prepare("DELETE FROM aplicacion_vacuna WHERE id = :id")->execute(['id' => $nuevaAplicacionId]);

// ---------------------------------------------------------
// PRUEBA 2: Regla RN-02 (Prohibido eliminar propietario con mascotas)
// ---------------------------------------------------------
echo "[TEST 2] Regla RN-02: Integridad referencial en borrado de propietario... ";
// Propietario ID 1 tiene mascotas activas
$bloqueado = false;
try {
    Propietario::delete(1);
} catch (\DomainException $e) {
    $bloqueado = true;
}

if ($bloqueado) {
    echo "PASÓ (Excepción DomainException capturada correctamente)" . PHP_EOL;
} else {
    echo "FALLÓ (Se permitió eliminar propietario con mascotas activas)" . PHP_EOL;
    exit(1);
}

// ---------------------------------------------------------
// PRUEBA 3: Regla RN-03 (Conflicto de horario de citas del veterinario)
// ---------------------------------------------------------
echo "[TEST 3] Regla RN-03: Restricción de agenda médica por médico/fecha/hora... ";
$fechaConsulta = '2026-11-20 10:00:00';

// Insertar primera consulta
$consulta1Id = Consulta::create([
    'mascota_id'      => 1,
    'veterinario_id'  => 1,
    'fecha'           => $fechaConsulta,
    'motivo'          => 'Consulta 1 para test RN-03',
    'costo'           => 50000,
    'proximo_control' => '2026-11-27'
]);

$conflictoDetectado = false;
try {
    // Intentar asignar otra cita al mismo veterinario exactamente al mismo tiempo
    Consulta::create([
        'mascota_id'      => 2,
        'veterinario_id'  => 1,
        'fecha'           => $fechaConsulta,
        'motivo'          => 'Consulta colisionada 2',
        'costo'           => 50000,
        'proximo_control' => null
    ]);
} catch (\DomainException $e) {
    $conflictoDetectado = true;
}

// Limpiar consulta 1
$pdo->prepare("DELETE FROM consulta WHERE id = :id")->execute(['id' => $consulta1Id]);

if ($conflictoDetectado) {
    echo "PASÓ (Conflicto bloqueado exitosamente por regla RN-03)" . PHP_EOL;
} else {
    echo "FALLÓ (Se permitió traslape de agenda)" . PHP_EOL;
    exit(1);
}

// ---------------------------------------------------------
// PRUEBA 4: Sanitización XSS (Helper e())
// ---------------------------------------------------------
echo "[TEST 4] Seguridad XSS: Sanitización de entradas con e()... ";
$xssVector = "<script>alert('pwned');</script>";
$escapado = e($xssVector);
if ($escapado === "&lt;script&gt;alert(&#039;pwned&#039;);&lt;/script&gt;") {
    echo "PASÓ (Etiquetas neutralizadas correctamente)" . PHP_EOL;
} else {
    echo "FALLÓ (Salida vulnerable: $escapado)" . PHP_EOL;
    exit(1);
}

// ---------------------------------------------------------
// PRUEBA 5: Generador de URLs de WhatsApp
// ---------------------------------------------------------
echo "[TEST 5] Motor de Alertas: Enlace de mensajería WhatsApp... ";
$url = Alerta::generarUrlWhatsapp('3001234567', 'Carlos Restrepo', 'Firulais', 'Rabia', '2026-12-01');
if (str_starts_with($url, 'https://api.whatsapp.com/send?phone=573001234567')) {
    echo "PASÓ (URL estructurada con prefijo 57 y texto codificado)" . PHP_EOL;
} else {
    echo "FALLÓ (URL mal formateada: $url)" . PHP_EOL;
    exit(1);
}

echo PHP_EOL . "=== TODAS LAS PRUEBAS AUTOMATIZADAS PASARON CON ÉXITO (5/5) ===" . PHP_EOL;
