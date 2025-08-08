<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Turístico Ecuador - Sistema Integrado</title>
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

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            text-align: center;
        }

        .hero-header {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 30px;
            padding: 50px 40px;
            margin-bottom: 40px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .hero-title {
            font-size: 3.5rem;
            color: #2e7d32;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #555;
            max-width: 800px;
            margin: 0 auto 40px;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .hero-btn {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        .hero-btn.secondary {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            color: #2e7d32;
            border: 2px solid #4caf50;
        }

        .hero-btn.secondary:hover {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4caf50, #2e7d32);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(76, 175, 80, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.5rem;
            color: #2e7d32;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            transform: scale(1.1);
        }

        .feature-title {
            font-size: 1.5rem;
            color: #2e7d32;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .feature-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .feature-list {
            list-style: none;
            text-align: left;
            margin-bottom: 25px;
        }

        .feature-list li {
            padding: 8px 0;
            color: #555;
            font-size: 0.95rem;
        }

        .feature-list li::before {
            content: '✓';
            color: #4caf50;
            font-weight: bold;
            margin-right: 10px;
        }

        .feature-btn {
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

        .feature-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .stats-title {
            font-size: 2rem;
            color: #2e7d32;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            border-radius: 15px;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 1rem;
            font-weight: 600;
        }

        .footer {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            border: 2px solid rgba(76, 175, 80, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .footer-text {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-link {
            color: #4caf50;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #2e7d32;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
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
    <div class="hero-container">
        <!-- Hero Section -->
        <div class="hero-header">
            <h1 class="hero-title">🏔️ Portal Turístico Ecuador</h1>
            <p class="hero-subtitle">Sistema Integrado de Gestión Turística</p>
            <p class="hero-description">
                Plataforma completa que integra todos los módulos desarrollados para la gestión turística.
                Desde reservas y pagos hasta análisis avanzado y gestión de contenido optimizado.
            </p>
            
            <div class="hero-actions">
                <a href="sistema_integrado.php" class="hero-btn">
                    <i class="fas fa-rocket"></i>
                    Acceder al Sistema
                </a>
                <a href="dashboard.php" class="hero-btn secondary">
                    <i class="fas fa-tachometer-alt"></i>
                    Ver Dashboard
                </a>
                <a href="index.php" class="hero-btn secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login/Registro
                </a>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="features-grid">
            <!-- RF7: Reservas y Pagos -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="feature-title">RF7: Sistema de Reservas y Pagos</h3>
                <p class="feature-description">
                    Gestión completa de reservas turísticas con múltiples métodos de pago integrados.
                </p>
                <ul class="feature-list">
                    <li>Reservas con múltiples destinos</li>
                    <li>Pagos seguros (tarjeta, PayPal, transferencia)</li>
                    <li>Historial de reservas detallado</li>
                    <li>Configuración de preferencias</li>
                </ul>
                <a href="formulario_rf7.php" class="feature-btn" target="_blank">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </a>
            </div>

            <!-- RF8: Análisis y Reportes -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">RF8: Sistema de Análisis y Reportes</h3>
                <p class="feature-description">
                    Dashboard avanzado con análisis predictivo y generación de reportes personalizados.
                </p>
                <ul class="feature-list">
                    <li>Dashboard en tiempo real</li>
                    <li>Análisis predictivo con IA</li>
                    <li>Reportes personalizables</li>
                    <li>Exportación múltiple (PDF, Excel, CSV)</li>
                </ul>
                <a href="formulario_rf8.php" class="feature-btn" target="_blank">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </a>
            </div>

            <!-- RF9: Notificaciones -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="feature-title">RF9: Sistema de Notificaciones</h3>
                <p class="feature-description">
                    Gestión integral de comunicaciones con plantillas y analytics de rendimiento.
                </p>
                <ul class="feature-list">
                    <li>Múltiples canales (Email, SMS, Push)</li>
                    <li>Plantillas personalizables</li>
                    <li>Programación automática</li>
                    <li>Analytics de rendimiento</li>
                </ul>
                <a href="formulario_rf9.php" class="feature-btn" target="_blank">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </a>
            </div>

            <!-- RF10: Gestión de Contenido -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 class="feature-title">RF10: Gestión de Contenido y SEO</h3>
                <p class="feature-description">
                    Editor avanzado de contenido con optimización SEO automática y biblioteca multimedia.
                </p>
                <ul class="feature-list">
                    <li>Editor de contenido avanzado</li>
                    <li>Optimización SEO automática</li>
                    <li>Biblioteca multimedia</li>
                    <li>Analytics de contenido</li>
                </ul>
                <a href="formulario_rf10.php" class="feature-btn" target="_blank">
                    <i class="fas fa-arrow-right"></i>
                    Acceder al Módulo
                </a>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <h2 class="stats-title">📊 Estadísticas del Sistema</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Módulos RF Completados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Evaluaciones Completadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10</div>
                    <div class="stat-label">Heurísticas Evaluadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">ISO 9241-11</div>
                    <div class="stat-label">Estándar Aplicado</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                Sistema desarrollado con estándares de usabilidad ISO 9241-11 y heurísticas de Nielsen
            </p>
            <div class="footer-links">
                <a href="evaluacion_rf7.html" class="footer-link" target="_blank">Evaluación RF7</a>
                <a href="evaluacion_rf8.html" class="footer-link" target="_blank">Evaluación RF8</a>
                <a href="evaluacion_rf9.html" class="footer-link" target="_blank">Evaluación RF9</a>
                <a href="evaluacion_rf10.html" class="footer-link" target="_blank">Evaluación RF10</a>
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
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Animación para las estadísticas
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach((stat, index) => {
                const finalValue = stat.textContent;
                stat.textContent = '0';
                
                setTimeout(() => {
                    animateNumber(stat, finalValue);
                }, 1000 + (index * 200));
            });
        });

        function animateNumber(element, finalValue) {
            const duration = 2000;
            const start = 0;
            const increment = finalValue / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= finalValue) {
                    element.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }

        // Efecto hover mejorado para las tarjetas
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Efecto hover para los botones
        document.querySelectorAll('.hero-btn, .feature-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.05)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
