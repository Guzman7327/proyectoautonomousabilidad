<?php
// Script de depuración para el registro
header('Content-Type: application/json');

// Habilitar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log de la petición
$log = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'files' => $_FILES,
    'headers' => getallheaders()
];

// Incluir archivo de conexión
require_once 'connect.php';

$response = [
    'success' => false,
    'message' => '',
    'debug' => $log
];

try {
    // Verificar si es una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Verificar conexión a la base de datos
    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $conn->connect_error);
    }

    // Obtener y limpiar datos del formulario
    $firstName = cleanInput($_POST['firstName'] ?? '');
    $lastName = cleanInput($_POST['lastName'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $terms = isset($_POST['terms']) ? true : false;

    // Agregar datos limpios al log
    $log['cleaned_data'] = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'password_length' => strlen($password),
        'confirmPassword_length' => strlen($confirmPassword),
        'terms' => $terms
    ];

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

    // Agregar errores de validación al log
    $log['validation_errors'] = $errors;

    // Si no hay errores, proceder con el registro
    if (empty($errors)) {
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
                'redirect' => 'home.php',
                'user_id' => $userId,
                'debug' => $log
            ];
        } else {
            throw new Exception('Error al insertar usuario en la base de datos: ' . $stmt->error);
        }
        
        $stmt->close();
    } else {
        // Hay errores de validación
        $response = [
            'success' => false,
            'message' => implode(', ', $errors),
            'errors' => $errors,
            'debug' => $log
        ];
    }
    
} catch (Exception $e) {
    $log['exception'] = $e->getMessage();
    $response = [
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage(),
        'debug' => $log
    ];
}

// Cerrar conexión
$conn->close();

// Enviar respuesta JSON
echo json_encode($response, JSON_PRETTY_PRINT);
?> 