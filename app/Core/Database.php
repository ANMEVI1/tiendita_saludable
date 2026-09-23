<?php

namespace App\Core;

use PDO;
use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Database
{
    private static $instance = null;
    private $connection;
    private $logger;

    private function __construct()
    {
        // Inicializar Logger
        $this->logger = new Logger('database');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/db_error.log', Logger::ERROR));

        $dbConfig = \App\Config\Database::getConfig();

        $portStr = !empty($dbConfig['port']) ? ";port={$dbConfig['port']}" : "";
        $dsn = "mysql:host={$dbConfig['host']}{$portStr};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";

        try {
            $this->connection = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
        } catch (PDOException $e) {
            // Guardar el error real en los logs en secreto
            $this->logger->error("Error de conexión PDO: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            // Mostrar vista amigable 500
            http_response_code(500);
            $errorMessage = $e->getMessage();
            require __DIR__ . '/../Views/errors/500.php';
            exit();
        }
    }

    // Prevenir clonación para mantener el Singleton
    private function __clone() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
