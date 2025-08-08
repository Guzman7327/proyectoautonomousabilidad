<?php
echo "<h2>Prueba Simple de Conexión</h2>";

// Configuración básica
$host = "localhost";
$user = "root";
$pass = "";

echo "<h3>Paso 1: Conectar a MySQL</h3>";

try {
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    echo "<p style='color: green;'>✓ Conexión a MySQL exitosa</p>";
    echo "<p><strong>Versión de MySQL:</strong> " . $conn->server_info . "</p>";
    
    // Verificar bases de datos existentes
    echo "<h3>Paso 2: Bases de datos existentes</h3>";
    $result = $conn->query("SHOW DATABASES");
    
    if ($result) {
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            $db_name = $row[0];
            $is_selected = ($db_name === 'registro') ? " <strong>(NUESTRA BD)</strong>" : "";
            echo "<li>$db_name$is_selected</li>";
        }
        echo "</ul>";
    }
    
    // Verificar si existe la base de datos registro
    echo "<h3>Paso 3: Verificar base de datos 'registro'</h3>";
    $result = $conn->query("SHOW DATABASES LIKE 'registro'");
    
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ La base de datos 'registro' existe</p>";
        
        // Intentar conectar a la base de datos específica
        echo "<h3>Paso 4: Conectar a la base de datos 'registro'</h3>";
        $conn_db = new mysqli($host, $user, $pass, 'registro');
        
        if ($conn_db->connect_error) {
            echo "<p style='color: red;'>✗ Error conectando a 'registro': " . $conn_db->connect_error . "</p>";
        } else {
            echo "<p style='color: green;'>✓ Conexión a 'registro' exitosa</p>";
            
            // Verificar tablas
            $result = $conn_db->query("SHOW TABLES");
            if ($result && $result->num_rows > 0) {
                echo "<p style='color: green;'>✓ Tablas encontradas: " . $result->num_rows . "</p>";
                echo "<ul>";
                while ($row = $result->fetch_array()) {
                    echo "<li>" . $row[0] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: orange;'>⚠ La base de datos está vacía</p>";
            }
            
            $conn_db->close();
        }
    } else {
        echo "<p style='color: red;'>✗ La base de datos 'registro' NO existe</p>";
        echo "<p><strong>Solución:</strong> Necesitas crear la base de datos primero</p>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<h3>Posibles soluciones:</h3>";
    echo "<ul>";
    echo "<li>Verifica que XAMPP esté ejecutándose</li>";
    echo "<li>Verifica que MySQL esté iniciado en XAMPP</li>";
    echo "<li>Verifica que el puerto 3306 esté disponible</li>";
    echo "<li>Reinicia XAMPP completamente</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>Información del sistema:</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>MySQL Extension:</strong> " . (extension_loaded('mysqli') ? 'Cargada' : 'No cargada') . "</li>";
echo "<li><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "</ul>";

echo "<h3>Próximos pasos:</h3>";
echo "<ul>";
echo "<li><a href='setup_database.php'>Configurar base de datos</a></li>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>Abrir phpMyAdmin</a></li>";
echo "</ul>";
?>
