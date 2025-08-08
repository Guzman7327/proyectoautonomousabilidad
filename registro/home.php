<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Acceso no autorizado'); location.href='index.html';</script>";
    exit;
}

$email = $_SESSION['email'];
$query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido</title>
</head>
<body>
  <h1>Bienvenido, <?php echo $user['firstName'] . ' ' . $user['lastName']; ?> 👋</h1>
  <p>Email: <?php echo $user['email']; ?></p>
  <a href="logout.php">Cerrar sesión</a>
</body>
</html>
