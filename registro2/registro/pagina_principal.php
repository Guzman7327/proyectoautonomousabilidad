<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Turístico Ecuador - Entrada Principal</title>
    <meta name="description" content="Portal oficial de turismo de Ecuador. Sistema integrado de gestión turística con módulos avanzados.">
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

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 30px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.8em;
            font-weight: bold;
            color: #2c3e50;
            text-decoration: none;
        }

        .logo i {
            color: #e74c3c;
            font-size: 1.2em;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 10px 20px;
            border-radius: 25px;
            position: relative;
        }

        .nav-menu a:hover {
            color: #e74c3c;
            background: rgba(231, 76, 60, 0.1);
            transform: translateY(-2px);
        }

        .btn-access {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white !important;
            padding: 12px 25px !important;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            transition: all 0.3s ease;
        }

        .btn-access:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1583416750470-965b2707b355?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(52, 152, 219, 0.8), rgba(155, 89, 182, 0.8));
            z-index: 1;
        }

        .hero-content {
            max-width: 800px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }

        .hero h1 {
            font-size: 4em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero p {
            font-size: 1.3em;
            margin-bottom: 30px;
            line-height: 1.8;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: white;
            color: #2c3e50;
            transform: translateY(-3px);
        }

        /* System Access Section */
        .system-access {
            padding: 100px 0;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            color: #2c3e50;
        }

        .section-title h2 {
            font-size: 3em;
            margin-bottom: 20px;
            position: relative;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border-radius: 2px;
        }

        .access-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .access-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .access-card:hover {
            transform: translateY(-5px);
            border-color: #e74c3c;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .access-card i {
            font-size: 3em;
            margin-bottom: 20px;
            color: #e74c3c;
        }

        .access-card h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .access-card p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .access-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .access-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .main-access-card {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
        }

        .main-access-card:hover {
            border-color: transparent;
            transform: translateY(-5px) scale(1.02);
        }

        .main-access-card .access-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            backdrop-filter: blur(10px);
        }

        .main-access-card .access-btn:hover {
            background: white;
            color: #2980b9;
        }

        /* Quick Stats */
        .quick-stats {
            padding: 80px 0;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .stat-item {
            text-align: center;
            padding: 30px 20px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .stat-number {
            font-size: 3em;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1em;
            color: #bdc3c7;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            margin-bottom: 20px;
            color: #e74c3c;
        }

        .footer-section p,
        .footer-section a {
            color: #bdc3c7;
            text-decoration: none;
            line-height: 1.8;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #e74c3c;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #34495e;
            color: #95a5a6;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero h1 {
                font-size: 2.5em;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .access-grid {
                grid-template-columns: 1fr;
            }

            .main-access-card {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="https://portal-turistico-ecuador.com/pagina_principal.php" class="logo">
                <i class="fas fa-mountain"></i>
                <span>Portal Turístico Ecuador</span>
            </a>
            <ul class="nav-menu">
                <li><a href="https://portal-turistico-ecuador.com/pagina_principal.php">Inicio</a></li>
                <li><a href="https://portal-turistico-ecuador.com/sistema_integrado.php">Sistema</a></li>
                <li><a href="https://portal-turistico-ecuador.com/dashboard.php">Dashboard</a></li>
                <li><a href="login.php" class="btn-access">
                    <i class="fas fa-sign-in-alt"></i> Acceder
                </a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>🇪🇨 Portal Turístico Ecuador</h1>
            <p>Sistema integrado de gestión turística. Desde reservas inteligentes hasta análisis avanzado, todo en una plataforma moderna y fácil de usar.</p>
            <div class="hero-buttons">
                <a href="https://portal-turistico-ecuador.com/sistema_integrado.php" class="btn btn-primary">
                    <i class="fas fa-cogs"></i> Sistema Integrado
                </a>
                <a href="https://portal-turistico-ecuador.com/dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- System Access Section -->
    <section class="system-access">
        <div class="container">
            <div class="section-title">
                <h2>Acceso al Sistema</h2>
                <p>Plataforma completa de gestión turística con módulos especializados</p>
            </div>
            <div class="access-grid">
                <!-- Main System Access -->
                <div class="access-card main-access-card">
                    <i class="fas fa-rocket"></i>
                    <h3>Sistema Integrado Completo</h3>
                    <p>Accede a todos los módulos desde una interfaz unificada. Panel de control centralizado con navegación intuitiva y funcionalidades avanzadas.</p>
                    <a href="https://portal-turistico-ecuador.com/sistema_integrado.php" class="access-btn">
                        <i class="fas fa-arrow-right"></i> Acceder al Sistema
                    </a>
                </div>

                <!-- Dashboard -->
                <div class="access-card">
                    <i class="fas fa-tachometer-alt"></i>
                    <h3>Dashboard Principal</h3>
                    <p>Panel de control con vista general de todas las operaciones, estadísticas en tiempo real y métricas de rendimiento.</p>
                    <a href="https://portal-turistico-ecuador.com/dashboard.php" class="access-btn">Acceder</a>
                </div>

                <!-- Reservas y Pagos -->
                <div class="access-card">
                    <i class="fas fa-calendar-check"></i>
                    <h3>Reservas y Pagos</h3>
                    <p>Sistema completo de reservas en tiempo real con procesamiento seguro de pagos y múltiples métodos.</p>
                    <a href="formulario_rf7.php" class="access-btn">Acceder</a>
                </div>

                <!-- Análisis y Reportes -->
                <div class="access-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Análisis y Reportes</h3>
                    <p>Dashboard avanzado con métricas, análisis de tendencias y reportes personalizables para la toma de decisiones.</p>
                    <a href="formulario_rf8.php" class="access-btn">Acceder</a>
                </div>

                <!-- Notificaciones -->
                <div class="access-card">
                    <i class="fas fa-bell"></i>
                    <h3>Notificaciones</h3>
                    <p>Sistema inteligente de comunicaciones y notificaciones push para mantener informados a usuarios y clientes.</p>
                    <a href="formulario_rf9.php" class="access-btn">Acceder</a>
                </div>

                <!-- Gestión de Contenido -->
                <div class="access-card">
                    <i class="fas fa-edit"></i>
                    <h3>Gestión de Contenido</h3>
                    <p>CMS avanzado con optimización SEO automática para gestionar todo el contenido del portal turístico.</p>
                    <a href="formulario_rf10.php" class="access-btn">Acceder</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="quick-stats">
        <div class="container">
            <div class="section-title">
                <h2 style="color: white;">Estadísticas del Sistema</h2>
                <p style="color: #bdc3c7;">Portal turístico completamente funcional</p>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-target="4">0</div>
                    <div class="stat-label">Módulos RF</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="28">0</div>
                    <div class="stat-label">Tablas BD</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="9">0</div>
                    <div class="stat-label">Usuarios</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="100">0</div>
                    <div class="stat-label">% Funcional</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Portal Turístico Ecuador</h3>
                    <p>Sistema integrado de gestión turística con tecnología avanzada para la industria del turismo en Ecuador.</p>
                </div>
                <div class="footer-section">
                    <h3>Sistema</h3>
                    <p><a href="https://portal-turistico-ecuador.com/sistema_integrado.php">Sistema Integrado</a></p>
                    <p><a href="https://portal-turistico-ecuador.com/dashboard.php">Dashboard</a></p>
                    <p><a href="admin.php">Administración</a></p>
                </div>
                <div class="footer-section">
                    <h3>Módulos</h3>
                    <p><a href="formulario_rf7.php">Reservas y Pagos</a></p>
                    <p><a href="formulario_rf8.php">Análisis y Reportes</a></p>
                    <p><a href="formulario_rf9.php">Notificaciones</a></p>
                    <p><a href="formulario_rf10.php">Gestión Contenido</a></p>
                </div>
                <div class="footer-section">
                    <h3>Acceso</h3>
                    <p><a href="login.php">Iniciar Sesión</a></p>
                    <p><a href="register.php">Registrarse</a></p>
                    <p><a href="index.html">Página Pública</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Portal Turístico Ecuador. Sistema integrado de gestión turística.</p>
            </div>
        </div>
    </footer>

    <script>
        // Animación de números en estadísticas
        function animateNumbers() {
            const numbers = document.querySelectorAll('.stat-number');
            numbers.forEach(number => {
                const target = parseInt(number.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    number.textContent = Math.floor(current);
                }, 20);
            });
        }

        // Scroll suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Intersection Observer para animaciones
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('quick-stats')) {
                        animateNumbers();
                    }
                }
            });
        }, observerOptions);

        // Observar sección de estadísticas
        observer.observe(document.querySelector('.quick-stats'));

        // Console info
        console.log('%c🇪🇨 Portal Turístico Ecuador - Página Principal', 
                    'color: #e74c3c; font-size: 18px; font-weight: bold;');
        console.log('%cSistema integrado completamente funcional', 
                    'color: #3498db; font-size: 12px;');
    </script>
</body>
</html>