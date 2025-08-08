<?php
session_start();
require_once "connect.php";

// Si ya está logueado como admin, redirigir
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    header("Location: admin.php");
    exit;
}

// Procesar login si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Todos los campos son requeridos";
    } else {
        // Buscar usuario admin
        $stmt = $conn->prepare("SELECT id, firstName, lastName, email, password, role, is_active FROM users WHERE email = ? AND role = 'admin'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (!$user['is_active']) {
                $error = "Su cuenta ha sido desactivada";
            } elseif (password_verify($password, $user['password'])) {
                // Login exitoso
                $userName = $user['firstName'] . ' ' . $user['lastName'];
                createUserSession($user['id'], $userName, $user['role']);
                
                logSystemActivity('admin_login', 'Login de administrador exitoso', $user['id']);
                
                header("Location: admin.php");
                exit;
            } else {
                $error = "Credenciales inválidas";
            }
        } else {
            $error = "Credenciales inválidas";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador - Portal Turístico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-title {
            color: #2e7d32;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Verdana', 'Geneva', sans-serif;
            transition: all 0.3s ease;
            background: #ffffff;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4caf50;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #45a049 0%, #2e7d32 100%);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #f44336;
            margin-bottom: 20px;
            text-align: center;
        }

        .back-link {
            margin-top: 20px;
        }

        .back-link a {
            color: #4caf50;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .admin-icon {
            font-size: 4rem;
            color: #4caf50;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="admin-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        
        <div class="login-header">
            <h1 class="login-title">Acceso Administrador</h1>
            <p class="login-subtitle">Portal Turístico E.C</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Correo Electrónico
                </label>
                <input type="email" id="email" name="email" required 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Volver al Portal Principal
            </a>
        </div>
    </div>
</body>
</html>
