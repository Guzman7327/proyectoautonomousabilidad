<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Notificaciones y Comunicaciones - Portal Turístico</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            width: 100%;
            max-width: 1400px;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .form-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .form-header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px;
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e9ecef;
        }

        .tab {
            flex: 1;
            padding: 18px 25px;
            text-align: center;
            cursor: pointer;
            background: #f8f9fa;
            border: none;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.4s ease;
            color: #6c757d;
            position: relative;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }

        .tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.2), transparent);
            transition: left 0.5s;
        }

        .tab:hover::before {
            left: 100%;
        }

        .tab.active {
            background: #007bff;
            color: white;
            transform: translateY(-2px);
        }

        .tab:hover {
            background: #0056b3;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1em;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .form-checkbox input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .form-checkbox label {
            margin: 0;
            font-weight: normal;
            font-size: 0.9em;
            color: #6c757d;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,123,255,0.3);
        }

        .submit-btn.danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .submit-btn.success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .submit-btn.warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }

        .form-info {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .form-info h3 {
            color: #1976d2;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .form-info p {
            color: #424242;
            font-size: 0.9em;
            line-height: 1.5;
        }

        .requirement-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .notification-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .notification-type {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .type-email {
            background: #d4edda;
            color: #155724;
        }

        .type-sms {
            background: #fff3cd;
            color: #856404;
        }

        .type-push {
            background: #d1ecf1;
            color: #0c5460;
        }

        .type-inapp {
            background: #f8d7da;
            color: #721c24;
        }

        .template-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .template-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .template-card:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }

        .template-card.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .template-card h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .template-card p {
            color: #6c757d;
            font-size: 0.9em;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .schedule-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }

        .time-slot {
            padding: 10px;
            text-align: center;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            background: #f8f9fa;
        }

        .time-slot.selected {
            background: #007bff;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-icon {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #007bff;
        }

        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }
            
            .form-content {
                padding: 20px;
            }
            
            .form-header {
                padding: 20px;
            }
            
            .form-header h1 {
                font-size: 2em;
            }

            .template-grid {
                grid-template-columns: 1fr;
            }

            .tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF9</div>
        
        <div class="form-header">
            <h1>📢 Portal Turístico Ecuador</h1>
            <p>Sistema de Notificaciones y Comunicaciones - Prototipo RF9</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('notifications')">
                    <i class="fas fa-bell"></i> Notificaciones
                </button>
                <button class="tab" onclick="showTab('templates')">
                    <i class="fas fa-file-alt"></i> Plantillas
                </button>
                <button class="tab" onclick="showTab('schedule')">
                    <i class="fas fa-clock"></i> Programación
                </button>
                <button class="tab" onclick="showTab('analytics')">
                    <i class="fas fa-chart-bar"></i> Analytics
                </button>
                <button class="tab" onclick="showTab('settings')">
                    <i class="fas fa-cog"></i> Configuración
                </button>
            </div>

            <!-- NOTIFICACIONES -->
            <div id="notifications" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Sistema de Notificaciones</h3>
                    <p>Gestiona notificaciones automáticas y manuales para usuarios, proveedores y administradores del portal turístico.</p>
                </div>

                <form id="notificationForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="notificationType">
                                <i class="fas fa-tag"></i> Tipo de Notificación
                            </label>
                            <select id="notificationType" name="notificationType" required>
                                <option value="">Selecciona tipo de notificación</option>
                                <option value="reservation">Confirmación de Reserva</option>
                                <option value="reminder">Recordatorio de Viaje</option>
                                <option value="promotion">Promoción Especial</option>
                                <option value="update">Actualización de Servicio</option>
                                <option value="alert">Alerta de Sistema</option>
                                <option value="welcome">Bienvenida</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="channel">
                                <i class="fas fa-broadcast-tower"></i> Canal de Envío
                            </label>
                            <select id="channel" name="channel" required>
                                <option value="">Selecciona canal</option>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="push">Push Notification</option>
                                <option value="inapp">In-App</option>
                                <option value="all">Todos los Canales</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="recipients">
                            <i class="fas fa-users"></i> Destinatarios
                        </label>
                        <select id="recipients" name="recipients" required>
                            <option value="">Selecciona destinatarios</option>
                            <option value="all">Todos los Usuarios</option>
                            <option value="active">Usuarios Activos</option>
                            <option value="new">Usuarios Nuevos</option>
                            <option value="premium">Usuarios Premium</option>
                            <option value="specific">Usuarios Específicos</option>
                            <option value="providers">Proveedores</option>
                            <option value="admins">Administradores</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            <i class="fas fa-heading"></i> Asunto
                        </label>
                        <input type="text" id="subject" name="subject" placeholder="Asunto de la notificación" required>
                    </div>

                    <div class="form-group">
                        <label for="message">
                            <i class="fas fa-comment"></i> Mensaje
                        </label>
                        <textarea id="message" name="message" rows="5" placeholder="Contenido de la notificación..." required></textarea>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="priority">
                                <i class="fas fa-exclamation-triangle"></i> Prioridad
                            </label>
                            <select id="priority" name="priority" required>
                                <option value="low">Baja</option>
                                <option value="normal" selected>Normal</option>
                                <option value="high">Alta</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="language">
                                <i class="fas fa-language"></i> Idioma
                            </label>
                            <select id="language" name="language" required>
                                <option value="es" selected>Español</option>
                                <option value="en">English</option>
                                <option value="fr">Français</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="template">
                                <i class="fas fa-file-alt"></i> Plantilla
                            </label>
                            <select id="template" name="template">
                                <option value="">Sin plantilla</option>
                                <option value="welcome">Bienvenida</option>
                                <option value="reservation">Reserva</option>
                                <option value="promotion">Promoción</option>
                                <option value="reminder">Recordatorio</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Opciones Adicionales</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="tracking" name="options[]" value="tracking" checked>
                                <label for="tracking">Seguimiento de apertura</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="personalization" name="options[]" value="personalization" checked>
                                <label for="personalization">Personalización</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="scheduling" name="options[]" value="scheduling">
                                <label for="scheduling">Programar envío</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-paper-plane"></i> Enviar Notificación
                    </button>
                </form>

                <!-- NOTIFICACIONES RECIENTES -->
                <div class="notification-card">
                    <div class="notification-header">
                        <h4><i class="fas fa-bell"></i> Notificaciones Recientes</h4>
                    </div>
                    
                    <div class="notification-card">
                        <div class="notification-header">
                            <h5>Confirmación de Reserva - Hotel Plaza Grande</h5>
                            <span class="notification-type type-email">Email</span>
                        </div>
                        <p><strong>Enviado:</strong> 15 minutos atrás</p>
                        <p><strong>Destinatarios:</strong> 1 usuario</p>
                        <p><strong>Estado:</strong> Enviado</p>
                        <button class="submit-btn">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </button>
                    </div>

                    <div class="notification-card">
                        <div class="notification-header">
                            <h5>Promoción Especial - Galápagos</h5>
                            <span class="notification-type type-push">Push</span>
                        </div>
                        <p><strong>Enviado:</strong> 2 horas atrás</p>
                        <p><strong>Destinatarios:</strong> 1,247 usuarios</p>
                        <p><strong>Estado:</strong> Enviado (98% entregado)</p>
                        <button class="submit-btn">
                            <i class="fas fa-chart-line"></i> Ver Analytics
                        </button>
                    </div>
                </div>
            </div>

            <!-- PLANTILLAS -->
            <div id="templates" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Gestión de Plantillas</h3>
                    <p>Crea y gestiona plantillas personalizables para diferentes tipos de notificaciones y comunicaciones.</p>
                </div>

                <div class="template-section">
                    <h4><i class="fas fa-plus-circle"></i> Crear Nueva Plantilla</h4>
                    <form id="templateForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="templateName">
                                    <i class="fas fa-tag"></i> Nombre de la Plantilla
                                </label>
                                <input type="text" id="templateName" name="templateName" placeholder="Ej: Confirmación de Reserva" required>
                            </div>

                            <div class="form-group">
                                <label for="templateCategory">
                                    <i class="fas fa-folder"></i> Categoría
                                </label>
                                <select id="templateCategory" name="templateCategory" required>
                                    <option value="">Selecciona categoría</option>
                                    <option value="reservation">Reservas</option>
                                    <option value="promotion">Promociones</option>
                                    <option value="reminder">Recordatorios</option>
                                    <option value="welcome">Bienvenida</option>
                                    <option value="alert">Alertas</option>
                                    <option value="custom">Personalizada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="templateSubject">
                                <i class="fas fa-heading"></i> Asunto
                            </label>
                            <input type="text" id="templateSubject" name="templateSubject" placeholder="Asunto de la plantilla" required>
                        </div>

                        <div class="form-group">
                            <label for="templateContent">
                                <i class="fas fa-edit"></i> Contenido
                            </label>
                            <textarea id="templateContent" name="templateContent" rows="8" placeholder="Contenido de la plantilla con variables como {{nombre}}, {{destino}}, etc..." required></textarea>
                        </div>

                        <div class="form-row-3">
                            <div class="form-group">
                                <label for="templateLanguage">
                                    <i class="fas fa-language"></i> Idioma
                                </label>
                                <select id="templateLanguage" name="templateLanguage" required>
                                    <option value="es" selected>Español</option>
                                    <option value="en">English</option>
                                    <option value="fr">Français</option>
                                    <option value="de">Deutsch</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="templateChannel">
                                    <i class="fas fa-broadcast-tower"></i> Canal
                                </label>
                                <select id="templateChannel" name="templateChannel" required>
                                    <option value="email" selected>Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="push">Push</option>
                                    <option value="inapp">In-App</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="templateStatus">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <select id="templateStatus" name="templateStatus" required>
                                    <option value="active" selected>Activa</option>
                                    <option value="inactive">Inactiva</option>
                                    <option value="draft">Borrador</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn success">
                            <i class="fas fa-save"></i> Guardar Plantilla
                        </button>
                    </form>
                </div>

                <!-- PLANTILLAS EXISTENTES -->
                <div class="template-section">
                    <h4><i class="fas fa-list"></i> Plantillas Existentes</h4>
                    <div class="template-grid">
                        <div class="template-card selected">
                            <h4><i class="fas fa-hotel"></i> Confirmación de Reserva</h4>
                            <p>Categoría: Reservas</p>
                            <p>Idioma: Español</p>
                            <p>Estado: Activa</p>
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>

                        <div class="template-card">
                            <h4><i class="fas fa-gift"></i> Promoción Especial</h4>
                            <p>Categoría: Promociones</p>
                            <p>Idioma: Español</p>
                            <p>Estado: Activa</p>
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>

                        <div class="template-card">
                            <h4><i class="fas fa-clock"></i> Recordatorio de Viaje</h4>
                            <p>Categoría: Recordatorios</p>
                            <p>Idioma: Español</p>
                            <p>Estado: Activa</p>
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>

                        <div class="template-card">
                            <h4><i class="fas fa-hand-wave"></i> Bienvenida</h4>
                            <p>Categoría: Bienvenida</p>
                            <p>Idioma: Español</p>
                            <p>Estado: Activa</p>
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROGRAMACIÓN -->
            <div id="schedule" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Programación de Notificaciones</h3>
                    <p>Programa notificaciones automáticas para enviarse en momentos específicos o bajo condiciones determinadas.</p>
                </div>

                <form id="scheduleForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="scheduleType">
                                <i class="fas fa-calendar"></i> Tipo de Programación
                            </label>
                            <select id="scheduleType" name="scheduleType" required>
                                <option value="">Selecciona tipo</option>
                                <option value="one-time">Una vez</option>
                                <option value="recurring">Recurrente</option>
                                <option value="trigger">Basado en evento</option>
                                <option value="conditional">Condicional</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="scheduleTemplate">
                                <i class="fas fa-file-alt"></i> Plantilla a Usar
                            </label>
                            <select id="scheduleTemplate" name="scheduleTemplate" required>
                                <option value="">Selecciona plantilla</option>
                                <option value="welcome">Bienvenida</option>
                                <option value="reservation">Confirmación de Reserva</option>
                                <option value="reminder">Recordatorio de Viaje</option>
                                <option value="promotion">Promoción Especial</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="scheduleDate">
                                <i class="fas fa-calendar-plus"></i> Fecha de Envío
                            </label>
                            <input type="date" id="scheduleDate" name="scheduleDate" required>
                        </div>

                        <div class="form-group">
                            <label for="scheduleTime">
                                <i class="fas fa-clock"></i> Hora de Envío
                            </label>
                            <input type="time" id="scheduleTime" name="scheduleTime" required>
                        </div>
                    </div>

                    <div class="schedule-section">
                        <h4><i class="fas fa-calendar-week"></i> Horarios Preferidos</h4>
                        <div class="time-slots">
                            <div class="time-slot selected">09:00</div>
                            <div class="time-slot">12:00</div>
                            <div class="time-slot">15:00</div>
                            <div class="time-slot">18:00</div>
                            <div class="time-slot">20:00</div>
                            <div class="time-slot">22:00</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="scheduleRecipients">
                            <i class="fas fa-users"></i> Destinatarios
                        </label>
                        <select id="scheduleRecipients" name="scheduleRecipients" required>
                            <option value="">Selecciona destinatarios</option>
                            <option value="all">Todos los Usuarios</option>
                            <option value="active">Usuarios Activos</option>
                            <option value="new">Usuarios Nuevos (últimos 7 días)</option>
                            <option value="inactive">Usuarios Inactivos</option>
                            <option value="premium">Usuarios Premium</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Condiciones de Envío</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="timezone" name="conditions[]" value="timezone" checked>
                                <label for="timezone">Respetar zona horaria</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="frequency" name="conditions[]" value="frequency" checked>
                                <label for="frequency">Limitar frecuencia</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="preferences" name="conditions[]" value="preferences" checked>
                                <label for="preferences">Respetar preferencias</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-clock"></i> Programar Notificación
                    </button>
                </form>

                <!-- PROGRAMACIONES ACTIVAS -->
                <div class="notification-card">
                    <div class="notification-header">
                        <h4><i class="fas fa-list"></i> Programaciones Activas</h4>
                    </div>
                    
                    <div class="notification-card">
                        <div class="notification-header">
                            <h5>Recordatorio Semanal - Usuarios Activos</h5>
                            <span class="notification-type type-email">Programado</span>
                        </div>
                        <p><strong>Próximo envío:</strong> Lunes, 09:00</p>
                        <p><strong>Frecuencia:</strong> Semanal</p>
                        <p><strong>Destinatarios:</strong> 2,847 usuarios</p>
                        <div class="form-row">
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="submit-btn danger">
                                <i class="fas fa-stop"></i> Pausar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ANALYTICS -->
            <div id="analytics" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Analytics de Notificaciones</h3>
                    <p>Analiza el rendimiento y efectividad de las notificaciones enviadas a través de diferentes canales.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div class="stat-value">15,432</div>
                        <div class="stat-label">Notificaciones Enviadas</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">Notificaciones Abiertas</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="stat-value">3,245</div>
                        <div class="stat-label">Clicks Generados</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-value">83.2%</div>
                        <div class="stat-label">Tasa de Apertura</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="notification-card">
                        <h4><i class="fas fa-chart-pie"></i> Rendimiento por Canal</h4>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-pie" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Rendimiento por Canal</p>
                        </div>
                    </div>

                    <div class="notification-card">
                        <h4><i class="fas fa-chart-bar"></i> Tendencias de Envío</h4>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-bar" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Tendencias</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Insight:</strong> Las notificaciones enviadas entre 18:00 y 20:00 tienen un 25% más de tasa de apertura.
                </div>
            </div>

            <!-- CONFIGURACIÓN -->
            <div id="settings" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Configuración del Sistema</h3>
                    <p>Configura las preferencias generales del sistema de notificaciones y comunicaciones.</p>
                </div>

                <form id="settingsForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="defaultLanguage">
                                <i class="fas fa-language"></i> Idioma por Defecto
                            </label>
                            <select id="defaultLanguage" name="defaultLanguage" required>
                                <option value="es" selected>Español</option>
                                <option value="en">English</option>
                                <option value="fr">Français</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="defaultChannel">
                                <i class="fas fa-broadcast-tower"></i> Canal por Defecto
                            </label>
                            <select id="defaultChannel" name="defaultChannel" required>
                                <option value="email" selected>Email</option>
                                <option value="sms">SMS</option>
                                <option value="push">Push</option>
                                <option value="inapp">In-App</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="maxRetries">
                                <i class="fas fa-redo"></i> Máximo de Reintentos
                            </label>
                            <input type="number" id="maxRetries" name="maxRetries" value="3" min="1" max="10" required>
                        </div>

                        <div class="form-group">
                            <label for="retryDelay">
                                <i class="fas fa-clock"></i> Retraso entre Reintentos (minutos)
                            </label>
                            <input type="number" id="retryDelay" name="retryDelay" value="5" min="1" max="60" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="senderEmail">
                            <i class="fas fa-envelope"></i> Email del Remitente
                        </label>
                        <input type="email" id="senderEmail" name="senderEmail" value="notificaciones@turismoecuador.com" required>
                    </div>

                    <div class="form-group">
                        <label for="senderName">
                            <i class="fas fa-user"></i> Nombre del Remitente
                        </label>
                        <input type="text" id="senderName" name="senderName" value="Portal Turístico Ecuador" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Configuraciones Avanzadas</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="trackingEnabled" name="advanced[]" value="tracking" checked>
                                <label for="trackingEnabled">Habilitar seguimiento</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="personalizationEnabled" name="advanced[]" value="personalization" checked>
                                <label for="personalizationEnabled">Habilitar personalización</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="analyticsEnabled" name="advanced[]" value="analytics" checked>
                                <label for="analyticsEnabled">Habilitar analytics</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-shield-alt"></i> Configuraciones de Seguridad</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="encryptionEnabled" name="security[]" value="encryption" checked>
                                <label for="encryptionEnabled">Encriptación SSL</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="rateLimitEnabled" name="security[]" value="rateLimit" checked>
                                <label for="rateLimitEnabled">Límite de tasa</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="spamProtection" name="security[]" value="spamProtection" checked>
                                <label for="spamProtection">Protección anti-spam</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-save"></i> Guardar Configuración
                    </button>
                </form>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Configuración Actualizada:</strong> Los cambios se han guardado exitosamente. El sistema está funcionando correctamente.
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Ocultar todas las pestañas
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remover clase active de todas las pestañas
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Mostrar la pestaña seleccionada
            document.getElementById(tabName).classList.add('active');
            
            // Agregar clase active al botón de la pestaña
            event.target.classList.add('active');
        }

        // Configurar fecha por defecto
        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('scheduleDate').value = tomorrow.toISOString().split('T')[0];
        });

        // Simular envío de notificaciones
        document.getElementById('notificationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Notificación enviada exitosamente a los destinatarios seleccionados.');
        });

        // Simular guardado de plantillas
        document.getElementById('templateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Plantilla guardada exitosamente.');
        });

        // Simular programación
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Notificación programada exitosamente.');
        });

        // Simular guardado de configuración
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Configuración guardada exitosamente.');
        });
    </script>
</body>
</html>
