
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Contacto</title>
  <!-- Otros enlaces como CSS externo, meta, favicon, etc. -->
</head>
<body>

   <a href="index1.php" class="encabezado-logo">
    <img src="img/logo.png" alt="Ir al inicio del portal">
    <span>PORTAL TURÍSTICO EC</span>
   </a>

  <!-- Aquí puedes pegarlo completo -->
  <!-- 👇 Empieza -->
   <section id="contacto" style="padding: 40px 20px; margin: auto;">
  <div class="contacto-container">
    <h2 tabindex="0">Formulario de contacto</h2>
    <p>Por favor, completa el siguiente formulario para enviar tus comentarios o sugerencias.</p>



    <form id="formContacto" aria-label="Formulario de contacto">
      <div class="form-group">
        <label for="nombre">Nombre <span style="color:red">*</span></label>
        <input type="text" id="nombre" name="nombre" required aria-required="true" aria-describedby="nombreHelp">
        <div id="nombreHelp" class="sr-only">Campo obligatorio</div>
      </div>

      <div class="form-group">
        <label for="asunto">Asunto <span style="color:red">*</span></label>
        <input type="text" id="asunto" name="asunto" required aria-required="true" aria-describedby="asuntoHelp">
        <div id="asuntoHelp" class="sr-only">Campo obligatorio</div>
      </div>

      <div class="form-group">
        <label for="mensaje">Mensaje <span style="color:red">*</span></label>
        <textarea id="mensaje" name="mensaje" rows="5" required aria-required="true" aria-describedby="mensajeHelp"></textarea>
        <div id="mensajeHelp" class="sr-only">Campo obligatorio</div>
      </div>

      <button type="submit">Enviar comentario</button>
      <div id="respuesta" role="alert" aria-live="polite"></div>
    </form>
  </div>
</section>



<style>


 .encabezado-logo {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: white;
    padding: 8px 12px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    z-index: 999;
    }

    .encabezado-logo img {
    height: 40px;
    width: auto;
    }

    .encabezado-logo span {
    font-weight: bold;
    color: #00796b;
    font-family: Arial, sans-serif;
    font-size: 16px;
    }

 .logo-derecha {
     position: absolute;
    top: 20px;
    right: 20px;
     z-index: 1000;
 }

 .logo-derecha img {
    height: 100px;
    width: auto;
    }


 body {
    background-image: url('img/fondo1.jpg');
     background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
        min-height: 100vh;
   }

  .contacto-container {
    max-width: 700px;
    background: #fff;
    padding: 30px;
    margin: auto;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
    color: #222;
  }

  .contacto-container h2 {
    text-align: center;
    color: #00796b;
    margin-bottom: 15px;
  }

  .contacto-container p {
    text-align: center;
    margin-bottom: 30px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
  }

  .form-group input:focus,
  .form-group textarea:focus {
    outline: none;
    border-color: #33d1b2;
    box-shadow: 0 0 0 2px rgba(51, 209, 178, 0.2);
  }

  button[type="submit"] {
    background-color: #00796b;
    color: white;
    border: none;
    padding: 14px 20px;
    font-size: 16px;
    border-radius: 10px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
  }

  button[type="submit"]:hover {
    background-color: #005f54;
  }

  #respuesta {
    margin-top: 15px;
    font-weight: bold;
    text-align: center;
  }

  /* Responsive para pantallas pequeñas */
  @media screen and (max-width: 480px) {
    .contacto-container {
      padding: 20px 15px;
    }

    button[type="submit"] {
      font-size: 15px;
      padding: 12px;
    }
  }

  .sr-only {
    position: absolute;
    left: -9999px;
    top: auto;
    width: 1px;
    height: 1px;
    overflow: hidden;
  }
  #btnVolverArriba {
  display: none;
  position: fixed;
  bottom: 30px;
  right: 30px;
  z-index: 999;
  background-color: #00796b;
  color: white;
  border: none;
  padding: 12px 16px;
  font-size: 20px;
  border-radius: 50%;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  transition: background-color 0.3s ease;
}
#btnVolverArriba:hover {
  background-color: #005f54;
}

</style>
  
    ...
  </section>
  <style>
    ...
  </style>
  <!-- 👆 Termina -->

  <button id="btnVolverArriba" title="Volver al inicio" aria-label="Volver al inicio">
  ⬆️

</button>
<script>
  // Mostrar el botón al hacer scroll
  window.onscroll = function () {
    const boton = document.getElementById("btnVolverArriba");
    boton.style.display = window.scrollY > 200 ? "block" : "none";
  };

  document.getElementById("btnVolverArriba").onclick = function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // Enviar formulario y redirigir
  document.getElementById("formContacto").addEventListener("submit", function (e) {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const asunto = document.getElementById("asunto").value.trim();
    const mensaje = document.getElementById("mensaje").value.trim();
    const respuesta = document.getElementById("respuesta");

    if (!nombre || !asunto || !mensaje) {
      respuesta.textContent = "Por favor, completa todos los campos obligatorios.";
      respuesta.style.color = "red";
      return;
    }

    // Aquí puedes agregar fetch() si deseas enviar a un backend...
    // Simulamos éxito:
    respuesta.textContent = "¡Comentario enviado con éxito!";
    respuesta.style.color = "green";

    // Redirigir después de 2 segundos
    setTimeout(() => {
      window.location.href = "index1.php";
    }, 2000);
  });
</script>


</body>
</html>





