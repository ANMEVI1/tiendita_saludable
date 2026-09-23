<?php
require __DIR__ . '/vendor/autoload.php';

// 1. Cargar variables de entorno de forma segura
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {}

// Inicializar sesión de forma segura
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Forzar codificación UTF-8 a nivel HTTP
header('Content-Type: text/html; charset=utf-8');

// Generar Token CSRF global si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Inicializar el Logger (Monolog) para tener registros profesionales
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$log = new Logger('tiendita');
$log->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::WARNING));

// 3. Inicializar el Router
$router = new \Bramus\Router\Router();

// Middleware: Protección de rutas administrativas
$router->before('GET|POST', '/admin/.*', function() {
    if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
        header('Location: /login');
        exit();
    }
});

// Middleware: Validación de CSRF para métodos POST protegidos
$router->before('POST', '/admin/.*', function() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header('HTTP/1.1 403 Forbidden');
        die('Error CSRF: Solicitud no autorizada.');
    }
});

// ----------------------------------------------------
// RUTAS PRINCIPALES (Fase 2: Conectadas a BD)
// ----------------------------------------------------

$router->get('/', 'App\Controllers\HomeController@index');
$router->get('/preventa', 'App\Controllers\HomeController@preventa');
$router->get('/sobre-nosotros', 'App\Controllers\HomeController@sobreNosotros');

// ----------------------------------------------------
// RUTAS API CHECKOUT Y COMPRA
// ----------------------------------------------------
$router->get('/api/checkout/metodos-pago', 'App\Controllers\ShopController@getMetodosPago');
$router->post('/api/checkout/procesar', 'App\Controllers\ShopController@procesarPedido');

// ----------------------------------------------------
// RUTAS API CLIENTE
// ----------------------------------------------------
$router->get('/api/cliente/perfil', 'App\Controllers\ClientController@getPerfil');
$router->post('/api/cliente/update', 'App\Controllers\ClientController@updatePerfil');
$router->get('/api/cliente/pedidos', 'App\Controllers\ClientController@getPedidos');

// ----------------------------------------------------
// RUTAS DE ADMINISTRACIÓN (Protegidas por Middleware)
// ----------------------------------------------------

$router->get('/admin', 'App\Controllers\AdminController@dashboard');
$router->get('/admin/productos', 'App\Controllers\AdminController@productos');
$router->post('/admin/productos/store', 'App\Controllers\AdminController@storeProducto');
$router->post('/admin/productos/update', 'App\Controllers\AdminController@updateProducto');
$router->post('/admin/productos/variantes/update', 'App\Controllers\AdminController@updateVariantes');
$router->post('/admin/productos/delete', 'App\Controllers\AdminController@deleteProducto');
$router->post('/admin/categorias/update', 'App\Controllers\AdminController@updateCategoria');

// RUTAS CRUD USUARIOS
$router->get('/admin/usuarios', 'App\Controllers\AdminController@usuarios');
$router->post('/admin/usuarios/store', 'App\Controllers\AdminController@storeUsuario');
$router->post('/admin/usuarios/update', 'App\Controllers\AdminController@updateUsuario');
$router->post('/admin/usuarios/delete', 'App\Controllers\AdminController@deleteUsuario');

// RUTAS CRUD CLIENTES
$router->get('/admin/clientes', 'App\Controllers\AdminController@clientes');
$router->post('/admin/clientes/store', 'App\Controllers\AdminController@storeCliente');
$router->post('/admin/clientes/update', 'App\Controllers\AdminController@updateCliente');
$router->post('/admin/clientes/delete', 'App\Controllers\AdminController@deleteCliente');

// RUTAS METODOS DE PAGO
$router->get('/admin/metodos-pago', 'App\Controllers\AdminController@metodosPago');
$router->post('/admin/metodos-pago/store', 'App\Controllers\AdminController@storeMetodoPago');
$router->post('/admin/metodos-pago/update', 'App\Controllers\AdminController@updateMetodoPago');
$router->post('/admin/metodos-pago/delete', 'App\Controllers\AdminController@deleteMetodoPago');

// RUTAS PEDIDOS
$router->get('/admin/pedidos', 'App\Controllers\AdminController@pedidos');
$router->post('/admin/pedidos/update-status', 'App\Controllers\AdminController@updatePedidoStatus');
$router->get('/admin/pedidos/detalle', 'App\Controllers\AdminController@getPedidoDetalle');

$router->get('/admin/configuracion', 'App\Controllers\AdminController@configuracion');
$router->post('/admin/configuracion/update', 'App\Controllers\AdminController@updateConfiguracion');
$router->post('/admin/cambiar-password', 'App\Controllers\AuthController@updatePassword');

// ----------------------------------------------------
// AUTENTICACIÓN
// ----------------------------------------------------

$router->get('/login', 'App\Controllers\AuthController@showLogin');
$router->post('/login', 'App\Controllers\AuthController@login');
$router->get('/logout', 'App\Controllers\AuthController@logout');
$router->post('/logout', 'App\Controllers\AuthController@logout');
$router->get('/registro', 'App\Controllers\AuthController@showRegistro');
$router->post('/registro', 'App\Controllers\AuthController@registro');
$router->get('/logout', 'App\Controllers\AuthController@logout');

// ----------------------------------------------------
// MANEJO DE ERRORES 404
// ----------------------------------------------------
$router->set404(function() use ($log) {
    header('HTTP/1.1 404 Not Found');
    $log->warning("Página no encontrada: " . $_SERVER['REQUEST_URI']);
    echo "<div style='font-family: sans-serif; text-align:center; margin-top:50px;'><h1>404 - Página no encontrada</h1><p><a href=\"/\">Volver al inicio</a></p></div>";
});

// Arrancar la aplicación
$router->run();
