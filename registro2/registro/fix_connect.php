<?php
// Script para corregir automáticamente connect.php
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Corrección de connect.php - Portal Turístico</title>
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
            <h1>🔧 Corrección de connect.php</h1>
            <p>Corrigiendo configuración de base de datos</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

// Verificar si ya se ejecutó la corrección
if (isset($_POST['confirm_fix'])) {
    echo "<h3>🔧 Ejecutando Corrección...</h3>";
    
    if (file_exists("connect.php")) {
        // Leer el contenido actual
        $content = file_get_contents("connect.php");
        
        // Verificar configuración actual
        preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $match);
        $currentDB = $match[1] ?? 'No encontrado';
        
        showStep("🔍 Configuración actual: \$db = '$currentDB'", "info");
        
        if ($currentDB === 'registro') {
            showStep("✅ connect.php ya está configurado correctamente", "success");
        } else {
            // Corregir la configuración
            $newContent = preg_replace('/\$db\s*=\s*["\'][^"\']+["\']/', '$db = "registro"', $content);
            
            if (file_put_contents("connect.php", $newContent)) {
                showStep("✅ connect.php corregido exitosamente", "success");
                showStep("✅ Cambiado de '$currentDB' a 'registro'", "success");
            } else {
                showStep("❌ Error al escribir el archivo connect.php", "error");
            }
        }
        
        // Verificar la corrección
        $newContent = file_get_contents("connect.php");
        preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $newContent, $match);
        $newDB = $match[1] ?? 'No encontrado';
        
        showStep("🔍 Configuración después de la corrección: \$db = '$newDB'", "info");
        
    } else {
        showStep("❌ Archivo connect.php no encontrado", "error");
    }
    
    echo "<h3>🎯 Prueba de Conexión</h3>";
    
    // Probar conexión
    try {
        ob_start();
        include_once "connect.php";
        ob_end_clean();
        
        if (isset($conn)) {
            if (!$conn->connect_error) {
                showStep("🎉 ¡Conexión exitosa!", "success");
                showStep("✅ El proyecto ahora funciona correctamente", "success");
                
                // Probar consulta
                $result = $conn->query("SELECT COUNT(*) as total FROM users");
                if ($result) {
                    $userCount = $result->fetch_assoc()['total'];
                    showStep("✅ Consulta de prueba exitosa: $userCount usuarios", "success");
                }
                
            } else {
                showStep("❌ Error de conexión: " . $conn->connect_error, "error");
            }
        } else {
            showStep("❌ Variable \$conn no definida", "error");
        }
        
    } catch (Exception $e) {
        showStep("❌ Excepción: " . $e->getMessage(), "error");
    }
    
    echo "<div class='actions'>
        <h3>🎉 ¡Corrección Completada!</h3>
        <a href='verify_project.php' class='btn'>🔍 Verificar Proyecto</a>
        <a href='index.php' class='btn btn-warning'>🏠 Ir a la Página Principal</a>
        <a href='admin.php' class='btn btn-warning'>🔧 Panel de Administración</a>
    </div>";
    
} else {
    // Mostrar información y botón de confirmación
    echo "<h3>📋 Información de la Corrección</h3>";
    
    if (file_exists("connect.php")) {
        $content = file_get_contents("connect.php");
        preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $match);
        $currentDB = $match[1] ?? 'No encontrado';
        
        showStep("🔍 Configuración actual detectada: \$db = '$currentDB'", "info");
        
        if ($currentDB === 'registro') {
            showStep("✅ connect.php ya está configurado correctamente", "success");
            showStep("💡 No es necesario hacer cambios", "info");
        } else {
            showStep("⚠️ Se detectó configuración incorrecta: '$currentDB'", "warning");
            showStep("💡 Se cambiará a 'registro'", "info");
        }
        
        echo "<div class='code-block'>
            <strong>Configuración que se aplicará:</strong><br>
            \$host = \"localhost\";<br>
            \$user = \"root\";<br>
            \$pass = \"\";<br>
            \$db = \"registro\";
        </div>";
        
    } else {
        showStep("❌ Archivo connect.php no encontrado", "error");
    }
    
    echo "<div class='actions'>
        <h3>⚠️ Confirmar Corrección</h3>
        <p>¿Quieres corregir la configuración de connect.php?</p>
        <form method='POST'>
            <button type='submit' name='confirm_fix' class='btn btn-warning'>🔧 Sí, Corregir Ahora</button>
        </form>
        <a href='verify_project.php' class='btn'>🔍 Volver a Verificar</a>
        <a href='index.php' class='btn'>❌ Cancelar</a>
    </div>";
}

echo "</div>
</body>
</html>";
?> 