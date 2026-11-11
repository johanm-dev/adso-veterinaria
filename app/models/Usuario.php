<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Usuario {
    public const ROLES_PERMITIDOS = ['admin', 'veterinario', 'recepcion'];

    public static function all(): array {
        $db = Database::getConnection();
        return $db->query("
            SELECT u.id, u.nombre, u.correo, u.rol, u.activo, u.creado_en,
                   v.tarjeta_profesional, v.especialidad, v.telefono AS vet_telefono
            FROM usuario u
            LEFT JOIN veterinario v ON v.usuario_id = u.id
            ORDER BY u.id DESC
        ")->fetchAll();
    }

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

    /**
     * Crea un usuario nuevo con contraseña cifrada en Bcrypt.
     * Si el rol es 'veterinario', también registra su perfil profesional en la tabla veterinario.
     */
    public static function create(array $data): int {
        $rol = $data['rol'] ?? '';
        if (!in_array($rol, self::ROLES_PERMITIDOS, true)) {
            throw new \InvalidArgumentException("Rol no válido. Debe ser admin, veterinario o recepcion.");
        }

        $nombre = trim($data['nombre'] ?? '');
        $correo = strtolower(trim($data['correo'] ?? ''));
        $password = $data['password'] ?? '';

        if ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            throw new \InvalidArgumentException("Nombre válido, correo electrónico válido y contraseña de al menos 6 caracteres son obligatorios.");
        }

        if ($rol === 'veterinario' && empty(trim($data['tarjeta_profesional'] ?? ''))) {
            throw new \InvalidArgumentException("La tarjeta profesional es obligatoria para el rol Veterinario.");
        }

        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("
                INSERT INTO usuario (nombre, correo, password_hash, rol, activo)
                VALUES (:nombre, :correo, :password_hash, :rol, TRUE)
                RETURNING id
            ");
            $stmt->execute([
                'nombre'        => $nombre,
                'correo'        => $correo,
                'password_hash' => $hash,
                'rol'           => $rol,
            ]);
            $usuarioId = (int)$stmt->fetchColumn();

            if ($rol === 'veterinario') {
                $stmtVet = $db->prepare("
                    INSERT INTO veterinario (usuario_id, nombre, tarjeta_profesional, especialidad, telefono)
                    VALUES (:usuario_id, :nombre, :tarjeta_profesional, :especialidad, :telefono)
                ");
                $stmtVet->execute([
                    'usuario_id'          => $usuarioId,
                    'nombre'              => $nombre,
                    'tarjeta_profesional' => trim($data['tarjeta_profesional']),
                    'especialidad'        => !empty($data['especialidad']) ? trim($data['especialidad']) : null,
                    'telefono'            => !empty($data['telefono']) ? trim($data['telefono']) : null,
                ]);
            }

            $db->commit();
            return $usuarioId;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    public static function toggleActivo(int $id, int $actorId): bool {
        if ($id === $actorId) {
            throw new \DomainException("No puedes desactivar tu propia cuenta de administrador mientras tienes sesión activa.");
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE usuario SET activo = NOT activo WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
