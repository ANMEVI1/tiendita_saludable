<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AuthController
{
    public function showLogin()
    {
        // Si ya está logueado y es admin, redirigir al admin
        if (isset($_SESSION['user_id']) && isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1) {
            header('Location: /admin');
            exit();
        }
        
        // Render view sin layout base (pantalla completa)
        require __DIR__ . '/../Views/pages/auth/login.php';
    }

    public function login()
    {
        // Validación CSRF
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $_SESSION['error_auth'] = 'Token CSRF inválido.';
            header('Location: /login');
            exit();
        }

        // Sanitización y límites
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error_auth'] = 'Por favor, complete todos los campos.';
            header('Location: /login');
            exit();
        }

        if (strlen($email) > 150 || strlen($password) > 100) {
            $_SESSION['error_auth'] = 'Datos ingresados exceden el límite permitido.';
            header('Location: /login');
            exit();
        }

        // Buscar usuario
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, email, password_hash, rol_id FROM ts_usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Prevenir fijación de sesión
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol_id'] = $user['rol_id'];
            $_SESSION['email'] = $user['email'];

            if ($user['rol_id'] == 1) {
                header('Location: /admin');
            } else {
                header('Location: /');
            }
            exit();
        }

        // Login fallido
        $_SESSION['error_auth'] = 'Credenciales incorrectas.';
        header('Location: /login');
        exit();
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit();
    }

    public function updatePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error_msg'] = 'Todos los campos de contraseña son obligatorios.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if (strlen($new_password) > 100 || strlen($current_password) > 100) {
            $_SESSION['error_msg'] = 'La contraseña excede el límite permitido.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error_msg'] = 'La nueva contraseña y la confirmación no coinciden.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT password_hash FROM ts_usuarios WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current_password, $user['password_hash'])) {
            $_SESSION['error_msg'] = 'La contraseña actual es incorrecta.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Hashing y actualización
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $updateStmt = $db->prepare("UPDATE ts_usuarios SET password_hash = :hash WHERE id = :id");
        if ($updateStmt->execute(['hash' => $new_hash, 'id' => $_SESSION['user_id']])) {
            $_SESSION['success_msg'] = '¡Contraseña actualizada correctamente!';
        } else {
            $_SESSION['error_msg'] = 'Error al actualizar la contraseña.';
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
