<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Categoria;
use App\Models\Configuracion;
use PDO;

class AdminController
{
    public function dashboard()
    {
        $db = Database::getInstance()->getConnection();
        
        $stats = [
            'productos' => $db->query("SELECT COUNT(*) FROM ts_productos WHERE eliminado_en IS NULL")->fetchColumn(),
            'categorias' => $db->query("SELECT COUNT(*) FROM ts_categorias WHERE eliminado_en IS NULL")->fetchColumn(),
            'pedidos' => 0 // Futura implementación
        ];

        require __DIR__ . '/../Views/pages/admin/dashboard.php';
    }

    public function productos()
    {
        $catModel = new Categoria();
        $categorias = $catModel->getCatalogoCompleto();
        
        require __DIR__ . '/../Views/pages/admin/productos.php';
    }

    public function configuracion()
    {
        $configWeb = Configuracion::getWebConfig();
        require __DIR__ . '/../Views/pages/admin/configuracion.php';
    }

    public function updateConfiguracion()
    {
        $db = Database::getInstance()->getConnection();
        
        // Loop a través de los valores enviados (asegurándose que existan en BD)
        // Sanitización básica: strip_tags para quitar HTML no deseado.
        foreach ($_POST as $clave => $valor) {
            if ($clave !== 'csrf_token') {
                $valorLimpio = strip_tags($valor);
                
                $stmt = $db->prepare("UPDATE ts_configuracion_web SET valor = :valor WHERE clave = :clave");
                $stmt->execute([
                    'valor' => $valorLimpio,
                    'clave' => $clave
                ]);
            }
        }

        $_SESSION['success_msg'] = 'Configuración actualizada correctamente.';
        header('Location: /admin/configuracion');
        exit();
    }

    public function updateProducto()
    {
        $db = Database::getInstance()->getConnection();
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
        $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING));
        $precio_regular = filter_input(INPUT_POST, 'precio_regular', FILTER_VALIDATE_FLOAT);

        if (!$id || !$nombre || !$categoria_id) {
            $_SESSION['error_msg'] = 'Datos inválidos o faltantes.';
            header('Location: /admin/productos');
            exit();
        }

        // Si la descripción está vacía, la guardamos como nula
        $descripcion = $descripcion === '' ? null : $descripcion;
        // Si el precio está vacío (0 o false), lo guardamos como nulo
        $precio_regular = $precio_regular ? $precio_regular : null;

        $stmt = $db->prepare("UPDATE ts_productos SET nombre = :nombre, descripcion = :descripcion, precio_regular = :precio, categoria_id = :categoria_id WHERE id = :id");
        $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio_regular,
            'categoria_id' => $categoria_id,
            'id' => $id
        ]);

        $_SESSION['success_msg'] = 'Producto actualizado exitosamente.';
        header('Location: /admin/productos');
        exit();
    }

    public function updateCategoria()
    {
        $db = Database::getInstance()->getConnection();
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
        $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING));

        if (!$id || !$nombre) {
            $_SESSION['error_msg'] = 'Datos inválidos o faltantes para la categoría.';
            header('Location: /admin/productos');
            exit();
        }

        $descripcion = $descripcion === '' ? null : $descripcion;

        $stmt = $db->prepare("UPDATE ts_categorias SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'id' => $id
        ]);

        $_SESSION['success_msg'] = 'Categoría actualizada exitosamente.';
        header('Location: /admin/productos');
        exit();
    }
}
