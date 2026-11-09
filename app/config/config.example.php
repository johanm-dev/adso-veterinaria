<?php
/**
 * Configuración del Entorno - Veterinaria Digital
 * Copiar a config.local.php para configuración en servidor local
 */

return [
    'db' => [
        'driver'   => 'pgsql',
        'host'     => '127.0.0.1',
        'port'     => 5432,
        'dbname'   => 'veterinaria',
        'user'     => 'app_veterinaria',
        'password' => 'TuClaveSegura123',
        'charset'  => 'utf8'
    ],
    'app' => [
        'name'     => 'Veterinaria Digital',
        'base_url' => 'http://localhost/veterinaria',
        'env'      => 'development',
        'timezone' => 'America/Bogota'
    ]
];
