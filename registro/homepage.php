<?php
session_start();
include("connect.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      text-align: center;
      padding: 10%;
    }
    h1 {
      font-size: 50px;
      color: #00796b;
    }
    p {
      font-size: 20px;
    }
    a {
      text-decoration: none;
      color: white;
      background: #00796b;
      padding: 10px 20px;
      border-radius: 5px;
      display: inline-block;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<?php
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        echo "<h1>¡Bienvenido, " . htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']) . "!</h1>";
    } else {
        echo "<h1>Bienvenido</h1><p>No se pudo cargar tu información.</p>";
    }
} else {
    echo "<h1>Acceso no autorizado</h1><p>Debes iniciar sesión.</p>";
}
?>

<a href="logout.php">Cerrar sesión</a>

</body>
</html>
