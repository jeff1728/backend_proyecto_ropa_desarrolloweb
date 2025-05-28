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

// Leer el cuerpo JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validar datos recibidos
if (!isset($data['usuario_id'], $data['producto_id'], $data['cantidad'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit();
}

$usuario_id = $data['usuario_id'];
$producto_id = $data['producto_id'];
$cantidad = $data['cantidad'];

try {
    $sql = "INSERT INTO carrito_detalle (usuario_id, producto_id, cantidad)
            VALUES (:usuario_id, :producto_id, :cantidad)
            ON CONFLICT (usuario_id, producto_id)
            DO UPDATE SET cantidad = carrito_detalle.cantidad + EXCLUDED.cantidad";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Producto agregado al carrito."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
