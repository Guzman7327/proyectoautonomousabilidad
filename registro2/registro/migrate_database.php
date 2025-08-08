<?php
// Script para migrar datos de 'resgistro' a 'registro'
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Migración de Base de Datos - Portal Turístico</title>
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
        .step.info {
            border-left-color: #2196f3;
            background: #e3f2fd;
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
        .progress {
            background: #e9ecef;
            border-radius: 10px;
            height: 20px;
            margin: 10px 0;
            overflow: hidden;
        }
        .progress-bar {
            background: linear-gradient(90deg, #4caf50, #45a049);
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔄 Migración de Base de Datos</h1>
            <p>Migrando datos de 'resgistro' a 'registro'</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

// Verificar si ya se ejecutó la migración
if (isset($_POST['confirm_migrate'])) {
    echo "<h3>🔄 Ejecutando Migración...</h3>";
    
    try {
        // Conectar a MySQL
        $mysqlConn = new mysqli("localhost", "root", "");
        if ($mysqlConn->connect_error) {
            showStep("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
        } else {
            showStep("✅ Conexión a MySQL exitosa", "success");
            
            // Paso 1: Verificar si existe 'resgistro'
            $result = $mysqlConn->query("SHOW DATABASES LIKE 'resgistro'");
            if ($result->num_rows > 0) {
                showStep("✅ Base de datos 'resgistro' encontrada", "success");
                
                // Paso 2: Verificar si 'registro' ya existe
                $result = $mysqlConn->query("SHOW DATABASES LIKE 'registro'");
                if ($result->num_rows > 0) {
                    showStep("⚠️ La base de datos 'registro' ya existe", "warning");
                    showStep("💡 Se eliminará 'registro' y se creará nueva", "warning");
                    
                    // Eliminar 'registro' si existe
                    if ($mysqlConn->query("DROP DATABASE registro")) {
                        showStep("✅ Base de datos 'registro' eliminada", "success");
                    } else {
                        showStep("❌ Error eliminando 'registro': " . $mysqlConn->error, "error");
                    }
                }
                
                // Paso 3: Crear nueva base de datos 'registro'
                if ($mysqlConn->query("CREATE DATABASE registro")) {
                    showStep("✅ Base de datos 'registro' creada", "success");
                    
                    // Paso 4: Obtener estructura de 'resgistro'
                    $mysqlConn->select_db("resgistro");
                    $tables = $mysqlConn->query("SHOW TABLES");
                    $tableList = [];
                    while ($row = $tables->fetch_array()) {
                        $tableList[] = $row[0];
                    }
                    
                    showStep("📋 Encontradas " . count($tableList) . " tablas para migrar", "info");
                    
                    // Paso 5: Migrar cada tabla
                    $migratedTables = 0;
                    foreach ($tableList as $table) {
                        showStep("🔄 Migrando tabla: $table", "info");
                        
                        // Obtener estructura de la tabla
                        $createTable = $mysqlConn->query("SHOW CREATE TABLE $table");
                        $createTableRow = $createTable->fetch_array();
                        $createTableSQL = $createTableRow[1];
                        
                        // Crear tabla en 'registro'
                        $mysqlConn->select_db("registro");
                        if ($mysqlConn->query($createTableSQL)) {
                            showStep("✅ Estructura de tabla '$table' creada", "success");
                            
                            // Migrar datos
                            $mysqlConn->select_db("resgistro");
                            $data = $mysqlConn->query("SELECT * FROM $table");
                            $rowCount = 0;
                            
                            if ($data->num_rows > 0) {
                                $mysqlConn->select_db("registro");
                                while ($row = $data->fetch_assoc()) {
                                    $columns = implode("`, `", array_keys($row));
                                    $values = "'" . implode("', '", array_map([$mysqlConn, 'real_escape_string'], $row)) . "'";
                                    $insertSQL = "INSERT INTO `$table` (`$columns`) VALUES ($values)";
                                    
                                    if ($mysqlConn->query($insertSQL)) {
                                        $rowCount++;
                                    }
                                }
                                showStep("✅ Migrados $rowCount registros de tabla '$table'", "success");
                            } else {
                                showStep("ℹ️ Tabla '$table' está vacía", "info");
                            }
                            
                            $migratedTables++;
                        } else {
                            showStep("❌ Error creando tabla '$table': " . $mysqlConn->error, "error");
                        }
                    }
                    
                    showStep("🎉 ¡Migración completada! $migratedTables tablas migradas", "success");
                    
                    // Paso 6: Verificar migración
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
                    
                    // Paso 7: Eliminar base de datos antigua
                    $mysqlConn->select_db("mysql"); // Cambiar a base de datos del sistema
                    if ($mysqlConn->query("DROP DATABASE resgistro")) {
                        showStep("🗑️ Base de datos 'resgistro' eliminada", "success");
                    } else {
                        showStep("⚠️ No se pudo eliminar 'resgistro': " . $mysqlConn->error, "warning");
                    }
                    
                } else {
                    showStep("❌ Error creando base de datos 'registro': " . $mysqlConn->error, "error");
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
        <h3>🎉 ¡Migración Completada!</h3>
        <a href='index.php' class='btn'>🏠 Ir a la Página Principal</a>
        <a href='admin.php' class='btn btn-warning'>🔧 Panel de Administración</a>
        <a href='http://localhost/phpmyadmin' class='btn btn-info' target='_blank'>🗄️ phpMyAdmin</a>
    </div>";
    
} else {
    // Mostrar información y botón de confirmación
    echo "<h3>📋 Información de la Migración</h3>";
    
    showStep("🔍 Se va a migrar toda la base de datos 'resgistro' a 'registro'", "info");
    showStep("💡 Se creará una nueva base de datos 'registro'", "info");
    showStep("📋 Se migrarán todas las tablas y datos", "info");
    showStep("🗑️ Se eliminará la base de datos 'resgistro' antigua", "warning");
    
    echo "<div class='code-block'>
        <strong>Proceso que se ejecutará:</strong><br>
        1. Crear base de datos 'registro'<br>
        2. Migrar estructura de todas las tablas<br>
        3. Migrar todos los datos<br>
        4. Eliminar base de datos 'resgistro'<br>
        5. Verificar conexión
    </div>";
    
    echo "<div class='actions'>
        <h3>⚠️ Confirmar Migración</h3>
        <p>¿Estás seguro de que quieres migrar la base de datos?</p>
        <form method='POST'>
            <button type='submit' name='confirm_migrate' class='btn btn-warning'>🔄 Sí, Migrar Ahora</button>
        </form>
        <a href='fix_database_name.php' class='btn btn-info'>🔍 Volver a Verificar</a>
        <a href='index.php' class='btn btn-danger'>❌ Cancelar</a>
    </div>";
}

echo "</div>
</body>
</html>";
?> 