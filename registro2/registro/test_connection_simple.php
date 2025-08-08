<?php
// Script simple para probar conexión
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Prueba de Conexión Simple</title>
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
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
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin: 10px 5px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
        }
        .btn:hover {
            background: #45a049;
        }
        .btn-warning {
            background: #ff9800;
        }
        .btn-danger {
            background: #f44336;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 14px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔧 Prueba de Conexión Simple</h1>
            <p>Diagnosticando problema de conexión</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

echo "<h3>🔍 Paso 1: Verificar Servicios</h3>";

// Verificar Apache
$apachePort = 80;
$apacheStatus = @fsockopen('localhost', $apachePort, $errno, $errstr, 5);
if ($apacheStatus) {
    showStep("✅ Apache funcionando en puerto $apachePort", "success");
    fclose($apacheStatus);
} else {
    showStep("❌ Apache no responde en puerto $apachePort", "error");
}

// Verificar MySQL
$mysqlPort = 3306;
$mysqlStatus = @fsockopen('localhost', $mysqlPort, $errno, $errstr, 5);
if ($mysqlStatus) {
    showStep("✅ MySQL funcionando en puerto $mysqlPort", "success");
    fclose($mysqlStatus);
} else {
    showStep("❌ MySQL no responde en puerto $mysqlPort", "error");
}

echo "<h3>🗄️ Paso 2: Verificar Base de Datos</h3>";

try {
    // Conectar a MySQL sin especificar base de datos
    $mysqlConn = new mysqli("localhost", "root", "");
    if ($mysqlConn->connect_error) {
        showStep("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
    } else {
        showStep("✅ Conexión a MySQL exitosa", "success");
        
        // Listar bases de datos
        $result = $mysqlConn->query("SHOW DATABASES");
        $databases = [];
        while ($row = $result->fetch_array()) {
            $databases[] = $row[0];
        }
        
        showStep("📋 Bases de datos disponibles: " . implode(', ', $databases), "info");
        
        // Verificar si existe 'registro'
        if (in_array('registro', $databases)) {
            showStep("✅ Base de datos 'registro' existe", "success");
            
            // Verificar tablas
            $mysqlConn->select_db("registro");
            $tables = $mysqlConn->query("SHOW TABLES");
            $tableCount = $tables->num_rows;
            showStep("✅ Base de datos 'registro' contiene $tableCount tablas", "success");
            
        } else {
            showStep("❌ Base de datos 'registro' no existe", "error");
            showStep("💡 Necesitas crear la base de datos 'registro'", "warning");
        }
        
        $mysqlConn->close();
    }
    
} catch (Exception $e) {
    showStep("❌ Excepción: " . $e->getMessage(), "error");
}

echo "<h3>🔧 Paso 3: Verificar connect.php</h3>";

if (file_exists("connect.php")) {
    $content = file_get_contents("connect.php");
    preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $match);
    $configuredDB = $match[1] ?? 'No encontrado';
    
    showStep("🔍 connect.php configurado para: '$configuredDB'", "info");
    
    if ($configuredDB === 'registro') {
        showStep("✅ connect.php está configurado correctamente", "success");
    } else {
        showStep("❌ connect.php está configurado incorrectamente", "error");
    }
} else {
    showStep("❌ Archivo connect.php no encontrado", "error");
}

echo "<h3>🎯 Paso 4: Prueba Directa de Conexión</h3>";

try {
    // Probar conexión directa
    $testConn = new mysqli("localhost", "root", "", "registro");
    if ($testConn->connect_error) {
        showStep("❌ Error de conexión directa: " . $testConn->connect_error, "error");
    } else {
        showStep("✅ Conexión directa exitosa", "success");
        
        // Probar consulta
        $result = $testConn->query("SELECT COUNT(*) as total FROM users");
        if ($result) {
            $userCount = $result->fetch_assoc()['total'];
            showStep("✅ Consulta de prueba exitosa: $userCount usuarios", "success");
        } else {
            showStep("⚠️ Error en consulta: " . $testConn->error, "warning");
        }
        
        $testConn->close();
    }
    
} catch (Exception $e) {
    showStep("❌ Excepción en conexión directa: " . $e->getMessage(), "error");
}

echo "<h3>🔧 Paso 5: Soluciones</h3>";

echo "<div class='code-block'>
    <strong>Si MySQL no responde:</strong><br>
    1. Abrir XAMPP Control Panel<br>
    2. Hacer clic en 'Start' en MySQL<br>
    3. Verificar que el puerto 3306 esté libre<br><br>
    
    <strong>Si la base de datos 'registro' no existe:</strong><br>
    1. Abrir phpMyAdmin: http://localhost/phpmyadmin<br>
    2. Crear nueva base de datos: 'registro'<br>
    3. Importar archivo: registro.sql<br><br>
    
    <strong>Si connect.php está mal configurado:</strong><br>
    1. Verificar que \$db = 'registro'<br>
    2. Verificar credenciales de MySQL
</div>";

echo "<div class='actions'>
    <h3>🔧 Acciones Disponibles</h3>
    <a href='fix_connect.php' class='btn btn-warning'>🔧 Corregir Automáticamente</a>
    <a href='http://localhost/phpmyadmin' class='btn btn-danger' target='_blank'>🗄️ phpMyAdmin</a>
    <a href='index.php' class='btn'>🏠 Probar Página Principal</a>
    <a href='verify_project.php' class='btn'>🔍 Verificación Completa</a>
</div>";

echo "</div>
</body>
</html>";
?> 