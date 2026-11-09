<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Mascota {
    public static function all(string $buscar = '', string $especie = ''): array {
        $db = Database::getConnection();
        $sql = "
            SELECT m.*, (p.nombres || ' ' || p.apellidos) AS propietario_nombre,
                   p.telefono AS propietario_telefono, p.documento AS propietario_doc
            FROM mascota m
            JOIN propietario p ON p.id = m.propietario_id
            WHERE m.activo = TRUE
        ";
        $params = [];

        if (!empty($buscar)) {
            $sql .= " AND (m.nombre ILIKE :q OR p.nombres ILIKE :q OR p.apellidos ILIKE :q OR p.documento ILIKE :q)";
            $params['q'] = "%$buscar%";
        }

        if (!empty($especie)) {
            $sql .= " AND m.especie = :especie";
            $params['especie'] = $especie;
        }

        $sql .= " ORDER BY m.nombre ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT m.*, (p.nombres || ' ' || p.apellidos) AS propietario_nombre,
                   p.telefono AS propietario_telefono, p.correo AS propietario_email,
                   p.documento AS propietario_doc, p.direccion AS propietario_dir
            FROM mascota m
            JOIN propietario p ON p.id = m.propietario_id
            WHERE m.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO mascota (nombre, especie, raza, sexo, fecha_nacimiento, peso_kg, propietario_id)
            VALUES (:nombre, :especie, :raza, :sexo, :fecha_nacimiento, :peso_kg, :propietario_id)
            RETURNING id
        ");
        $stmt->execute([
            'nombre'           => trim($data['nombre']),
            'especie'          => $data['especie'],
            'raza'             => !empty($data['raza']) ? trim($data['raza']) : null,
            'sexo'             => $data['sexo'],
            'fecha_nacimiento' => !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null,
            'peso_kg'          => !empty($data['peso']) ? (float)$data['peso'] : null,
            'propietario_id'   => (int)$data['propietario_id'],
        ]);
        return (int)$stmt->fetchColumn();
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE mascota
            SET nombre           = :nombre,
                especie          = :especie,
                raza             = :raza,
                sexo             = :sexo,
                fecha_nacimiento = :fecha_nacimiento,
                peso_kg          = :peso_kg,
                propietario_id   = :propietario_id
            WHERE id = :id
        ");
        return $stmt->execute([
            'nombre'           => trim($data['nombre']),
            'especie'          => $data['especie'],
            'raza'             => !empty($data['raza']) ? trim($data['raza']) : null,
            'sexo'             => $data['sexo'],
            'fecha_nacimiento' => !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null,
            'peso_kg'          => !empty($data['peso']) ? (float)$data['peso'] : null,
            'propietario_id'   => (int)$data['propietario_id'],
            'id'               => $id
        ]);
    }

    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE mascota SET activo = FALSE WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function getHistorialConsultas(int $mascotaId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT c.*, v.nombre AS veterinario_nombre, v.tarjeta_profesional
            FROM consulta c
            JOIN veterinario v ON v.id = c.veterinario_id
            WHERE c.mascota_id = :id
            ORDER BY c.fecha DESC
        ");
        $stmt->execute(['id' => $mascotaId]);
        return $stmt->fetchAll();
    }

    public static function getHistorialVacunas(int $mascotaId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT av.*, v.nombre AS vacuna_nombre, v.intervalo_dias,
                   vet.nombre AS veterinario_nombre
            FROM aplicacion_vacuna av
            JOIN vacuna v ON v.id = av.vacuna_id
            JOIN veterinario vet ON vet.id = av.veterinario_id
            WHERE av.mascota_id = :id
            ORDER BY av.fecha_aplicacion DESC
        ");
        $stmt->execute(['id' => $mascotaId]);
        return $stmt->fetchAll();
    }
}
