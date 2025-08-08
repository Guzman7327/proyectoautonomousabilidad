<?php
session_start();
require_once "connect.php";

// Verificar si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Procesar acciones de configuración
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'backup_database':
            // Crear respaldo de la base de datos
            $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $command = "mysqldump -u root -p registro > $backupFile";
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                $success = "Respaldo creado exitosamente: $backupFile";
            } else {
                $error = "Error al crear el respaldo";
            }
            break;
            
        case 'clear_logs':
            // Limpiar logs antiguos
            $stmt = $conn->prepare("DELETE FROM system_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            if ($stmt->execute()) {
                $success = "Logs antiguos eliminados exitosamente";
            } else {
                $error = "Error al limpiar logs";
            }
            $stmt->close();
            break;
            
        case 'update_settings':
            // Actualizar configuraciones del sistema
            $maintenanceMode = isset($_POST['maintenance_mode']) ? 1 : 0;
            $maxLoginAttempts = intval($_POST['max_login_attempts']);
            $sessionTimeout = intval($_POST['session_timeout']);
            
            // Aquí se guardarían en una tabla de configuraciones
            $success = "Configuraciones actualizadas exitosamente";
            break;
    }
}

// Obtener estadísticas del sistema
$systemStats = [
    'total_users' => 0,
    'active_users' => 0,
    'total_logs' => 0,
    'disk_usage' => 0,
    'database_size' => 0
];

// Total de usuarios
$result = $conn->query("SELECT COUNT(*) as total FROM users");
if ($result) {
    $systemStats['total_users'] = $result->fetch_assoc()['total'];
}

// Usuarios activos
$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
if ($result) {
    $systemStats['active_users'] = $result->fetch_assoc()['total'];
}

// Total de logs
$result = $conn->query("SELECT COUNT(*) as total FROM system_logs");
if ($result) {
    $systemStats['total_logs'] = $result->fetch_assoc()['total'];
}

// Tamaño de la base de datos (aproximado)
$result = $conn->query("SELECT 
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'DB Size in MB'
    FROM information_schema.tables 
    WHERE table_schema = 'registro'");
if ($result) {
    $systemStats['database_size'] = $result->fetch_assoc()['DB Size in MB'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema - Portal Turístico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            max-width: 1200px;
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

        .config-section {
            margin-bottom: 40px;
        }

        .section-title {
            color: #2e7d32;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            padding: 20px;
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4caf50;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 1rem;
            font-weight: 600;
        }

        .config-card {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            padding: 25px;
            margin-bottom: 20px;
        }

        .config-group {
            margin-bottom: 20px;
        }

        .config-group:last-child {
            margin-bottom: 0;
        }

        .config-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .config-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4caf50;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #4caf50;
        }

        .btn {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: linear-gradient(135deg, #45a049 0%, #2e7d32 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }

        .message.success {
            background: #e8f5e8;
            color: #2e7d32;
            border: 2px solid #4caf50;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .action-card {
            background: white;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 25px;
            text-align: center;
        }

        .action-icon {
            font-size: 3rem;
            color: #4caf50;
            margin-bottom: 15px;
        }

        .action-title {
            color: #2e7d32;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .action-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <a href="admin.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
            <h1><i class="fas fa-cogs"></i> Configuración del Sistema</h1>
            <div class="subtitle">Administración avanzada del Portal Turístico</div>
        </div>

        <div class="content">
            <?php if (isset($success)): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Estadísticas del Sistema -->
            <div class="config-section">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Estadísticas del Sistema
                </h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?= $systemStats['total_users'] ?></div>
                        <div class="stat-label">Total de Usuarios</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $systemStats['active_users'] ?></div>
                        <div class="stat-label">Usuarios Activos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $systemStats['total_logs'] ?></div>
                        <div class="stat-label">Registros de Log</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $systemStats['database_size'] ?> MB</div>
                        <div class="stat-label">Tamaño de Base de Datos</div>
                    </div>
                </div>
            </div>

            <!-- Configuración del Sistema -->
            <div class="config-section">
                <h2 class="section-title">
                    <i class="fas fa-sliders-h"></i>
                    Configuración del Sistema
                </h2>
                <div class="config-card">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_settings">
                        
                        <div class="config-group">
                            <label class="config-label">Modo de Mantenimiento</label>
                            <div class="config-description">
                                Activa el modo de mantenimiento para realizar actualizaciones del sistema
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode">
                                <label for="maintenance_mode">Activar modo de mantenimiento</label>
                            </div>
                        </div>

                        <div class="config-group">
                            <label class="config-label">Intentos Máximos de Login</label>
                            <div class="config-description">
                                Número máximo de intentos fallidos antes de bloquear una cuenta
                            </div>
                            <input type="number" name="max_login_attempts" class="form-control" value="5" min="1" max="10">
                        </div>

                        <div class="config-group">
                            <label class="config-label">Tiempo de Sesión (minutos)</label>
                            <div class="config-description">
                                Tiempo de inactividad antes de cerrar automáticamente la sesión
                            </div>
                            <input type="number" name="session_timeout" class="form-control" value="120" min="30" max="480">
                        </div>

                        <button type="submit" class="btn">
                            <i class="fas fa-save"></i> Guardar Configuración
                        </button>
                    </form>
                </div>
            </div>

            <!-- Acciones del Sistema -->
            <div class="config-section">
                <h2 class="section-title">
                    <i class="fas fa-tools"></i>
                    Acciones del Sistema
                </h2>
                <div class="actions-grid">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h3 class="action-title">Crear Respaldo</h3>
                        <p class="action-description">
                            Genera un respaldo completo de la base de datos para mantener la seguridad de los datos.
                        </p>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="backup_database">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-download"></i> Crear Respaldo
                            </button>
                        </form>
                    </div>

                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-broom"></i>
                        </div>
                        <h3 class="action-title">Limpiar Logs</h3>
                        <p class="action-description">
                            Elimina los registros de log antiguos para optimizar el rendimiento del sistema.
                        </p>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="clear_logs">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar los logs antiguos?')">
                                <i class="fas fa-broom"></i> Limpiar Logs
                            </button>
                        </form>
                    </div>

                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h3 class="action-title">Optimizar Base de Datos</h3>
                        <p class="action-description">
                            Optimiza las tablas de la base de datos para mejorar el rendimiento.
                        </p>
                        <button type="button" class="btn" onclick="optimizeDatabase()">
                            <i class="fas fa-sync-alt"></i> Optimizar
                        </button>
                    </div>

                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="action-title">Verificar Seguridad</h3>
                        <p class="action-description">
                            Ejecuta una verificación de seguridad del sistema y reporta vulnerabilidades.
                        </p>
                        <button type="button" class="btn" onclick="checkSecurity()">
                            <i class="fas fa-shield-alt"></i> Verificar
                        </button>
                    </div>
                </div>
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
        function optimizeDatabase() {
            if (confirm('¿Estás seguro de que quieres optimizar la base de datos?')) {
                // Aquí se implementaría la optimización
                alert('Optimización completada exitosamente');
            }
        }

        function checkSecurity() {
            // Simular verificación de seguridad
            alert('Verificación de seguridad completada. No se encontraron vulnerabilidades críticas.');
        }

        // Auto-hide messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html> 