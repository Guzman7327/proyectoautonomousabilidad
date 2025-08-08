<?php
// Script para verificar y corregir el nombre de la base de datos
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Corrección de Nombre de Base de Datos - Portal Turístico</title>
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
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔧 Corrección de Nombre de Base de Datos</h1>
            <p>Verificando y corrigiendo inconsistencias en el nombre de la base de datos</p>
        </div>";

// Función para mostrar paso
function showStep($message, $type = 'info') {
    echo "<div class='step $type'>$message</div>";
}

echo "<h3>🔍 Paso 1: Verificando bases de datos existentes</h3>";

$mysqlConn = new mysqli("localhost", "root", "");
if ($mysqlConn->connect_error) {
    showStep("❌ Error conectando a MySQL: " . $mysqlConn->connect_error, "error");
    echo "</div></body></html>";
    exit;
}

// Listar todas las bases de datos
$result = $mysqlConn->query("SHOW DATABASES");
$databases = [];
while ($row = $result->fetch_array()) {
    $databases[] = $row[0];
}

echo "<div class='step'>
    <h3>📋 Bases de datos encontradas:</h3>
    <ul>";
foreach ($databases as $db) {
    echo "<li>$db</li>";
}
echo "</ul>
</div>";

// Verificar si existe 'registro' y 'resgistro'
$registroExists = in_array('registro', $databases);
$resgistroExists = in_array('resgistro', $databases);

if ($registroExists && $resgistroExists) {
    showStep("⚠️ Se encontraron ambas bases de datos: 'registro' y 'resgistro'", "warning");
    showStep("💡 Esto puede causar confusión. Se recomienda usar solo 'registro'", "warning");
    
    // Comparar contenido
    $mysqlConn->select_db("registro");
    $tablesRegistro = $mysqlConn->query("SHOW TABLES");
    $tableCountRegistro = $tablesRegistro->num_rows;
    
    $mysqlConn->select_db("resgistro");
    $tablesResgistro = $mysqlConn->query("SHOW TABLES");
    $tableCountResgistro = $tablesResgistro->num_rows;
    
    echo "<div class='step'>
        <h3>📊 Comparación de bases de datos:</h3>
        <p><strong>Base de datos 'registro':</strong> $tableCountRegistro tablas</p>
        <p><strong>Base de datos 'resgistro':</strong> $tableCountResgistro tablas</p>
    </div>";
    
    if ($tableCountRegistro > $tableCountResgistro) {
        showStep("✅ La base de datos 'registro' tiene más tablas. Se recomienda usar esta.", "success");
    } elseif ($tableCountResgistro > $tableCountRegistro) {
        showStep("⚠️ La base de datos 'resgistro' tiene más tablas. Considera migrar los datos.", "warning");
    } else {
        showStep("ℹ️ Ambas bases de datos tienen la misma cantidad de tablas.", "info");
    }
    
} elseif ($registroExists) {
    showStep("✅ Base de datos 'registro' existe y es la única", "success");
} elseif ($resgistroExists) {
    showStep("⚠️ Solo existe la base de datos 'resgistro' (con 'g')", "warning");
    showStep("💡 Se recomienda renombrarla a 'registro' para consistencia", "warning");
    
    // Opción para renombrar
    if (isset($_POST['rename'])) {
        if ($mysqlConn->query("RENAME DATABASE resgistro TO registro")) {
            showStep("✅ Base de datos renombrada exitosamente de 'resgistro' a 'registro'", "success");
            $registroExists = true;
            $resgistroExists = false;
        } else {
            showStep("❌ Error renombrando la base de datos: " . $mysqlConn->error, "error");
        }
    } else {
        echo "<div class='step warning'>
            <h3>🔄 Renombrar Base de Datos</h3>
            <p>¿Quieres renombrar 'resgistro' a 'registro'?</p>
            <form method='POST'>
                <button type='submit' name='rename' class='btn btn-warning'>🔄 Renombrar a 'registro'</button>
            </form>
        </div>";
    }
    
} else {
    showStep("❌ No se encontró ninguna base de datos 'registro' o 'resgistro'", "error");
    showStep("💡 Necesitas crear la base de datos 'registro'", "warning");
}

echo "<h3>🔧 Paso 2: Verificando configuración en archivos</h3>";

// Verificar connect.php
if (file_exists("connect.php")) {
    $content = file_get_contents("connect.php");
    preg_match('/\$db\s*=\s*["\']([^"\']+)["\']/', $content, $match);
    $configuredDB = $match[1] ?? 'No encontrado';
    
    if ($configuredDB === 'registro') {
        showStep("✅ connect.php está configurado correctamente para 'registro'", "success");
    } else {
        showStep("⚠️ connect.php está configurado para '$configuredDB' en lugar de 'registro'", "warning");
    }
} else {
    showStep("❌ Archivo connect.php no encontrado", "error");
}

echo "<h3>🔗 Paso 3: Probando conexión final</h3>";

try {
    // Probar conexión con 'registro'
    $testConn = new mysqli("localhost", "root", "", "registro");
    if (!$testConn->connect_error) {
        showStep("✅ Conexión exitosa a la base de datos 'registro'", "success");
        
        // Verificar tablas
        $tables = $testConn->query("SHOW TABLES");
        $tableCount = $tables->num_rows;
        showStep("✅ Base de datos 'registro' contiene $tableCount tablas", "success");
        
        // Verificar usuarios
        $result = $testConn->query("SELECT COUNT(*) as total FROM users");
        if ($result) {
            $userCount = $result->fetch_assoc()['total'];
            showStep("✅ Tabla 'users' contiene $userCount usuarios", "success");
        }
        
        $testConn->close();
        
    } else {
        showStep("❌ Error conectando a 'registro': " . $testConn->connect_error, "error");
    }
    
} catch (Exception $e) {
    showStep("❌ Excepción: " . $e->getMessage(), "error");
}

echo "<h3>📊 Paso 4: Resumen</h3>";

if ($registroExists && !$resgistroExists) {
    showStep("🎉 ¡Todo está correcto! La base de datos 'registro' existe y es la única.", "success");
} elseif ($registroExists && $resgistroExists) {
    showStep("⚠️ Existen ambas bases de datos. Considera eliminar una para evitar confusión.", "warning");
} elseif ($resgistroExists && !$registroExists) {
    showStep("⚠️ Solo existe 'resgistro'. Usa el botón de arriba para renombrarla.", "warning");
} else {
    showStep("❌ No existe ninguna base de datos. Crea 'registro' en phpMyAdmin.", "error");
}

// Acciones disponibles
echo "<div class='actions'>
    <h3>🔧 Acciones Disponibles</h3>
    <a href='fix_connection.php' class='btn'>🔧 Solución Automática</a>
    <a href='check_database_config.php' class='btn btn-warning'>🔍 Verificar Configuración</a>
    <a href='index.php' class='btn'>🏠 Página Principal</a>
    <a href='http://localhost/phpmyadmin' class='btn btn-danger' target='_blank'>🗄️ phpMyAdmin</a>
</div>";

$mysqlConn->close();

echo "</div>
<script>
    // Auto-refresh después de 5 segundos si se renombró la base de datos
    setTimeout(function() {
        location.reload();
    }, 5000);
</script>
</body>
</html>";
?> 