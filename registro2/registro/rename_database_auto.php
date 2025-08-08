<?php
// Script para renombrar automáticamente la base de datos
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Renombrar Base de Datos - Portal Turístico</title>
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
            <h1>🔄 Renombrar Base de Datos</h1>
            <p>Renombrando 'resgistro' a 'registro' automáticamente</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

// Verificar si ya se ejecutó el renombrado
if (isset($_POST['confirm_rename'])) {
    echo "<h3>🔄 Ejecutando Renombrado...</h3>";
    
    try {
        // Conectar a MySQL
        $mysqlConn = new mysqli("localhost", "root", "");
        if ($mysqlConn->connect_error) {
            showStep("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
        } else {
            showStep("✅ Conexión a MySQL exitosa", "success");
            
            // Verificar si existe 'resgistro'
            $result = $mysqlConn->query("SHOW DATABASES LIKE 'resgistro'");
            if ($result->num_rows > 0) {
                showStep("✅ Base de datos 'resgistro' encontrada", "success");
                
                // Verificar si 'registro' ya existe
                $result = $mysqlConn->query("SHOW DATABASES LIKE 'registro'");
                if ($result->num_rows > 0) {
                    showStep("⚠️ La base de datos 'registro' ya existe", "warning");
                    showStep("💡 Se eliminará 'registro' y luego se renombrará 'resgistro'", "warning");
                    
                    // Eliminar 'registro' si existe
                    if ($mysqlConn->query("DROP DATABASE registro")) {
                        showStep("✅ Base de datos 'registro' eliminada", "success");
                    } else {
                        showStep("❌ Error eliminando 'registro': " . $mysqlConn->error, "error");
                    }
                }
                
                // Renombrar 'resgistro' a 'registro'
                if ($mysqlConn->query("RENAME DATABASE resgistro TO registro")) {
                    showStep("🎉 ¡Base de datos renombrada exitosamente!", "success");
                    showStep("✅ 'resgistro' → 'registro'", "success");
                    
                    // Verificar que el renombrado fue exitoso
                    $result = $mysqlConn->query("SHOW DATABASES LIKE 'registro'");
                    if ($result->num_rows > 0) {
                        showStep("✅ Verificación: 'registro' existe", "success");
                        
                        // Verificar tablas
                        $mysqlConn->select_db("registro");
                        $tables = $mysqlConn->query("SHOW TABLES");
                        $tableCount = $tables->num_rows;
                        showStep("✅ Base de datos 'registro' contiene $tableCount tablas", "success");
                        
                        // Verificar usuarios
                        $result = $mysqlConn->query("SELECT COUNT(*) as total FROM users");
                        if ($result) {
                            $userCount = $result->fetch_assoc()['total'];
                            showStep("✅ Tabla 'users' contiene $userCount usuarios", "success");
                        }
                        
                    } else {
                        showStep("❌ Error: 'registro' no existe después del renombrado", "error");
                    }
                    
                } else {
                    showStep("❌ Error renombrando la base de datos: " . $mysqlConn->error, "error");
                }
                
            } else {
                showStep("❌ Base de datos 'resgistro' no encontrada", "error");
                showStep("💡 Verifica que el nombre sea correcto", "warning");
            }
            
            $mysqlConn->close();
        }
        
    } catch (Exception $e) {
        showStep("❌ Excepción: " . $e->getMessage(), "error");
    }
    
    echo "<h3>🎯 Prueba Final</h3>";
    
    // Probar conexión con connect.php
    try {
        ob_start();
        include_once "connect.php";
        ob_end_clean();
        
        if (isset($conn) && !$conn->connect_error) {
            showStep("🎉 ¡Conexión exitosa con connect.php!", "success");
            showStep("✅ El proyecto ahora funciona correctamente", "success");
        } else {
            showStep("❌ Error en connect.php: " . ($conn->connect_error ?? "Variable \$conn no definida"), "error");
        }
        
    } catch (Exception $e) {
        showStep("❌ Error probando connect.php: " . $e->getMessage(), "error");
    }
    
    echo "<div class='actions'>
        <h3>🎉 ¡Renombrado Completado!</h3>
        <a href='index.php' class='btn'>🏠 Ir a la Página Principal</a>
        <a href='admin.php' class='btn btn-warning'>🔧 Panel de Administración</a>
        <a href='http://localhost/phpmyadmin' class='btn btn-danger' target='_blank'>🗄️ phpMyAdmin</a>
    </div>";
    
} else {
    // Mostrar información y botón de confirmación
    echo "<h3>📋 Información del Renombrado</h3>";
    
    showStep("🔍 Se va a renombrar la base de datos 'resgistro' a 'registro'", "info");
    showStep("💡 Esto corregirá la inconsistencia en el nombre", "info");
    showStep("⚠️ Si existe 'registro', se eliminará y reemplazará", "warning");
    
    echo "<div class='code-block'>
        <strong>Comando que se ejecutará:</strong><br>
        RENAME DATABASE resgistro TO registro;
    </div>";
    
    echo "<div class='actions'>
        <h3>⚠️ Confirmar Renombrado</h3>
        <p>¿Estás seguro de que quieres renombrar la base de datos?</p>
        <form method='POST'>
            <button type='submit' name='confirm_rename' class='btn btn-warning'>🔄 Sí, Renombrar Ahora</button>
        </form>
        <a href='fix_database_name.php' class='btn'>🔍 Volver a Verificar</a>
        <a href='index.php' class='btn btn-danger'>❌ Cancelar</a>
    </div>";
}

echo "</div>
</body>
</html>";
?> 