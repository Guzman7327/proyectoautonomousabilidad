<?php
// Script de prueba de conexión a la base de datos
echo "<h2>Prueba de Conexión a Base de Datos</h2>";

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "registro";

echo "<h3>Configuración:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Usuario:</strong> $user</li>";
echo "<li><strong>Contraseña:</strong> " . (empty($pass) ? "Vacía" : "Configurada") . "</li>";
echo "<li><strong>Base de datos:</strong> $db</li>";
echo "</ul>";

// Paso 1: Probar conexión sin especificar base de datos
echo "<h3>Paso 1: Conectando a MySQL sin especificar base de datos...</h3>";
$conn_test = new mysqli($host, $user, $pass);

if ($conn_test->connect_error) {
    echo "<p style='color: red;'><strong>Error de conexión a MySQL:</strong> " . $conn_test->connect_error . "</p>";
    echo "<p><strong>Posibles soluciones:</strong></p>";
    echo "<ul>";
    echo "<li>Verifica que XAMPP esté ejecutándose</li>";
    echo "<li>Verifica que el servicio MySQL esté iniciado</li>";
    echo "<li>Verifica que el puerto 3306 esté disponible</li>";
    echo "</ul>";
} else {
    echo "<p style='color: green;'><strong>✓ Conexión a MySQL exitosa</strong></p>";
    
    // Paso 2: Verificar si la base de datos existe
    echo "<h3>Paso 2: Verificando si la base de datos '$db' existe...</h3>";
    $result = $conn_test->query("SHOW DATABASES LIKE '$db'");
    
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'><strong>✓ La base de datos '$db' existe</strong></p>";
        
        // Paso 3: Probar conexión a la base de datos específica
        echo "<h3>Paso 3: Conectando a la base de datos '$db'...</h3>";
        $conn_db = new mysqli($host, $user, $pass, $db);
        
        if ($conn_db->connect_error) {
            echo "<p style='color: red;'><strong>Error de conexión a la base de datos '$db':</strong> " . $conn_db->connect_error . "</p>";
        } else {
            echo "<p style='color: green;'><strong>✓ Conexión a la base de datos '$db' exitosa</strong></p>";
            
            // Paso 4: Verificar tablas
            echo "<h3>Paso 4: Verificando tablas...</h3>";
            $result = $conn_db->query("SHOW TABLES");
            
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'><strong>✓ Tablas encontradas:</strong></p>";
                echo "<ul>";
                while ($row = $result->fetch_array()) {
                    echo "<li>" . $row[0] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: orange;'><strong>⚠ La base de datos está vacía (sin tablas)</strong></p>";
                echo "<p>Necesitas importar el archivo registro.sql</p>";
            }
            
            $conn_db->close();
        }
    } else {
        echo "<p style='color: red;'><strong>✗ La base de datos '$db' NO existe</strong></p>";
        echo "<p><strong>Para crear la base de datos:</strong></p>";
        echo "<ol>";
        echo "<li>Abre phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Crea una nueva base de datos llamada 'registro'</li>";
        echo "<li>Importa el archivo registro.sql</li>";
        echo "</ol>";
    }
    
    $conn_test->close();
}

echo "<hr>";
echo "<h3>Información del sistema:</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>MySQL Extension:</strong> " . (extension_loaded('mysqli') ? 'Cargada' : 'No cargada') . "</li>";
echo "<li><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "</ul>";
?>
