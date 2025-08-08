<?php
session_start();

// Verificar si el usuario está logueado (opcional)
$usuario_logueado = isset($_SESSION['usuario_id']) ? true : false;
$usuario_nombre = $usuario_logueado ? $_SESSION['usuario_nombre'] ?? 'Usuario' : 'Invitado';

// Permitir acceso sin login para demostración
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Integrado - Portal Turístico Ecuador</title>
    <meta name="description" content="Sistema integrado completo de gestión turística con todos los módulos unificados.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            min-height: 100vh;
            color: #333;
        }

        .system-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            border-right: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(76, 175, 80, 0.2);
        }

        .sidebar-title {
            font-size: 1.5rem;
            color: #2e7d32;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sidebar-subtitle {
            font-size: 0.9rem;
            color: #666;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section {
            margin-bottom: 30px;
        }

        .nav-section-title {
            padding: 0 20px 10px;
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-item {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
        }

        .nav-item:hover {
            background: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
            border-left-color: #4caf50;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            color: #2e7d32;
            border-left-color: #2e7d32;
            font-weight: bold;
        }

        .nav-item i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }

        .content-header {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .content-title {
            font-size: 2rem;
            color: #2e7d32;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .content-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 20px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #666;
        }

        .breadcrumb a {
            color: #4caf50;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .content-area {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 30px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            min-height: 600px;
        }

        .welcome-section {
            text-align: center;
            padding: 60px 20px;
        }

        .welcome-icon {
            font-size: 4rem;
            color: #4caf50;
            margin-bottom: 20px;
        }

        .welcome-title {
            font-size: 2.5rem;
            color: #2e7d32;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .welcome-text {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .action-card {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(76, 175, 80, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(76, 175, 80, 0.2);
        }

        .action-icon {
            font-size: 2.5rem;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        .action-title {
            font-size: 1.2rem;
            color: #2e7d32;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .action-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #4caf50;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
            }
        }

        .mobile-menu-toggle {
            display: none;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e8f5e8;
            border-top: 4px solid #4caf50;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="system-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">🏔️ Portal Turístico</div>
                <div class="sidebar-subtitle">Sistema Integrado</div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Navegación Principal</div>
                    <a href="https://portal-turistico-ecuador.com/pagina_principal.php" class="nav-item">
                        <i class="fas fa-home"></i>
                        Página Principal
                    </a>
                    <a href="#" class="nav-item active" onclick="showContent('welcome')">
                        <i class="fas fa-cogs"></i>
                        Sistema Integrado
                    </a>
                    <a href="https://portal-turistico-ecuador.com/dashboard.php" class="nav-item">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                    <a href="login.php" class="nav-item">
                        <i class="fas fa-sign-in-alt"></i>
                        Login/Registro
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Módulos RF</div>
                    <a href="formulario_rf7.php" class="nav-item" target="_blank">
                        <i class="fas fa-calendar-check"></i>
                        RF7: Reservas y Pagos
                    </a>
                    <a href="formulario_rf8.php" class="nav-item" target="_blank">
                        <i class="fas fa-chart-line"></i>
                        RF8: Análisis y Reportes
                    </a>
                    <a href="formulario_rf9.php" class="nav-item" target="_blank">
                        <i class="fas fa-bell"></i>
                        RF9: Notificaciones
                    </a>
                    <a href="formulario_rf10.php" class="nav-item" target="_blank">
                        <i class="fas fa-edit"></i>
                        RF10: Gestión de Contenido
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Evaluaciones</div>
                    <a href="evaluacion_rf7.html" class="nav-item" target="_blank">
                        <i class="fas fa-clipboard-check"></i>
                        Evaluación RF7
                    </a>
                    <a href="evaluacion_rf8.html" class="nav-item" target="_blank">
                        <i class="fas fa-clipboard-check"></i>
                        Evaluación RF8
                    </a>
                    <a href="evaluacion_rf9.html" class="nav-item" target="_blank">
                        <i class="fas fa-clipboard-check"></i>
                        Evaluación RF9
                    </a>
                    <a href="evaluacion_rf10.html" class="nav-item" target="_blank">
                        <i class="fas fa-clipboard-check"></i>
                        Evaluación RF10
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Administración</div>
                    <a href="admin.php" class="nav-item">
                        <i class="fas fa-users-cog"></i>
                        Gestión de Usuarios
                    </a>
                    <a href="accessibility.php" class="nav-item">
                        <i class="fas fa-universal-access"></i>
                        Accesibilidad
                    </a>
                    <a href="logout.php" class="nav-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <div class="breadcrumb">
                    <a href="https://portal-turistico-ecuador.com/pagina_principal.php">Inicio</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="https://portal-turistico-ecuador.com/dashboard.php">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Sistema Integrado</span>
                </div>
                <h1 class="content-title">Sistema Integrado de Gestión Turística</h1>
                <p class="content-subtitle">Accede a todos los módulos y funcionalidades del portal turístico</p>
            </div>

            <div class="content-area">
                <div class="welcome-section">
                    <div class="welcome-icon">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h2 class="welcome-title">¡Bienvenido al Portal Turístico Ecuador!</h2>
                    <p class="welcome-text">
                        Sistema completo de gestión turística que integra todos los módulos desarrollados.
                        Desde reservas y pagos hasta análisis avanzado y gestión de contenido.
                    </p>

                    <div class="quick-actions">
                        <div class="action-card" onclick="openModule('rf7')">
                            <div class="action-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="action-title">Reservas y Pagos</div>
                            <div class="action-description">
                                Gestiona reservas turísticas con múltiples métodos de pago
                            </div>
                        </div>

                        <div class="action-card" onclick="openModule('rf8')">
                            <div class="action-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="action-title">Análisis y Reportes</div>
                            <div class="action-description">
                                Dashboard con análisis predictivo y reportes avanzados
                            </div>
                        </div>

                        <div class="action-card" onclick="openModule('rf9')">
                            <div class="action-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="action-title">Notificaciones</div>
                            <div class="action-description">
                                Sistema de comunicaciones con plantillas y analytics
                            </div>
                        </div>

                        <div class="action-card" onclick="openModule('rf10')">
                            <div class="action-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-title">Gestión de Contenido</div>
                            <div class="action-description">
                                Editor avanzado con optimización SEO automática
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Funciones de navegación
        function showContent(contentType) {
            // Aquí se puede implementar la lógica para mostrar diferentes contenidos
            console.log('Mostrando contenido:', contentType);
        }

        function openModule(module) {
            showLoading();
            
            const moduleUrls = {
                'rf7': 'formulario_rf7.php',
                'rf8': 'formulario_rf8.php',
                'rf9': 'formulario_rf9.php',
                'rf10': 'formulario_rf10.php'
            };

            setTimeout(() => {
                window.open(moduleUrls[module], '_blank');
                hideLoading();
            }, 500);
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }

        // Cerrar sidebar en móviles al hacer clic fuera
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Efecto hover para las tarjetas de acción
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
