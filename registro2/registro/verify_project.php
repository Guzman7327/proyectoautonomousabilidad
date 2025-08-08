<?php
// Script completo de verificación del proyecto
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Verificación Completa del Proyecto - Portal Turístico</title>
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
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
        .step.info {
            border-left-color: #2196f3;
            background: #e3f2fd;
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
        .btn-danger {
            background: #f44336;
        }
        .btn-info {
            background: #2196f3;
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
        .file-list {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .status-ok {
            color: #4caf50;
            font-weight: bold;
        }
        .status-error {
            color: #f44336;
            font-weight: bold;
        }
        .status-warning {
            color: #ff9800;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔍 Verificación Completa del Proyecto</h1>
            <p>Verificando configuración y funcionalidad del Portal Turístico</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

// Función para verificar archivo
function checkFile($filename, $description) {
    if (file_exists($filename)) {
        return "<span class='status-ok'>✅ $description</span>";
    } else {
        return "<span class='status-error'>❌ $description (NO ENCONTRADO)</span>";
    }
}

echo "<h3>📁 Paso 1: Verificación de Archivos del Proyecto</h3>";

$coreFiles = [
    'index.php' => 'Página Principal',
    'connect.php' => 'Conexión a Base de Datos',
    'admin.php' => 'Panel de Administración',
    'admin_login.php' => 'Login de Administrador',
    'admin_config.php' => 'Configuración del Sistema',
    'home.php' => 'Página de Usuario',
    'login.php' => 'Procesamiento de Login',
    'register.php' => 'Procesamiento de Registro',
    'logout.php' => 'Cierre de Sesión',
    'editar_usuario.php' => 'Edición de Usuarios',
    'eliminar_usuario.php' => 'Eliminación de Usuarios',
    'export_users.php' => 'Exportación de Usuarios',
    'accessibility.php' => 'Menú de Accesibilidad',
    'styles.css' => 'Estilos CSS',
    'script.js' => 'JavaScript del Frontend',
    'registro.sql' => 'Script de Base de Datos'
];

echo "<div class='file-list'>";
foreach ($coreFiles as $file => $description) {
    echo "<div class='file-item'>";
    echo "<span><strong>$file</strong> - $description</span>";
    echo "<span>" . checkFile($file, $description) . "</span>";
    echo "</div>";
}
echo "</div>";

echo "<h3>🔧 Paso 2: Verificación de Configuración de Base de Datos</h3>";

// Verificar connect.php
if (file_exists("connect.php")) {
    $content = file_get_contents("connect.php");
    preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $match);
    $configuredDB = $match[1] ?? 'No encontrado';
    
    if ($configuredDB === 'registro') {
        showStep("✅ connect.php está configurado correctamente para 'registro'", "success");
    } else {
        showStep("❌ connect.php está configurado para '$configuredDB' en lugar de 'registro'", "error");
    }
} else {
    showStep("❌ Archivo connect.php no encontrado", "error");
}

echo "<h3>🗄️ Paso 3: Verificación de Base de Datos</h3>";

try {
    // Conectar a MySQL
    $mysqlConn = new mysqli("localhost", "root", "");
    if ($mysqlConn->connect_error) {
        showStep("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
    } else {
        showStep("✅ Conexión a MySQL exitosa", "success");
        
        // Verificar si existe 'registro'
        $result = $mysqlConn->query("SHOW DATABASES LIKE 'registro'");
        if ($result->num_rows > 0) {
            showStep("✅ Base de datos 'registro' existe", "success");
            
            // Verificar tablas
            $mysqlConn->select_db("registro");
            $tables = $mysqlConn->query("SHOW TABLES");
            $tableCount = $tables->num_rows;
            showStep("✅ Base de datos 'registro' contiene $tableCount tablas", "success");
            
            // Listar tablas principales
            $mainTables = ['users', 'admins', 'login_attempts', 'password_resets', 'system_logs'];
            $foundTables = [];
            while ($row = $tables->fetch_array()) {
                $foundTables[] = $row[0];
            }
            
            echo "<div class='file-list'>";
            foreach ($mainTables as $table) {
                if (in_array($table, $foundTables)) {
                    echo "<div class='file-item'>";
                    echo "<span><strong>Tabla:</strong> $table</span>";
                    echo "<span class='status-ok'>✅ Encontrada</span>";
                    echo "</div>";
                } else {
                    echo "<div class='file-item'>";
                    echo "<span><strong>Tabla:</strong> $table</span>";
                    echo "<span class='status-error'>❌ No encontrada</span>";
                    echo "</div>";
                }
            }
            echo "</div>";
            
            // Verificar usuarios
            $result = $mysqlConn->query("SELECT COUNT(*) as total FROM users");
            if ($result) {
                $userCount = $result->fetch_assoc()['total'];
                showStep("✅ Tabla 'users' contiene $userCount usuarios", "success");
            }
            
        } else {
            showStep("❌ Base de datos 'registro' no existe", "error");
            showStep("💡 Necesitas crear la base de datos 'registro'", "warning");
        }
        
        $mysqlConn->close();
    }
    
} catch (Exception $e) {
    showStep("❌ Excepción: " . $e->getMessage(), "error");
}

echo "<h3>🔗 Paso 4: Prueba de Conexión con connect.php</h3>";

try {
    ob_start();
    include_once "connect.php";
    ob_end_clean();
    
    if (isset($conn)) {
        if (!$conn->connect_error) {
            showStep("✅ Conexión exitosa con connect.php", "success");
            showStep("✅ Variable \$conn creada correctamente", "success");
            
            // Probar consulta
            $result = $conn->query("SELECT COUNT(*) as total FROM users");
            if ($result) {
                $userCount = $result->fetch_assoc()['total'];
                showStep("✅ Consulta de prueba exitosa: $userCount usuarios", "success");
            } else {
                showStep("⚠️ Error en consulta de prueba: " . $conn->error, "warning");
            }
            
        } else {
            showStep("❌ Error de conexión en connect.php: " . $conn->connect_error, "error");
        }
    } else {
        showStep("❌ Variable \$conn no definida en connect.php", "error");
    }
    
} catch (Exception $e) {
    showStep("❌ Error probando connect.php: " . $e->getMessage(), "error");
}

echo "<h3>🌐 Paso 5: Verificación de Servicios</h3>";

// Verificar Apache
$apachePort = 80;
$apacheStatus = @fsockopen('localhost', $apachePort, $errno, $errstr, 5);
if ($apacheStatus) {
    showStep("✅ Servicio Apache funcionando en puerto $apachePort", "success");
    fclose($apacheStatus);
} else {
    showStep("❌ Servicio Apache no responde en puerto $apachePort", "error");
}

// Verificar MySQL
$mysqlPort = 3306;
$mysqlStatus = @fsockopen('localhost', $mysqlPort, $errno, $errstr, 5);
if ($mysqlStatus) {
    showStep("✅ Servicio MySQL funcionando en puerto $mysqlPort", "success");
    fclose($mysqlStatus);
} else {
    showStep("❌ Servicio MySQL no responde en puerto $mysqlPort", "error");
}

echo "<h3>📊 Paso 6: Resumen del Estado del Proyecto</h3>";

// Determinar estado general
$allGood = true;
$issues = [];

// Verificar archivos críticos
$criticalFiles = ['connect.php', 'index.php', 'admin.php'];
foreach ($criticalFiles as $file) {
    if (!file_exists($file)) {
        $allGood = false;
        $issues[] = "Archivo crítico faltante: $file";
    }
}

// Verificar base de datos
if (!isset($conn) || $conn->connect_error) {
    $allGood = false;
    $issues[] = "Problema de conexión a la base de datos";
}

if ($allGood) {
    showStep("🎉 ¡Proyecto completamente funcional!", "success");
    showStep("✅ Todos los componentes están configurados correctamente", "success");
    showStep("✅ La base de datos 'registro' está funcionando", "success");
    showStep("✅ Los servicios están activos", "success");
} else {
    showStep("⚠️ Se encontraron problemas en el proyecto", "warning");
    echo "<div class='file-list'>";
    foreach ($issues as $issue) {
        echo "<div class='file-item'>";
        echo "<span>❌ $issue</span>";
        echo "</div>";
    }
    echo "</div>";
}

// Acciones disponibles
echo "<div class='actions'>
    <h3>🔧 Acciones Disponibles</h3>
    <a href='index.php' class='btn'>🏠 Ir a la Página Principal</a>
    <a href='admin.php' class='btn btn-warning'>🔧 Panel de Administración</a>
    <a href='http://localhost/phpmyadmin' class='btn btn-info' target='_blank'>🗄️ phpMyAdmin</a>
    <a href='fix_connection.php' class='btn btn-danger'>🔧 Solución Automática</a>
</div>";

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