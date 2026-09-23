<?php

namespace App\Controllers;

use App\Models\Producto;

class HomeController
{
    public function index()
    {
        $configWeb = \App\Models\Configuracion::getWebConfig();
        
        $limiteCategoria = isset($configWeb['limite_productos_categoria_inicio']) ? (int)$configWeb['limite_productos_categoria_inicio'] : 8;

        $categoriaModel = new \App\Models\Categoria();
        $catalogo = $categoriaModel->getCatalogoCompleto($limiteCategoria);

        require __DIR__ . '/../Views/pages/home.php';
    }

    public function preventa()
    {
        $configWeb = \App\Models\Configuracion::getWebConfig();
        
        $limitePreventa = isset($configWeb['limite_productos_preventa_inicio']) ? (int)$configWeb['limite_productos_preventa_inicio'] : 4;

        $productoModel = new Producto();
        $preventas = $productoModel->getPreventas($limitePreventa);

        require __DIR__ . '/../Views/pages/preventa.php';
    }

    public function sobreNosotros()
    {
        $configWeb = \App\Models\Configuracion::getWebConfig();
        require __DIR__ . '/../Views/pages/sobre_nosotros.php';
    }
}
