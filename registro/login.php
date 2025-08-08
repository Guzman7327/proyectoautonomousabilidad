<?php
session_start();
require_once "connect.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['login_email'] ?? '';
    $password = $data['login_password'] ?? '';

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['nombre'] = $user['firstName'];
            $_SESSION['role'] = $user['role'];

            $redirect = ($user['role'] === 'admin') ? "admin.php" : "index1.php";
            echo json_encode(["ok" => true, "redirect" => $redirect]);
        } else {
            echo json_encode(["ok" => false, "mensaje" => "❌ Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["ok" => false, "mensaje" => "❌ Usuario no encontrado"]);
    }

    $stmt->close();
    $conn->close();
}
?>
