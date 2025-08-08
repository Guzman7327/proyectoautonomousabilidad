<?php
// Mostrar errores en pantalla (útil durante desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datos de conexión
$host = "localhost";
$user = "root";
$pass = "";
$db = "registro";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error al conectar a la base de datos: " . $conn->connect_error);
}
?>
