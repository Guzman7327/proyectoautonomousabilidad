<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso Administrador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #e0f2f1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      max-width: 360px;
      width: 100%;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 25px;
      color: #00796b;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #00796b;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #004d40;
    }

    @media (max-width: 400px) {
      .login-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Acceso administrador</h2>
    <form method="post" action="admin_login_handler.php">
      <input type="email" name="email" placeholder="Correo" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <input type="submit" value="Entrar">
    </form>
  </div>
</body>
</html>
