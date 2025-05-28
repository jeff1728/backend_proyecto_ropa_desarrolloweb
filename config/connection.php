<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

$host = "localhost";
$dbname = "proyectoWebBDD";
$user = "postgres";
$password = "1728036060";

try {
    $conexion = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conectado a la base de datos";
} catch(PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>

