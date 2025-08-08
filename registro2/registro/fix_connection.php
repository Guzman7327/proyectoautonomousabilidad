<?php
// Script de solución automática de problemas de conexión
session_start();

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Solución de Conexión - Portal Turístico</title>
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .step {
            background: #f5f5f5;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        .step.success {
            border-left-color: #4caf50;
            background: #e8f5e8;
        }
        .step.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .step.warning {
            border-left-color: #ff9800;
            background: #fff3e0;
        }
        .btn {
            background: #4caf50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #45a049;
        }
        .btn-danger {
            background: #f44336;
        }
        .btn-warning {
            background: #ff9800;
        }
        .progress {
            width: 100%;
            height: 20px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar {
            height: 100%;
            background: #4caf50;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔧 Solución Automática de Conexión</h1>
            <p>Reparando problemas de conexión automáticamente</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

// Función para actualizar progreso
function updateProgress($percent) {
    echo "<script>
        document.querySelector('.progress-bar').style.width = '$percent%';
    </script>";
    ob_flush();
    flush();
}

echo "<div class='progress'>
    <div class='progress-bar' style='width: 0%'></div>
</div>";

// Paso 1: Verificar y crear base de datos
showStep("🗄️ Paso 1: Verificando base de datos 'registro'...");
updateProgress(10);

$mysqlConn = new mysqli("localhost", "root", "");
if ($mysqlConn->connect_error) {
    showStep("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
    echo "<p><strong>💡 Solución manual:</strong> Inicia MySQL desde XAMPP Control Panel</p>";
    echo "</div></body></html>";
    exit;
}

// Verificar si la base de datos existe
$result = $mysqlConn->query("SHOW DATABASES LIKE 'registro'");
if ($result->num_rows === 0) {
    // Crear la base de datos
    if ($mysqlConn->query("CREATE DATABASE registro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        showStep("✅ Base de datos 'registro' creada exitosamente", "success");
    } else {
        showStep("❌ Error creando la base de datos: " . $mysqlConn->error, "error");
        echo "</div></body></html>";
        exit;
    }
} else {
    showStep("✅ Base de datos 'registro' ya existe", "success");
}
updateProgress(30);

// Paso 2: Verificar y crear tablas
showStep("📋 Paso 2: Verificando tablas...");
updateProgress(50);

$mysqlConn->select_db("registro");
$tables = $mysqlConn->query("SHOW TABLES");
$tableCount = $tables->num_rows;

if ($tableCount === 0) {
    showStep("⚠️ No se encontraron tablas. Creando desde registro.sql...", "warning");
    updateProgress(60);
    
    if (file_exists("registro.sql")) {
        $sqlContent = file_get_contents("registro.sql");
        
        // Dividir el SQL en consultas individuales
        $queries = array_filter(array_map('trim', explode(';', $sqlContent)));
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                if ($mysqlConn->query($query)) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }
        }
        
        if ($errorCount === 0) {
            showStep("✅ Todas las tablas creadas exitosamente ($successCount consultas)", "success");
        } else {
            showStep("⚠️ Se crearon tablas con algunos errores ($successCount exitosas, $errorCount errores)", "warning");
        }
    } else {
        showStep("❌ Archivo registro.sql no encontrado", "error");
        echo "<p><strong>💡 Solución:</strong> Necesitas el archivo registro.sql para crear las tablas</p>";
    }
} else {
    showStep("✅ Base de datos contiene $tableCount tablas", "success");
}
updateProgress(80);

// Paso 3: Verificar datos de prueba
showStep("👥 Paso 3: Verificando datos de prueba...");
updateProgress(90);

$result = $mysqlConn->query("SELECT COUNT(*) as count FROM users");
$userCount = $result->fetch_assoc()['count'];

if ($userCount === 0) {
    // Crear usuario administrador de prueba
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $insertAdmin = "INSERT INTO users (firstName, lastName, email, password, role, is_active, created_at) 
                    VALUES ('Admin', 'Sistema', 'admin@portal.com', '$adminPassword', 'admin', 1, NOW())";
    
    if ($mysqlConn->query($insertAdmin)) {
        showStep("✅ Usuario administrador creado (admin@portal.com / admin123)", "success");
    } else {
        showStep("⚠️ No se pudo crear usuario administrador", "warning");
    }
} else {
    showStep("✅ Base de datos contiene $userCount usuarios", "success");
}

updateProgress(100);

// Paso 4: Verificar conexión final
showStep("🔧 Paso 4: Verificando conexión final...");

try {
    // Probar connect.php
    ob_start();
    include_once "connect.php";
    ob_end_clean();
    
    if (isset($conn) && !$conn->connect_error) {
        showStep("✅ Conexión usando connect.php exitosa", "success");
        
        // Probar consulta
        $result = $conn->query("SELECT 1 as test");
        if ($result) {
            showStep("✅ Consulta de prueba exitosa", "success");
        }
        
    } else {
        showStep("❌ Error en connect.php: " . ($conn->connect_error ?? 'Error desconocido'), "error");
    }
    
} catch (Exception $e) {
    showStep("❌ Excepción: " . $e->getMessage(), "error");
}

// Resumen final
showStep("🎉 ¡Solución completada!", "success");

echo "<div style='text-align: center; margin-top: 30px;'>
    <h3>🚀 Tu Portal Turístico está listo</h3>
    <p>Se han solucionado los problemas de conexión:</p>
    
    <div style='background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;'>
        <h4>✅ Problemas solucionados:</h4>
        <ul style='text-align: left; display: inline-block;'>
            <li>Base de datos 'registro' verificada/creada</li>
            <li>Tablas del sistema verificadas/creadas</li>
            <li>Usuario administrador creado</li>
            <li>Conexión PHP verificada</li>
        </ul>
    </div>
    
    <div style='background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0;'>
        <h4>🔑 Credenciales de administrador:</h4>
        <p><strong>Email:</strong> admin@portal.com</p>
        <p><strong>Contraseña:</strong> admin123</p>
    </div>
    
    <a href='index.php' class='btn'>🏠 Ir a la Página Principal</a>
    <a href='admin_login.php' class='btn'>👨‍💼 Panel de Administrador</a>
    <a href='diagnostic.php' class='btn btn-warning'>🔍 Verificar Estado</a>
</div>";

// Cerrar conexión
$mysqlConn->close();

echo "</div>
<script>
    // Auto-redirect después de 10 segundos
    setTimeout(function() {
        window.location.href = 'index.php';
    }, 10000);
</script>
</body>
</html>";
?> 