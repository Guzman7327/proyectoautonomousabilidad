<?php
// Script para verificar la configuración de la base de datos en connect.php
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Verificación de Configuración - Portal Turístico</title>
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
        .config-card {
            background: #f5f5f5;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        .config-card.success {
            border-left-color: #4caf50;
            background: #e8f5e8;
        }
        .config-card.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .config-card.warning {
            border-left-color: #ff9800;
            background: #fff3e0;
        }
        .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .config-item:last-child {
            border-bottom: none;
        }
        .config-label {
            font-weight: bold;
            color: #2e7d32;
        }
        .config-value {
            font-family: monospace;
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 4px;
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
            <h1>🔍 Verificación de Configuración de Base de Datos</h1>
            <p>Comprobando la configuración en connect.php</p>
        </div>";

// Función para mostrar configuración
function showConfig($title, $items, $type = 'info') {
    echo "<div class='config-card $type'>
        <h3>$title</h3>";
    foreach ($items as $label => $value) {
        echo "<div class='config-item'>
            <span class='config-label'>$label:</span>
            <span class='config-value'>$value</span>
        </div>";
    }
    echo "</div>";
}

echo "<h3>📋 Paso 1: Verificando archivo connect.php</h3>";

if (file_exists("connect.php")) {
    echo "<div class='config-card success'>
        <h3>✅ Archivo connect.php encontrado</h3>
        <div class='config-item'>
            <span class='config-label'>Tamaño del archivo:</span>
            <span class='config-value'>" . number_format(filesize("connect.php")) . " bytes</span>
        </div>
    </div>";
    
    // Leer el contenido del archivo
    $content = file_get_contents("connect.php");
    
    // Extraer configuración usando expresiones regulares
    preg_match('/\$host\s*=\s*["\']([^"\']+)["\']/', $content, $hostMatch);
    preg_match('/\$user\s*=\s*["\']([^"\']+)["\']/', $content, $userMatch);
    preg_match('/\$pass\s*=\s*["\']([^"\']+)["\']/', $content, $passMatch);
    preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $dbMatch);
    
    $host = $hostMatch[1] ?? 'No encontrado';
    $user = $userMatch[1] ?? 'No encontrado';
    $pass = $passMatch[1] ?? 'No encontrado';
    $db = $dbMatch[1] ?? 'No encontrado';
    
    // Mostrar configuración actual
    $configItems = [
        'Host' => $host,
        'Usuario' => $user,
        'Contraseña' => empty($pass) ? '(vacía)' : '***',
        'Base de datos' => $db
    ];
    
    $configType = 'info';
    if ($host === 'localhost' && $user === 'root' && $db === 'registro') {
        $configType = 'success';
    } elseif ($host === 'No encontrado' || $user === 'No encontrado' || $db === 'No encontrado') {
        $configType = 'error';
    } else {
        $configType = 'warning';
    }
    
    showConfig("📊 Configuración Actual", $configItems, $configType);
    
    // Verificar si la configuración es correcta
    echo "<h3>✅ Paso 2: Validando configuración</h3>";
    
    $issues = [];
    $recommendations = [];
    
    if ($host !== 'localhost') {
        $issues[] = "Host configurado como '$host' en lugar de 'localhost'";
        $recommendations[] = "Cambiar host a 'localhost'";
    }
    
    if ($user !== 'root') {
        $issues[] = "Usuario configurado como '$user' en lugar de 'root'";
        $recommendations[] = "Cambiar usuario a 'root'";
    }
    
    if (!empty($pass)) {
        $issues[] = "Contraseña configurada (debería estar vacía para XAMPP)";
        $recommendations[] = "Dejar contraseña vacía para XAMPP";
    }
    
    if ($db !== 'registro') {
        $issues[] = "Base de datos configurada como '$db' en lugar de 'registro'";
        $recommendations[] = "Cambiar base de datos a 'registro'";
    }
    
    if (empty($issues)) {
        echo "<div class='config-card success'>
            <h3>🎉 Configuración Correcta</h3>
            <p>La configuración en connect.php es correcta para XAMPP.</p>
        </div>";
    } else {
        echo "<div class='config-card error'>
            <h3>⚠️ Problemas Encontrados</h3>
            <ul>";
        foreach ($issues as $issue) {
            echo "<li>$issue</li>";
        }
        echo "</ul>
        </div>";
        
        echo "<div class='config-card warning'>
            <h3>💡 Recomendaciones</h3>
            <ul>";
        foreach ($recommendations as $rec) {
            echo "<li>$rec</li>";
        }
        echo "</ul>
        </div>";
        
        // Mostrar configuración correcta
        echo "<div class='config-card'>
            <h3>📝 Configuración Correcta</h3>
            <div class='code-block'>
// Configuración de la base de datos
\$host = \"localhost\";
\$user = \"root\";
\$pass = \"\";
\$db = \"registro\";
            </div>
        </div>";
    }
    
} else {
    echo "<div class='config-card error'>
        <h3>❌ Archivo connect.php no encontrado</h3>
        <p>El archivo connect.php no existe en el directorio actual.</p>
    </div>";
}

echo "<h3>🔗 Paso 3: Probando conexión con la configuración actual</h3>";

try {
    // Incluir connect.php
    ob_start();
    include_once "connect.php";
    ob_end_clean();
    
    if (isset($conn)) {
        if (!$conn->connect_error) {
            echo "<div class='config-card success'>
                <h3>✅ Conexión Exitosa</h3>
                <div class='config-item'>
                    <span class='config-label'>Estado:</span>
                    <span class='config-value'>Conectado</span>
                </div>
                <div class='config-item'>
                    <span class='config-label'>Base de datos:</span>
                    <span class='config-value'>" . $conn->database . "</span>
                </div>
                <div class='config-item'>
                    <span class='config-label'>Host:</span>
                    <span class='config-value'>" . $conn->host_info . "</span>
                </div>
            </div>";
            
            // Probar consulta
            $result = $conn->query("SELECT COUNT(*) as total FROM users");
            if ($result) {
                $userCount = $result->fetch_assoc()['total'];
                echo "<div class='config-card success'>
                    <h3>✅ Consulta de Prueba Exitosa</h3>
                    <div class='config-item'>
                        <span class='config-label'>Usuarios en la base de datos:</span>
                        <span class='config-value'>$userCount</span>
                    </div>
                </div>";
            }
            
        } else {
            echo "<div class='config-card error'>
                <h3>❌ Error de Conexión</h3>
                <div class='config-item'>
                    <span class='config-label'>Error:</span>
                    <span class='config-value'>" . $conn->connect_error . "</span>
                </div>
            </div>";
        }
    } else {
        echo "<div class='config-card error'>
            <h3>❌ Variable \$conn no definida</h3>
            <p>El archivo connect.php no está creando la variable \$conn correctamente.</p>
        </div>";
    }
    
} catch (Exception $e) {
    echo "<div class='config-card error'>
        <h3>❌ Excepción</h3>
        <div class='config-item'>
            <span class='config-label'>Error:</span>
            <span class='config-value'>" . $e->getMessage() . "</span>
        </div>
    </div>";
}

echo "<h3>📊 Paso 4: Resumen</h3>";

// Determinar estado general
$allGood = true;
if (!file_exists("connect.php")) $allGood = false;
if (isset($conn) && $conn->connect_error) $allGood = false;

if ($allGood) {
    echo "<div class='config-card success'>
        <h3>🎉 ¡Configuración Correcta!</h3>
        <p>La configuración de la base de datos está funcionando correctamente.</p>
    </div>";
} else {
    echo "<div class='config-card warning'>
        <h3>⚠️ Se encontraron problemas</h3>
        <p>Revisa los detalles arriba y corrige los problemas identificados.</p>
    </div>";
}

// Acciones disponibles
echo "<div class='actions'>
    <h3>🔧 Acciones Disponibles</h3>
    <a href='fix_connection.php' class='btn'>🔧 Solución Automática</a>
    <a href='diagnostic.php' class='btn btn-warning'>🔍 Diagnóstico Completo</a>
    <a href='index.php' class='btn'>🏠 Página Principal</a>
    <a href='http://localhost/phpmyadmin' class='btn btn-danger' target='_blank'>🗄️ phpMyAdmin</a>
</div>";

echo "</div>
<script>
    // Auto-refresh cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
</body>
</html>";
?> 