<?php
// Incluir archivo de conexión
require_once 'connect.php';

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener y limpiar datos del formulario
    $firstName = cleanInput($_POST['firstName'] ?? '');
    $lastName = cleanInput($_POST['lastName'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $terms = isset($_POST['terms']) ? true : false;
    
    // Array para almacenar errores
    $errors = [];
    
    // Validar nombre
    if (empty($firstName)) {
        $errors[] = "El nombre es obligatorio";
    } elseif (strlen($firstName) < 2) {
        $errors[] = "El nombre debe tener al menos 2 caracteres";
    } elseif (strlen($firstName) > 50) {
        $errors[] = "El nombre no puede exceder 50 caracteres";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $firstName)) {
        $errors[] = "El nombre solo puede contener letras y espacios";
    }
    
    // Validar apellido
    if (empty($lastName)) {
        $errors[] = "El apellido es obligatorio";
    } elseif (strlen($lastName) < 2) {
        $errors[] = "El apellido debe tener al menos 2 caracteres";
    } elseif (strlen($lastName) > 50) {
        $errors[] = "El apellido no puede exceder 50 caracteres";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $lastName)) {
        $errors[] = "El apellido solo puede contener letras y espacios";
    }
    
    // Validar email
    if (empty($email)) {
        $errors[] = "El email es obligatorio";
    } elseif (!isValidEmail($email)) {
        $errors[] = "El formato del email no es válido";
    } elseif (strlen($email) > 100) {
        $errors[] = "El email no puede exceder 100 caracteres";
    } elseif (userExists($email)) {
        $errors[] = "Este email ya está registrado";
    }
    
    // Validar contraseña
    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria";
    } elseif (strlen($password) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres";
    } elseif (strlen($password) > 128) {
        $errors[] = "La contraseña no puede exceder 128 caracteres";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/", $password)) {
        $errors[] = "La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial";
    }
    
    // Validar confirmación de contraseña
    if (empty($confirmPassword)) {
        $errors[] = "Debe confirmar la contraseña";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    // Validar términos y condiciones
    if (!$terms) {
        $errors[] = "Debe aceptar los términos y condiciones";
    }
    
    // Si no hay errores, proceder con el registro
    if (empty($errors)) {
        try {
            // Hash de la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Preparar consulta de inserción
            $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role, is_active, created_at) VALUES (?, ?, ?, ?, 'user', 1, NOW())");
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                $userId = $conn->insert_id;
                
                // Registrar actividad del sistema
                logSystemActivity('user_registration', "Nuevo usuario registrado: $email", $userId);
                
                // Crear sesión del usuario
                createUserSession($userId, $firstName . ' ' . $lastName, 'user');
                
                // Respuesta de éxito
                $response = [
                    'success' => true,
                    'message' => '¡Registro exitoso! Bienvenido al Portal Turístico.',
                    'redirect' => 'home.php'
                ];
                
                // Enviar respuesta JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
                
            } else {
                $errors[] = "Error al crear la cuenta. Intente nuevamente.";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            // Log del error
            error_log("Error en registro: " . $e->getMessage());
            $errors[] = "Error interno del servidor. Intente nuevamente.";
        }
    }
    
    // Si hay errores, enviar respuesta con errores
    if (!empty($errors)) {
        $response = [
            'success' => false,
            'message' => 'Error en el registro',
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
