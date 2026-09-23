<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class ClientController
{
    private function verifyAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit();
        }
    }

    public function getPerfil()
    {
        $this->verifyAuth();
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT c.nombres, c.apellidos, c.telefono_whatsapp as telefono, u.email 
            FROM ts_clientes c 
            JOIN ts_usuarios u ON c.usuario_id = u.id 
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($perfil ?: []);
        exit();
    }

    public function updatePerfil()
    {
        $this->verifyAuth();
        $db = Database::getInstance()->getConnection();
        
        // El contenido puede venir como JSON si usamos fetch desde Alpine
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $data = $_POST;
        }

        $nombres = trim(strip_tags($data['nombres'] ?? ''));
        $apellidos = trim(strip_tags($data['apellidos'] ?? ''));
        $telefono = trim(strip_tags($data['telefono'] ?? ''));
        
        $password_actual = $data['password_actual'] ?? '';
        $password_nueva = $data['password_nueva'] ?? '';
        $password_confirmacion = $data['password_confirmacion'] ?? '';

        try {
            // Validar cambio de contraseña
            if (!empty($password_nueva)) {
                if ($password_nueva !== $password_confirmacion) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Las nuevas contraseñas no coinciden.']);
                    exit();
                }

                $stmtAuth = $db->prepare("SELECT password_hash FROM ts_usuarios WHERE id = :id");
                $stmtAuth->execute(['id' => $_SESSION['user_id']]);
                $userAuth = $stmtAuth->fetch(PDO::FETCH_ASSOC);

                if (!$userAuth || !password_verify($password_actual, $userAuth['password_hash'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'La contraseña actual es incorrecta.']);
                    exit();
                }
            }

            $db->beginTransaction();

            // Actualizar ts_clientes
            $stmtCliente = $db->prepare("UPDATE ts_clientes SET nombres = :n, apellidos = :a, telefono_whatsapp = :t WHERE usuario_id = :id");
            $stmtCliente->execute([
                'n' => $nombres,
                'a' => $apellidos,
                't' => $telefono,
                'id' => $_SESSION['user_id']
            ]);

            // Actualizar contraseña si fue proporcionada y validada
            if (!empty($password_nueva)) {
                $hash = password_hash($password_nueva, PASSWORD_DEFAULT);
                $stmtAuthUpdate = $db->prepare("UPDATE ts_usuarios SET password_hash = :p WHERE id = :id");
                $stmtAuthUpdate->execute([
                    'p' => $hash,
                    'id' => $_SESSION['user_id']
                ]);
            }

            $db->commit();
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Perfil actualizado exitosamente.']);
        } catch (\Exception $e) {
            $db->rollBack();
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el perfil.']);
        }
        exit();
    }

    public function getPedidos()
    {
        $this->verifyAuth();
        $db = Database::getInstance()->getConnection();
        
        // Obtener el ID del cliente
        $stmtCliente = $db->prepare("SELECT id FROM ts_clientes WHERE usuario_id = :uid");
        $stmtCliente->execute(['uid' => $_SESSION['user_id']]);
        $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }

        $sql = "
            SELECT 
                p.id, p.total, p.estado_pedido, p.creado_en,
                mp.nombre as metodo_pago
            FROM ts_pedidos p
            JOIN ts_metodos_pago mp ON p.metodo_pago_id = mp.id
            WHERE p.cliente_id = :cid AND p.eliminado_en IS NULL
            ORDER BY p.creado_en DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(['cid' => $cliente['id']]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($pedidos);
        exit();
    }
}
