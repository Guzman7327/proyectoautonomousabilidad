<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario - Portal Turístico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            position: relative;
        }

        .form-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .form-header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px;
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e9ecef;
        }

        .tab {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
            background: #f8f9fa;
            border: none;
            font-size: 1.1em;
            font-weight: 500;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .tab.active {
            background: #007bff;
            color: white;
            transform: translateY(-2px);
        }

        .tab:hover {
            background: #0056b3;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1em;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2em;
        }

        .password-toggle:hover {
            color: #007bff;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .form-checkbox input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .form-checkbox label {
            margin: 0;
            font-weight: normal;
            font-size: 0.9em;
            color: #6c757d;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,123,255,0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .validation-message {
            font-size: 0.9em;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .validation-message.success {
            color: #28a745;
        }

        .validation-message.error {
            color: #dc3545;
        }

        .validation-message.warning {
            color: #ffc107;
        }

        .form-info {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .form-info h3 {
            color: #1976d2;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .form-info p {
            color: #424242;
            font-size: 0.9em;
            line-height: 1.5;
        }

        .screenshot-info {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }

        .screenshot-info h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .screenshot-info p {
            color: #6c757d;
            font-size: 0.9em;
        }

        .requirement-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-content {
                padding: 20px;
            }
            
            .form-header {
                padding: 20px;
            }
            
            .form-header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF1</div>
        
        <div class="form-header">
            <h1>🌴 Portal Turístico Ecuador</h1>
            <p>Registro e Inicio de Sesión - Prototipo RF1</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('login')">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
                <button class="tab" onclick="showTab('register')">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
            </div>

            <!-- FORMULARIO DE LOGIN -->
            <div id="login" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Información de Acceso</h3>
                    <p>Utiliza tus credenciales para acceder al portal turístico. Si no tienes cuenta, puedes registrarte en la pestaña "Registrarse".</p>
                </div>

                <form id="loginForm">
                    <div class="form-group">
                        <label for="loginEmail">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" id="loginEmail" name="email" placeholder="ejemplo@correo.com" required>
                        <div class="validation-message success">
                            <i class="fas fa-check-circle"></i> Formato de email válido
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">
                            <i class="fas fa-lock"></i> Contraseña
                        </label>
                        <div class="password-field">
                            <input type="password" id="loginPassword" name="password" placeholder="Ingresa tu contraseña" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="validation-message warning">
                            <i class="fas fa-exclamation-triangle"></i> Mínimo 8 caracteres
                        </div>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="rememberMe" name="remember">
                        <label for="rememberMe">Recordar mi sesión</label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="form-footer">
                    <a href="#"><i class="fas fa-key"></i> ¿Olvidaste tu contraseña?</a>
                </div>
            </div>

            <!-- FORMULARIO DE REGISTRO -->
            <div id="register" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Crear Nueva Cuenta</h3>
                    <p>Completa el formulario para crear tu cuenta y acceder a todos los servicios del portal turístico.</p>
                </div>

                <form id="registerForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">
                                <i class="fas fa-user"></i> Nombre
                            </label>
                            <input type="text" id="firstName" name="firstName" placeholder="Tu nombre" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Campo válido
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName">
                                <i class="fas fa-user"></i> Apellido
                            </label>
                            <input type="text" id="lastName" name="lastName" placeholder="Tu apellido" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Campo válido
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="registerEmail">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" id="registerEmail" name="email" placeholder="ejemplo@correo.com" required>
                        <div class="validation-message success">
                            <i class="fas fa-check-circle"></i> Email disponible
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="registerPassword">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <div class="password-field">
                                <input type="password" id="registerPassword" name="password" placeholder="Mínimo 8 caracteres" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('registerPassword')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Contraseña segura
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">
                                <i class="fas fa-lock"></i> Confirmar Contraseña
                            </label>
                            <div class="password-field">
                                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Repite tu contraseña" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Contraseñas coinciden
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input type="tel" id="phone" name="phone" placeholder="+593 99 123 4567" required>
                        <div class="validation-message warning">
                            <i class="fas fa-exclamation-triangle"></i> Formato: +593 99 123 4567
                        </div>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Acepto los <a href="#" style="color: #007bff;">Términos y Condiciones</a> y la <a href="#" style="color: #007bff;">Política de Privacidad</a></label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="newsletter" name="newsletter">
                        <label for="newsletter">Deseo recibir información sobre ofertas turísticas</label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus"></i> Crear Cuenta
                    </button>
                </form>

                <div class="form-footer">
                    <p>¿Ya tienes cuenta? <a href="#" onclick="showTab('login')">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura del Formulario RF1</h4>
            <p><strong>URL:</strong> http://localhost/registro/registro/formulario_prototipo.php</p>
            <p><strong>Estado:</strong> Prototipo funcional - Listo para captura de imagen</p>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Ocultar todos los contenidos de pestañas
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remover clase active de todas las pestañas
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Mostrar el contenido de la pestaña seleccionada
            document.getElementById(tabName).classList.add('active');

            // Activar la pestaña seleccionada
            event.target.classList.add('active');
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;
            const icon = toggle.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validación en tiempo real
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Formulario de login enviado (Simulación)');
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Formulario de registro enviado (Simulación)');
        });

        // Efectos visuales adicionales
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const formContainer = document.querySelector('.form-container');
            formContainer.style.opacity = '0';
            formContainer.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                formContainer.style.transition = 'all 0.6s ease';
                formContainer.style.opacity = '1';
                formContainer.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
