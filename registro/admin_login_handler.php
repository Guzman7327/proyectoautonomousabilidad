<?php
session_start();
include("connect.php");

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['firstName'];
        header("Location: admin.php");
        exit;
    } else {
        echo "❌ Contraseña incorrecta.";
    }
} else {
    echo "❌ No se encontró administrador.";
}
?>

