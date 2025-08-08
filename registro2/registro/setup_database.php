<?php
// Script para configurar automáticamente la base de datos
echo "<h2>Configuración Automática de Base de Datos</h2>";

// Configuración
$host = "localhost";
$user = "root";
$pass = "";
$db = "registro";
$sql_file = "registro.sql";

echo "<h3>Iniciando configuración...</h3>";

// Paso 1: Conectar a MySQL
echo "<p>Conectando a MySQL...</p>";
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión a MySQL: " . $conn->connect_error . "</p>");
}

echo "<p style='color: green;'>✓ Conexión a MySQL exitosa</p>";

// Paso 2: Crear base de datos si no existe
echo "<p>Creando base de datos '$db'...</p>";
$sql = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Base de datos '$db' creada/verificada</p>";
} else {
    die("<p style='color: red;'>Error creando base de datos: " . $conn->error . "</p>");
}

// Paso 3: Seleccionar la base de datos
$conn->select_db($db);
echo "<p style='color: green;'>✓ Base de datos seleccionada</p>";

// Paso 4: Verificar si el archivo SQL existe
if (!file_exists($sql_file)) {
    die("<p style='color: red;'>Error: El archivo '$sql_file' no existe</p>");
}

echo "<p>Archivo SQL encontrado: $sql_file</p>";

// Paso 5: Leer y ejecutar el archivo SQL con manejo de delimitadores
echo "<p>Importando estructura de la base de datos...</p>";

$sql_content = file_get_contents($sql_file);

// Procesar el contenido SQL con manejo de delimitadores
$queries = [];
$current_query = '';
$delimiter = ';';
$in_procedure = false;

$lines = explode("\n", $sql_content);

foreach ($lines as $line) {
    $line = trim($line);
    
    // Ignorar líneas vacías y comentarios
    if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
        continue;
    }
    
    // Detectar cambio de delimitador
    if (preg_match('/^DELIMITER\s+(.+)$/i', $line, $matches)) {
        if (!empty($current_query)) {
            $queries[] = $current_query;
        }
        $delimiter = $matches[1];
        $current_query = '';
        continue;
    }
    
    // Agregar línea al query actual
    $current_query .= $line . "\n";
    
    // Verificar si el query termina con el delimitador actual
    if (substr($line, -strlen($delimiter)) === $delimiter) {
        $current_query = rtrim($current_query, "\n");
        $current_query = rtrim($current_query, $delimiter);
        if (!empty(trim($current_query))) {
            $queries[] = trim($current_query);
        }
        $current_query = '';
    }
}

// Agregar el último query si existe
if (!empty(trim($current_query))) {
    $queries[] = trim($current_query);
}

$success_count = 0;
$error_count = 0;

foreach ($queries as $query) {
    $query = trim($query);
    
    // Ignorar queries vacíos
    if (empty($query)) {
        continue;
    }
    
    // Ejecutar la consulta
    if ($conn->query($query) === TRUE) {
        $success_count++;
    } else {
        $error_count++;
        echo "<p style='color: orange;'>⚠ Error en consulta: " . $conn->error . "</p>";
        echo "<p style='font-size: 12px; color: #666;'>Query: " . substr($query, 0, 100) . "...</p>";
    }
}

echo "<h3>Resultado de la importación:</h3>";
echo "<ul>";
echo "<li><strong>Consultas exitosas:</strong> $success_count</li>";
echo "<li><strong>Errores:</strong> $error_count</li>";
echo "</ul>";

// Paso 6: Verificar tablas creadas
echo "<h3>Tablas creadas:</h3>";
$result = $conn->query("SHOW TABLES");

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: orange;'>No se encontraron tablas</p>";
}

// Paso 7: Verificar usuario administrador
echo "<h3>Verificando usuario administrador:</h3>";
$result = $conn->query("SELECT id, firstName, lastName, email, role FROM users WHERE role = 'admin'");

if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Usuario administrador encontrado:</p>";
    while ($row = $result->fetch_assoc()) {
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $row['id'] . "</li>";
        echo "<li><strong>Nombre:</strong> " . $row['firstName'] . " " . $row['lastName'] . "</li>";
        echo "<li><strong>Email:</strong> " . $row['email'] . "</li>";
        echo "<li><strong>Rol:</strong> " . $row['role'] . "</li>";
        echo "</ul>";
    }
} else {
    echo "<p style='color: orange;'>⚠ No se encontró usuario administrador</p>";
}

$conn->close();

echo "<hr>";
echo "<h3>Configuración completada</h3>";
echo "<p>Ahora puedes:</p>";
echo "<ul>";
echo "<li><a href='index.php'>Ir al inicio del proyecto</a></li>";
echo "<li><a href='test_connection.php'>Probar la conexión</a></li>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>Ver en phpMyAdmin</a></li>";
echo "</ul>";

echo "<h3>Credenciales del administrador:</h3>";
echo "<ul>";
echo "<li><strong>Email:</strong> admin@admin.com</li>";
echo "<li><strong>Contraseña:</strong> password</li>";
echo "</ul>";
?>
