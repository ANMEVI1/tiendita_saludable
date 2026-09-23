<?php

namespace App\Controllers;

use App\Models\Producto;

class HomeController
{
    public function index()
    {
        $categoriaModel = new \App\Models\Categoria();
        $catalogo = $categoriaModel->getCatalogoCompleto();

        $configWeb = \App\Models\Configuracion::getWebConfig();

        require __DIR__ . '/../Views/pages/home.php';
    }

    public function preventa()
    {
        $productoModel = new Producto();
        $preventas = $productoModel->getPreventas();

        $configWeb = \App\Models\Configuracion::getWebConfig();

        require __DIR__ . '/../Views/pages/preventa.php';
    }

    public function sobreNosotros()
    {
        $configWeb = \App\Models\Configuracion::getWebConfig();
        require __DIR__ . '/../Views/pages/sobre_nosotros.php';
    }
}
