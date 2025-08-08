<?php
session_start();
require_once "connect.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Verificar si la sesión es válida
if (!isSessionValid()) {
    logout();
    header("Location: index.php");
    exit;
}

// Obtener información del usuario
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];
$userRole = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Turístico - Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            padding: 20px 0;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-name {
            font-weight: 600;
        }

        .logout-btn {
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

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .main-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-section {
            background: white;
            border-radius: 15px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-title {
            color: #2e7d32;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-subtitle {
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: rgba(76, 175, 80, 0.4);
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            color: #4caf50;
            margin-bottom: 20px;
        }

        .feature-title {
            color: #2e7d32;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .feature-description {
            color: #6c757d;
            line-height: 1.6;
        }

        .admin-link {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .admin-link:hover {
            background: linear-gradient(135deg, #45a049 0%, #2e7d32 100%);
            transform: translateY(-2px);
        }

        .stats-section {
            background: white;
            border-radius: 15px;
            border: 2px solid rgba(76, 175, 80, 0.2);
            padding: 30px;
            margin-top: 30px;
        }

        .stats-title {
            color: #2e7d32;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #4caf50;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #4caf50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .user-info {
                flex-direction: column;
                gap: 10px;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Botón de accesibilidad flotante */
        .accessibility-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .accessibility-btn {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .accessibility-btn:hover {
            transform: scale(1.1);
        }

        .accessibility-btn:focus {
            outline: 3px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }

        /* Responsive para botón de accesibilidad */
        @media (max-width: 768px) {
            .accessibility-float {
                bottom: 20px;
                right: 20px;
            }
            
            .accessibility-btn {
                width: 50px;
                height: 50px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-globe-americas"></i>
                Portal Turístico E.C
            </div>
            <div class="user-info">
                <div class="user-name">
                    <i class="fas fa-user"></i>
                    Bienvenido, <?= htmlspecialchars($userName) ?>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="welcome-section">
            <h1 class="welcome-title">¡Bienvenido al Portal Turístico!</h1>
            <p class="welcome-subtitle">
                Descubre los mejores destinos turísticos de Ecuador y planifica tu próxima aventura
            </p>
            
            <?php if ($userRole === 'admin'): ?>
                <a href="admin.php" class="admin-link">
                    <i class="fas fa-cog"></i>
                    Panel de Administración
                </a>
            <?php endif; ?>
        </section>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="feature-title">Destinos Turísticos</h3>
                <p class="feature-description">
                    Explora los destinos más populares de Ecuador, desde las playas del Pacífico hasta los Andes y la Amazonía.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hotel"></i>
                </div>
                <h3 class="feature-title">Alojamientos</h3>
                <p class="feature-description">
                    Encuentra los mejores hoteles, hostales y resorts para tu estadía en cualquier parte del país.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-route"></i>
                </div>
                <h3 class="feature-title">Rutas Turísticas</h3>
                <p class="feature-description">
                    Descubre rutas personalizadas y recomendaciones de viaje para aprovechar al máximo tu experiencia.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="feature-title">Reservas</h3>
                <p class="feature-description">
                    Reserva tus tours, actividades y experiencias turísticas de manera fácil y segura.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="feature-title">Reseñas</h3>
                <p class="feature-description">
                    Lee y comparte experiencias con otros viajeros para tomar las mejores decisiones.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3 class="feature-title">Información</h3>
                <p class="feature-description">
                    Accede a información detallada sobre clima, transporte, cultura y más para cada destino.
                </p>
            </div>
        </div>

        <section class="stats-section">
            <h2 class="stats-title">Estadísticas del Portal</h2>
            <div class="stats-grid">
                <?php
                // Obtener estadísticas básicas
                $userStats = getUserStats();
                ?>
                <div class="stat-item">
                    <div class="stat-number"><?= $userStats['total_users'] ?></div>
                    <div class="stat-label">Usuarios Registrados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $userStats['users_today'] ?></div>
                    <div class="stat-label">Nuevos Hoy</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $userStats['users_month'] ?></div>
                    <div class="stat-label">Nuevos Este Mes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Soporte Disponible</div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Botón de accesibilidad flotante -->
    <div class="accessibility-float">
        <button class="accessibility-btn" onclick="location.href='accessibility.php'" title="Configuración de Accesibilidad">
            <i class="fas fa-universal-access"></i>
        </button>
    </div>
</body>
</html>
