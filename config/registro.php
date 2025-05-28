<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once 'connection.php';

$data = json_decode(file_get_contents("php://input"));

if (
    isset($data->nombre) &&
    isset($data->correo) &&
    isset($data->contrasena) &&
    isset($data->telefono)
) {
    $nombre = $data->nombre;
    $correo = $data->correo;
    $contrasena = password_hash($data->contrasena, PASSWORD_DEFAULT);
    $telefono = $data->telefono;

    try {
        $stmt = $conexion->prepare("SELECT correo FROM clientes WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => false, "message" => "Correo electrónico ya registrado"]);
        } else {
            $sql = "INSERT INTO clientes (nombre, correo, contrasena, telefono, fecha_registro) 
                    VALUES (:nombre, :correo, :contrasena, :telefono, NOW())";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':contrasena', $contrasena);
            $stmt->bindParam(':telefono', $telefono);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al registrar usuario"]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
}
?>

