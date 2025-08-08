<?php
session_start();
require_once "connect.php";

// Verificar si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}

// Verificar si es una petición AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Petición inválida']);
    exit;
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

// Obtener datos
$userId = intval($_POST['id'] ?? 0);
$firstName = cleanInput($_POST['firstName'] ?? '');
$lastName = cleanInput($_POST['lastName'] ?? '');
$email = cleanInput($_POST['email'] ?? '');
$role = cleanInput($_POST['role'] ?? 'user');

// Validar datos
if ($userId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'ID de usuario inválido']);
    exit;
}

if (empty($firstName) || strlen($firstName) < 2) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'El nombre debe tener al menos 2 caracteres']);
    exit;
}

if (empty($lastName) || strlen($lastName) < 2) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'El apellido debe tener al menos 2 caracteres']);
    exit;
}

if (empty($email) || !isValidEmail($email)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Email inválido']);
    exit;
}

if (!in_array($role, ['user', 'admin'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Rol inválido']);
    exit;
}

// Verificar si el email ya existe en otro usuario
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Este email ya está registrado por otro usuario']);
    exit;
}
$stmt->close();

// Actualizar usuario
try {
    $stmt = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssssi", $firstName, $lastName, $email, $role, $userId);
    
    if ($stmt->execute()) {
        // Registrar actividad
        logSystemActivity('edit_user', "Usuario editado: $email", $_SESSION['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'mensaje' => 'Usuario actualizado correctamente']);
    } else {
        throw new Exception("Error al actualizar usuario");
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error al editar usuario: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Error interno del servidor']);
}
?>
