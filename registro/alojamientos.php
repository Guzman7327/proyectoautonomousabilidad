<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Alojamientos en Ecuador</title>
  <link rel="stylesheet" href="styles.css">
  <?php include 'header.php'; ?>
  <style>
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background: #f1e5e5ff;
      color: #222;
      font-family: Arial, sans-serif;
      overflow-x: hidden;
    }


    .contenedor-alojamientos {
      max-width: 1200px;
      margin: auto;
      padding: 2rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }
    .alojamiento-card {
      background-color: #e0f2f1;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }
    .alojamiento-card:hover {
      transform: scale(1.02);
    }
    .alojamiento-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .alojamiento-info {
      padding: 1rem;
    }
    .alojamiento-info h3 {
      margin: 0;
      color: #00796b;
    }
    .alojamiento-info p {
      margin: 0.5rem 0;
      color: #333;
    }
    .precio {
      color: #d32f2f;
      font-weight: bold;
      margin-top: 0.5rem;
    }
    header, footer {
      width: 100%;
      display: block;
    }

  </style>
</head>
<body>
<main style="flex: 1;">
  <h2 style="text-align: center; margin-top: 2rem; color: #00796b;">Alojamientos Turísticos en Ecuador</h2>

  <div class="contenedor-alojamientos">

  <!-- Alojamiento 1 -->
  <div class="alojamiento-card">
    <img src="img/hostal_quito.jpg" alt="Hostal Colonial Quito">
    <div class="alojamiento-info">
      <h3>Hostal Colonial Quito</h3>
      <p><strong>Ciudad:</strong> Quito</p>
      <p>A pocos metros del centro histórico. Ambiente cálido y tradicional.</p>
      <p class="precio">$35 por noche</p>
    </div>
  </div>

  <!-- Alojamiento 2 -->
  <div class="alojamiento-card">
    <img src="img/resort_manta.jpg" alt="Resort Playa Blanca">
    <div class="alojamiento-info">
      <h3>Resort Playa Blanca</h3>
      <p><strong>Ciudad:</strong> Manta</p>
      <p>Resort frente al mar con piscinas, spa y desayuno incluido.</p>
      <p class="precio">$85 por noche</p>
    </div>
  </div>

  <!-- Alojamiento 3 -->
  <div class="alojamiento-card">
    <img src="img/cabana_tena.jpg" alt="Cabañas Amazónicas">
    <div class="alojamiento-info">
      <h3>Cabañas Amazónicas</h3>
      <p><strong>Ciudad:</strong> Tena</p>
      <p>Inmersas en la selva. Perfectas para eco-turismo y avistamiento de aves.</p>
      <p class="precio">$40 por noche</p>
    </div>
  </div>



</main>
</footer>
</body>
</html>
