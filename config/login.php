 <?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once 'connection.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->correo) && isset($data->contrasena)) {
    $correo = $data->correo;
    $contrasena = $data->contrasena;

    try {
        // Buscar usuario por correo
        $stmt = $conexion->prepare("SELECT * FROM clientes WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar contraseña
            if (password_verify($contrasena, $usuario['contrasena'])) {
                echo json_encode([
                    "success" => true,
                    "message" => "Inicio de sesión exitoso",
                    "usuario" => [
                        "id_usuario" => $usuario['id_usuario'],
                        "nombre" => $usuario['nombre'],
                        "correo" => $usuario['correo']
                    ]
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Correo no registrado"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
}
?>

