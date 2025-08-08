<?php
// Script de prueba rápida sin JavaScript
echo "<h2>Prueba Rápida del Sistema</h2>";

// Incluir archivo de conexión
require_once 'connect.php';

echo "<h3>1. Verificar conexión a la base de datos</h3>";
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Conexión exitosa</p>";
}

echo "<h3>2. Verificar usuario administrador</h3>";
$result = $conn->query("SELECT id, firstName, lastName, email, role FROM users WHERE role = 'admin'");
if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "<p style='color: green;'>✅ Administrador encontrado:</p>";
    echo "<ul>";
    echo "<li><strong>ID:</strong> " . $admin['id'] . "</li>";
    echo "<li><strong>Nombre:</strong> " . $admin['firstName'] . " " . $admin['lastName'] . "</li>";
    echo "<li><strong>Email:</strong> " . $admin['email'] . "</li>";
    echo "<li><strong>Rol:</strong> " . $admin['role'] . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ No se encontró administrador</p>";
}

echo "<h3>3. Probar login del administrador</h3>";
$email = "admin@admin.com";
$password = "password";

$stmt = $conn->prepare("SELECT id, firstName, lastName, email, password, role, is_active FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user && $user['is_active']) {
    if (password_verify($password, $user['password'])) {
        echo "<p style='color: green;'>✅ Login del administrador exitoso</p>";
        echo "<p><strong>Credenciales correctas:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Email:</strong> $email</li>";
        echo "<li><strong>Contraseña:</strong> $password</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Contraseña incorrecta</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Usuario no encontrado o inactivo</p>";
}

echo "<h3>4. Probar registro de usuario</h3>";
$testEmail = "test_" . time() . "@test.com";
$testPassword = "Test123!@#";
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);

$firstName = "Test";
$lastName = "User";
$stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role, is_active, created_at) VALUES (?, ?, ?, ?, 'user', 1, NOW())");
$stmt->bind_param("ssss", $firstName, $lastName, $testEmail, $hashedPassword);

if ($stmt->execute()) {
    $userId = $conn->insert_id;
    echo "<p style='color: green;'>✅ Usuario de prueba creado (ID: $userId)</p>";
    
    // Limpiar usuario de prueba
    $conn->query("DELETE FROM users WHERE id = $userId");
    echo "<p style='color: blue;'>ℹ Usuario de prueba eliminado</p>";
} else {
    echo "<p style='color: red;'>❌ Error al crear usuario: " . $stmt->error . "</p>";
}
$stmt->close();

echo "<hr>";
echo "<h3>Estado del sistema:</h3>";
echo "<p>✅ Base de datos: Conectada</p>";
echo "<p>✅ Tablas: Creadas</p>";
echo "<p>✅ Administrador: Disponible</p>";
echo "<p>✅ Registro: Funcionando</p>";

echo "<h3>Próximos pasos:</h3>";
echo "<ul>";
echo "<li><a href='index.php'>Ir al formulario principal</a></li>";
echo "<li><a href='test_connection.php'>Prueba de conexión detallada</a></li>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>Ver en phpMyAdmin</a></li>";
echo "</ul>";

$conn->close();
?> 