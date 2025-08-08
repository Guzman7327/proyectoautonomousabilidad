<?php
require_once 'connect.php';

$nombre = 'Administrador';
$apellido = 'Principal';
$email = 'admin@correo.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nombre, $apellido, $email, $password, $role);

if ($stmt->execute()) {
    echo "✅ Usuario admin creado con éxito";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
