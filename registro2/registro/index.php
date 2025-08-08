<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Turístico - Registro y Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Contenedor único con pestañas -->
  <div class="main-container">
    <!-- Pestañas de navegación -->
    <div class="tab-navigation">
      <button class="tab-btn active" data-tab="login">
        <i class="fas fa-sign-in-alt"></i>
        <span>Iniciar Sesión</span>
      </button>
      <button class="tab-btn" data-tab="register">
        <i class="fas fa-user-plus"></i>
        <span>Registrarse</span>
      </button>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content">
      <!-- Pestaña de Login -->
      <div class="tab-pane active" id="login-tab">
        <div class="form-header">
          <h1 class="form-title">Bienvenido de vuelta</h1>
          <p class="form-subtitle">Inicia sesión en tu cuenta</p>
        </div>
        <form method="post" action="login.php" id="loginForm" novalidate>
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-envelope"></i>
              <span>Datos de Acceso</span>
            </div>
            
            <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" id="loginEmail" name="email" required>
              <label for="loginEmail">Correo electrónico *</label>
              <div class="error-message" id="loginEmailError"></div>
            </div>
            
            <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" id="loginPassword" name="password" required>
              <label for="loginPassword">Contraseña *</label>
              <button type="button" class="toggle-password" onclick="togglePassword('loginPassword')">
                <i class="fas fa-eye"></i>
              </button>
              <div class="error-message" id="loginPasswordError"></div>
            </div>
          </div>
          
          <div class="form-options">
            <label class="checkbox-group">
              <input type="checkbox" name="remember" id="remember">
              <span class="checkmark"></span>
              Recordarme
            </label>
            <a href="#" id="forgotPassword" class="forgot-link">¿Olvidaste tu contraseña?</a>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
          </div>
        </form>
        
        <div class="social-login">
          <div class="divider">
            <span>o continúa con</span>
          </div>
          <div class="social-buttons">
            <button class="social-btn google">
              <i class="fab fa-google"></i>
              <span>Google</span>
            </button>
            <button class="social-btn facebook">
              <i class="fab fa-facebook-f"></i>
              <span>Facebook</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Pestaña de Registro -->
      <div class="tab-pane" id="register-tab">
        <div class="form-header">
          <h1 class="form-title">Crear cuenta</h1>
          <p class="form-subtitle">Únete a nuestra comunidad turística</p>
        </div>
        <form method="post" action="register.php" id="registerForm" novalidate>
          <!-- Datos Personales -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-user"></i>
              <span>Datos Personales</span>
            </div>
            
            <div class="input-group">
              <i class="fas fa-user"></i>
              <input type="text" id="firstName" name="firstName" required>
              <label for="firstName">Nombre *</label>
              <div class="error-message" id="firstNameError"></div>
            </div>
            
            <div class="input-group">
              <i class="fas fa-user"></i>
              <input type="text" id="lastName" name="lastName" required>
              <label for="lastName">Apellido *</label>
              <div class="error-message" id="lastNameError"></div>
            </div>
          </div>
          
          <!-- Datos de Contacto -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-envelope"></i>
              <span>Datos de Contacto</span>
            </div>
            
            <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" required>
              <label for="email">Correo electrónico *</label>
              <div class="error-message" id="emailError"></div>
            </div>
          </div>
          
          <!-- Credenciales -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-lock"></i>
              <span>Credenciales</span>
            </div>
            
            <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" required>
              <label for="password">Contraseña *</label>
              <button type="button" class="toggle-password" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
              </button>
              <div class="error-message" id="passwordError"></div>
            </div>
            
            <div class="password-strength" id="passwordStrength" style="display: none;">
              <div class="strength-bar">
                <div class="strength-fill" id="strengthFill"></div>
              </div>
              <span class="strength-text" id="strengthText"></span>
            </div>
            
            <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" id="confirmPassword" name="confirmPassword" required>
              <label for="confirmPassword">Confirmar contraseña *</label>
              <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                <i class="fas fa-eye"></i>
              </button>
              <div class="error-message" id="confirmPasswordError"></div>
            </div>
          </div>
          
          <div class="form-options">
            <label class="checkbox-group">
              <input type="checkbox" name="terms" id="terms" required>
              <span class="checkmark"></span>
              Acepto los <a href="#" class="terms-link">términos y condiciones</a>
            </label>
            <label class="checkbox-group">
              <input type="checkbox" name="newsletter" id="newsletter">
              <span class="checkmark"></span>
              Recibir notificaciones y ofertas
            </label>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary" id="signUpBtn">
              <i class="fas fa-user-plus"></i> Crear cuenta
            </button>
          </div>
        </form>
        
        <div class="social-login">
          <div class="divider">
            <span>o regístrate con</span>
          </div>
          <div class="social-buttons">
            <button class="social-btn google">
              <i class="fab fa-google"></i>
              <span>Google</span>
            </button>
            <button class="social-btn facebook">
              <i class="fab fa-facebook-f"></i>
              <span>Facebook</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Mensajes de éxito -->
    <div class="success-message" id="successMessage" style="display:none;">
      <i class="fas fa-check-circle"></i>
      <p>¡Registro exitoso! Redirigiendo...</p>
    </div>
  </div>
  
                <!-- Botón de accesibilidad flotante -->
              <div class="accessibility-float">
                <button class="accessibility-btn" onclick="location.href='accessibility.php'" title="Configuración de Accesibilidad">
                  <i class="fas fa-universal-access"></i>
                </button>
              </div>
              
                             <script src="script_simple.js"></script>
            </body>
            </html>
