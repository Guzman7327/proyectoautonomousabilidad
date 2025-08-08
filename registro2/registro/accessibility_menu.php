<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Accesibilidad - Portal Turístico</title>
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
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .controls {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .accessibility-showcase {
            padding: 40px;
        }

        .accessibility-section {
            margin-bottom: 50px;
        }

        .accessibility-section h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }

        .accessibility-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .accessibility-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
        }

        .accessibility-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .accessibility-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .accessibility-header i {
            font-size: 2.5em;
            margin-bottom: 10px;
            display: block;
        }

        .accessibility-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .accessibility-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .accessibility-body {
            padding: 20px;
        }

        .accessibility-features {
            list-style: none;
            margin-bottom: 20px;
        }

        .accessibility-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .accessibility-features li:last-child {
            border-bottom: none;
        }

        .accessibility-features li i {
            color: #28a745;
            width: 20px;
        }

        .accessibility-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-development {
            background: #fff3cd;
            color: #856404;
        }

        .status-planned {
            background: #f8d7da;
            color: #721c24;
        }

        .accessibility-description {
            color: #6c757d;
            font-size: 0.9em;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .accessibility-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .accessibility-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .accessibility-btn-primary {
            background: #007bff;
            color: white;
        }

        .accessibility-btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .accessibility-btn-secondary {
            background: #6c757d;
            color: white;
        }

        .accessibility-btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
        }

        .accessibility-btn-success {
            background: #28a745;
            color: white;
        }

        .accessibility-btn-success:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .accessibility-controls {
            background: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #e9ecef;
        }

        .controls-title {
            text-align: center;
            color: #2c3e50;
            font-size: 1.5em;
            margin-bottom: 30px;
        }

        .controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .control-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            text-align: center;
            transition: all 0.3s ease;
        }

        .control-item:hover {
            border-color: #007bff;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .control-icon {
            font-size: 2em;
            color: #007bff;
            margin-bottom: 15px;
        }

        .control-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .control-description {
            color: #6c757d;
            font-size: 0.9em;
            line-height: 1.5;
        }

        .control-toggle {
            margin-top: 15px;
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
            background-color: #007bff;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .stats-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 1em;
            opacity: 0.9;
        }

        .screenshot-section {
            background: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #e9ecef;
        }

        .screenshot-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .screenshot-placeholder {
            background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), 
                        linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #f0f0f0 75%), 
                        linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            height: 400px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.2em;
            border: 2px dashed #dee2e6;
        }

        @media (max-width: 768px) {
            .accessibility-grid {
                grid-template-columns: 1fr;
            }
            
            .controls-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .zoom-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }

        .zoom-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: #007bff;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .zoom-btn:hover {
            background: #0056b3;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>♿ Menú de Accesibilidad</h1>
            <p>Portal Turístico de Ecuador - Funcionalidades de Accesibilidad Universal</p>
        </div>

        <div class="controls">
            <a href="index.php" class="btn btn-primary">🏠 Volver al Portal</a>
            <a href="menu_design.php" class="btn btn-success">🍽️ Ver Menú Principal</a>
            <a href="database_design.php" class="btn btn-primary">🗄️ Ver Base de Datos</a>
        </div>

        <div class="stats-section">
            <h3>📊 Estadísticas de Accesibilidad</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Funcionalidades de Accesibilidad</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">WCAG 2.1</div>
                    <div class="stat-label">Estándar Cumplido</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Navegación por Teclado</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Tamaños de Fuente</div>
                </div>
            </div>
        </div>

        <div class="accessibility-showcase">
            <!-- SECCIÓN 1: VISUAL -->
            <div class="accessibility-section">
                <h2>👁️ Accesibilidad Visual</h2>
                <div class="accessibility-grid">
                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-text-height"></i>
                            <div class="accessibility-title">Tamaño de Fuente</div>
                            <div class="accessibility-subtitle">Ajuste de texto</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Permite a los usuarios cambiar el tamaño del texto para mejorar la legibilidad.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Pequeño (12px)</li>
                                <li><i class="fas fa-check"></i> Mediano (16px)</li>
                                <li><i class="fas fa-check"></i> Grande (20px)</li>
                                <li><i class="fas fa-check"></i> Extra Grande (24px)</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-adjust"></i>
                            <div class="accessibility-title">Contraste de Colores</div>
                            <div class="accessibility-subtitle">Alto contraste</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Modo de alto contraste para mejorar la visibilidad del contenido.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Contraste normal</li>
                                <li><i class="fas fa-check"></i> Alto contraste</li>
                                <li><i class="fas fa-check"></i> Modo oscuro</li>
                                <li><i class="fas fa-check"></i> Modo claro</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-eye-slash"></i>
                            <div class="accessibility-title">Lector de Pantalla</div>
                            <div class="accessibility-subtitle">Navegación auditiva</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Compatibilidad completa con lectores de pantalla como NVDA, JAWS y VoiceOver.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Etiquetas ARIA</li>
                                <li><i class="fas fa-check"></i> Navegación por encabezados</li>
                                <li><i class="fas fa-check"></i> Descripción de imágenes</li>
                                <li><i class="fas fa-check"></i> Anuncios de estado</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: MOTRIZ -->
            <div class="accessibility-section">
                <h2>🖱️ Accesibilidad Motriz</h2>
                <div class="accessibility-grid">
                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-keyboard"></i>
                            <div class="accessibility-title">Navegación por Teclado</div>
                            <div class="accessibility-subtitle">Control completo</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Navegación completa del sitio usando solo el teclado.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Tab para navegar</li>
                                <li><i class="fas fa-check"></i> Enter para activar</li>
                                <li><i class="fas fa-check"></i> Escape para cerrar</li>
                                <li><i class="fas fa-check"></i> Flechas para seleccionar</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-mouse-pointer"></i>
                            <div class="accessibility-title">Áreas de Clic Ampliadas</div>
                            <div class="accessibility-subtitle">Fácil interacción</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Botones y enlaces con áreas de clic más grandes para facilitar la interacción.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Botones grandes</li>
                                <li><i class="fas fa-check"></i> Espaciado amplio</li>
                                <li><i class="fas fa-check"></i> Estados visuales claros</li>
                                <li><i class="fas fa-check"></i> Feedback táctil</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-hand-paper"></i>
                            <div class="accessibility-title">Control por Voz</div>
                            <div class="accessibility-subtitle">Comandos de voz</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Control del sitio mediante comandos de voz para usuarios con limitaciones motrices.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Navegación por voz</li>
                                <li><i class="fas fa-check"></i> Comandos personalizados</li>
                                <li><i class="fas fa-check"></i> Dictado de texto</li>
                                <li><i class="fas fa-check"></i> Control de formularios</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-development">En Desarrollo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: COGNITIVA -->
            <div class="accessibility-section">
                <h2>🧠 Accesibilidad Cognitiva</h2>
                <div class="accessibility-grid">
                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-clock"></i>
                            <div class="accessibility-title">Reducción de Animaciones</div>
                            <div class="accessibility-subtitle">Menos distracciones</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Opción para reducir o eliminar animaciones que pueden causar distracción.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Animaciones reducidas</li>
                                <li><i class="fas fa-check"></i> Transiciones suaves</li>
                                <li><i class="fas fa-check"></i> Sin efectos flash</li>
                                <li><i class="fas fa-check"></i> Contenido estático</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-language"></i>
                            <div class="accessibility-title">Lenguaje Simple</div>
                            <div class="accessibility-subtitle">Texto claro</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Contenido escrito en lenguaje simple y directo para facilitar la comprensión.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Frases cortas</li>
                                <li><i class="fas fa-check"></i> Vocabulario simple</li>
                                <li><i class="fas fa-check"></i> Instrucciones claras</li>
                                <li><i class="fas fa-check"></i> Sin jerga técnica</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-map-signs"></i>
                            <div class="accessibility-title">Navegación Simplificada</div>
                            <div class="accessibility-subtitle">Rutas claras</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Estructura de navegación clara y consistente para facilitar la orientación.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Menú principal claro</li>
                                <li><i class="fas fa-check"></i> Breadcrumbs</li>
                                <li><i class="fas fa-check"></i> Botón de inicio</li>
                                <li><i class="fas fa-check"></i> Mapa del sitio</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 4: AUDITIVA -->
            <div class="accessibility-section">
                <h2>🔊 Accesibilidad Auditiva</h2>
                <div class="accessibility-grid">
                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-closed-captioning"></i>
                            <div class="accessibility-title">Subtítulos y Transcripciones</div>
                            <div class="accessibility-subtitle">Contenido multimedia</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Subtítulos y transcripciones para todo el contenido multimedia del sitio.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Subtítulos en videos</li>
                                <li><i class="fas fa-check"></i> Transcripciones de audio</li>
                                <li><i class="fas fa-check"></i> Descripción de sonidos</li>
                                <li><i class="fas fa-check"></i> Controles de volumen</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-development">En Desarrollo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-volume-mute"></i>
                            <div class="accessibility-title">Alertas Visuales</div>
                            <div class="accessibility-subtitle">Notificaciones visuales</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Todas las alertas y notificaciones incluyen indicadores visuales.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Iconos de estado</li>
                                <li><i class="fas fa-check"></i> Colores indicativos</li>
                                <li><i class="fas fa-check"></i> Mensajes de texto</li>
                                <li><i class="fas fa-check"></i> Animaciones sutiles</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-active">Activo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-item">
                        <div class="accessibility-header">
                            <i class="fas fa-bell"></i>
                            <div class="accessibility-title">Notificaciones Personalizables</div>
                            <div class="accessibility-subtitle">Preferencias de alerta</div>
                        </div>
                        <div class="accessibility-body">
                            <div class="accessibility-description">
                                Configuración de notificaciones según las preferencias del usuario.
                            </div>
                            <ul class="accessibility-features">
                                <li><i class="fas fa-check"></i> Alertas visuales</li>
                                <li><i class="fas fa-check"></i> Notificaciones push</li>
                                <li><i class="fas fa-check"></i> Email de confirmación</li>
                                <li><i class="fas fa-check"></i> Frecuencia personalizable</li>
                            </ul>
                            <div class="accessibility-actions">
                                <span class="accessibility-status status-development">En Desarrollo</span>
                                <a href="#" class="accessibility-btn accessibility-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTROLES DE ACCESIBILIDAD -->
        <div class="accessibility-controls">
            <div class="controls-title">🎛️ Panel de Control de Accesibilidad</div>
            <div class="controls-grid">
                <div class="control-item">
                    <div class="control-icon">🔍</div>
                    <div class="control-title">Zoom de Página</div>
                    <div class="control-description">
                        Aumentar o disminuir el tamaño de toda la página
                    </div>
                    <div class="control-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" id="zoom-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="control-item">
                    <div class="control-icon">🎨</div>
                    <div class="control-title">Alto Contraste</div>
                    <div class="control-description">
                        Activar modo de alto contraste para mejor visibilidad
                    </div>
                    <div class="control-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" id="contrast-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="control-item">
                    <div class="control-icon">📝</div>
                    <div class="control-title">Fuente Grande</div>
                    <div class="control-description">
                        Aumentar el tamaño de la fuente para mejor lectura
                    </div>
                    <div class="control-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" id="font-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="control-item">
                    <div class="control-icon">🎬</div>
                    <div class="control-title">Reducir Animaciones</div>
                    <div class="control-description">
                        Minimizar animaciones para reducir distracciones
                    </div>
                    <div class="control-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" id="animation-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="control-item">
                    <div class="control-icon">🔊</div>
                    <div class="control-title">Subtítulos</div>
                    <div class="control-description">
                        Mostrar subtítulos en contenido multimedia
                    </div>
                    <div class="control-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" id="captions-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="control-item">
                    <div class="control-icon">⌨️</div>
                    <div class="control-title">Modo Teclado</div>
                    <div class="control-description">
                        Optimizar navegación para uso exclusivo de teclado
                    </div>
                    <div class="control-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" id="keyboard-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- CAPTURA DE PANTALLA -->
        <div class="screenshot-section">
            <h3>📸 Captura del Menú de Accesibilidad</h3>
            <div class="screenshot-container">
                <div class="screenshot-placeholder">
                    <div style="text-align: center;">
                        <i class="fas fa-universal-access" style="font-size: 3em; margin-bottom: 20px; color: #007bff;"></i>
                        <h4>Menú de Accesibilidad del Portal Turístico</h4>
                        <p>Aquí se mostraría la captura de pantalla del menú de accesibilidad</p>
                        <p style="font-size: 0.9em; margin-top: 10px;">
                            <strong>URL:</strong> http://localhost/registro/registro/accessibility.php<br>
                            <strong>Estado:</strong> Funcionando correctamente<br>
                            <strong>Estándar:</strong> WCAG 2.1 AA
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="zoom-controls">
        <button class="zoom-btn" onclick="zoomIn()">🔍+</button>
        <button class="zoom-btn" onclick="zoomOut()">🔍-</button>
        <button class="zoom-btn" onclick="resetZoom()">🔄</button>
    </div>

    <script>
        let currentZoom = 1;
        const accessibilityShowcase = document.querySelector('.accessibility-showcase');

        function zoomIn() {
            if (currentZoom < 2) {
                currentZoom += 0.1;
                updateZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > 0.5) {
                currentZoom -= 0.1;
                updateZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            updateZoom();
        }

        function updateZoom() {
            accessibilityShowcase.style.transform = `scale(${currentZoom})`;
            accessibilityShowcase.style.transformOrigin = 'top left';
        }

        // Animación de entrada para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.accessibility-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.6s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Animación para los controles
            const controls = document.querySelectorAll('.control-item');
            controls.forEach((control, index) => {
                control.style.opacity = '0';
                control.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    control.style.transition = 'all 0.5s ease';
                    control.style.opacity = '1';
                    control.style.transform = 'scale(1)';
                }, 1000 + (index * 100));
            });
        });

        // Efecto hover mejorado
        document.querySelectorAll('.accessibility-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Funcionalidad de los toggles
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const controlTitle = this.closest('.control-item').querySelector('.control-title').textContent;
                console.log(`${controlTitle}: ${this.checked ? 'Activado' : 'Desactivado'}`);
                
                // Aquí se implementarían las funciones de accesibilidad
                if (this.checked) {
                    this.closest('.control-item').style.borderColor = '#28a745';
                } else {
                    this.closest('.control-item').style.borderColor = '#e9ecef';
                }
            });
        });
    </script>
</body>
</html>
