<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseño del Menú - Portal Turístico</title>
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

        .menu-showcase {
            padding: 40px;
        }

        .menu-section {
            margin-bottom: 50px;
        }

        .menu-section h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .menu-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
        }

        .menu-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .menu-item-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .menu-item-header i {
            font-size: 2.5em;
            margin-bottom: 10px;
            display: block;
        }

        .menu-item-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .menu-item-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .menu-item-body {
            padding: 20px;
        }

        .menu-features {
            list-style: none;
            margin-bottom: 20px;
        }

        .menu-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu-features li:last-child {
            border-bottom: none;
        }

        .menu-features li i {
            color: #28a745;
            width: 20px;
        }

        .menu-status {
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

        .menu-description {
            color: #6c757d;
            font-size: 0.9em;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .menu-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .menu-btn {
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

        .menu-btn-primary {
            background: #007bff;
            color: white;
        }

        .menu-btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .menu-btn-secondary {
            background: #6c757d;
            color: white;
        }

        .menu-btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
        }

        .menu-btn-success {
            background: #28a745;
            color: white;
        }

        .menu-btn-success:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .navigation-flow {
            background: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #e9ecef;
        }

        .flow-title {
            text-align: center;
            color: #2c3e50;
            font-size: 1.5em;
            margin-bottom: 30px;
        }

        .flow-diagram {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .flow-step {
            background: white;
            border: 2px solid #007bff;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            color: #007bff;
            font-weight: bold;
            position: relative;
            transition: all 0.3s ease;
        }

        .flow-step:hover {
            background: #007bff;
            color: white;
            transform: scale(1.1);
        }

        .flow-arrow {
            font-size: 2em;
            color: #007bff;
        }

        .flow-labels {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .flow-label {
            text-align: center;
            flex: 1;
            min-width: 150px;
        }

        .flow-label h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .flow-label p {
            color: #6c757d;
            font-size: 0.9em;
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

        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }
            
            .flow-diagram {
                flex-direction: column;
            }
            
            .flow-arrow {
                transform: rotate(90deg);
            }
            
            .flow-labels {
                flex-direction: column;
            }
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
            <h1>🍽️ Diseño del Menú Principal</h1>
            <p>Portal Turístico de Ecuador - Navegación y Funcionalidades</p>
        </div>

        <div class="controls">
            <a href="index.php" class="btn btn-primary">🏠 Volver al Portal</a>
            <a href="database_design.php" class="btn btn-success">🗄️ Ver Base de Datos</a>
            <a href="admin.php" class="btn btn-primary">⚙️ Panel Admin</a>
        </div>

        <div class="stats-section">
            <h3>📊 Estadísticas del Menú</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Secciones Principales</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Funcionalidades</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Responsive</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Niveles de Usuario</div>
                </div>
            </div>
        </div>

        <div class="menu-showcase">
            <!-- SECCIÓN 1: AUTENTICACIÓN -->
            <div class="menu-section">
                <h2>🔐 Autenticación y Usuarios</h2>
                <div class="menu-grid">
                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-user-plus"></i>
                            <div class="menu-item-title">Registro de Usuarios</div>
                            <div class="menu-item-subtitle">Nuevos usuarios</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Formulario completo de registro con validación en tiempo real y verificación de datos.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Validación de email único</li>
                                <li><i class="fas fa-check"></i> Contraseña segura</li>
                                <li><i class="fas fa-check"></i> Términos y condiciones</li>
                                <li><i class="fas fa-check"></i> Verificación de datos</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-active">Activo</span>
                                <a href="index.php" class="menu-btn menu-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-sign-in-alt"></i>
                            <div class="menu-item-title">Inicio de Sesión</div>
                            <div class="menu-item-subtitle">Acceso al sistema</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Sistema de login con autenticación segura y protección contra ataques.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Login de usuarios</li>
                                <li><i class="fas fa-check"></i> Login de administradores</li>
                                <li><i class="fas fa-check"></i> Bloqueo por intentos</li>
                                <li><i class="fas fa-check"></i> Sesiones seguras</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-active">Activo</span>
                                <a href="index.php" class="menu-btn menu-btn-primary">Probar</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-user-cog"></i>
                            <div class="menu-item-title">Gestión de Perfiles</div>
                            <div class="menu-item-subtitle">Configuración personal</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Panel para editar información personal, preferencias y configuración de cuenta.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Editar perfil</li>
                                <li><i class="fas fa-check"></i> Cambiar contraseña</li>
                                <li><i class="fas fa-check"></i> Preferencias</li>
                                <li><i class="fas fa-check"></i> Accesibilidad</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-development">En Desarrollo</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DESTINOS Y ALOJAMIENTOS -->
            <div class="menu-section">
                <h2>🗺️ Destinos y Alojamientos</h2>
                <div class="menu-grid">
                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-map-marked-alt"></i>
                            <div class="menu-item-title">Explorar Destinos</div>
                            <div class="menu-item-subtitle">Descubre Ecuador</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Catálogo completo de destinos turísticos con información detallada y galería de imágenes.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Galápagos</li>
                                <li><i class="fas fa-check"></i> Quito Colonial</li>
                                <li><i class="fas fa-check"></i> Baños de Agua Santa</li>
                                <li><i class="fas fa-check"></i> Manta y Salinas</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-active">Activo</span>
                                <a href="rutas.php" class="menu-btn menu-btn-primary">Explorar</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-bed"></i>
                            <div class="menu-item-title">Alojamientos</div>
                            <div class="menu-item-subtitle">Hoteles y más</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Reserva de hoteles, hostales, cabañas y resorts con filtros avanzados.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Hoteles de lujo</li>
                                <li><i class="fas fa-check"></i> Hostales económicos</li>
                                <li><i class="fas fa-check"></i> Cabañas rústicas</li>
                                <li><i class="fas fa-check"></i> Resorts todo incluido</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-active">Activo</span>
                                <a href="alojamientos.php" class="menu-btn menu-btn-primary">Reservar</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-calendar-check"></i>
                            <div class="menu-item-title">Sistema de Reservas</div>
                            <div class="menu-item-subtitle">Booking online</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Sistema completo de reservas con confirmación, pagos y gestión de fechas.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Reserva online</li>
                                <li><i class="fas fa-check"></i> Confirmación automática</li>
                                <li><i class="fas fa-check"></i> Gestión de fechas</li>
                                <li><i class="fas fa-check"></i> Historial de reservas</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-development">En Desarrollo</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: ACTIVIDADES Y EXPERIENCIAS -->
            <div class="menu-section">
                <h2>🎯 Actividades y Experiencias</h2>
                <div class="menu-grid">
                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-mountain"></i>
                            <div class="menu-item-title">Actividades de Aventura</div>
                            <div class="menu-item-subtitle">Turismo activo</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Experiencias de aventura como rafting, escalada, senderismo y más.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Rafting en Baños</li>
                                <li><i class="fas fa-check"></i> Senderismo en montañas</li>
                                <li><i class="fas fa-check"></i> Escalada de roca</li>
                                <li><i class="fas fa-check"></i> Ciclismo de montaña</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-planned">Planificado</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-utensils"></i>
                            <div class="menu-item-title">Experiencias Gastronómicas</div>
                            <div class="menu-item-subtitle">Sabores de Ecuador</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Tours gastronómicos, clases de cocina y experiencias culinarias locales.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Clases de ceviche</li>
                                <li><i class="fas fa-check"></i> Tours de chocolate</li>
                                <li><i class="fas fa-check"></i> Degustación de vinos</li>
                                <li><i class="fas fa-check"></i> Mercados locales</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-planned">Planificado</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-camera"></i>
                            <div class="menu-item-title">Tours Culturales</div>
                            <div class="menu-item-subtitle">Historia y tradición</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Recorridos por sitios históricos, museos y experiencias culturales auténticas.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Centro histórico de Quito</li>
                                <li><i class="fas fa-check"></i> Museos y galerías</li>
                                <li><i class="fas fa-check"></i> Artesanías locales</li>
                                <li><i class="fas fa-check"></i> Comunidades indígenas</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-planned">Planificado</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 4: ADMINISTRACIÓN -->
            <div class="menu-section">
                <h2>⚙️ Panel de Administración</h2>
                <div class="menu-grid">
                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-users-cog"></i>
                            <div class="menu-item-title">Gestión de Usuarios</div>
                            <div class="menu-item-subtitle">Control de usuarios</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Panel completo para administrar usuarios, roles y permisos del sistema.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Lista de usuarios</li>
                                <li><i class="fas fa-check"></i> Editar perfiles</li>
                                <li><i class="fas fa-check"></i> Gestionar roles</li>
                                <li><i class="fas fa-check"></i> Bloquear usuarios</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-active">Activo</span>
                                <a href="admin.php" class="menu-btn menu-btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-chart-line"></i>
                            <div class="menu-item-title">Estadísticas y Reportes</div>
                            <div class="menu-item-subtitle">Análisis de datos</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Dashboard con estadísticas, reportes y métricas del portal turístico.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Usuarios registrados</li>
                                <li><i class="fas fa-check"></i> Reservas realizadas</li>
                                <li><i class="fas fa-check"></i> Destinos populares</li>
                                <li><i class="fas fa-check"></i> Ingresos generados</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-development">En Desarrollo</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <div class="menu-item-header">
                            <i class="fas fa-cogs"></i>
                            <div class="menu-item-title">Configuración del Sistema</div>
                            <div class="menu-item-subtitle">Ajustes generales</div>
                        </div>
                        <div class="menu-item-body">
                            <div class="menu-description">
                                Configuración de parámetros del sistema, notificaciones y mantenimiento.
                            </div>
                            <ul class="menu-features">
                                <li><i class="fas fa-check"></i> Configuración general</li>
                                <li><i class="fas fa-check"></i> Gestión de notificaciones</li>
                                <li><i class="fas fa-check"></i> Mantenimiento del sistema</li>
                                <li><i class="fas fa-check"></i> Respaldos automáticos</li>
                            </ul>
                            <div class="menu-actions">
                                <span class="menu-status status-development">En Desarrollo</span>
                                <a href="#" class="menu-btn menu-btn-secondary">Próximamente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FLUJO DE NAVEGACIÓN -->
        <div class="navigation-flow">
            <div class="flow-title">🔄 Flujo de Navegación del Usuario</div>
            <div class="flow-diagram">
                <div class="flow-step">1</div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">2</div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">3</div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">4</div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">5</div>
            </div>
            <div class="flow-labels">
                <div class="flow-label">
                    <h4>🏠 Página Principal</h4>
                    <p>Portal de entrada con login y registro</p>
                </div>
                <div class="flow-label">
                    <h4>🗺️ Explorar Destinos</h4>
                    <p>Catálogo de lugares turísticos</p>
                </div>
                <div class="flow-label">
                    <h4>🏨 Seleccionar Alojamiento</h4>
                    <p>Elegir hotel o alojamiento</p>
                </div>
                <div class="flow-label">
                    <h4>📅 Hacer Reserva</h4>
                    <p>Proceso de booking online</p>
                </div>
                <div class="flow-label">
                    <h4>✅ Confirmación</h4>
                    <p>Reserva confirmada y pagada</p>
                </div>
            </div>
        </div>

        <!-- CAPTURA DE PANTALLA -->
        <div class="screenshot-section">
            <h3>📸 Captura del Menú Principal</h3>
            <div class="screenshot-container">
                <div class="screenshot-placeholder">
                    <div style="text-align: center;">
                        <i class="fas fa-camera" style="font-size: 3em; margin-bottom: 20px; color: #007bff;"></i>
                        <h4>Menú Principal del Portal Turístico</h4>
                        <p>Aquí se mostraría la captura de pantalla del menú principal</p>
                        <p style="font-size: 0.9em; margin-top: 10px;">
                            <strong>URL:</strong> http://localhost/registro/registro/<br>
                            <strong>Estado:</strong> Funcionando correctamente
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
        const menuShowcase = document.querySelector('.menu-showcase');

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
            menuShowcase.style.transform = `scale(${currentZoom})`;
            menuShowcase.style.transformOrigin = 'top left';
        }

        // Animación de entrada para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.menu-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.6s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Animación para los pasos del flujo
            const steps = document.querySelectorAll('.flow-step');
            steps.forEach((step, index) => {
                step.style.opacity = '0';
                step.style.transform = 'scale(0.5)';
                setTimeout(() => {
                    step.style.transition = 'all 0.5s ease';
                    step.style.opacity = '1';
                    step.style.transform = 'scale(1)';
                }, 1000 + (index * 200));
            });
        });

        // Efecto hover mejorado
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Efecto para los pasos del flujo
        document.querySelectorAll('.flow-step').forEach(step => {
            step.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.2)';
                this.style.background = '#007bff';
                this.style.color = 'white';
            });
            
            step.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.background = 'white';
                this.style.color = '#007bff';
            });
        });
    </script>
</body>
</html>
