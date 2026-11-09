<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Usuario {
    public static function findByEmail(string $correo): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE correo = :correo AND activo = TRUE LIMIT 1");
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, nombre, correo, rol, activo, creado_en FROM usuario WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
