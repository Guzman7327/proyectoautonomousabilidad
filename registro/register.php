<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER["CONTENT_TYPE"], 'application/json') !== false) {
    include("connect.php");
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["fName"], $data["lName"], $data["reg_email"], $data["reg_password"])) {
        $fName = $data["fName"];
        $lName = $data["lName"];
        $email = $data["reg_email"];
        $password = password_hash($data["reg_password"], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fName, $lName, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "✅ Usuario registrado con éxito"]);
        } else {
            echo json_encode(["mensaje" => "❌ Error al registrar: " . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo json_encode(["mensaje" => "❌ Faltan datos"]);
        exit;
    }
}
?>
