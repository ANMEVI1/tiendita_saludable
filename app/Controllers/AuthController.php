<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AuthController
{
    public function showLogin()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1) {
            header('Location: /admin');
            exit();
        }
        
        require __DIR__ . '/../Views/pages/auth/login.php';
    }

    public function login()
    {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $_SESSION['error_auth'] = 'Token CSRF inválido.';
            header('Location: /login');
            exit();
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
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

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, email, password_hash, rol_id, activo FROM ts_usuarios WHERE email = :email AND eliminado_en IS NULL LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            if (!$user['activo']) {
                $_SESSION['error_auth'] = 'Su cuenta se encuentra inactiva. Contacte a soporte.';
                header('Location: /login');
                exit();
            }

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

        $_SESSION['error_auth'] = 'Credenciales incorrectas.';
        header('Location: /login');
        exit();
    }

    public function showRegistro()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit();
        }
        
        require __DIR__ . '/../Views/pages/auth/registro.php';
    }

    public function registro()
    {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $_SESSION['error_auth'] = 'Token CSRF inválido.';
            header('Location: /registro');
            exit();
        }

        $nombres = isset($_POST['nombres']) ? trim(strip_tags($_POST['nombres'])) : '';
        $apellidos = isset($_POST['apellidos']) ? trim(strip_tags($_POST['apellidos'])) : '';
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $telefono = isset($_POST['telefono']) ? trim(strip_tags($_POST['telefono'])) : '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!$nombres || !$apellidos || !$email || empty($password)) {
            $_SESSION['error_auth'] = 'Por favor, complete todos los campos obligatorios.';
            header('Location: /registro');
            exit();
        }

        if (strlen($password) < 6) {
            $_SESSION['error_auth'] = 'La contraseña debe tener al menos 6 caracteres.';
            header('Location: /registro');
            exit();
        }

        if ($password !== $confirm_password) {
            $_SESSION['error_auth'] = 'Las contraseñas no coinciden.';
            header('Location: /registro');
            exit();
        }

        $db = Database::getInstance()->getConnection();

        // Verificar correo duplicado
        $checkStmt = $db->prepare("SELECT id FROM ts_usuarios WHERE email = :email AND eliminado_en IS NULL LIMIT 1");
        $checkStmt->execute(['email' => $email]);
        if ($checkStmt->fetch()) {
            $_SESSION['error_auth'] = 'El correo electrónico ya se encuentra registrado.';
            header('Location: /registro');
            exit();
        }

        try {
            $db->beginTransaction();

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $userStmt = $db->prepare("INSERT INTO ts_usuarios (email, password_hash, rol_id, activo) VALUES (:email, :hash, 3, 1)");
            $userStmt->execute([
                'email' => $email,
                'hash'  => $password_hash
            ]);
            $usuario_id = $db->lastInsertId();

            $clientStmt = $db->prepare("INSERT INTO ts_clientes (usuario_id, nombres, apellidos, telefono_whatsapp, activo) VALUES (:usuario_id, :nombres, :apellidos, :telefono, 1)");
            $clientStmt->execute([
                'usuario_id' => $usuario_id,
                'nombres'    => $nombres,
                'apellidos'  => $apellidos,
                'telefono'   => $telefono ?: null
            ]);

            $db->commit();

            // Iniciar sesión automáticamente
            session_regenerate_id(true);
            $_SESSION['user_id'] = $usuario_id;
            $_SESSION['rol_id'] = 3;
            $_SESSION['email'] = $email;
            $_SESSION['success_msg'] = '¡Bienvenido a La Tiendita Saludable! Tu cuenta ha sido creada exitosamente.';

            header('Location: /');
            exit();

        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error_auth'] = 'Ocurrió un error al procesar el registro. Intente nuevamente.';
            header('Location: /registro');
            exit();
        }
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
