

<?php
session_start();
include 'lang.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Turístico Ecuador</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  
 

  <style>

    .btn-cerrar-sesion {
      background-color: #d32f2f;
      color: white;
      padding: 8px 14px;
      font-size: 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s ease;
    }

    .btn-cerrar-sesion:hover {
      background-color: #b71c1c;
    }

    .nombre-usuario {
      font-weight: bold;
    }



    .flecha {
      display: inline-block;
      transition: transform 0.3s ease;
    }

    /* Cuando el menú está abierto */
    .flecha.abierta {
      transform: rotate(180deg);
    }


    @font-face {
      font-family: 'OpenDyslexic';
      src: url('https://cdn.jsdelivr.net/gh/antijingoist/open-dyslexic/otf/OpenDyslexic-Regular.otf') format('opentype');
    }

    .dyslexic-font,
    .dyslexic-font * {
      font-family: 'OpenDyslexic', Arial, sans-serif !important;
    }



    /* === RESALTADO DE FOCO === */
    :focus {
      outline: 3px solid #FFD700 !important; /* Amarillo dorado */
      outline-offset: 2px;
      border-radius: 4px;
    }

    /* === RESALTADO DE ENLACES === */
    a:hover,
    a:focus {
      background-color: #004d40 !important;
      color: #fff !important;
      padding: 2px 6px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }


     .espaciado-linea * {
      line-height: 2 !important;
    }
    .espaciado-palabra * {
      word-spacing: 0.5em !important;
    }
    .espaciado-caracter * {
      letter-spacing: 0.1em !important;
    }

    .monocromo, .monocromo *:not(.no-monocromo):not(.no-monocromo *) {
      filter: grayscale(100%) !important;
      color: black !important;
      background-color: white !important;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f1e5e5ff;   /* Fondo claro */
      color: #222;           /* Texto oscuro */
    }
    header {
      background-color: #00796b;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      flex-wrap: wrap;
    }
    .nav-left {
      display: flex;
      align-items: center;
    }
    .nav-left img {
      height: 40px;
      margin-right: 10px;
    }
    .nav-left span {
      font-weight: bold;
      font-size: 18px;
    }
    .nav-center {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .nav-center a {
      color: white;
      text-decoration: none;
      font-size: 15px;
      padding: 4px 10px;
    }
    .nav-center a:hover,
    .nav-center a.active {
      background-color: #004d40;
      border-radius: 4px;
    }
    .nav-right {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .nav-right button {
      padding: 6px 12px;
      font-size: 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-acceso {
      background-color: #d32f2f;
      color: white;
    }
    .btn-login {
      background-color: white;
      color: #004d40;
    }
    .dropdown {
      position: relative;
    }
    .menu-accesibilidad {
      position: absolute;
      top: 100%;
      left: 0;
      max-height: 400px;
      overflow-y: auto;
      background-color: #004d40;
      display: none;
      flex-direction: column;
      border-radius: 8px;
      z-index: 9999;
      width: 260px;
      scrollbar-width: thin;
      scrollbar-color: #888 #004d40;
      transition: all 0.3s ease-in-out;
    }

    /* Estilo del scroll para navegadores WebKit */
    .menu-accesibilidad::-webkit-scrollbar {
      width: 8px;
    }
    .menu-accesibilidad::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }
    .menu-accesibilidad::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    .menu-accesibilidad button {
      background-color: #004d40;
      color: white;
      border: none;
      padding: 16px 20px;
      font-size: 18px;
      line-height: 1.6;
      text-align: left;
      margin-bottom: 5px;
      display: flex;
      align-items: center;
      gap: 12px;
      white-space: normal;
      border-bottom: 1px solid #00695c;
    }

    /* === MEDIA QUERY PARA HACERLO RESPONSIVO === */
    @media (max-width: 600px) {
      .menu-accesibilidad {
        width: 100vw;
        left: 0;
        right: 0;
        max-height: 60vh;
        border-radius: 0;
        font-size: 16px;
        padding-top: 10px;
      }

      .menu-accesibilidad button {
        font-size: 16px;
        padding: 14px 16px;
      }

      .dropdown {
        width: 100%;
      }

      .btn-acceso {
        width: 100%;
        font-size: 16px;
      }
    }



   #lupa {
      display: none;
      position: absolute;
      width: 120px;
      height: 120px;
      border: 3px solid #00796b;
      border-radius: 50%;
      box-shadow: 0 0 8px rgba(0,0,0,0.4);
      pointer-events: none;
      z-index: 1000;
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
    }


    .zoom {
      transform: scale(1.1);
      transform-origin: top left;
    }
    .high-contrast,
    .high-contrast * {
      background-color: black !important;
      color: yellow !important;
      border-color: yellow !important;
    }
    .large-text,
    .large-text * {
      font-size: 1.25em !important;
    }
    .small-text,
    .small-text * {
      font-size: 0.8em !important;
    }
    .dyslexic-font,
    .dyslexic-font * {
      font-family: 'OpenDyslexic', Arial, sans-serif !important;
    }
    .carousel {
      max-width: 90%;
      margin: 20px auto;
    }
    .carousel img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 10px;
    }
    #map {
      height: 300px;              /* puedes ajustar entre 200 y 300 */
      width: 600px;               /* ancho fijo */
      max-width: 90%;             /* para que sea responsive */
      margin: 20px auto;
      border-radius: 10px;
    }
    .destinos {
      max-width: 90%;
      margin: auto;
      display: flex;
      justify-content: space-between;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }
    .destino {
      background: #ffffff;
      color: #222; /* 👈 Forzamos color de texto visible */
      padding: 10px;
      border-radius: 10px;
      width: 30%;
      min-width: 250px;
    }
    .destino img {
      width: 100%;
      border-radius: 10px;
    }
    .destino h4 {
      margin: 10px 0 5px;
      color: #33d1b2;
    }
    .destino p {
      font-size: 14px;
    }
    .destino button {
      margin-top: 10px;
      background: #00796b;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
    }
   #modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 9999;
    }

    .modal-content {
      background-color: #ffffff;
      color: #222;
      width: 100%;
      max-width: 400px;
      margin: 5% auto;
      padding: 30px 25px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      position: relative;
      animation: fadeIn 0.3s ease;
      background: linear-gradient(to bottom, #ffffff, #e0f2f1);
      border: 2px solid #00796b;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    

    @keyframes fadeIn {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .close-btn {
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 22px;
      font-weight: bold;
      cursor: pointer;
      color: #666;
    }

    #formToggle {
      display: flex;
      justify-content: space-between;
      background-color: #f1f1f1;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
    }

    #formToggle button {
      flex: 1;
      padding: 10px;
      background: none;
      border: none;
      font-weight: bold;
      color: #555;
      cursor: pointer;
      transition: background 0.3s;
    }

    #formToggle button.active {
      background-color: #00796b;
      color: white;
      background-color: #00796b;
      color: white;
      transition: all 0.3s ease-in-out;
    }

    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
      transition: border-color 0.3s;
    }

    .modal-content input[type="text"]:focus,
    .modal-content input[type="email"]:focus,
    .modal-content input[type="password"]:focus {
      border-color: #00796b;
      outline: none;
    }

    .modal-content input[type="submit"] {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color: #00796b;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .modal-content input[type="submit"]:hover {
      background-color: #005f56;
    }

    .password-container {
      position: relative;
    }

    .password-container input {
      padding-right: 40px;
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      cursor: pointer;
      color: #888;
    }


  </style>
</head>
 <?php include 'login_modal.php'; ?>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


<body>

<div id="contenido-textual">
<header>

  <div class="nav-left">
    <img src="img/logo.png" alt="Logo">
    <span><?= $t['titulo_portal'] ?></span>
  </div>
 <div class="nav-center">
     <a href="index1.php"><?= $t['inicio'] ?></a>
    <a href="rutas.php"><?= $t['rutas'] ?></a>
    <a href="alojamientos.php"><?= $t['alojamientos'] ?></a>
    <a href="acessiblilidad.php"><?= $t['accesibilidad'] ?></a>
    <a href="contacto.php"><?= $t['contacto'] ?></a>
    </div>
  <div class="nav-right">
    <div class="dropdown">
      <button class="btn-acceso" onclick="toggleMenu()" id="btnAccesibilidad">
        <i class="fas fa-universal-access"></i> Accesibilidad <span class="flecha">▼</span>
      </button>
     <div class="menu-accesibilidad no-monocromo" id="menuAccesibilidad">
        <button onclick="document.body.classList.toggle('zoom')">
          🔍 Zoom 
        </button>
        <button onclick="toggleLupa()">
          🎯 Lupa puntual 
        </button>
        <button onclick="document.body.classList.toggle('high-contrast')">
          🌗 Alto contraste 
        </button>
        <button onclick="document.body.classList.add('large-text'); document.body.classList.remove('small-text')">
          ➕ Aumentar texto 
        </button>
        <button onclick="document.body.classList.add('small-text'); document.body.classList.remove('large-text')">
          ➖ Reducir texto 
        </button>
        <button onclick="document.body.classList.toggle('dyslexic-font')">
          🧠 Fuente legible 
        </button>
        <button onclick="document.body.classList.toggle('espaciado-linea')">
          📏 Espaciado entre líneas 
        </button>
        <button onclick="document.body.classList.toggle('espaciado-palabra')">
          🧩 Espaciado entre palabras 
        </button>
        <button onclick="document.body.classList.toggle('espaciado-caracter')">
          🔡 Espaciado entre caracteres 
        </button>
        <button onclick="leerTexto()">
          🔊 Lectura de texto 
        </button>
        <button onclick="document.body.classList.toggle('monocromo')">
          ⚫⚪ Modo monocromático 
        </button>
        <div style="padding: 10px; background: #003b33; text-align: center; font-size: 14px;">
          🌐 Idioma:
          <a href="?lang=es" style="color: gold; margin: 0 5px;">ES</a> |
          <a href="?lang=en" style="color: gold; margin: 0 5px;">EN</a>
        </div>
        <button onclick="resetAccesibilidad()">
          ♻️ Restablecer 
        </button>
      </div>

    </div>
    <?php if (isset($_SESSION['nombre'])): ?>
      <form method="post" action="logout.php">
        <button type="submit" class="btn-cerrar-sesion" aria-label="Cerrar sesión de <?= htmlspecialchars($_SESSION['nombre']) ?>">
          <i class="fas fa-sign-out-alt"></i>
          <span class="nombre-usuario"><?= htmlspecialchars($_SESSION['nombre']) ?></span>
        </button>
      </form>

    <?php else: ?>
      <button class="btn-login" onclick="openModal()">Iniciar sesión</button>
    <?php endif; ?>

  </div>
</header>
  <div class="carousel">
    <img src="img/banner0.jpg" alt="Amazonía" id="carouselImage">
  </div>
  <h3 style="text-align:center"><?= $t['mapa_rutas'] ?></h3>
  <div id="map"></div>
  <h3 style="text-align:center; color: #33d1b2;"><?= $t['destinos_destacados'] ?></h3>
  <div class="destinos">
    <div class="destino">
      <img src="img/cuenca.jpg" alt="Cuenca">
      <h4>Cuenca</h4>
      <p>Ciudad patrimonial de la humanidad con arquitectura colonial y entorno tradicional.</p>
      <button>Ver más</button>
    </div>
    <div class="destino">
      <img src="img/salinas.jpg" alt="Salinas">
      <h4>Salinas</h4>
      <p>Hermosa playa del Pacífico con excelente infraestructura turística y accesibilidad.</p>
      <button>Ver más</button>
    </div>
    <div class="destino">
      <img src="img/manta.jpg" alt="Manta">
      <h4>Manta</h4>
      <p>Puerto pesquero con hermosas playas y deliciosa gastronomía marina.</p>
      <button>Ver más</button>
    </div>
  </div>
  <div id="lupa"></div>
  <script>
  function toggleMenu() {
    const menu = document.getElementById("menuAccesibilidad");
    const flecha = document.querySelector("#btnAccesibilidad .flecha");

    const abierto = menu.style.display === "flex";
    menu.style.display = abierto ? "none" : "flex";
    
    flecha.classList.toggle("abierta", !abierto);
  }

  function toggleLupa() {
      const lupa = document.getElementById("lupa");
      if (lupa.style.display === "none" || !lupa.style.display) {
        lupa.style.display = "block";
        document.addEventListener("mousemove", moverLupa);
      } else {
        lupa.style.display = "none";
        document.removeEventListener("mousemove", moverLupa);
      }
    }

    let canvasCache = null;

    function moverLupa(e) {
      const lupa = document.getElementById("lupa");
      const size = 120;
      const zoom = 2;

      lupa.style.left = `${e.pageX - size / 2}px`;
      lupa.style.top = `${e.pageY - size / 2}px`;

      if (!canvasCache) {
        html2canvas(document.body, {
          scale: 2,
          windowWidth: window.innerWidth,
          windowHeight: window.innerHeight,
          ignoreElements: el => el.id === "map"
        }).then(canvas => {
          canvasCache = canvas;
          actualizarLupa(canvas, e, size, zoom);
        });
      } else {
        actualizarLupa(canvasCache, e, size, zoom);
      }
    }

    function actualizarLupa(canvas, e, size, zoom) {
      const ctx = canvas.getContext("2d", { willReadFrequently: true });
      const x = Math.floor(e.pageX * 2); // por scale: 2
      const y = Math.floor(e.pageY * 2);
      const areaSize = Math.floor(size / zoom);

      const sx = Math.max(0, x - areaSize / 2);
      const sy = Math.max(0, y - areaSize / 2);

      // Limita para evitar fuera del canvas
      const safeWidth = Math.min(areaSize, canvas.width - sx);
      const safeHeight = Math.min(areaSize, canvas.height - sy);

      try {
        const imgData = ctx.getImageData(sx, sy, safeWidth, safeHeight);
        const tempCanvas = document.createElement("canvas");
        tempCanvas.width = size;
        tempCanvas.height = size;

        const tempCtx = tempCanvas.getContext("2d");
        tempCtx.putImageData(imgData, 0, 0);

        const lupa = document.getElementById("lupa");
        lupa.style.backgroundImage = `url(${tempCanvas.toDataURL()})`;
        lupa.style.backgroundPosition = "center";
        lupa.style.backgroundSize = "cover";

        // Detectar si está sobre el mapa
        const isOverMap = document.elementFromPoint(e.clientX, e.clientY)?.id === "map";
        lupa.style.display = isOverMap ? "none" : "block";
      } catch (err) {
        console.warn("Error al actualizar lupa:", err);
      }
    }

  function leerTexto() {
    const texto = document.querySelector("main")?.innerText || document.body.innerText;
    const msg = new SpeechSynthesisUtterance(texto);
    window.speechSynthesis.speak(msg);
  }
  function resetAccesibilidad() {
    document.body.classList.remove("zoom", "high-contrast", "large-text", "small-text", "dyslexic-font", "monocromo");
    document.getElementById("lupa").style.display = "none";
    document.removeEventListener("mousemove", moverLupa);
    window.speechSynthesis.cancel();
    canvasCache = null;
  }
  // Leaflet Map
  const map = L.map('map').setView([-1.8312, -78.1834], 6);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
  L.marker([-2.9, -79.0]).addTo(map).bindPopup("Cuenca");
  L.marker([-2.2, -80.97]).addTo(map).bindPopup("Salinas");
  L.marker([-0.9677, -80.7165]).addTo(map).bindPopup("Manta");
  </script>

  <script>
    function openModal() {
      const modal = document.getElementById("modal");
      if (modal) modal.style.display = "block";
    }

    function closeModal() {
      const modal = document.getElementById("modal");
      if (modal) modal.style.display = "none";
    }

    // También permite cerrar el modal al hacer clic fuera
    window.onclick = function(event) {
      const modal = document.getElementById("modal");
      if (event.target === modal) {
        modal.style.display = "none";
      }
    }
      </script>
      <script>
  document.getElementById("formRegister").addEventListener("submit", async function (e) {
    e.preventDefault(); // evita recargar la página

    const data = {
      fName: this.fName.value,
      lName: this.lName.value,
      reg_email: this.reg_email.value,
      reg_password: this.reg_password.value,
      signUp: true
    };

    try {
      const response = await fetch("register.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();
      alert(result.mensaje);
      if (response.ok && result.mensaje.includes("✅")) {
        this.reset();
        closeModal(); // ✅ cierra el modal automáticamente
      }

    } catch (error) {
      console.error("❌ Error en el registro:", error);
      alert("Error en la conexión.");
    }
  });
</script>
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


</footer>
</div>
</body>
</html>