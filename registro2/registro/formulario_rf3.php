<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Perfil - Portal Turístico</title>
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
            max-width: 1000px;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
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

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
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

        .profile-preview {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .profile-preview h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-item i {
            color: #007bff;
            width: 20px;
        }

        .preferences-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .preference-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .preference-item:hover {
            border-color: #007bff;
            transform: translateY(-2px);
        }

        .preference-item i {
            font-size: 2em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .preference-item.active {
            background: #e3f2fd;
            border-color: #2196f3;
        }

        .security-status {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }

        .security-status h4 {
            color: #155724;
            margin-bottom: 10px;
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .security-item i {
            color: #28a745;
            width: 20px;
        }

        @media (max-width: 768px) {
            .form-row,
            .form-row-3 {
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
        <div class="requirement-badge">RF3</div>
        
        <div class="form-header">
            <h1>🌴 Portal Turístico Ecuador</h1>
            <p>Gestión de Perfil y Preferencias - Prototipo RF3</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('profile')">
                    <i class="fas fa-user"></i> Perfil
                </button>
                <button class="tab" onclick="showTab('preferences')">
                    <i class="fas fa-cog"></i> Preferencias
                </button>
                <button class="tab" onclick="showTab('security')">
                    <i class="fas fa-shield-alt"></i> Seguridad
                </button>
            </div>

            <!-- FORMULARIO DE PERFIL -->
            <div id="profile" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Información Personal</h3>
                    <p>Actualiza tu información personal para personalizar tu experiencia en el portal turístico.</p>
                </div>

                <form id="profileForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">
                                <i class="fas fa-user"></i> Nombre
                            </label>
                            <input type="text" id="firstName" name="firstName" value="María" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Campo válido
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName">
                                <i class="fas fa-user"></i> Apellido
                            </label>
                            <input type="text" id="lastName" name="lastName" value="González" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Campo válido
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input type="email" id="email" name="email" value="maria.gonzalez@email.com" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Email válido
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i> Teléfono
                            </label>
                            <input type="tel" id="phone" name="phone" value="+593 99 123 4567" required>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="birthDate">
                                <i class="fas fa-calendar"></i> Fecha de Nacimiento
                            </label>
                            <input type="date" id="birthDate" name="birthDate" value="1990-05-15">
                        </div>

                        <div class="form-group">
                            <label for="nationality">
                                <i class="fas fa-flag"></i> Nacionalidad
                            </label>
                            <select id="nationality" name="nationality">
                                <option value="ecuador" selected>Ecuador</option>
                                <option value="colombia">Colombia</option>
                                <option value="peru">Perú</option>
                                <option value="venezuela">Venezuela</option>
                                <option value="usa">Estados Unidos</option>
                                <option value="spain">España</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="language">
                                <i class="fas fa-language"></i> Idioma Preferido
                            </label>
                            <select id="language" name="language">
                                <option value="es" selected>Español</option>
                                <option value="en">English</option>
                                <option value="fr">Français</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">
                            <i class="fas fa-map-marker-alt"></i> Dirección
                        </label>
                        <textarea id="address" name="address" rows="3" placeholder="Ingresa tu dirección completa">Av. Amazonas N45-123, Quito, Ecuador</textarea>
                    </div>

                    <div class="form-group">
                        <label for="bio">
                            <i class="fas fa-user-edit"></i> Biografía
                        </label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Cuéntanos sobre ti y tus intereses turísticos">Viajera apasionada por la cultura y la aventura. Me encanta explorar nuevos destinos y conocer diferentes culturas.</textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>

                <!-- PREVIEW DE PERFIL -->
                <div class="profile-preview">
                    <h4><i class="fas fa-eye"></i> Vista Previa del Perfil</h4>
                    <div class="profile-details">
                        <div class="detail-item">
                            <i class="fas fa-user"></i>
                            <span><strong>María González</strong></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-envelope"></i>
                            <span>maria.gonzalez@email.com</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <span>+593 99 123 4567</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span>15 de Mayo, 1990</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-flag"></i>
                            <span>Ecuador</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-language"></i>
                            <span>Español</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO DE PREFERENCIAS -->
            <div id="preferences" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Preferencias Turísticas</h3>
                    <p>Personaliza tu experiencia seleccionando tus preferencias de viaje y destinos favoritos.</p>
                </div>

                <form id="preferencesForm">
                    <div class="form-group">
                        <label for="travelStyle">
                            <i class="fas fa-plane"></i> Estilo de Viaje Preferido
                        </label>
                        <select id="travelStyle" name="travelStyle" required>
                            <option value="">Selecciona tu estilo</option>
                            <option value="cultural" selected>Cultural</option>
                            <option value="adventure">Aventura</option>
                            <option value="relaxation">Relajación</option>
                            <option value="luxury">Lujo</option>
                            <option value="budget">Económico</option>
                            <option value="family">Familiar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="budgetRange">
                            <i class="fas fa-dollar-sign"></i> Rango de Presupuesto
                        </label>
                        <select id="budgetRange" name="budgetRange">
                            <option value="">Sin preferencia</option>
                            <option value="low" selected>$500 - $1,000</option>
                            <option value="medium">$1,000 - $3,000</option>
                            <option value="high">$3,000 - $5,000</option>
                            <option value="luxury">$5,000+</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="accommodationType">
                            <i class="fas fa-bed"></i> Tipo de Alojamiento Preferido
                        </label>
                        <select id="accommodationType" name="accommodationType">
                            <option value="">Sin preferencia</option>
                            <option value="hotel" selected>Hotel</option>
                            <option value="hostel">Hostal</option>
                            <option value="resort">Resort</option>
                            <option value="cabin">Cabaña</option>
                            <option value="apartment">Apartamento</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-heart"></i> Destinos Favoritos</label>
                        <div class="preferences-grid">
                            <div class="preference-item active">
                                <i class="fas fa-mountain"></i>
                                <div>Montaña</div>
                            </div>
                            <div class="preference-item active">
                                <i class="fas fa-umbrella-beach"></i>
                                <div>Playa</div>
                            </div>
                            <div class="preference-item">
                                <i class="fas fa-city"></i>
                                <div>Ciudad</div>
                            </div>
                            <div class="preference-item active">
                                <i class="fas fa-tree"></i>
                                <div>Naturaleza</div>
                            </div>
                            <div class="preference-item">
                                <i class="fas fa-utensils"></i>
                                <div>Gastronomía</div>
                            </div>
                            <div class="preference-item">
                                <i class="fas fa-landmark"></i>
                                <div>Histórico</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="activities">
                            <i class="fas fa-hiking"></i> Actividades de Interés
                        </label>
                        <div class="form-checkbox">
                            <input type="checkbox" id="hiking" name="activities[]" value="hiking" checked>
                            <label for="hiking">Senderismo</label>
                        </div>
                        <div class="form-checkbox">
                            <input type="checkbox" id="photography" name="activities[]" value="photography" checked>
                            <label for="photography">Fotografía</label>
                        </div>
                        <div class="form-checkbox">
                            <input type="checkbox" id="museums" name="activities[]" value="museums" checked>
                            <label for="museums">Museos</label>
                        </div>
                        <div class="form-checkbox">
                            <input type="checkbox" id="shopping" name="activities[]" value="shopping">
                            <label for="shopping">Compras</label>
                        </div>
                        <div class="form-checkbox">
                            <input type="checkbox" id="nightlife" name="activities[]" value="nightlife">
                            <label for="nightlife">Vida Nocturna</label>
                        </div>
                        <div class="form-checkbox">
                            <input type="checkbox" id="spa" name="activities[]" value="spa">
                            <label for="spa">Spa y Bienestar</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dietaryRestrictions">
                            <i class="fas fa-utensils"></i> Restricciones Alimentarias
                        </label>
                        <select id="dietaryRestrictions" name="dietaryRestrictions">
                            <option value="">Ninguna</option>
                            <option value="vegetarian">Vegetariano</option>
                            <option value="vegan">Vegano</option>
                            <option value="gluten-free">Sin Gluten</option>
                            <option value="dairy-free">Sin Lácteos</option>
                            <option value="halal">Halal</option>
                            <option value="kosher">Kosher</option>
                        </select>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="newsletter" name="newsletter" checked>
                        <label for="newsletter">Recibir boletín de ofertas turísticas</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="notifications" name="notifications" checked>
                        <label for="notifications">Recibir notificaciones de nuevos destinos</label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Guardar Preferencias
                    </button>
                </form>
            </div>

            <!-- FORMULARIO DE SEGURIDAD -->
            <div id="security" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Configuración de Seguridad</h3>
                    <p>Gestiona la seguridad de tu cuenta y configura métodos de autenticación adicionales.</p>
                </div>

                <form id="securityForm">
                    <div class="form-group">
                        <label for="currentPassword">
                            <i class="fas fa-lock"></i> Contraseña Actual
                        </label>
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="Ingresa tu contraseña actual" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newPassword">
                                <i class="fas fa-key"></i> Nueva Contraseña
                            </label>
                            <input type="password" id="newPassword" name="newPassword" placeholder="Mínimo 8 caracteres">
                            <div class="validation-message warning">
                                <i class="fas fa-exclamation-triangle"></i> Mínimo 8 caracteres
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirmNewPassword">
                                <i class="fas fa-key"></i> Confirmar Nueva Contraseña
                            </label>
                            <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Repite la nueva contraseña">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="securityQuestion">
                            <i class="fas fa-question-circle"></i> Pregunta de Seguridad
                        </label>
                        <select id="securityQuestion" name="securityQuestion">
                            <option value="">Selecciona una pregunta</option>
                            <option value="pet" selected>¿Cuál es el nombre de tu primera mascota?</option>
                            <option value="city">¿En qué ciudad naciste?</option>
                            <option value="school">¿Cuál es el nombre de tu primera escuela?</option>
                            <option value="mother">¿Cuál es el apellido de soltera de tu madre?</option>
                            <option value="car">¿Cuál fue tu primer carro?</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="securityAnswer">
                            <i class="fas fa-answer"></i> Respuesta de Seguridad
                        </label>
                        <input type="text" id="securityAnswer" name="securityAnswer" placeholder="Tu respuesta" value="Luna">
                    </div>

                    <div class="form-group">
                        <label for="phoneVerification">
                            <i class="fas fa-mobile-alt"></i> Verificación por Teléfono
                        </label>
                        <input type="tel" id="phoneVerification" name="phoneVerification" value="+593 99 123 4567" placeholder="Número para verificación">
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="twoFactorAuth" name="twoFactorAuth">
                        <label for="twoFactorAuth">Activar autenticación de dos factores</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="loginAlerts" name="loginAlerts" checked>
                        <label for="loginAlerts">Recibir alertas de inicio de sesión</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="sessionTimeout" name="sessionTimeout" checked>
                        <label for="sessionTimeout">Cerrar sesión automáticamente después de 30 minutos</label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-shield-alt"></i> Actualizar Seguridad
                    </button>
                </form>

                <!-- ESTADO DE SEGURIDAD -->
                <div class="security-status">
                    <h4><i class="fas fa-shield-check"></i> Estado de Seguridad de la Cuenta</h4>
                    <div class="security-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Contraseña fuerte configurada</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Pregunta de seguridad establecida</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Verificación por teléfono activa</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-times-circle"></i>
                        <span>Autenticación de dos factores desactivada</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Alertas de inicio de sesión activas</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura del Formulario RF3</h4>
            <p><strong>URL:</strong> http://localhost/registro/registro/formulario_rf3.php</p>
            <p><strong>Estado:</strong> Prototipo funcional - Gestión de Perfil y Preferencias</p>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Perfil actualizado exitosamente (Simulación)');
        });

        document.getElementById('preferencesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Preferencias guardadas exitosamente (Simulación)');
        });

        document.getElementById('securityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Configuración de seguridad actualizada (Simulación)');
        });

        // Interactividad para preferencias
        document.querySelectorAll('.preference-item').forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

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
