<?php
// Incluir archivo de conexión
require_once 'connect.php';

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener y limpiar datos del formulario
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['rememberMe']) ? true : false;
    
    // Array para almacenar errores
    $errors = [];
    
    // Validar email
    if (empty($email)) {
        $errors[] = "El email es obligatorio";
    } elseif (!isValidEmail($email)) {
        $errors[] = "El formato del email no es válido";
    }
    
    // Validar contraseña
    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria";
    }
    
    // Si no hay errores de validación, proceder con el login
    if (empty($errors)) {
        
        // Obtener IP del usuario
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        // Verificar si la cuenta está bloqueada
        if (isAccountBlocked($email)) {
            $errors[] = "Cuenta temporalmente bloqueada. Intente nuevamente en 15 minutos.";
            
            // Registrar intento bloqueado
            logLoginAttempt($email, 0, $ip);
            
        } else {
            
            // Obtener información del usuario
            $user = getUserInfo($email);
            
            if ($user && $user['is_active']) {
                
                // Verificar contraseña
                if (password_verify($password, $user['password'])) {
                    
                    // Login exitoso
                    logLoginAttempt($email, 1, $ip);
                    
                    // Actualizar último login
                    updateLastLogin($user['id']);
                    
                    // Crear sesión del usuario
                    createUserSession($user['id'], $user['firstName'] . ' ' . $user['lastName'], $user['role']);
                    
                    // Configurar cookie de "recordarme" si está marcado
                    if ($rememberMe) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + (30 * 24 * 60 * 60); // 30 días
                        
                        // Guardar token en la base de datos (opcional)
                        $stmt = $conn->prepare("UPDATE users SET remember_token = ?, remember_expires = ? WHERE id = ?");
                        $stmt->bind_param("ssi", $token, date('Y-m-d H:i:s', $expires), $user['id']);
                        $stmt->execute();
                        $stmt->close();
                        
                        // Establecer cookie
                        setcookie('remember_token', $token, $expires, '/', '', false, true);
                    }
                    
                    // Registrar actividad del sistema
                    logSystemActivity('user_login', "Usuario inició sesión: $email", $user['id']);
                    
                    // Respuesta de éxito
                    $response = [
                        'success' => true,
                        'message' => '¡Inicio de sesión exitoso! Bienvenido de vuelta.',
                        'redirect' => 'home.php',
                        'user' => [
                            'id' => $user['id'],
                            'name' => $user['firstName'] . ' ' . $user['lastName'],
                            'email' => $user['email'],
                            'role' => $user['role']
                        ]
                    ];
                    
                    // Enviar respuesta JSON
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                    
                } else {
                    // Contraseña incorrecta
                    $errors[] = "Email o contraseña incorrectos";
                    logLoginAttempt($email, 0, $ip);
                }
                
            } elseif ($user && !$user['is_active']) {
                // Cuenta desactivada
                $errors[] = "Su cuenta ha sido desactivada. Contacte al administrador.";
                logLoginAttempt($email, 0, $ip);
                
            } else {
                // Usuario no existe
                $errors[] = "Email o contraseña incorrectos";
                logLoginAttempt($email, 0, $ip);
            }
        }
    }
    
    // Si hay errores, enviar respuesta con errores
    if (!empty($errors)) {
        $response = [
            'success' => false,
            'message' => 'Error en el inicio de sesión',
            'errors' => $errors
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
} else {
    // Si no es POST, redirigir a la página principal
    header('Location: index.php');
    exit;
}
?>
