<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseño Relacional - Base de Datos Turística</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
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

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #117a8b;
            transform: translateY(-2px);
        }

        .diagram-container {
            padding: 30px;
            min-height: 600px;
            position: relative;
        }

        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .table-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .table-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-type {
            background: rgba(255,255,255,0.2);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 400;
        }

        .table-body {
            padding: 0;
        }

        .field {
            padding: 8px 20px;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9em;
        }

        .field:last-child {
            border-bottom: none;
        }

        .field-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .field-type {
            color: #6c757d;
            font-size: 0.8em;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .field-constraints {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        .constraint {
            font-size: 0.7em;
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
        }

        .constraint.pk {
            background: #dc3545;
        }

        .constraint.fk {
            background: #28a745;
        }

        .constraint.unique {
            background: #ffc107;
            color: #212529;
        }

        .constraint.notnull {
            background: #17a2b8;
        }

        .relationships {
            background: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #e9ecef;
        }

        .relationships h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.5em;
        }

        .relationship-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 15px;
        }

        .relationship-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .relationship-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .relationship-details {
            color: #6c757d;
            font-size: 0.9em;
        }

        .stats {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .stat-item {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .legend {
            background: white;
            padding: 20px;
            border-top: 1px solid #e9ecef;
        }

        .legend h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }

        .legend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9em;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .table-grid {
                grid-template-columns: 1fr;
            }
            
            .relationship-list {
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
            <h1>🏗️ Diseño Relacional de Base de Datos</h1>
            <p>Sistema Turístico de Ecuador - Base de Datos "registro"</p>
        </div>

        <div class="controls">
            <a href="index.php" class="btn btn-primary">🏠 Volver al Portal</a>
            <a href="admin.php" class="btn btn-success">⚙️ Panel Administrativo</a>
            <a href="registro.sql" class="btn btn-info" download>📥 Descargar SQL</a>
        </div>

        <div class="stats">
            <h3>📊 Estadísticas del Sistema</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">13</div>
                    <div class="stat-label">Tablas Principales</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Relaciones FK</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Vistas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Procedimientos</div>
                </div>
            </div>
        </div>

        <div class="diagram-container">
            <div class="table-grid">
                <!-- Tabla Users -->
                <div class="table-card">
                    <div class="table-header">
                        <span>👥 users</span>
                        <span class="table-type">Entidad Principal</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">firstName</div>
                            <span class="field-type">VARCHAR(50)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">lastName</div>
                            <span class="field-type">VARCHAR(50)</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">email</div>
                                <div class="field-constraints">
                                    <span class="constraint unique">UNIQUE</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">password</div>
                            <span class="field-type">VARCHAR(255)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">role</div>
                            <span class="field-type">ENUM('user','admin')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">is_active</div>
                            <span class="field-type">TINYINT(1)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">created_at</div>
                            <span class="field-type">TIMESTAMP</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Admins -->
                <div class="table-card">
                    <div class="table-header">
                        <span>👑 admins</span>
                        <span class="table-type">Entidad Especializada</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">user_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                    <span class="constraint unique">UNIQUE</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">admin_level</div>
                            <span class="field-type">ENUM('super_admin','admin','moderator')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">permissions</div>
                            <span class="field-type">JSON</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Destinations -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🗺️ destinations</span>
                        <span class="table-type">Entidad Principal</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">name</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">description</div>
                            <span class="field-type">TEXT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">location</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">province</div>
                            <span class="field-type">VARCHAR(50)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">category</div>
                            <span class="field-type">ENUM('playa','montaña','ciudad','selva','isla','cultural')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">rating</div>
                            <span class="field-type">DECIMAL(3,2)</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Accommodations -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🏨 accommodations</span>
                        <span class="table-type">Entidad Principal</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">name</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">destination_id</div>
                            <div class="field-constraints">
                                <span class="constraint fk">FK</span>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">type</div>
                            <span class="field-type">ENUM('hotel','hostal','cabaña','resort','apartamento','casa')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">price_per_night</div>
                            <span class="field-type">DECIMAL(10,2)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">rating</div>
                            <span class="field-type">DECIMAL(3,2)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">amenities</div>
                            <span class="field-type">JSON</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Bookings -->
                <div class="table-card">
                    <div class="table-header">
                        <span>📅 bookings</span>
                        <span class="table-type">Entidad de Transacción</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">user_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">accommodation_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">check_in</div>
                            <span class="field-type">DATE</span>
                        </div>
                        <div class="field">
                            <div class="field-name">check_out</div>
                            <span class="field-type">DATE</span>
                        </div>
                        <div class="field">
                            <div class="field-name">total_price</div>
                            <span class="field-type">DECIMAL(10,2)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">status</div>
                            <span class="field-type">ENUM('pending','confirmed','cancelled','completed')</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Reviews -->
                <div class="table-card">
                    <div class="table-header">
                        <span>⭐ reviews</span>
                        <span class="table-type">Entidad de Evaluación</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">user_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">destination_id</div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">accommodation_id</div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">rating</div>
                            <span class="field-type">INT CHECK (1-5)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">comment</div>
                            <span class="field-type">TEXT</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Activities -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🎯 activities</span>
                        <span class="table-type">Entidad Principal</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">name</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">destination_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">category</div>
                            <span class="field-type">ENUM('aventura','cultural','gastronomía','naturaleza','deportes','relax')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">duration</div>
                            <span class="field-type">VARCHAR(50)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">price</div>
                            <span class="field-type">DECIMAL(10,2)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">difficulty</div>
                            <span class="field-type">ENUM('fácil','moderado','difícil','experto')</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Login Attempts -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🔐 login_attempts</span>
                        <span class="table-type">Entidad de Seguridad</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">email</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">ip_address</div>
                            <span class="field-type">VARCHAR(45)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">success</div>
                            <span class="field-type">TINYINT(1)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">attempted_at</div>
                            <span class="field-type">TIMESTAMP</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla System Logs -->
                <div class="table-card">
                    <div class="table-header">
                        <span>📋 system_logs</span>
                        <span class="table-type">Entidad de Auditoría</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">user_id</div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">action</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">description</div>
                            <span class="field-type">TEXT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">level</div>
                            <span class="field-type">ENUM('info','warning','error','critical')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">created_at</div>
                            <span class="field-type">TIMESTAMP</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Notifications -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🔔 notifications</span>
                        <span class="table-type">Entidad de Comunicación</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">user_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">title</div>
                            <span class="field-type">VARCHAR(200)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">message</div>
                            <span class="field-type">TEXT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">type</div>
                            <span class="field-type">ENUM('info','success','warning','error')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">is_read</div>
                            <span class="field-type">TINYINT(1)</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla User Sessions -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🔄 user_sessions</span>
                        <span class="table-type">Entidad de Sesión</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">user_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">session_token</div>
                            <span class="field-type">VARCHAR(255)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">is_active</div>
                            <span class="field-type">TINYINT(1)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">expires_at</div>
                            <span class="field-type">DATETIME</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Password Resets -->
                <div class="table-card">
                    <div class="table-header">
                        <span>🔑 password_resets</span>
                        <span class="table-type">Entidad de Seguridad</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">email</div>
                            <span class="field-type">VARCHAR(100)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">token</div>
                            <span class="field-type">VARCHAR(255)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">used</div>
                            <span class="field-type">TINYINT(1)</span>
                        </div>
                        <div class="field">
                            <div class="field-name">expires_at</div>
                            <span class="field-type">DATETIME</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Accessibility Preferences -->
                <div class="table-card">
                    <div class="table-header">
                        <span>♿ accessibility_preferences</span>
                        <span class="table-type">Entidad de Configuración</span>
                    </div>
                    <div class="table-body">
                        <div class="field">
                            <div>
                                <div class="field-name">id</div>
                                <div class="field-constraints">
                                    <span class="constraint pk">PK</span>
                                    <span class="constraint notnull">NOT NULL</span>
                                </div>
                            </div>
                            <span class="field-type">INT AUTO_INCREMENT</span>
                        </div>
                        <div class="field">
                            <div>
                                <div class="field-name">user_id</div>
                                <div class="field-constraints">
                                    <span class="constraint fk">FK</span>
                                    <span class="constraint unique">UNIQUE</span>
                                </div>
                            </div>
                            <span class="field-type">INT</span>
                        </div>
                        <div class="field">
                            <div class="field-name">font_size</div>
                            <span class="field-type">ENUM('small','medium','large','x-large')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">contrast</div>
                            <span class="field-type">ENUM('normal','high')</span>
                        </div>
                        <div class="field">
                            <div class="field-name">animations</div>
                            <span class="field-type">TINYINT(1)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relationships">
            <h3>🔗 Relaciones entre Tablas</h3>
            <div class="relationship-list">
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → 👑 admins</div>
                    <div class="relationship-details">Relación 1:1 - Un usuario puede ser administrador</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">🗺️ destinations → 🏨 accommodations</div>
                    <div class="relationship-details">Relación 1:N - Un destino puede tener múltiples alojamientos</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">🗺️ destinations → 🎯 activities</div>
                    <div class="relationship-details">Relación 1:N - Un destino puede tener múltiples actividades</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → 📅 bookings</div>
                    <div class="relationship-details">Relación 1:N - Un usuario puede hacer múltiples reservas</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">🏨 accommodations → 📅 bookings</div>
                    <div class="relationship-details">Relación 1:N - Un alojamiento puede tener múltiples reservas</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → ⭐ reviews</div>
                    <div class="relationship-details">Relación 1:N - Un usuario puede escribir múltiples reseñas</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">🗺️ destinations → ⭐ reviews</div>
                    <div class="relationship-details">Relación 1:N - Un destino puede tener múltiples reseñas</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">🏨 accommodations → ⭐ reviews</div>
                    <div class="relationship-details">Relación 1:N - Un alojamiento puede tener múltiples reseñas</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → 🔔 notifications</div>
                    <div class="relationship-details">Relación 1:N - Un usuario puede recibir múltiples notificaciones</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → 🔄 user_sessions</div>
                    <div class="relationship-details">Relación 1:N - Un usuario puede tener múltiples sesiones</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → ♿ accessibility_preferences</div>
                    <div class="relationship-details">Relación 1:1 - Un usuario tiene una configuración de accesibilidad</div>
                </div>
                <div class="relationship-item">
                    <div class="relationship-title">👥 users → 📋 system_logs</div>
                    <div class="relationship-details">Relación 1:N - Un usuario puede generar múltiples logs del sistema</div>
                </div>
            </div>
        </div>

        <div class="legend">
            <h4>📖 Leyenda de Constraint</h4>
            <div class="legend-grid">
                <div class="legend-item">
                    <div class="legend-color" style="background: #dc3545;"></div>
                    <span>PK - Primary Key (Clave Primaria)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #28a745;"></div>
                    <span>FK - Foreign Key (Clave Foránea)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ffc107;"></div>
                    <span>UNIQUE - Valor Único</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #17a2b8;"></div>
                    <span>NOT NULL - No Nulo</span>
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
        const diagramContainer = document.querySelector('.diagram-container');

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
            diagramContainer.style.transform = `scale(${currentZoom})`;
            diagramContainer.style.transformOrigin = 'top left';
        }

        // Animación de entrada para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.table-card');
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
        document.querySelectorAll('.table-card').forEach(card => {
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