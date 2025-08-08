<?php
// Script de diagnóstico detallado de conexión
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Diagnóstico de Conexión - Portal Turístico</title>
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
        .solution-box {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .error-details {
            background: #ffebee;
            border: 1px solid #f44336;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔍 Diagnóstico Detallado de Conexión</h1>
            <p>Identificando y solucionando problemas de conexión</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

// Función para mostrar solución
function showSolution($title, $steps) {
    echo "<div class='solution-box'>
        <h4>💡 $title</h4>
        <ol>";
    foreach ($steps as $step) {
        echo "<li>$step</li>";
    }
    echo "</ol></div>";
}

echo "<h3>🔍 Paso 1: Verificando servicios de XAMPP</h3>";

// Verificar si Apache está ejecutándose
$apacheRunning = false;
$apachePort = 80;
$connection = @fsockopen('localhost', $apachePort, $errno, $errstr, 5);
if (is_resource($connection)) {
    $apacheRunning = true;
    fclose($connection);
}

if ($apacheRunning) {
    showStep("✅ Apache está ejecutándose en el puerto $apachePort", "success");
} else {
    showStep("❌ Apache no está ejecutándose en el puerto $apachePort", "error");
    showSolution("Solución para Apache:", [
        "Abre XAMPP Control Panel",
        "Haz clic en 'Start' junto a Apache",
        "Espera a que el estado cambie a verde",
        "Verifica que no haya conflictos de puerto"
    ]);
}

// Verificar si MySQL está ejecutándose
$mysqlRunning = false;
$mysqlPort = 3306;
$connection = @fsockopen('localhost', $mysqlPort, $errno, $errstr, 5);
if (is_resource($connection)) {
    $mysqlRunning = true;
    fclose($connection);
}

if ($mysqlRunning) {
    showStep("✅ MySQL está ejecutándose en el puerto $mysqlPort", "success");
} else {
    showStep("❌ MySQL no está ejecutándose en el puerto $mysqlPort", "error");
    showSolution("Solución para MySQL:", [
        "Abre XAMPP Control Panel",
        "Haz clic en 'Start' junto a MySQL",
        "Espera a que el estado cambie a verde",
        "Si hay error, revisa los logs en XAMPP"
    ]);
}

echo "<h3>🔗 Paso 2: Probando conexión directa a MySQL</h3>";

// Intentar conexión sin especificar base de datos
$testConn = new mysqli("localhost", "root", "");
if ($testConn->connect_error) {
    showStep("❌ Error conectando a MySQL: " . $testConn->connect_error, "error");
    
    // Mostrar detalles del error
    echo "<div class='error-details'>
        <strong>Detalles del error:</strong><br>
        Error: " . $testConn->connect_error . "<br>
        Errno: " . $testConn->connect_errno . "<br>
        Host: localhost<br>
        Usuario: root<br>
        Contraseña: (vacía)
    </div>";
    
    // Soluciones específicas según el error
    if (strpos($testConn->connect_error, 'Access denied') !== false) {
        showSolution("Error de acceso denegado:", [
            "Verifica que el usuario 'root' no tenga contraseña",
            "Si tienes contraseña, actualiza connect.php",
            "Prueba con el usuario 'root' y contraseña vacía",
            "Verifica permisos en phpMyAdmin"
        ]);
    } elseif (strpos($testConn->connect_error, 'Connection refused') !== false) {
        showSolution("Conexión rechazada:", [
            "MySQL no está ejecutándose",
            "Inicia MySQL desde XAMPP Control Panel",
            "Verifica que no haya otro MySQL ejecutándose",
            "Revisa los logs de error de MySQL"
        ]);
    } elseif (strpos($testConn->connect_error, 'Can\'t connect') !== false) {
        showSolution("No se puede conectar:", [
            "Verifica que XAMPP esté ejecutándose",
            "Comprueba que el puerto 3306 esté libre",
            "Reinicia XAMPP completamente",
            "Verifica la configuración de firewall"
        ]);
    }
    
} else {
    showStep("✅ Conexión a MySQL exitosa", "success");
    echo "<div class='step'>
        <strong>Información de MySQL:</strong><br>
        Versión: " . $testConn->server_info . "<br>
        Host: " . $testConn->host_info . "<br>
        Puerto: " . $testConn->port . "
    </div>";
}

echo "<h3>🗄️ Paso 3: Verificando base de datos</h3>";

if ($mysqlRunning && !$testConn->connect_error) {
    // Listar todas las bases de datos
    $result = $testConn->query("SHOW DATABASES");
    if ($result) {
        $databases = [];
        while ($row = $result->fetch_array()) {
            $databases[] = $row[0];
        }
        
        echo "<div class='step'>
            <strong>Bases de datos disponibles:</strong><br>";
        foreach ($databases as $db) {
            echo "• $db<br>";
        }
        echo "</div>";
        
        // Verificar si existe la base de datos 'registro'
        if (in_array('registro', $databases)) {
            showStep("✅ Base de datos 'registro' existe", "success");
            
            // Verificar tablas
            $testConn->select_db("registro");
            $tables = $testConn->query("SHOW TABLES");
            if ($tables) {
                $tableCount = $tables->num_rows;
                showStep("✅ Base de datos 'registro' contiene $tableCount tablas", "success");
                
                if ($tableCount > 0) {
                    echo "<div class='step'>
                        <strong>Tablas encontradas:</strong><br>";
                    while ($table = $tables->fetch_array()) {
                        echo "• " . $table[0] . "<br>";
                    }
                    echo "</div>";
                }
            }
            
        } else {
            showStep("❌ Base de datos 'registro' no existe", "error");
            showSolution("Crear base de datos:", [
                "Abre phpMyAdmin: http://localhost/phpmyadmin",
                "Haz clic en 'Nueva' en el panel izquierdo",
                "Escribe 'registro' como nombre",
                "Selecciona 'utf8mb4_unicode_ci' como cotejamiento",
                "Haz clic en 'Crear'",
                "Importa el archivo registro.sql"
            ]);
        }
    }
}

echo "<h3>📁 Paso 4: Verificando archivo connect.php</h3>";

if (file_exists("connect.php")) {
    showStep("✅ Archivo connect.php existe", "success");
    
    // Leer y analizar el archivo
    $content = file_get_contents("connect.php");
    
    // Extraer configuración
    preg_match('/\$host\s*=\s*["\']([^"\']+)["\']/', $content, $hostMatch);
    preg_match('/\$user\s*=\s*["\']([^"\']+)["\']/', $content, $userMatch);
    preg_match('/\$pass\s*=\s*["\']([^"\']+)["\']/', $content, $passMatch);
    preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $dbMatch);
    
    $host = $hostMatch[1] ?? 'No encontrado';
    $user = $userMatch[1] ?? 'No encontrado';
    $pass = $passMatch[1] ?? 'No encontrado';
    $db = $dbMatch[1] ?? 'No encontrado';
    
    echo "<div class='step'>
        <strong>Configuración actual:</strong><br>
        Host: $host<br>
        Usuario: $user<br>
        Contraseña: " . (empty($pass) ? '(vacía)' : '***') . "<br>
        Base de datos: $db
    </div>";
    
    // Verificar si la configuración es correcta
    if ($host === 'localhost' && $user === 'root' && $db === 'registro') {
        showStep("✅ Configuración de connect.php correcta", "success");
    } else {
        showStep("⚠️ Configuración de connect.php puede tener problemas", "warning");
    }
    
} else {
    showStep("❌ Archivo connect.php no existe", "error");
}

echo "<h3>🔧 Paso 5: Prueba de conexión con connect.php</h3>";

// Intentar cargar connect.php
try {
    ob_start();
    include_once "connect.php";
    ob_end_clean();
    
    if (isset($conn)) {
        if (!$conn->connect_error) {
            showStep("✅ Conexión usando connect.php exitosa", "success");
            
            // Probar consulta
            $result = $conn->query("SELECT 1 as test");
            if ($result) {
                showStep("✅ Consulta de prueba exitosa", "success");
            } else {
                showStep("⚠️ Error en consulta de prueba", "warning");
            }
            
        } else {
            showStep("❌ Error en connect.php: " . $conn->connect_error, "error");
        }
    } else {
        showStep("❌ Variable \$conn no está definida en connect.php", "error");
    }
    
} catch (Exception $e) {
    showStep("❌ Excepción al cargar connect.php: " . $e->getMessage(), "error");
}

echo "<h3>📋 Paso 6: Resumen y Soluciones</h3>";

// Determinar el problema principal
$problems = [];
if (!$apacheRunning) $problems[] = "Apache no está ejecutándose";
if (!$mysqlRunning) $problems[] = "MySQL no está ejecutándose";
if ($testConn->connect_error) $problems[] = "Error de conexión a MySQL";

if (empty($problems)) {
    showStep("🎉 ¡No se encontraron problemas! La conexión debería funcionar.", "success");
} else {
    showStep("⚠️ Problemas encontrados: " . implode(", ", $problems), "warning");
}

echo "<div class='solution-box'>
    <h4>🚀 Pasos para solucionar problemas comunes:</h4>
    <ol>
        <li><strong>Reiniciar XAMPP:</strong> Cierra XAMPP completamente y vuelve a abrirlo</li>
        <li><strong>Verificar puertos:</strong> Asegúrate de que los puertos 80 y 3306 estén libres</li>
        <li><strong>Crear base de datos:</strong> Si no existe 'registro', créala en phpMyAdmin</li>
        <li><strong>Importar SQL:</strong> Ejecuta registro.sql en phpMyAdmin</li>
        <li><strong>Verificar permisos:</strong> Asegúrate de que el usuario 'root' no tenga contraseña</li>
    </ol>
</div>";

// Acciones disponibles
echo "<div style='text-align: center; margin-top: 30px;'>
    <h3>🔧 Acciones Disponibles</h3>
    <a href='http://localhost/phpmyadmin' class='btn btn-warning' target='_blank'>🗄️ phpMyAdmin</a>
    <a href='index.php' class='btn'>🏠 Página Principal</a>
    <a href='quick_test.php' class='btn'>🔍 Prueba Rápida</a>
    <a href='verify_connection.php' class='btn'>📊 Verificación Completa</a>
</div>";

// Cerrar conexión
if (isset($testConn)) {
    $testConn->close();
}

echo "</div></body></html>";
?> 