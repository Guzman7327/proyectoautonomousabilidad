<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

include("connect.php");
$result = $conn->query("SELECT id, firstName, lastName, email FROM users");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {


      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f5f5f5;
      padding: 20px;
    }

    h2, h3 {
      text-align: center;
      color: #333;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background: #00796b;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    input[type="text"],
    input[type="email"] {
      padding: 8px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[type="submit"] {
      padding: 8px 14px;
      background-color: #00796b;
      border: none;
      border-radius: 4px;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #004d40;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        text-align: center;
      }

      .logo {
        width: 90px;
        height: auto;
        object-fit: contain;
      }

    @media (max-width: 700px) {
      table, tbody, tr, td, th {
        display: block;
        width: 100%;
      }

      tr {
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        background: #fafafa;
      }

      th {
        text-align: left;
        padding: 10px 0;
        background: none;
        color: #00796b;
      }

      td {
        padding: 5px 0;
        text-align: left;
      }

      input[type="submit"] {
        width: 100%;
      }

      
      .title-group h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #333;
      }

      .subtitle {
        font-size: 1rem;
        margin-top: 5px;
        color: #00796b;
        font-weight: 500;
      }

    }
  </style>
</head>
<body>
    <div class="header">
      <img src="img/logo.png" alt="Logo" class="logo">
      <div class="title-group">
        <h1>PORTAL TURÍSTICO E.C</h1>
        <h2>Bienvenido, <strong><?= htmlspecialchars($_SESSION['admin']) ?></strong> 👋</h2>
        <a href="logout.php" style="float: right; margin: 10px; color: #00796b; font-weight: bold;">Cerrar sesión ✖️</a>
      </div>
    </div>



    
       
  <div class="container">
      <h3>Usuarios Registrados</h3>

    <table>
      <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Correo</th>
        <th>Acción</th>
      </tr>

      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="POST" action="editar_usuario.php">
          <td>
            <input type="text" name="firstName" value="<?= htmlspecialchars($row['firstName']) ?>">
          </td>
          <td>
            <input type="text" name="lastName" value="<?= htmlspecialchars($row['lastName']) ?>">
          </td>
          <td>
            <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>">
          </td>
          <td>
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="submit" value="Guardar">
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
