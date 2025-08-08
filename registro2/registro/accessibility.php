<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Accesibilidad - Portal Turístico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            min-height: 100vh;
            padding: 20px;
            transition: all 0.3s ease;
        }

        /* Modo alto contraste */
        body.high-contrast {
            background: #000000 !important;
            color: #ffffff !important;
        }

        body.high-contrast .main-container {
            background: #000000 !important;
            color: #ffffff !important;
            border: 2px solid #ffffff !important;
        }

        body.high-contrast .accessibility-card {
            background: #000000 !important;
            color: #ffffff !important;
            border: 2px solid #ffffff !important;
        }

        body.high-contrast .btn {
            background: #ffffff !important;
            color: #000000 !important;
            border: 2px solid #ffffff !important;
        }

        /* Tamaños de fuente */
        body.font-small {
            font-size: 14px;
        }

        body.font-medium {
            font-size: 16px;
        }

        body.font-large {
            font-size: 18px;
        }

        body.font-extra-large {
            font-size: 20px;
        }

        .main-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
        }

        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .content {
            padding: 30px;
        }

        .accessibility-section {
            margin-bottom: 30px;
        }

        .section-title {
            color: #2e7d32;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .accessibility-card {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            padding: 20px;
            margin-bottom: 15px;
        }

        .setting-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .setting-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .setting-label {
            font-weight: 600;
            color: #495057;
        }

        .setting-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #4caf50;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .font-size-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .font-btn {
            background: #4caf50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .font-btn:hover {
            background: #2e7d32;
        }

        .font-btn.active {
            background: #2e7d32;
            font-weight: bold;
        }

        .color-scheme-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .color-btn {
            width: 40px;
            height: 40px;
            border: 3px solid #e9ecef;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .color-btn:hover {
            transform: scale(1.1);
        }

        .color-btn.active {
            border-color: #4caf50;
        }

        .color-default {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        }

        .color-blue {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }

        .color-purple {
            background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%);
        }

        .color-orange {
            background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
        }

        .reset-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .reset-btn:hover {
            background: #5a6268;
        }

        .accessibility-info {
            background: #e8f5e8;
            border: 2px solid #4caf50;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .accessibility-info h3 {
            color: #2e7d32;
            margin-top: 0;
        }

        .accessibility-info ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .accessibility-info li {
            margin-bottom: 5px;
            color: #495057;
        }

        @media (max-width: 768px) {
            .setting-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .font-size-controls,
            .color-scheme-controls {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body class="font-medium">
    <div class="main-container">
        <div class="header">
            <a href="<?= isset($_SESSION['user_id']) ? ($_SESSION['user_role'] === 'admin' ? 'admin.php' : 'home.php') : 'index.php' ?>" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h1><i class="fas fa-universal-access"></i> Accesibilidad</h1>
            <div class="subtitle">Configuración de accesibilidad del Portal Turístico</div>
        </div>

        <div class="content">
            <!-- Tamaño de fuente -->
            <div class="accessibility-section">
                <h2 class="section-title">
                    <i class="fas fa-text-height"></i>
                    Tamaño de Fuente
                </h2>
                <div class="accessibility-card">
                    <div class="setting-group">
                        <div>
                            <div class="setting-label">Ajustar tamaño de texto</div>
                            <div class="setting-description">Cambia el tamaño de la fuente para mejorar la legibilidad</div>
                        </div>
                        <div class="font-size-controls">
                            <button class="font-btn" onclick="setFontSize('small')">A</button>
                            <button class="font-btn active" onclick="setFontSize('medium')">A</button>
                            <button class="font-btn" onclick="setFontSize('large')">A</button>
                            <button class="font-btn" onclick="setFontSize('extra-large')">A</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Esquema de colores -->
            <div class="accessibility-section">
                <h2 class="section-title">
                    <i class="fas fa-palette"></i>
                    Esquema de Colores
                </h2>
                <div class="accessibility-card">
                    <div class="setting-group">
                        <div>
                            <div class="setting-label">Color del tema</div>
                            <div class="setting-description">Selecciona un esquema de colores que te resulte más cómodo</div>
                        </div>
                        <div class="color-scheme-controls">
                            <div class="color-btn color-default active" onclick="setColorScheme('default')" title="Verde (Predeterminado)"></div>
                            <div class="color-btn color-blue" onclick="setColorScheme('blue')" title="Azul"></div>
                            <div class="color-btn color-purple" onclick="setColorScheme('purple')" title="Púrpura"></div>
                            <div class="color-btn color-orange" onclick="setColorScheme('orange')" title="Naranja"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alto contraste -->
            <div class="accessibility-section">
                <h2 class="section-title">
                    <i class="fas fa-adjust"></i>
                    Alto Contraste
                </h2>
                <div class="accessibility-card">
                    <div class="setting-group">
                        <div>
                            <div class="setting-label">Modo alto contraste</div>
                            <div class="setting-description">Activa el modo de alto contraste para mejor visibilidad</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="highContrast" onchange="toggleHighContrast()">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Reducir animaciones -->
            <div class="accessibility-section">
                <h2 class="section-title">
                    <i class="fas fa-stop-circle"></i>
                    Animaciones
                </h2>
                <div class="accessibility-card">
                    <div class="setting-group">
                        <div>
                            <div class="setting-label">Reducir animaciones</div>
                            <div class="setting-description">Reduce las animaciones para usuarios sensibles al movimiento</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="reduceMotion" onchange="toggleReduceMotion()">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Navegación por teclado -->
            <div class="accessibility-section">
                <h2 class="section-title">
                    <i class="fas fa-keyboard"></i>
                    Navegación por Teclado
                </h2>
                <div class="accessibility-card">
                    <div class="setting-group">
                        <div>
                            <div class="setting-label">Resaltar enlaces al navegar</div>
                            <div class="setting-description">Resalta visualmente los enlaces cuando navegas con el teclado</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="keyboardNav" onchange="toggleKeyboardNav()">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botón de reset -->
            <div style="text-align: center;">
                <button class="reset-btn" onclick="resetAccessibility()">
                    <i class="fas fa-undo"></i> Restablecer Configuración
                </button>
            </div>

            <!-- Información de accesibilidad -->
            <div class="accessibility-info">
                <h3><i class="fas fa-info-circle"></i> Información de Accesibilidad</h3>
                <p>Este portal está diseñado para ser accesible para todos los usuarios. Características incluidas:</p>
                <ul>
                    <li><strong>Navegación por teclado:</strong> Usa Tab para navegar entre elementos</li>
                    <li><strong>Lectores de pantalla:</strong> Compatible con lectores de pantalla</li>
                    <li><strong>Alto contraste:</strong> Modo de alto contraste disponible</li>
                    <li><strong>Tamaño de fuente:</strong> Ajustable según preferencias</li>
                    <li><strong>Esquemas de color:</strong> Múltiples opciones de color</li>
                    <li><strong>Reducción de movimiento:</strong> Para usuarios sensibles</li>
                </ul>
                <p><strong>Atajos de teclado:</strong></p>
                <ul>
                    <li><kbd>Tab</kbd> - Navegar entre elementos</li>
                    <li><kbd>Enter</kbd> - Activar botones/enlaces</li>
                    <li><kbd>Escape</kbd> - Cerrar modales/diálogos</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Cargar configuración guardada
        document.addEventListener('DOMContentLoaded', function() {
            loadAccessibilitySettings();
        });

        // Funciones de accesibilidad
        function setFontSize(size) {
            const body = document.body;
            const buttons = document.querySelectorAll('.font-btn');
            
            // Remover clases de tamaño anteriores
            body.classList.remove('font-small', 'font-medium', 'font-large', 'font-extra-large');
            
            // Aplicar nuevo tamaño
            body.classList.add('font-' + size);
            
            // Actualizar botones activos
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Guardar preferencia
            localStorage.setItem('fontSize', size);
        }

        function setColorScheme(scheme) {
            const body = document.body;
            const buttons = document.querySelectorAll('.color-btn');
            
            // Remover esquemas anteriores
            body.classList.remove('color-default', 'color-blue', 'color-purple', 'color-orange');
            
            // Aplicar nuevo esquema
            body.classList.add('color-' + scheme);
            
            // Actualizar botones activos
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Guardar preferencia
            localStorage.setItem('colorScheme', scheme);
        }

        function toggleHighContrast() {
            const body = document.body;
            const checkbox = document.getElementById('highContrast');
            
            if (checkbox.checked) {
                body.classList.add('high-contrast');
                localStorage.setItem('highContrast', 'true');
            } else {
                body.classList.remove('high-contrast');
                localStorage.setItem('highContrast', 'false');
            }
        }

        function toggleReduceMotion() {
            const checkbox = document.getElementById('reduceMotion');
            
            if (checkbox.checked) {
                document.documentElement.style.setProperty('--transition-duration', '0.01ms');
                localStorage.setItem('reduceMotion', 'true');
            } else {
                document.documentElement.style.setProperty('--transition-duration', '0.3s');
                localStorage.setItem('reduceMotion', 'false');
            }
        }

        function toggleKeyboardNav() {
            const checkbox = document.getElementById('keyboardNav');
            
            if (checkbox.checked) {
                document.body.classList.add('keyboard-nav');
                localStorage.setItem('keyboardNav', 'true');
            } else {
                document.body.classList.remove('keyboard-nav');
                localStorage.setItem('keyboardNav', 'false');
            }
        }

        function resetAccessibility() {
            // Resetear todas las configuraciones
            localStorage.removeItem('fontSize');
            localStorage.removeItem('colorScheme');
            localStorage.removeItem('highContrast');
            localStorage.removeItem('reduceMotion');
            localStorage.removeItem('keyboardNav');
            
            // Recargar página para aplicar cambios
            location.reload();
        }

        function loadAccessibilitySettings() {
            // Cargar tamaño de fuente
            const fontSize = localStorage.getItem('fontSize') || 'medium';
            setFontSize(fontSize);
            
            // Cargar esquema de color
            const colorScheme = localStorage.getItem('colorScheme') || 'default';
            setColorScheme(colorScheme);
            
            // Cargar alto contraste
            const highContrast = localStorage.getItem('highContrast') === 'true';
            if (highContrast) {
                document.getElementById('highContrast').checked = true;
                toggleHighContrast();
            }
            
            // Cargar reducción de movimiento
            const reduceMotion = localStorage.getItem('reduceMotion') === 'true';
            if (reduceMotion) {
                document.getElementById('reduceMotion').checked = true;
                toggleReduceMotion();
            }
            
            // Cargar navegación por teclado
            const keyboardNav = localStorage.getItem('keyboardNav') === 'true';
            if (keyboardNav) {
                document.getElementById('keyboardNav').checked = true;
                toggleKeyboardNav();
            }
        }

        // Navegación por teclado mejorada
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
    </script>
</body>
</html> 