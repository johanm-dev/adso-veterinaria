<?php
namespace App\Controllers;

use App\Config\Database;

class ReporteController extends Controller {
    public function index(): void {
        $this->requireLogin();

        $tipo = $_GET['tipo'] ?? 'consultas';
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        $datos = $this->obtenerDatosReporte($tipo, $fechaInicio, $fechaFin);

        $this->render('reportes/index', [
            'tipo'        => $tipo,
            'fechaInicio' => $fechaInicio,
            'fechaFin'    => $fechaFin,
            'datos'       => $datos,
        ]);
    }

    public function exportarCsv(): void {
        $this->requireLogin();

        $tipo = $_GET['tipo'] ?? 'consultas';
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        $datos = $this->obtenerDatosReporte($tipo, $fechaInicio, $fechaFin);

        $filename = "reporte_{$tipo}_{$fechaInicio}_{$fechaFin}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // BOM UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        if (!empty($datos)) {
            fputcsv($output, array_keys($datos[0]), ';');
            foreach ($datos as $fila) {
                fputcsv($output, $fila, ';');
            }
        } else {
            fputcsv($output, ['Mensaje'], ';');
            fputcsv($output, ['No se encontraron registros en el rango seleccionado.'], ';');
        }

        fclose($output);
        exit;
    }

    private function obtenerDatosReporte(string $tipo, string $inicio, string $fin): array {
        $db = Database::getConnection();

        if ($tipo === 'vacunas') {
            $stmt = $db->prepare("
                SELECT av.id AS id_aplicacion, av.fecha_aplicacion, av.proxima_dosis, av.lote,
                       v.nombre AS vacuna, m.nombre AS mascota, m.especie,
                       (p.nombres || ' ' || p.apellidos) AS propietario, p.telefono,
                       vet.nombre AS veterinario
                FROM aplicacion_vacuna av
                JOIN vacuna v ON v.id = av.vacuna_id
                JOIN mascota m ON m.id = av.mascota_id
                JOIN propietario p ON p.id = m.propietario_id
                JOIN veterinario vet ON vet.id = av.veterinario_id
                WHERE av.fecha_aplicacion BETWEEN :inicio AND :fin
                ORDER BY av.fecha_aplicacion DESC
            ");
            $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
            return $stmt->fetchAll();
        }

        if ($tipo === 'mascotas') {
            $stmt = $db->prepare("
                SELECT m.id, m.nombre AS mascota, m.especie, m.raza, m.sexo, m.fecha_nacimiento, m.peso_kg,
                       (p.nombres || ' ' || p.apellidos) AS propietario, p.documento, p.telefono
                FROM mascota m
                JOIN propietario p ON p.id = m.propietario_id
                WHERE m.activo = TRUE
                ORDER BY m.nombre ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        // Consultas
        $stmt = $db->prepare("
            SELECT c.id AS id_consulta, c.fecha, c.motivo, c.diagnostico, c.costo, c.proximo_control,
                   m.nombre AS mascota, m.especie,
                   (p.nombres || ' ' || p.apellidos) AS propietario, p.telefono,
                   v.nombre AS veterinario
            FROM consulta c
            JOIN mascota m ON m.id = c.mascota_id
            JOIN propietario p ON p.id = m.propietario_id
            JOIN veterinario v ON v.id = c.veterinario_id
            WHERE c.fecha::date BETWEEN :inicio AND :fin
            ORDER BY c.fecha DESC
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }
}
