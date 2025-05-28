<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Incluir la conexión
require_once 'connection.php';

try {
    // Consulta para obtener los datos de la tabla clientes
    $sql = "SELECT nombre, telefono, contrasena, correo, fecha_registro FROM clientes";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Contraseña</th>
                <th>Correo</th>
                <th>Fecha de Registro</th>
              </tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['telefono']}</td>
                    <td>{$row['contrasena']}</td>
                    <td>{$row['correo']}</td>
                    <td>{$row['fecha_registro']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No hay datos en la tabla cliente.";
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
