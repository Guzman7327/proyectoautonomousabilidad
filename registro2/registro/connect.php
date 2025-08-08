<?php
// Configuración de errores (desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de zona horaria
date_default_timezone_set('America/Guayaquil');

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "registro";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8");

// Función para obtener estadísticas de usuarios
function getUserStats() {
    global $conn;
    
    $stats = [
        'total_users' => 0,
        'users_today' => 0,
        'users_month' => 0
    ];
    
    // Total de usuarios
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    if ($result) {
        $stats['total_users'] = $result->fetch_assoc()['total'];
    }
    
    // Usuarios registrados hoy
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE DATE(created_at) = CURDATE()");
    if ($result) {
        $stats['users_today'] = $result->fetch_assoc()['total'];
    }
    
    // Usuarios registrados este mes
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    if ($result) {
        $stats['users_month'] = $result->fetch_assoc()['total'];
    }
    
    return $stats;
}

// Función para limpiar datos de entrada
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para verificar si el usuario existe
function userExists($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Función para registrar intento de login
function logLoginAttempt($email, $success, $ip) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO login_attempts (email, success, ip_address, attempted_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sis", $email, $success, $ip);
    $stmt->execute();
    $stmt->close();
}

// Función para verificar si la cuenta está bloqueada
function isAccountBlocked($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE email = ? AND success = 0 AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $attempts = $result->fetch_assoc()['attempts'];
    $stmt->close();
    return $attempts >= 5;
}

// Función para obtener información del usuario
function getUserInfo($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, firstName, lastName, email, password, role, is_active FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

// Función para actualizar último login
function updateLastLogin($userId) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
}

// Función para crear sesión de usuario
function createUserSession($userId, $userName, $userRole) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $userName;
    $_SESSION['user_role'] = $userRole;
    $_SESSION['login_time'] = time();
}

// Función para verificar si la sesión es válida
function isSessionValid() {
    if (!isset($_SESSION['login_time'])) {
        return false;
    }
    
    // Sesión expira después de 2 horas
    $sessionTimeout = 2 * 60 * 60; // 2 horas en segundos
    return (time() - $_SESSION['login_time']) < $sessionTimeout;
}

// Función para cerrar sesión
function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    session_unset();
}

// Función para generar token de recuperación de contraseña
function generatePasswordResetToken($email) {
    global $conn;
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
    $stmt->bind_param("sssss", $email, $token, $expires, $token, $expires);
    $stmt->execute();
    $stmt->close();
    
    return $token;
}

// Función para verificar token de recuperación
function verifyPasswordResetToken($email, $token) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires_at > NOW() AND used = 0");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Función para marcar token como usado
function markTokenAsUsed($email, $token) {
    global $conn;
    $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE email = ? AND token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $stmt->close();
}

// Función para registrar actividad del sistema
function logSystemActivity($action, $details, $userId = null) {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $stmt = $conn->prepare("INSERT INTO system_logs (user_id, action, description, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issss", $userId, $action, $details, $ip, $userAgent);
    $stmt->execute();
    $stmt->close();
}

// Función para verificar credenciales de administrador
function verifyAdminCredentials($email, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, email, password, is_active FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();
    
    if ($admin && password_verify($password, $admin['password']) && $admin['is_active']) {
        return $admin;
    }
    return false;
}

// Función para crear sesión de administrador
function createAdminSession($adminId, $adminEmail) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['admin_id'] = $adminId;
    $_SESSION['admin_email'] = $adminEmail;
    $_SESSION['admin_login_time'] = time();
    $_SESSION['is_admin'] = true;
}

// Función para verificar si es administrador
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Función para obtener todos los usuarios
function getAllUsers($limit = null, $offset = 0, $search = '', $filter = '') {
    global $conn;
    
    $sql = "SELECT id, firstName, lastName, email, role, is_active, created_at, last_login FROM users WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($search)) {
        $sql .= " AND (firstName LIKE ? OR lastName LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "sss";
    }
    
    if (!empty($filter)) {
        switch ($filter) {
            case 'active':
                $sql .= " AND is_active = 1";
                break;
            case 'inactive':
                $sql .= " AND is_active = 0";
                break;
            case 'admin':
                $sql .= " AND role = 'admin'";
                break;
            case 'user':
                $sql .= " AND role = 'user'";
                break;
        }
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $users;
}

// Función para contar usuarios
function countUsers($search = '', $filter = '') {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($search)) {
        $sql .= " AND (firstName LIKE ? OR lastName LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "sss";
    }
    
    if (!empty($filter)) {
        switch ($filter) {
            case 'active':
                $sql .= " AND is_active = 1";
                break;
            case 'inactive':
                $sql .= " AND is_active = 0";
                break;
            case 'admin':
                $sql .= " AND role = 'admin'";
                break;
            case 'user':
                $sql .= " AND role = 'user'";
                break;
        }
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'];
    $stmt->close();
    
    return $count;
}

// Función para actualizar usuario
function updateUser($userId, $data) {
    global $conn;
    
    $sql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, role = ?, is_active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $data['firstName'], $data['lastName'], $data['email'], $data['role'], $data['is_active'], $userId);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Función para eliminar usuario
function deleteUser($userId) {
    global $conn;
    
    // Verificar que no se elimine a sí mismo
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
        return false;
    }
    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Función para obtener estadísticas del sistema
function getSystemStats() {
    global $conn;
    
    $stats = [
        'total_users' => 0,
        'active_users' => 0,
        'inactive_users' => 0,
        'admin_users' => 0,
        'regular_users' => 0,
        'users_today' => 0,
        'users_this_week' => 0,
        'users_this_month' => 0,
        'total_logins' => 0,
        'failed_logins' => 0
    ];
    
    // Estadísticas de usuarios
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    if ($result) {
        $stats['total_users'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
    if ($result) {
        $stats['active_users'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_active = 0");
    if ($result) {
        $stats['inactive_users'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    if ($result) {
        $stats['admin_users'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    if ($result) {
        $stats['regular_users'] = $result->fetch_assoc()['total'];
    }
    
    // Usuarios por período
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE DATE(created_at) = CURDATE()");
    if ($result) {
        $stats['users_today'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    if ($result) {
        $stats['users_this_week'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    if ($result) {
        $stats['users_this_month'] = $result->fetch_assoc()['total'];
    }
    
    // Estadísticas de login
    $result = $conn->query("SELECT COUNT(*) as total FROM login_attempts WHERE success = 1");
    if ($result) {
        $stats['total_logins'] = $result->fetch_assoc()['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM login_attempts WHERE success = 0");
    if ($result) {
        $stats['failed_logins'] = $result->fetch_assoc()['total'];
    }
    
    return $stats;
}

// Configuración de sesión segura
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Función para iniciar sesión segura
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerar ID de sesión periódicamente para prevenir session fixation
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutos
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

// Iniciar sesión segura
startSecureSession();

// Definir modo de desarrollo
define('DEVELOPMENT_MODE', true); // Cambiar a false en producción

// Función para debug (solo en desarrollo)
function debug($data) {
    if (DEVELOPMENT_MODE) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

// Función para respuesta JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Función para respuesta de error
function errorResponse($message, $statusCode = 400) {
    jsonResponse(['error' => true, 'mensaje' => $message], $statusCode);
}

// Función para respuesta de éxito
function successResponse($message, $data = null) {
    $response = ['success' => true, 'mensaje' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    jsonResponse($response);
}

// Verificar si la base de datos tiene las tablas necesarias
function checkDatabaseStructure() {
    global $conn;
    
    $requiredTables = ['users', 'admins', 'login_attempts', 'password_resets', 'system_logs'];
    $missingTables = [];
    
    foreach ($requiredTables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows === 0) {
            $missingTables[] = $table;
        }
    }
    
    if (!empty($missingTables)) {
        error_log("Tablas faltantes en la base de datos: " . implode(', ', $missingTables));
        if (DEVELOPMENT_MODE) {
            die("❌ Faltan tablas en la base de datos: " . implode(', ', $missingTables) . 
                "<br>Ejecute el archivo registro.sql");
        }
    }
}

// Verificar estructura de la base de datos (solo en desarrollo)
if (DEVELOPMENT_MODE) {
    checkDatabaseStructure();
}
?>
