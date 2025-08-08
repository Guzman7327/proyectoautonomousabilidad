<div id="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">×</span>

    <div id="formToggle">
      <button id="toggleLogin" class="active" onclick="showLogin()">Iniciar sesión</button>
      <button id="toggleRegister" onclick="showRegister()">Registrarse</button>
    </div>

    <!-- Formulario de Login -->
     <p style="text-align: center; font-size: 14px;">
      ¿No tienes una cuenta? 
      <a href="#" onclick="showRegister(); return false;" style="color: #00796b; font-weight: bold;">¡Regístrate aquí!</a>
    </p>
    <form id="formLogin" method="POST">
      <div id="loginMensaje" style="color: red; margin-bottom: 10px;"></div>
      <input type="email" name="login_email" placeholder="Correo" required>
      <div class="password-container">
        <input type="password" name="login_password" id="loginPassword" placeholder="Contraseña" required>
        <span class="toggle-password" onclick="togglePassword('loginPassword', this)">👁️</span>
      </div>
      <input type="submit" name="signIn" value="Iniciar sesión">
    </form>

    <!-- Formulario de Registro -->
    <form id="formRegister" style="display:none;">
      <input type="text" name="fName" placeholder="Nombre" required>
      <input type="text" name="lName" placeholder="Apellido" required>
      <input type="email" name="reg_email" placeholder="Correo" required>
      <div class="password-container">
        <input type="password" name="reg_password" id="regPassword" placeholder="Contraseña" required>
        <span class="toggle-password" onclick="togglePassword('regPassword', this)">👁️</span>
      </div>
      <input type="submit" name="signUp" value="Registrarse">
    </form>
  </div>
</div>

<style>
  #formToggle {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
  }
  #formToggle button {
    flex: 1;
    padding: 10px;
    border: none;
    background-color: #eee;
    cursor: pointer;
  }
  #formToggle button.active {
    background-color: #00796b;
    color: white;
  }
  .password-container {
    position: relative;
  }
  .password-container input {
    width: 100%;
    padding-right: 30px;
  }
  .toggle-password {
    position: absolute;
    right: 10px;
    top: 9px;
    cursor: pointer;
    font-size: 18px;
  }
</style>

<script>
  function showLogin() {
    document.getElementById("formLogin").style.display = "block";
    document.getElementById("formRegister").style.display = "none";
    document.getElementById("toggleLogin").classList.add("active");
    document.getElementById("toggleRegister").classList.remove("active");
  }

  function showRegister() {
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("formRegister").style.display = "block";
    document.getElementById("toggleRegister").classList.add("active");
    document.getElementById("toggleLogin").classList.remove("active");
  }

  function togglePassword(inputId, toggleSpan) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
      input.type = "text";
      toggleSpan.textContent = "🙈";
    } else {
      input.type = "password";
      toggleSpan.textContent = "👁️";
    }
  }

  // Cierra modal si se hace clic fuera del contenido
  window.onclick = function(event) {
    const modal = document.getElementById("modal");
    if (event.target === modal) {
      closeModal();
    }
  }

  function closeModal() {
    document.getElementById("modal").style.display = "none";
  }

  function openModal() {
    document.getElementById("modal").style.display = "block";
    showLogin(); // Por defecto, muestra login
  }

  // Manejo del formulario de login con fetch (Paso 2)
  document.getElementById("formLogin").addEventListener("submit", async function (e) {
    e.preventDefault(); // Previene recarga

    const data = {
      login_email: this.login_email.value,
      login_password: this.login_password.value
    };

    try {
      const response = await fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await response.json();
      const mensaje = document.getElementById("loginMensaje");

      if (result.ok) {
        mensaje.innerText = "";
        closeModal(); // Cierra el modal
        window.location.href = result.redirect; // Redirige
      } else {
        mensaje.innerText = result.mensaje;
      }

    } catch (error) {
      console.error("Error:", error);
      document.getElementById("loginMensaje").innerText = "❌ Error de conexión.";
    }
  });

</script>
