<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'connection.php';

$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : null;

if (!$id_usuario) {
    echo json_encode(["success" => false, "message" => "ID de usuario no proporcionado"]);
    exit();
}

try {
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.talla, p.color, p.stock, c.cantidad
            FROM carrito_detalle c
            JOIN productos p ON c.producto_id = p.id_producto
            WHERE c.usuario_id = :id_usuario";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}

