<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$usuario_id = $data['usuario_id'];
$producto_id = $data['producto_id'];
$cantidad = $data['cantidad'];

try {
    $sql = "UPDATE carrito_detalle
            SET cantidad = :cantidad
            WHERE usuario_id = :usuario_id AND producto_id = :producto_id";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':cantidad', $cantidad);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':producto_id', $producto_id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Cantidad actualizada."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
