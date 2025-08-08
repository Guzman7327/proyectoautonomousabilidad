<?php
// Script de verificación de conexión a la base de datos
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
    <title>Verificación de Conexión - Portal Turístico</title>
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
        .status-card {
            background: #f5f5f5;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        .status-card.success {
            border-left-color: #4caf50;
            background: #e8f5e8;
        }
        .status-card.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .status-card.warning {
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
        .btn-warning {
            background: #ff9800;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .info-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .info-label {
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔍 Verificación de Conexión</h1>
            <p>Comprobando la conexión entre el proyecto y la base de datos</p>
        </div>";

// Función para mostrar estado
function showStatus($message, $type = 'info') {
    echo "<div class='status-card $type'>$message</div>";
}

// 1. Verificar configuración del archivo connect.php
echo "<h3>📋 Paso 1: Verificando configuración</h3>";

if (file_exists("connect.php")) {
    showStatus("✅ Archivo connect.php encontrado", "success");
    
    // Leer el contenido del archivo
    $connectContent = file_get_contents("connect.php");
    
    // Extraer configuración de base de datos
    preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $connectContent, $matches);
    $dbName = $matches[1] ?? 'No encontrado';
    
    echo "<div class='info-grid'>
        <div class='info-item'>
            <div class='info-label'>Base de datos configurada:</div>
            <div>$dbName</div>
        </div>
        <div class='info-item'>
            <div class='info-label'>Host:</div>
            <div>localhost</div>
        </div>
        <div class='info-item'>
            <div class='info-label'>Usuario:</div>
            <div>root</div>
        </div>
    </div>";
    
} else {
    showStatus("❌ Archivo connect.php no encontrado", "error");
    echo "</div></body></html>";
    exit;
}

// 2. Verificar conexión a MySQL
echo "<h3>🔗 Paso 2: Verificando conexión a MySQL</h3>";

$mysqlConn = new mysqli("localhost", "root", "");
if ($mysqlConn->connect_error) {
    showStatus("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
    echo "<p><strong>💡 Solución:</strong> Asegúrate de que XAMPP esté ejecutándose y MySQL esté activo</p>";
} else {
    showStatus("✅ Conexión a MySQL exitosa", "success");
    echo "<div class='info-item'>
        <div class='info-label'>Versión de MySQL:</div>
        <div>" . $mysqlConn->server_info . "</div>
    </div>";
}

// 3. Verificar si la base de datos existe
echo "<h3>🗄️ Paso 3: Verificando base de datos '$dbName'</h3>";

$result = $mysqlConn->query("SHOW DATABASES LIKE '$dbName'");
if ($result->num_rows > 0) {
    showStatus("✅ Base de datos '$dbName' existe", "success");
    
    // 4. Verificar tablas
    echo "<h3>📋 Paso 4: Verificando tablas</h3>";
    
    $mysqlConn->select_db($dbName);
    $tables = $mysqlConn->query("SHOW TABLES");
    $tableCount = $tables->num_rows;
    
    if ($tableCount > 0) {
        showStatus("✅ Base de datos contiene $tableCount tablas", "success");
        
        echo "<div class='info-item'>
            <div class='info-label'>Tablas encontradas:</div>
            <div>";
        
        $tableList = [];
        while ($table = $tables->fetch_array()) {
            $tableList[] = $table[0];
        }
        echo implode(', ', $tableList);
        echo "</div></div>";
        
        // 5. Verificar datos de prueba
        echo "<h3>👥 Paso 5: Verificando datos</h3>";
        
        $result = $mysqlConn->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $userCount = $result->fetch_assoc()['count'];
            showStatus("✅ Tabla 'users' contiene $userCount usuarios", "success");
        } else {
            showStatus("⚠️ No se pudo verificar la tabla 'users'", "warning");
        }
        
        // Verificar administradores
        $result = $mysqlConn->query("SELECT COUNT(*) as count FROM admins");
        if ($result) {
            $adminCount = $result->fetch_assoc()['count'];
            showStatus("✅ Tabla 'admins' contiene $adminCount administradores", "success");
        } else {
            showStatus("⚠️ No se pudo verificar la tabla 'admins'", "warning");
        }
        
    } else {
        showStatus("❌ No se encontraron tablas en la base de datos", "error");
        echo "<p><strong>💡 Solución:</strong> Ejecuta el archivo registro.sql para crear las tablas</p>";
    }
    
} else {
    showStatus("❌ Base de datos '$dbName' no existe", "error");
    echo "<p><strong>💡 Solución:</strong> Crea la base de datos '$dbName' en phpMyAdmin</p>";
}

// 6. Probar conexión usando connect.php
echo "<h3>🔧 Paso 6: Probando conexión con connect.php</h3>";

try {
    // Incluir connect.php
    ob_start();
    include_once "connect.php";
    ob_end_clean();
    
    if (isset($conn) && !$conn->connect_error) {
        showStatus("✅ Conexión usando connect.php exitosa", "success");
        
        // Probar una consulta simple
        $result = $conn->query("SELECT 1 as test");
        if ($result) {
            showStatus("✅ Consulta de prueba exitosa", "success");
        } else {
            showStatus("⚠️ Error en consulta de prueba", "warning");
        }
        
    } else {
        showStatus("❌ Error en connect.php: " . ($conn->connect_error ?? 'Error desconocido'), "error");
    }
    
} catch (Exception $e) {
    showStatus("❌ Excepción al cargar connect.php: " . $e->getMessage(), "error");
}

// 7. Verificar archivos del proyecto
echo "<h3>📁 Paso 7: Verificando archivos del proyecto</h3>";

$requiredFiles = [
    'index.php' => 'Página principal',
    'styles.css' => 'Estilos CSS',
    'script.js' => 'JavaScript',
    'admin.php' => 'Panel de administrador',
    'admin_login.php' => 'Login de administrador',
    'registro.sql' => 'Script de base de datos'
];

$missingFiles = [];
foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "<div class='info-item'>
            <div class='info-label'>✅ $description:</div>
            <div>$file (" . number_format($size) . " bytes)</div>
        </div>";
    } else {
        $missingFiles[] = $file;
        echo "<div class='info-item'>
            <div class='info-label'>❌ $description:</div>
            <div>$file (No encontrado)</div>
        </div>";
    }
}

if (empty($missingFiles)) {
    showStatus("✅ Todos los archivos principales están presentes", "success");
} else {
    showStatus("⚠️ Faltan algunos archivos: " . implode(', ', $missingFiles), "warning");
}

// Resumen final
echo "<h3>📊 Resumen de Verificación</h3>";

$allGood = true;
if ($mysqlConn->connect_error) $allGood = false;
if ($result->num_rows === 0) $allGood = false;

if ($allGood) {
    showStatus("🎉 ¡Todo está funcionando correctamente! La base de datos está conectada al proyecto.", "success");
} else {
    showStatus("⚠️ Se encontraron algunos problemas. Revisa los detalles arriba.", "warning");
}

// Acciones disponibles
echo "<div class='actions'>
    <h3>🔧 Acciones Disponibles</h3>
    <p>Utiliza estos enlaces para acceder a tu aplicación:</p>
    
    <a href='index.php' class='btn'>🏠 Ir a la Página Principal</a>
    <a href='admin_login.php' class='btn'>👨‍💼 Panel de Administrador</a>
    <a href='http://localhost/phpmyadmin' class='btn btn-warning' target='_blank'>🗄️ phpMyAdmin</a>
</div>";

// Cerrar conexión
if (isset($mysqlConn)) {
    $mysqlConn->close();
}

echo "</div>
<script>
    // Auto-refresh cada 60 segundos
    setTimeout(function() {
        location.reload();
    }, 60000);
</script>
</body>
</html>";
?> 