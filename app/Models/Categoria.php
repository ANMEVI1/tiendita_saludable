<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Categoria extends Model
{
    protected $table = 'ts_categorias';

    public function getCatalogoCompleto()
    {
        // Traer categorías activas con sus productos, ordenadas
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE activo = 1 AND eliminado_en IS NULL
            ORDER BY orden ASC
        ");
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categorias as &$cat) {
            $cat['productos'] = $this->getProductosPorCategoria($cat['id']);
        }

        return $categorias;
    }

    private function getProductosPorCategoria($categoria_id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM ts_productos 
            WHERE categoria_id = :cat_id AND activo = 1 AND eliminado_en IS NULL
            ORDER BY orden ASC
        ");
        $stmt->execute(['cat_id' => $categoria_id]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($productos as &$prod) {
            $prod['variantes'] = $this->getVariantesProducto($prod['id']);
            $prod['caracteristicas'] = $this->getCaracteristicasProducto($prod['id']);
        }

        return $productos;
    }

    private function getVariantesProducto($producto_id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM ts_producto_variantes 
            WHERE producto_id = :prod_id 
            ORDER BY orden ASC
        ");
        $stmt->execute(['prod_id' => $producto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCaracteristicasProducto($producto_id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM ts_producto_caracteristicas 
            WHERE producto_id = :prod_id 
            ORDER BY orden ASC
        ");
        $stmt->execute(['prod_id' => $producto_id]);
        
        $caracteristicas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $agrupadas = [];
        foreach ($caracteristicas as $c) {
            $grupo = $c['grupo'] ?? 'general';
            if (!isset($agrupadas[$grupo])) {
                $agrupadas[$grupo] = [];
            }
            $agrupadas[$grupo][] = $c['valor'];
        }
        return $agrupadas;
    }
}
