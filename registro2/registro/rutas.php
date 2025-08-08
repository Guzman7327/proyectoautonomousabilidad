<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rutas Turísticas</title>
  
  <?php include 'header.php'; ?>
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #e0f2f1;
      color: #222;
    }

    header {
      background-color: #00796b;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .nav {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .nav a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 6px 10px;
      border-radius: 5px;
    }

    .nav a:hover,
    .nav a.active {
      background-color: #004d40;
    }

    main {
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #00796b;
    }

    .video-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .video-container video {
      width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav {
        flex-direction: column;
        gap: 8px;
      }

      main {
        margin: 20px 10px;
      }
    }
  </style>
</head>
<body>

<main>
  <h1>Rutas destacadas en Ecuador</h1>
  <div class="video-container">
    <video controls autoplay muted loop>
      <source src="vid/turismov.mp4" type="video/mp4">
      Tu navegador no soporta la etiqueta de video.
    </video>
  </div>
</main>

</main>
 <footer style="background-color: #2b2b2b; color: white; padding: 20px 0; text-align: center;">
  <p>© 2024 Portal Turístico Ecuador. Todos los derechos reservados.</p>
  <div style="margin-top: 10px;">
    <a href="#" style="color: gold; margin: 0 10px;">Política de Privacidad</a> |
    <a href="#" style="color: gold; margin: 0 10px;">Términos de Uso</a> |
    <a href="#" style="color: gold; margin: 0 10px;">Guardar Destino</a> |
    <a href="#" style="color: gold; margin: 0 10px;">Editar Destino</a> |
    <a href="#" style="color: gold; margin: 0 10px;">Búsqueda Avanzada</a> |
    <a href="#" style="color: gold; margin: 0 10px;">Eliminar Destino</a> |
    <a href="#" style="color: gold; margin: 0 10px;">Contacto</a>
  </div>

</body>
</html>
