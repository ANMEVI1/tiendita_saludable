<?php

namespace App\Config;

class Database
{
    /**
     * Retorna el array de configuración de la BD para diferentes entornos
     */
    public static function getConfig()
    {
        return [
            'host'     => App::env('DB_HOST', '127.0.0.1'),
            'database' => App::env('DB_NAME', 'db_tiendita_saludable'),
            'username' => App::env('DB_USER', 'root'),
            'password' => App::env('DB_PASS', ''),
            'charset'  => 'utf8mb4',
            'options'  => [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]
        ];
    }
}
