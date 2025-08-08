<?php
// Script de prueba para el registro de usuarios
echo "<h2>Prueba de Registro de Usuarios</h2>";

// Incluir archivo de conexión
require_once 'connect.php';

echo "<h3>Paso 1: Verificar conexión</h3>";
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}
echo "<p style='color: green;'>✓ Conexión exitosa</p>";

echo "<h3>Paso 2: Verificar tabla users</h3>";
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Tabla 'users' existe</p>";
} else {
    die("<p style='color: red;'>✗ Tabla 'users' no existe</p>");
}

echo "<h3>Paso 3: Verificar estructura de la tabla users</h3>";
$result = $conn->query("DESCRIBE users");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h3>Paso 4: Verificar usuarios existentes</h3>";
$result = $conn->query("SELECT COUNT(*) as total FROM users");
if ($result) {
    $total = $result->fetch_assoc()['total'];
    echo "<p>Total de usuarios en la base de datos: <strong>$total</strong></p>";
    
    if ($total > 0) {
        echo "<h4>Usuarios existentes:</h4>";
        $result = $conn->query("SELECT id, firstName, lastName, email, role, created_at FROM users ORDER BY id DESC LIMIT 5");
        if ($result) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th><th>Fecha</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['firstName'] . "</td>";
                echo "<td>" . $row['lastName'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}

echo "<h3>Paso 5: Probar inserción de usuario de prueba</h3>";

// Datos de prueba
$testData = [
    'firstName' => 'Usuario',
    'lastName' => 'Prueba',
    'email' => 'test_' . time() . '@test.com',
    'password' => 'Test123!@#'
];

echo "<p>Intentando insertar usuario de prueba:</p>";
echo "<ul>";
echo "<li><strong>Nombre:</strong> " . $testData['firstName'] . "</li>";
echo "<li><strong>Apellido:</strong> " . $testData['lastName'] . "</li>";
echo "<li><strong>Email:</strong> " . $testData['email'] . "</li>";
echo "<li><strong>Contraseña:</strong> " . $testData['password'] . "</li>";
echo "</ul>";

try {
    // Hash de la contraseña
    $hashedPassword = password_hash($testData['password'], PASSWORD_DEFAULT);
    
    // Preparar consulta de inserción
    $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role, is_active, created_at) VALUES (?, ?, ?, ?, 'user', 1, NOW())");
    $stmt->bind_param("ssss", $testData['firstName'], $testData['lastName'], $testData['email'], $hashedPassword);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        $userId = $conn->insert_id;
        echo "<p style='color: green;'>✓ Usuario de prueba insertado correctamente (ID: $userId)</p>";
        
        // Verificar que se insertó correctamente
        $result = $conn->query("SELECT * FROM users WHERE id = $userId");
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo "<p style='color: green;'>✓ Usuario verificado en la base de datos</p>";
            echo "<ul>";
            echo "<li><strong>ID:</strong> " . $user['id'] . "</li>";
            echo "<li><strong>Nombre:</strong> " . $user['firstName'] . "</li>";
            echo "<li><strong>Apellido:</strong> " . $user['lastName'] . "</li>";
            echo "<li><strong>Email:</strong> " . $user['email'] . "</li>";
            echo "<li><strong>Rol:</strong> " . $user['role'] . "</li>";
            echo "<li><strong>Activo:</strong> " . ($user['is_active'] ? 'Sí' : 'No') . "</li>";
            echo "</ul>";
        }
        
        // Limpiar usuario de prueba
        $conn->query("DELETE FROM users WHERE id = $userId");
        echo "<p style='color: blue;'>ℹ Usuario de prueba eliminado</p>";
        
    } else {
        echo "<p style='color: red;'>✗ Error al insertar usuario: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Excepción: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Resultado de la prueba</h3>";
echo "<p>Si todos los pasos anteriores muestran ✓, entonces el registro debería funcionar correctamente.</p>";

echo "<h3>Próximos pasos:</h3>";
echo "<ul>";
echo "<li><a href='index.php'>Ir al formulario de registro</a></li>";
echo "<li><a href='test_connection.php'>Probar conexión general</a></li>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>Ver en phpMyAdmin</a></li>";
echo "</ul>";

$conn->close();
?>
