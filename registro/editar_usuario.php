<?php
require_once "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET firstName = ?, lastName = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $id);

    if ($stmt->execute()) {
        header("Location: admin.php"); // volver al panel
    } else {
        echo "❌ Error al actualizar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
