<?php

namespace App\Models;

use PDO;

class Producto extends Model
{
    protected $table = 'ts_productos';

    public function getCatalogoRegular()
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM {$this->table} p
            LEFT JOIN ts_categorias c ON p.categoria_id = c.id
            WHERE p.eliminado_en IS NULL 
              AND p.activo = 1 
              AND p.es_preventa = 0
            ORDER BY c.nombre ASC, p.nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPreventas()
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM {$this->table} p
            LEFT JOIN ts_categorias c ON p.categoria_id = c.id
            WHERE p.eliminado_en IS NULL 
              AND p.activo = 1 
              AND p.es_preventa = 1
            ORDER BY p.creado_en DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
