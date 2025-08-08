<?php
session_start();
require_once "connect.php";

// Registrar actividad antes de cerrar sesión
if (isset($_SESSION['user_id'])) {
    logSystemActivity('logout', 'Cierre de sesión', $_SESSION['user_id']);
}

// Eliminar cookie "recordarme" si existe
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    // Eliminar token de la base de datos
    $stmt = $conn->prepare("DELETE FROM user_sessions WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();
    
    // Eliminar cookie
    setcookie('remember_token', '', time() - 3600, '/');
}

// Destruir sesión
session_destroy();
session_unset();

// Redirigir al login
header("Location: index.php");
exit;
?>
