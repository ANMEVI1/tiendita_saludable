<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Configuracion {
    public static function getWebConfig() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT clave, valor FROM ts_configuracion_web WHERE activo = 1");
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $config = [];
        foreach ($resultados as $row) {
            $config[$row['clave']] = $row['valor'];
        }
        
        return $config;
    }
}
