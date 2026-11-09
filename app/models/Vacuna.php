<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Vacuna {
    public static function all(): array {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM vacuna ORDER BY nombre ASC")->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM vacuna WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Aplica la regla RN-01: Al registrar una vacuna, la próxima dosis se calcula
     * automáticamente sumando intervalo_dias a la fecha de aplicación.
     */
    public static function aplicar(array $data): int {
        $db = Database::getConnection();

        $vacuna = self::findById((int)$data['vacuna_id']);
        if (!$vacuna) {
            throw new \InvalidArgumentException("La vacuna seleccionada no existe en el catálogo.");
        }

        $fechaAplicacion = $data['fecha_aplicacion'];
        $intervaloDias = (int)$vacuna['intervalo_dias'];

        // Cálculo automático de la regla RN-01
        $fechaObj = new \DateTime($fechaAplicacion);
        $fechaObj->modify("+{$intervaloDias} days");
        $proximaDosis = $fechaObj->format('Y-m-d');

        $stmt = $db->prepare("
            INSERT INTO aplicacion_vacuna (mascota_id, vacuna_id, veterinario_id, fecha_aplicacion, proxima_dosis, lote)
            VALUES (:mascota_id, :vacuna_id, :veterinario_id, :fecha_aplicacion, :proxima_dosis, :lote)
            RETURNING id
        ");

        $stmt->execute([
            'mascota_id'        => (int)$data['mascota_id'],
            'vacuna_id'         => (int)$data['vacuna_id'],
            'veterinario_id'    => (int)$data['veterinario_id'],
            'fecha_aplicacion'  => $fechaAplicacion,
            'proxima_dosis'     => $proximaDosis,
            'lote'              => !empty($data['lote']) ? trim($data['lote']) : null,
        ]);

        return (int)$stmt->fetchColumn();
    }

    public static function listadoAplicaciones(): array {
        $db = Database::getConnection();
        return $db->query("
            SELECT av.*, m.nombre AS mascota_nombre, m.especie,
                   (p.nombres || ' ' || p.apellidos) AS propietario_nombre,
                   v.nombre AS vacuna_nombre, vet.nombre AS veterinario_nombre
            FROM aplicacion_vacuna av
            JOIN mascota m ON m.id = av.mascota_id
            JOIN propietario p ON p.id = m.propietario_id
            JOIN vacuna v ON v.id = av.vacuna_id
            JOIN veterinario vet ON vet.id = av.veterinario_id
            ORDER BY av.fecha_aplicacion DESC
        ")->fetchAll();
    }
}
