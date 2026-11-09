<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Propietario {
    public static function all(string $buscar = ''): array {
        $db = Database::getConnection();
        if (!empty($buscar)) {
            $stmt = $db->prepare("
                SELECT p.*, (p.nombres || ' ' || p.apellidos) AS nombre_completo,
                       COUNT(m.id) AS total_mascotas
                FROM propietario p
                LEFT JOIN mascota m ON m.propietario_id = p.id AND m.activo = TRUE
                WHERE p.nombres ILIKE :q OR p.apellidos ILIKE :q OR p.documento ILIKE :q OR p.telefono ILIKE :q
                GROUP BY p.id
                ORDER BY p.nombres ASC
            ");
            $stmt->execute(['q' => "%$buscar%"]);
        } else {
            $stmt = $db->query("
                SELECT p.*, (p.nombres || ' ' || p.apellidos) AS nombre_completo,
                       COUNT(m.id) AS total_mascotas
                FROM propietario p
                LEFT JOIN mascota m ON m.propietario_id = p.id AND m.activo = TRUE
                GROUP BY p.id
                ORDER BY p.nombres ASC
            ");
        }
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, (p.nombres || ' ' || p.apellidos) AS nombre_completo FROM propietario p WHERE p.id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO propietario (documento, nombres, apellidos, telefono, correo, direccion)
            VALUES (:documento, :nombres, :apellidos, :telefono, :correo, :direccion)
            RETURNING id
        ");
        $stmt->execute([
            'documento' => trim($data['documento']),
            'nombres'   => trim($data['nombres']),
            'apellidos' => trim($data['apellidos']),
            'telefono'  => trim($data['telefono']),
            'correo'    => !empty($data['correo']) ? trim($data['correo']) : null,
            'direccion' => !empty($data['direccion']) ? trim($data['direccion']) : null,
        ]);
        return (int)$stmt->fetchColumn();
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE propietario
            SET documento = :documento,
                nombres   = :nombres,
                apellidos = :apellidos,
                telefono  = :telefono,
                correo    = :correo,
                direccion = :direccion
            WHERE id = :id
        ");
        return $stmt->execute([
            'documento' => trim($data['documento']),
            'nombres'   => trim($data['nombres']),
            'apellidos' => trim($data['apellidos']),
            'telefono'  => trim($data['telefono']),
            'correo'    => !empty($data['correo']) ? trim($data['correo']) : null,
            'direccion' => !empty($data['direccion']) ? trim($data['direccion']) : null,
            'id'        => $id
        ]);
    }

    public static function countMascotas(int $id): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM mascota WHERE propietario_id = :id AND activo = TRUE");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Aplica la regla RN-02: No se puede eliminar un propietario con mascotas registradas.
     */
    public static function delete(int $id): bool {
        if (self::countMascotas($id) > 0) {
            throw new \DomainException("Regla RN-02: No se puede eliminar el propietario porque tiene mascotas activas asociadas.");
        }
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM propietario WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
