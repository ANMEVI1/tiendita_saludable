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
            'usuarios' => $db->query("SELECT COUNT(*) FROM ts_usuarios WHERE eliminado_en IS NULL")->fetchColumn(),
            'clientes' => $db->query("SELECT COUNT(*) FROM ts_clientes WHERE eliminado_en IS NULL")->fetchColumn()
        ];

        // Recientes para el resumen del Dashboard
        $ultimosProductos = $db->query("SELECT p.*, c.nombre as categoria_nombre FROM ts_productos p JOIN ts_categorias c ON p.categoria_id = c.id WHERE p.eliminado_en IS NULL ORDER BY p.id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        $ultimosUsuarios = $db->query("SELECT u.*, r.nombre as rol_nombre FROM ts_usuarios u JOIN ts_roles r ON u.rol_id = r.id WHERE u.eliminado_en IS NULL ORDER BY u.id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

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
        
        foreach ($_POST as $clave => $valor) {
            if ($clave !== 'csrf_token') {
                $valorLimpio = is_string($valor) ? trim(strip_tags($valor)) : $valor;
                
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

    public function storeProducto()
    {
        $db = Database::getInstance()->getConnection();
        
        $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
        $nombre = isset($_POST['nombre']) ? trim(strip_tags($_POST['nombre'])) : '';
        $descripcion = isset($_POST['descripcion']) ? trim(strip_tags($_POST['descripcion'])) : '';
        $precio_regular = filter_input(INPUT_POST, 'precio_regular', FILTER_VALIDATE_FLOAT);
        $es_preventa = isset($_POST['es_preventa']) ? 1 : 0;

        if (!$nombre || !$categoria_id) {
            $_SESSION['error_msg'] = 'El nombre y la categoría son obligatorios.';
            header('Location: /admin/productos');
            exit();
        }

        $descripcion = $descripcion === '' ? null : $descripcion;
        $precio_regular = ($precio_regular !== false && $precio_regular !== null) ? $precio_regular : null;

        $imagen_url = $this->handleImageUpload();

        $stmt = $db->prepare("INSERT INTO ts_productos (categoria_id, nombre, descripcion, imagen_url, precio_regular, es_preventa) VALUES (:categoria_id, :nombre, :descripcion, :imagen_url, :precio, :es_preventa)");
        $stmt->execute([
            'categoria_id' => $categoria_id,
            'nombre'       => $nombre,
            'descripcion'  => $descripcion,
            'imagen_url'   => $imagen_url,
            'precio'       => $precio_regular,
            'es_preventa'  => $es_preventa
        ]);

        $_SESSION['success_msg'] = 'Producto creado exitosamente.';
        header('Location: /admin/productos');
        exit();
    }

    public function updateProducto()
    {
        $db = Database::getInstance()->getConnection();
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
        $nombre = isset($_POST['nombre']) ? trim(strip_tags($_POST['nombre'])) : '';
        $descripcion = isset($_POST['descripcion']) ? trim(strip_tags($_POST['descripcion'])) : '';
        $precio_regular = filter_input(INPUT_POST, 'precio_regular', FILTER_VALIDATE_FLOAT);
        $es_preventa = isset($_POST['es_preventa']) ? 1 : 0;

        if (!$id || !$nombre || !$categoria_id) {
            $_SESSION['error_msg'] = 'Datos inválidos o faltantes.';
            header('Location: /admin/productos');
            exit();
        }

        $descripcion = $descripcion === '' ? null : $descripcion;
        $precio_regular = ($precio_regular !== false && $precio_regular !== null) ? $precio_regular : null;

        $nueva_imagen = $this->handleImageUpload();
        
        if ($nueva_imagen !== null && $nueva_imagen !== '') {
            $stmt = $db->prepare("UPDATE ts_productos SET nombre = :nombre, descripcion = :descripcion, precio_regular = :precio, categoria_id = :categoria_id, imagen_url = :imagen_url, es_preventa = :es_preventa WHERE id = :id");
            $stmt->execute([
                'nombre'       => $nombre,
                'descripcion'  => $descripcion,
                'precio'       => $precio_regular,
                'categoria_id' => $categoria_id,
                'imagen_url'   => $nueva_imagen,
                'es_preventa'  => $es_preventa,
                'id'           => $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE ts_productos SET nombre = :nombre, descripcion = :descripcion, precio_regular = :precio, categoria_id = :categoria_id, es_preventa = :es_preventa WHERE id = :id");
            $stmt->execute([
                'nombre'       => $nombre,
                'descripcion'  => $descripcion,
                'precio'       => $precio_regular,
                'categoria_id' => $categoria_id,
                'es_preventa'  => $es_preventa,
                'id'           => $id
            ]);
        }

        $_SESSION['success_msg'] = 'Producto actualizado exitosamente.';
        header('Location: /admin/productos');
        exit();
    }

    public function deleteProducto()
    {
        $db = Database::getInstance()->getConnection();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $stmt = $db->prepare("UPDATE ts_productos SET eliminado_en = NOW() WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $_SESSION['success_msg'] = 'Producto eliminado correctamente.';
        }

        header('Location: /admin/productos');
        exit();
    }

    public function updateVariantes()
    {
        $db = Database::getInstance()->getConnection();
        $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
        
        if (!$producto_id) {
            $_SESSION['error_msg'] = 'ID de producto no válido.';
            header('Location: /admin/productos');
            exit();
        }

        $variantes = $_POST['variantes'] ?? [];
        $caracteristicas = $_POST['caracteristicas'] ?? [];

        try {
            $db->beginTransaction();

            // 1. Limpiar variantes y características anteriores
            $stmtDelVar = $db->prepare("DELETE FROM ts_producto_variantes WHERE producto_id = :id");
            $stmtDelVar->execute(['id' => $producto_id]);

            $stmtDelCar = $db->prepare("DELETE FROM ts_producto_caracteristicas WHERE producto_id = :id");
            $stmtDelCar->execute(['id' => $producto_id]);

            // 2. Insertar las nuevas variantes
            if (!empty($variantes)) {
                $stmtInsVar = $db->prepare("INSERT INTO ts_producto_variantes (producto_id, nombre, subtitulo, precio, precio_secundario, texto_secundario, orden) VALUES (:pid, :nombre, :sub, :precio, :presec, :txtsec, :orden)");
                $orden = 1;
                foreach ($variantes as $v) {
                    if (trim($v['nombre']) === '' || trim($v['precio']) === '') continue; // Skip empty rows
                    $stmtInsVar->execute([
                        'pid' => $producto_id,
                        'nombre' => trim(strip_tags($v['nombre'])),
                        'sub' => !empty($v['subtitulo']) ? trim(strip_tags($v['subtitulo'])) : null,
                        'precio' => floatval($v['precio']),
                        'presec' => !empty($v['precio_secundario']) ? floatval($v['precio_secundario']) : null,
                        'txtsec' => !empty($v['texto_secundario']) ? trim(strip_tags($v['texto_secundario'])) : null,
                        'orden' => $orden++
                    ]);
                }
            }

            // 3. Insertar las nuevas características
            if (!empty($caracteristicas)) {
                $stmtInsCar = $db->prepare("INSERT INTO ts_producto_caracteristicas (producto_id, grupo, valor, orden) VALUES (:pid, :grupo, :valor, :orden)");
                $orden = 1;
                foreach ($caracteristicas as $c) {
                    if (trim($c['grupo']) === '' || trim($c['valor']) === '') continue; // Skip empty rows
                    $stmtInsCar->execute([
                        'pid' => $producto_id,
                        'grupo' => trim(strip_tags($c['grupo'])),
                        'valor' => trim(strip_tags($c['valor'])),
                        'orden' => $orden++
                    ]);
                }
            }

            $db->commit();
            $_SESSION['success_msg'] = 'Opciones actualizadas exitosamente.';
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error_msg'] = 'Ocurrió un error al guardar: ' . $e->getMessage();
        }

        header('Location: /admin/productos');
        exit();
    }

    public function updateCategoria()
    {
        $db = Database::getInstance()->getConnection();
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombre = isset($_POST['nombre']) ? trim(strip_tags($_POST['nombre'])) : '';
        $descripcion = isset($_POST['descripcion']) ? trim(strip_tags($_POST['descripcion'])) : '';
        $plantilla_html = isset($_POST['plantilla_html']) ? trim(strip_tags($_POST['plantilla_html'])) : 'ESTANDAR';

        if (!$id || !$nombre) {
            $_SESSION['error_msg'] = 'Datos inválidos o faltantes para la categoría.';
            header('Location: /admin/productos');
            exit();
        }

        $descripcion = $descripcion === '' ? null : $descripcion;

        $stmt = $db->prepare("UPDATE ts_categorias SET nombre = :nombre, descripcion = :descripcion, plantilla_html = :plantilla_html WHERE id = :id");
        $stmt->execute([
            'nombre'         => $nombre,
            'descripcion'    => $descripcion,
            'plantilla_html' => $plantilla_html,
            'id'             => $id
        ]);

        $_SESSION['success_msg'] = 'Categoría actualizada exitosamente.';
        header('Location: /admin/productos');
        exit();
    }

    // ----------------------------------------------------
    // CRUD USUARIOS
    // ----------------------------------------------------
    public function usuarios()
    {
        $db = Database::getInstance()->getConnection();
        $usuarios = $db->query("SELECT u.*, r.nombre as rol_nombre FROM ts_usuarios u JOIN ts_roles r ON u.rol_id = r.id WHERE u.eliminado_en IS NULL ORDER BY u.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $roles = $db->query("SELECT * FROM ts_roles WHERE activo = 1")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/pages/admin/usuarios.php';
    }

    public function storeUsuario()
    {
        $db = Database::getInstance()->getConnection();

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $rol_id = filter_input(INPUT_POST, 'rol_id', FILTER_VALIDATE_INT);

        if (!$email || empty($password) || !$rol_id) {
            $_SESSION['error_msg'] = 'Email, contraseña y rol son obligatorios.';
            header('Location: /admin/usuarios');
            exit();
        }

        // Verificar si email ya existe
        $checkStmt = $db->prepare("SELECT id FROM ts_usuarios WHERE email = :email AND eliminado_en IS NULL LIMIT 1");
        $checkStmt->execute(['email' => $email]);
        if ($checkStmt->fetch()) {
            $_SESSION['error_msg'] = 'El correo electrónico ya está registrado en el sistema.';
            header('Location: /admin/usuarios');
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO ts_usuarios (email, password_hash, rol_id, activo) VALUES (:email, :hash, :rol_id, 1)");
        $stmt->execute([
            'email'  => $email,
            'hash'   => $password_hash,
            'rol_id' => $rol_id
        ]);

        $_SESSION['success_msg'] = 'Usuario creado exitosamente.';
        header('Location: /admin/usuarios');
        exit();
    }

    public function updateUsuario()
    {
        $db = Database::getInstance()->getConnection();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $rol_id = filter_input(INPUT_POST, 'rol_id', FILTER_VALIDATE_INT);
        $activo = isset($_POST['activo']) ? 1 : 0;
        $password = $_POST['password'] ?? '';

        if (!$id || !$email || !$rol_id) {
            $_SESSION['error_msg'] = 'Datos inválidos para actualizar el usuario.';
            header('Location: /admin/usuarios');
            exit();
        }

        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE ts_usuarios SET email = :email, rol_id = :rol_id, activo = :activo, password_hash = :hash WHERE id = :id");
            $stmt->execute([
                'email'  => $email,
                'rol_id' => $rol_id,
                'activo' => $activo,
                'hash'   => $password_hash,
                'id'     => $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE ts_usuarios SET email = :email, rol_id = :rol_id, activo = :activo WHERE id = :id");
            $stmt->execute([
                'email'  => $email,
                'rol_id' => $rol_id,
                'activo' => $activo,
                'id'     => $id
            ]);
        }

        $_SESSION['success_msg'] = 'Usuario actualizado exitosamente.';
        header('Location: /admin/usuarios');
        exit();
    }

    public function deleteUsuario()
    {
        $db = Database::getInstance()->getConnection();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            if ($id == $_SESSION['user_id']) {
                $_SESSION['error_msg'] = 'No puedes eliminar tu propia cuenta de usuario activa.';
            } else {
                $stmt = $db->prepare("UPDATE ts_usuarios SET eliminado_en = NOW(), activo = 0 WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $_SESSION['success_msg'] = 'Usuario eliminado correctamente.';
            }
        }

        header('Location: /admin/usuarios');
        exit();
    }

    // ----------------------------------------------------
    // CRUD CLIENTES
    // ----------------------------------------------------
    public function clientes()
    {
        $db = Database::getInstance()->getConnection();
        $clientes = $db->query("SELECT c.*, u.email as usuario_email FROM ts_clientes c JOIN ts_usuarios u ON c.usuario_id = u.id WHERE c.eliminado_en IS NULL ORDER BY c.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Usuarios que aún no tienen cliente asociado
        $usuariosDisponibles = $db->query("SELECT u.id, u.email FROM ts_usuarios u LEFT JOIN ts_clientes c ON u.id = c.usuario_id WHERE c.id IS NULL AND u.eliminado_en IS NULL")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/pages/admin/clientes.php';
    }

    public function storeCliente()
    {
        $db = Database::getInstance()->getConnection();

        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $nombres = isset($_POST['nombres']) ? trim(strip_tags($_POST['nombres'])) : '';
        $apellidos = isset($_POST['apellidos']) ? trim(strip_tags($_POST['apellidos'])) : '';
        $tipo_documento = isset($_POST['tipo_documento']) ? trim(strip_tags($_POST['tipo_documento'])) : 'DNI';
        $nro_documento = isset($_POST['nro_documento']) ? trim(strip_tags($_POST['nro_documento'])) : '';
        $telefono_whatsapp = isset($_POST['telefono_whatsapp']) ? trim(strip_tags($_POST['telefono_whatsapp'])) : '';
        $direccion = isset($_POST['direccion']) ? trim(strip_tags($_POST['direccion'])) : '';

        if (!$usuario_id || !$nombres || !$apellidos) {
            $_SESSION['error_msg'] = 'Usuario, nombres y apellidos son campos obligatorios.';
            header('Location: /admin/clientes');
            exit();
        }

        $stmt = $db->prepare("INSERT INTO ts_clientes (usuario_id, nombres, apellidos, tipo_documento, nro_documento, telefono_whatsapp, direccion, activo) VALUES (:usuario_id, :nombres, :apellidos, :tipo_documento, :nro_documento, :telefono, :direccion, 1)");
        $stmt->execute([
            'usuario_id'     => $usuario_id,
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'tipo_documento' => $tipo_documento,
            'nro_documento'  => $nro_documento ?: null,
            'telefono'       => $telefono_whatsapp ?: null,
            'direccion'      => $direccion ?: null
        ]);

        $_SESSION['success_msg'] = 'Perfil de cliente registrado con éxito.';
        header('Location: /admin/clientes');
        exit();
    }

    public function updateCliente()
    {
        $db = Database::getInstance()->getConnection();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombres = isset($_POST['nombres']) ? trim(strip_tags($_POST['nombres'])) : '';
        $apellidos = isset($_POST['apellidos']) ? trim(strip_tags($_POST['apellidos'])) : '';
        $tipo_documento = isset($_POST['tipo_documento']) ? trim(strip_tags($_POST['tipo_documento'])) : 'DNI';
        $nro_documento = isset($_POST['nro_documento']) ? trim(strip_tags($_POST['nro_documento'])) : '';
        $telefono_whatsapp = isset($_POST['telefono_whatsapp']) ? trim(strip_tags($_POST['telefono_whatsapp'])) : '';
        $direccion = isset($_POST['direccion']) ? trim(strip_tags($_POST['direccion'])) : '';
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (!$id || !$nombres || !$apellidos) {
            $_SESSION['error_msg'] = 'Nombres y apellidos son campos obligatorios.';
            header('Location: /admin/clientes');
            exit();
        }

        $stmt = $db->prepare("UPDATE ts_clientes SET nombres = :nombres, apellidos = :apellidos, tipo_documento = :tipo_doc, nro_documento = :nro_doc, telefono_whatsapp = :telefono, direccion = :direccion, activo = :activo WHERE id = :id");
        $stmt->execute([
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'tipo_doc'       => $tipo_documento,
            'nro_doc'        => $nro_documento ?: null,
            'telefono'       => $telefono_whatsapp ?: null,
            'direccion'      => $direccion ?: null,
            'activo'         => $activo,
            'id'             => $id
        ]);

        $_SESSION['success_msg'] = 'Perfil de cliente actualizado con éxito.';
        header('Location: /admin/clientes');
        exit();
    }

    public function deleteCliente()
    {
        $db = Database::getInstance()->getConnection();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $stmt = $db->prepare("UPDATE ts_clientes SET eliminado_en = NOW(), activo = 0 WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $_SESSION['success_msg'] = 'Perfil de cliente eliminado correctamente.';
        }

        header('Location: /admin/clientes');
        exit();
    }

    /**
     * Procesa la subida de imagen de producto de forma profesional y segura
     */
    private function handleImageUpload(): ?string
    {
        if (isset($_FILES['imagen_archivo']) && $_FILES['imagen_archivo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['imagen_archivo'];
            $maxSize = 5 * 1024 * 1024;
            
            if ($file['size'] > $maxSize) {
                return null;
            }

            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mimeType = '';
            
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
            } else {
                $mimeType = $file['type'];
            }

            if (!in_array($mimeType, $allowedMimes)) {
                return null;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!$extension) {
                $extension = str_replace('image/', '', $mimeType);
                if ($extension === 'jpeg') $extension = 'jpg';
            }

            $filename = 'prod_' . bin2hex(random_bytes(8)) . '.' . strtolower($extension);
            $uploadDir = __DIR__ . '/../../assets/uploads/productos/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return 'assets/uploads/productos/' . $filename;
            }
        }

        if (!empty($_POST['imagen_url_input'])) {
            $url = trim(strip_tags($_POST['imagen_url_input']));
            if (filter_var($url, FILTER_VALIDATE_URL) || strpos($url, 'assets/') === 0 || strpos($url, '/') === 0) {
                return $url;
            }
        }

        return null;
    }
}
