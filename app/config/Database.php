<?php
namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $configFile = __DIR__ . '/config.local.php';
            if (!file_exists($configFile)) {
                $configFile = __DIR__ . '/config.example.php';
            }

            $config = require $configFile;
            $db = $config['db'];

            $dsn = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s",
                $db['host'],
                $db['port'],
                $db['dbname']
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $db['user'], $db['password'], $options);
            } catch (PDOException $e) {
                throw new RuntimeException("Error al conectar a la base de datos PostgreSQL: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }
}
