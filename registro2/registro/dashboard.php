<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Turístico Ecuador - Dashboard</title>
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

        /* Header Navigation */
        .top-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2e7d32;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.3em;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-links a:hover {
            background: rgba(46, 125, 50, 0.1);
            color: #2e7d32;
        }

        .nav-links a.active {
            background: #2e7d32;
            color: white;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dashboard-title {
            font-size: 2.5rem;
            color: #2e7d32;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .dashboard-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .user-details h3 {
            color: #2e7d32;
            font-size: 1.3rem;
        }

        .user-details p {
            color: #666;
            font-size: 0.9rem;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .module-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4caf50, #2e7d32);
        }

        .module-card:hover {
            transform: translateY(-5px);
            border-color: rgba(76, 175, 80, 0.4);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .module-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #2e7d32;
            transition: all 0.3s ease;
        }

        .module-card:hover .module-icon {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            transform: scale(1.1);
        }

        .module-title {
            font-size: 1.5rem;
            color: #2e7d32;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .module-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .module-features {
            list-style: none;
            margin-bottom: 25px;
            text-align: left;
        }

        .module-features li {
            padding: 5px 0;
            color: #555;
            font-size: 0.9rem;
        }

        .module-features li::before {
            content: '✓';
            color: #4caf50;
            font-weight: bold;
            margin-right: 8px;
        }

        .module-btn {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .module-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-title {
            font-size: 1.8rem;
            color: #2e7d32;
            margin-bottom: 20px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .navigation-bar {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-btn {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .logout-btn {
            background: linear-gradient(135deg, #f44336, #d32f2f);
        }

        .logout-btn:hover {
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 10px;
            }

            .dashboard-title {
                font-size: 2rem;
            }

            .modules-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .nav-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        .accessibility-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .accessibility-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .accessibility-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="top-nav">
        <div class="nav-container">
            <a href="https://portal-turistico-ecuador.com/pagina_principal.php" class="nav-logo">
                <i class="fas fa-mountain"></i>
                Portal Turístico Ecuador
            </a>
            <div class="nav-links">
                <a href="https://portal-turistico-ecuador.com/pagina_principal.php">Inicio</a>
                <a href="https://portal-turistico-ecuador.com/dashboard.php" class="active">Dashboard</a>
                <a href="https://portal-turistico-ecuador.com/sistema_integrado.php">Sistema Integrado</a>
                <a href="login.php">Login</a>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">🏔️ Portal Turístico Ecuador</h1>
            <p class="dashboard-subtitle">Sistema Integrado de Gestión Turística</p>
            
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <h3>Bienvenido, Usuario</h3>
                    <p>Panel de Administración</p>
                </div>
            </div>
        </div>

        <!-- Barra de Navegación -->
        <div class="navigation-bar">
            <div class="nav-buttons">
                <a href="index.php" class="nav-btn">
                    <i class="fas fa-home"></i>
                    Inicio
                </a>
                <a href="admin.php" class="nav-btn">
                    <i class="fas fa-users-cog"></i>
                    Administración
                </a>
                <a href="accessibility.php" class="nav-btn">
                    <i class="fas fa-universal-access"></i>
                    Accesibilidad
                </a>
                <a href="logout.php" class="nav-btn logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="stats-section">
            <h2 class="stats-title">📊 Estadísticas del Sistema</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">1,247</div>
                    <div class="stat-label">Reservas Activas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">89%</div>
                    <div class="stat-label">Satisfacción Usuarios</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">156</div>
                    <div class="stat-label">Destinos Disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Soporte Disponible</div>
                </div>
            </div>
        </div>

        <!-- Módulos del Sistema -->
        <div class="modules-grid">
            <!-- RF7: Sistema de Reservas y Pagos -->
            <div class="module-card" onclick="window.location.href='formulario_rf7.php'">
                <div class="module-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="module-title">RF7: Sistema de Reservas y Pagos</h3>
                <p class="module-description">
                    Gestión completa de reservas turísticas con múltiples métodos de pago, 
                    historial de transacciones y configuración personalizada.
                </p>
                <ul class="module-features">
                    <li>Reservas con múltiples destinos</li>
                    <li>Pagos seguros (tarjeta, PayPal, transferencia)</li>
                    <li>Historial de reservas</li>
                    <li>Configuración de preferencias</li>
                </ul>
                <button class="module-btn">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </button>
            </div>

            <!-- RF8: Sistema de Análisis y Reportes -->
            <div class="module-card" onclick="window.location.href='formulario_rf8.php'">
                <div class="module-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="module-title">RF8: Sistema de Análisis y Reportes</h3>
                <p class="module-description">
                    Dashboard avanzado con análisis predictivo, generación de reportes 
                    y exportación de datos en múltiples formatos.
                </p>
                <ul class="module-features">
                    <li>Dashboard en tiempo real</li>
                    <li>Análisis predictivo con IA</li>
                    <li>Reportes personalizables</li>
                    <li>Exportación múltiple (PDF, Excel, CSV)</li>
                </ul>
                <button class="module-btn">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </button>
            </div>

            <!-- RF9: Sistema de Notificaciones -->
            <div class="module-card" onclick="window.location.href='formulario_rf9.php'">
                <div class="module-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="module-title">RF9: Sistema de Notificaciones</h3>
                <p class="module-description">
                    Gestión integral de comunicaciones con plantillas personalizables, 
                    programación automática y analytics de envíos.
                </p>
                <ul class="module-features">
                    <li>Múltiples canales (Email, SMS, Push)</li>
                    <li>Plantillas personalizables</li>
                    <li>Programación automática</li>
                    <li>Analytics de rendimiento</li>
                </ul>
                <button class="module-btn">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </button>
            </div>

            <!-- RF10: Sistema de Gestión de Contenido -->
            <div class="module-card" onclick="window.location.href='formulario_rf10.php'">
                <div class="module-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 class="module-title">RF10: Gestión de Contenido y SEO</h3>
                <p class="module-description">
                    Editor avanzado de contenido con optimización SEO automática, 
                    biblioteca multimedia y analytics de contenido.
                </p>
                <ul class="module-features">
                    <li>Editor de contenido avanzado</li>
                    <li>Optimización SEO automática</li>
                    <li>Biblioteca multimedia</li>
                    <li>Analytics de contenido</li>
                </ul>
                <button class="module-btn">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </button>
            </div>
        </div>
    </div>

    <!-- Botón de accesibilidad flotante -->
    <div class="accessibility-float">
        <button class="accessibility-btn" onclick="location.href='accessibility.php'" title="Configuración de Accesibilidad">
            <i class="fas fa-universal-access"></i>
        </button>
    </div>

    <script>
        // Animación de entrada para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.module-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Efecto hover mejorado
        document.querySelectorAll('.module-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
