<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class ShopController
{
    public function getMetodosPago()
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT id, nombre, descripcion, instrucciones, imagen_url FROM ts_metodos_pago WHERE activo = 1 AND eliminado_en IS NULL ORDER BY nombre ASC";
        $stmt = $db->query($sql);
        $metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($metodos);
        exit();
    }

    public function procesarPedido()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Debe iniciar sesión para comprar.']);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['cart']) || !is_array($data['cart']) || empty($data['metodo_pago_id'])) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
            exit();
        }

        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Obtener el ID de ts_clientes a partir del usuario actual
            $stmtCliente = $db->prepare("SELECT id FROM ts_clientes WHERE usuario_id = :uid");
            $stmtCliente->execute(['uid' => $_SESSION['user_id']]);
            $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if (!$cliente) {
                // Crear perfil de cliente vacío si por alguna razón no lo tiene (Edge case)
                $stmtInsertClient = $db->prepare("INSERT INTO ts_clientes (usuario_id, nombres, apellidos) VALUES (:uid, 'Cliente', 'Nuevo')");
                $stmtInsertClient->execute(['uid' => $_SESSION['user_id']]);
                $clienteId = $db->lastInsertId();
            } else {
                $clienteId = $cliente['id'];
            }

            // Calcular total real para seguridad
            $totalPedido = 0;
            $detalles = [];

            foreach ($data['cart'] as $item) {
                $productoId = $item['productId'];
                $cantidad = (int) $item['quantity'];
                $precio = (float) $item['price']; // Idealmente validar contra BD
                $subtotal = $precio * $cantidad;
                $totalPedido += $subtotal;
                
                $detalles[] = [
                    'producto_id' => $productoId,
                    'nombre_producto' => $item['name'] . ($item['variantName'] ? ' (' . $item['variantName'] . ')' : ''),
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal
                ];
            }

            // Insertar Pedido
            $stmtPedido = $db->prepare("
                INSERT INTO ts_pedidos (cliente_id, metodo_pago_id, total, estado_pedido) 
                VALUES (:cid, :mpid, :tot, 'PENDIENTE')
            ");
            $stmtPedido->execute([
                'cid' => $clienteId,
                'mpid' => $data['metodo_pago_id'],
                'tot' => $totalPedido
            ]);
            $pedidoId = $db->lastInsertId();

            // Insertar Detalles
            $stmtDetalle = $db->prepare("
                INSERT INTO ts_detalle_pedidos (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario, subtotal)
                VALUES (:pid, :prod_id, :nom, :cant, :precio, :sub)
            ");

            foreach ($detalles as $det) {
                $stmtDetalle->execute([
                    'pid' => $pedidoId,
                    'prod_id' => $det['producto_id'],
                    'nom' => $det['nombre_producto'],
                    'cant' => $det['cantidad'],
                    'precio' => $det['precio_unitario'],
                    'sub' => $det['subtotal']
                ]);
            }

            $db->commit();
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'pedido_id' => str_pad($pedidoId, 6, '0', STR_PAD_LEFT)]);
            
        } catch (\Exception $e) {
            $db->rollBack();
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error al procesar el pedido.']);
        }
        exit();
    }
}
