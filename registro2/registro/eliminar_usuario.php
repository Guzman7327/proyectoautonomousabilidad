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

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);
$userId = intval($input['id'] ?? 0);

// Validar ID de usuario
if ($userId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'ID de usuario inválido']);
    exit;
}

// Verificar que no se está eliminando a sí mismo
if ($userId === $_SESSION['user_id']) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'No puede eliminar su propia cuenta']);
    exit;
}

// Obtener información del usuario antes de eliminarlo
$stmt = $conn->prepare("SELECT email, firstName, lastName FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Usuario no encontrado']);
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Eliminar usuario
try {
    // Iniciar transacción
    $conn->begin_transaction();
    
    // Eliminar sesiones del usuario
    $stmt = $conn->prepare("DELETE FROM user_sessions WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    
    // Eliminar intentos de login
    $stmt = $conn->prepare("DELETE FROM login_attempts WHERE email = ?");
    $stmt->bind_param("s", $user['email']);
    $stmt->execute();
    $stmt->close();
    
    // Eliminar tokens de recuperación de contraseña
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $user['email']);
    $stmt->execute();
    $stmt->close();
    
    // Eliminar logs del sistema relacionados
    $stmt = $conn->prepare("DELETE FROM system_logs WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    
    // Finalmente, eliminar el usuario
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        // Confirmar transacción
        $conn->commit();
        
        // Registrar actividad
        logSystemActivity('delete_user', "Usuario eliminado: {$user['email']} ({$user['firstName']} {$user['lastName']})", $_SESSION['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'mensaje' => 'Usuario eliminado correctamente']);
    } else {
        throw new Exception("Error al eliminar usuario");
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    
    error_log("Error al eliminar usuario: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Error interno del servidor']);
}
?> 