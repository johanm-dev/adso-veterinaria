<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Consulta {
    public static function all(): array {
        $db = Database::getConnection();
        return $db->query("
            SELECT c.*, m.nombre AS mascota_nombre, m.especie,
                   (p.nombres || ' ' || p.apellidos) AS propietario_nombre,
                   v.nombre AS veterinario_nombre
            FROM consulta c
            JOIN mascota m ON m.id = c.mascota_id
            JOIN propietario p ON p.id = m.propietario_id
            JOIN veterinario v ON v.id = c.veterinario_id
            ORDER BY c.fecha DESC
        ")->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT c.*, m.nombre AS mascota_nombre, m.especie, m.raza, m.sexo,
                   (p.nombres || ' ' || p.apellidos) AS propietario_nombre, p.telefono AS propietario_telefono,
                   v.nombre AS veterinario_nombre, v.tarjeta_profesional
            FROM consulta c
            JOIN mascota m ON m.id = c.mascota_id
            JOIN propietario p ON p.id = m.propietario_id
            JOIN veterinario v ON v.id = c.veterinario_id
            WHERE c.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Valida la regla RN-03: No puede haber dos consultas para el mismo veterinario
     * en la misma fecha y hora (timestamp).
     */
    public static function verificarConflictoHorario(int $veterinarioId, string $fechaTimestamp, ?int $excluirConsultaId = null): bool {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM consulta WHERE veterinario_id = :vet_id AND fecha = :fecha";
        $params = [
            'vet_id' => $veterinarioId,
            'fecha'  => $fechaTimestamp
        ];
        if ($excluirConsultaId !== null) {
            $sql .= " AND id != :excluir_id";
            $params['excluir_id'] = $excluirConsultaId;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    public static function create(array $data): int {
        $timestamp = $data['fecha']; // Formato 'YYYY-MM-DD HH:MM:SS'

        // Validación de RN-03
        if (self::verificarConflictoHorario((int)$data['veterinario_id'], $timestamp)) {
            throw new \DomainException("Regla RN-03: El veterinario ya tiene una consulta programada en esa fecha y hora.");
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO consulta (mascota_id, veterinario_id, fecha, motivo, diagnostico, costo, proximo_control)
            VALUES (:mascota_id, :veterinario_id, :fecha, :motivo, :diagnostico, :costo, :proximo_control)
            RETURNING id
        ");

        $stmt->execute([
            'mascota_id'      => (int)$data['mascota_id'],
            'veterinario_id'  => (int)$data['veterinario_id'],
            'fecha'           => $timestamp,
            'motivo'          => trim($data['motivo']),
            'diagnostico'     => !empty($data['diagnostico']) ? trim($data['diagnostico']) : null,
            'costo'           => !empty($data['costo']) ? (float)$data['costo'] : 0.00,
            'proximo_control' => !empty($data['proximo_control']) ? $data['proximo_control'] : null,
        ]);

        return (int)$stmt->fetchColumn();
    }

    public static function getVeterinarios(): array {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM veterinario ORDER BY nombre ASC")->fetchAll();
    }
}
