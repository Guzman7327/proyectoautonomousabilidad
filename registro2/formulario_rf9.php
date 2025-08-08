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
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1200px;
            position: relative;
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
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
            background: #f8f9fa;
            border: none;
            font-size: 1em;
            font-weight: 500;
            transition: all 0.3s ease;
            color: #6c757d;
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
                                <option value="promotion">Promociones Especiales</option>
                                <option value="update">Actualización de Estado</option>
                                <option value="cancellation">Cancelación de Servicio</option>
                                <option value="welcome">Bienvenida a Nuevos Usuarios</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="recipientType">
                                <i class="fas fa-users"></i> Tipo de Destinatario
                            </label>
                            <select id="recipientType" name="recipientType" required>
                                <option value="">Selecciona destinatario</option>
                                <option value="all">Todos los Usuarios</option>
                                <option value="registered">Usuarios Registrados</option>
                                <option value="premium">Usuarios Premium</option>
                                <option value="providers">Proveedores de Servicios</option>
                                <option value="admins">Administradores</option>
                                <option value="custom">Lista Personalizada</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="notificationTitle">
                                <i class="fas fa-heading"></i> Título de la Notificación
                            </label>
                            <input type="text" id="notificationTitle" name="notificationTitle" placeholder="Ej: ¡Tu reserva ha sido confirmada!" required>
                        </div>

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
                    </div>

                    <div class="form-group">
                        <label for="notificationMessage">
                            <i class="fas fa-comment"></i> Mensaje
                        </label>
                        <textarea id="notificationMessage" name="notificationMessage" rows="4" placeholder="Escribe el contenido de la notificación..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-paper-plane"></i> Canales de Envío</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="emailChannel" name="channels[]" value="email" checked>
                                <label for="emailChannel">Email</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="smsChannel" name="channels[]" value="sms">
                                <label for="smsChannel">SMS</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="pushChannel" name="channels[]" value="push" checked>
                                <label for="pushChannel">Notificación Push</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="inappChannel" name="channels[]" value="inapp">
                                <label for="inappChannel">Notificación In-App</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="whatsappChannel" name="channels[]" value="whatsapp">
                                <label for="whatsappChannel">WhatsApp</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="telegramChannel" name="channels[]" value="telegram">
                                <label for="telegramChannel">Telegram</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sendImmediately">
                            <i class="fas fa-clock"></i> Envío
                        </label>
                        <div class="form-row">
                            <div class="form-checkbox">
                                <input type="radio" id="sendNow" name="sendType" value="now" checked>
                                <label for="sendNow">Enviar inmediatamente</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="radio" id="sendLater" name="sendType" value="later">
                                <label for="sendLater">Programar para más tarde</label>
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
                            <h4>Confirmación de Reserva - Hotel Plaza Grande</h4>
                            <span class="notification-type type-email">Email</span>
                        </div>
                        <p><strong>Destinatario:</strong> maria.gonzalez@email.com</p>
                        <p><strong>Enviado:</strong> Hace 2 horas</p>
                        <p><strong>Estado:</strong> <span style="color: #28a745;">Entregado</span></p>
                    </div>

                    <div class="notification-card">
                        <div class="notification-header">
                            <h4>Promoción Especial - Galápagos</h4>
                            <span class="notification-type type-push">Push</span>
                        </div>
                        <p><strong>Destinatarios:</strong> 1,247 usuarios</p>
                        <p><strong>Enviado:</strong> Hace 1 día</p>
                        <p><strong>Estado:</strong> <span style="color: #28a745;">Entregado (98%)</span></p>
                    </div>

                    <div class="notification-card">
                        <div class="notification-header">
                            <h4>Recordatorio de Viaje - Cuenca</h4>
                            <span class="notification-type type-sms">SMS</span>
                        </div>
                        <p><strong>Destinatario:</strong> +593 99 123 4567</p>
                        <p><strong>Enviado:</strong> Hace 3 días</p>
                        <p><strong>Estado:</strong> <span style="color: #ffc107;">Pendiente</span></p>
                    </div>
                </div>
            </div>

            <!-- PLANTILLAS -->
            <div id="templates" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Plantillas de Notificaciones</h3>
                    <p>Gestiona plantillas predefinidas para diferentes tipos de notificaciones y personaliza el contenido.</p>
                </div>

                <div class="template-section">
                    <h4><i class="fas fa-plus"></i> Crear Nueva Plantilla</h4>
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
                                    <option value="reservations">Reservas</option>
                                    <option value="promotions">Promociones</option>
                                    <option value="reminders">Recordatorios</option>
                                    <option value="updates">Actualizaciones</option>
                                    <option value="welcome">Bienvenida</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="templateSubject">
                                <i class="fas fa-heading"></i> Asunto
                            </label>
                            <input type="text" id="templateSubject" name="templateSubject" placeholder="Asunto del email o título de la notificación" required>
                        </div>

                        <div class="form-group">
                            <label for="templateContent">
                                <i class="fas fa-edit"></i> Contenido
                            </label>
                            <textarea id="templateContent" name="templateContent" rows="6" placeholder="Contenido de la plantilla. Puedes usar variables como {{nombre}}, {{destino}}, {{fecha}}..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-palette"></i> Variables Disponibles</label>
                            <div class="form-row-3">
                                <div class="form-checkbox">
                                    <input type="checkbox" id="varName" name="variables[]" value="nombre">
                                    <label for="varName">{{nombre}} - Nombre del usuario</label>
                                </div>
                                <div class="form-checkbox">
                                    <input type="checkbox" id="varDestination" name="variables[]" value="destino">
                                    <label for="varDestination">{{destino}} - Destino turístico</label>
                                </div>
                                <div class="form-checkbox">
                                    <input type="checkbox" id="varDate" name="variables[]" value="fecha">
                                    <label for="varDate">{{fecha}} - Fecha de reserva</label>
                                </div>
                                <div class="form-checkbox">
                                    <input type="checkbox" id="varPrice" name="variables[]" value="precio">
                                    <label for="varPrice">{{precio}} - Precio del servicio</label>
                                </div>
                                <div class="form-checkbox">
                                    <input type="checkbox" id="varReservationId" name="variables[]" value="reserva_id">
                                    <label for="varReservationId">{{reserva_id}} - ID de reserva</label>
                                </div>
                                <div class="form-checkbox">
                                    <input type="checkbox" id="varCompany" name="variables[]" value="empresa">
                                    <label for="varCompany">{{empresa}} - Nombre de la empresa</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn success">
                            <i class="fas fa-save"></i> Guardar Plantilla
                        </button>
                    </form>
                </div>

                <div class="template-section">
                    <h4><i class="fas fa-list"></i> Plantillas Existentes</h4>
                    <div class="template-grid">
                        <div class="template-card">
                            <h4>Confirmación de Reserva</h4>
                            <p>Categoría: Reservas</p>
                            <p>Variables: {{nombre}}, {{destino}}, {{fecha}}, {{precio}}</p>
                            <button class="submit-btn">Editar</button>
                        </div>

                        <div class="template-card">
                            <h4>Recordatorio de Viaje</h4>
                            <p>Categoría: Recordatorios</p>
                            <p>Variables: {{nombre}}, {{destino}}, {{fecha}}</p>
                            <button class="submit-btn">Editar</button>
                        </div>

                        <div class="template-card">
                            <h4>Promoción Especial</h4>
                            <p>Categoría: Promociones</p>
                            <p>Variables: {{nombre}}, {{destino}}, {{descuento}}</p>
                            <button class="submit-btn">Editar</button>
                        </div>

                        <div class="template-card">
                            <h4>Bienvenida Nuevo Usuario</h4>
                            <p>Categoría: Bienvenida</p>
                            <p>Variables: {{nombre}}, {{empresa}}</p>
                            <button class="submit-btn">Editar</button>
                        </div>

                        <div class="template-card">
                            <h4>Actualización de Estado</h4>
                            <p>Categoría: Actualizaciones</p>
                            <p>Variables: {{nombre}}, {{reserva_id}}, {{estado}}</p>
                            <button class="submit-btn">Editar</button>
                        </div>

                        <div class="template-card">
                            <h4>Cancelación de Servicio</h4>
                            <p>Categoría: Actualizaciones</p>
                            <p>Variables: {{nombre}}, {{destino}}, {{fecha}}, {{reembolso}}</p>
                            <button class="submit-btn">Editar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROGRAMACIÓN -->
            <div id="schedule" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Programación de Notificaciones</h3>
                    <p>Programa notificaciones automáticas para enviarse en momentos específicos o bajo ciertas condiciones.</p>
                </div>

                <form id="scheduleForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="scheduleType">
                                <i class="fas fa-calendar"></i> Tipo de Programación
                            </label>
                            <select id="scheduleType" name="scheduleType" required>
                                <option value="">Selecciona tipo de programación</option>
                                <option value="one-time">Una vez</option>
                                <option value="recurring">Recurrente</option>
                                <option value="trigger">Basada en Eventos</option>
                                <option value="conditional">Condicional</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="scheduleDate">
                                <i class="fas fa-calendar-alt"></i> Fecha de Envío
                            </label>
                            <input type="date" id="scheduleDate" name="scheduleDate" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="scheduleTime">
                                <i class="fas fa-clock"></i> Hora de Envío
                            </label>
                            <input type="time" id="scheduleTime" name="scheduleTime" required>
                        </div>

                        <div class="form-group">
                            <label for="timezone">
                                <i class="fas fa-globe"></i> Zona Horaria
                            </label>
                            <select id="timezone" name="timezone" required>
                                <option value="America/Guayaquil" selected>Ecuador (GMT-5)</option>
                                <option value="America/New_York">Nueva York (GMT-5)</option>
                                <option value="Europe/Madrid">Madrid (GMT+1)</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                    </div>

                    <div class="schedule-section">
                        <h4><i class="fas fa-clock"></i> Horarios de Envío Recomendados</h4>
                        <div class="time-slots">
                            <div class="time-slot">09:00</div>
                            <div class="time-slot">12:00</div>
                            <div class="time-slot">15:00</div>
                            <div class="time-slot">18:00</div>
                            <div class="time-slot">20:00</div>
                            <div class="time-slot">21:00</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-repeat"></i> Recurrencia (si aplica)</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="radio" id="daily" name="recurrence" value="daily">
                                <label for="daily">Diario</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="radio" id="weekly" name="recurrence" value="weekly">
                                <label for="weekly">Semanal</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="radio" id="monthly" name="recurrence" value="monthly">
                                <label for="monthly">Mensual</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="endDate">
                            <i class="fas fa-calendar-times"></i> Fecha de Finalización
                        </label>
                        <input type="date" id="endDate" name="endDate" placeholder="Dejar vacío para sin límite">
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-calendar-plus"></i> Programar Notificación
                    </button>
                </form>

                <!-- PROGRAMACIONES ACTIVAS -->
                <div class="notification-card">
                    <div class="notification-header">
                        <h4><i class="fas fa-list"></i> Programaciones Activas</h4>
                    </div>
                    
                    <div class="notification-card">
                        <div class="notification-header">
                            <h4>Recordatorio Semanal - Promociones</h4>
                            <span class="notification-type type-email">Programado</span>
                        </div>
                        <p><strong>Próximo envío:</strong> Lunes, 09:00</p>
                        <p><strong>Frecuencia:</strong> Semanal</p>
                        <p><strong>Destinatarios:</strong> Usuarios Premium</p>
                        <button class="submit-btn warning">Editar</button>
                        <button class="submit-btn danger">Cancelar</button>
                    </div>

                    <div class="notification-card">
                        <div class="notification-header">
                            <h4>Bienvenida Nuevos Usuarios</h4>
                            <span class="notification-type type-push">Automático</span>
                        </div>
                        <p><strong>Trigger:</strong> Registro de usuario</p>
                        <p><strong>Retraso:</strong> 5 minutos</p>
                        <p><strong>Estado:</strong> <span style="color: #28a745;">Activo</span></p>
                        <button class="submit-btn warning">Editar</button>
                        <button class="submit-btn danger">Desactivar</button>
                    </div>
                </div>
            </div>

            <!-- ANALYTICS -->
            <div id="analytics" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Analytics de Notificaciones</h3>
                    <p>Analiza el rendimiento de las notificaciones, tasas de apertura, clics y engagement de los usuarios.</p>
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
                        <div class="stat-value">87.3%</div>
                        <div class="stat-label">Tasa de Apertura</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="stat-value">23.1%</div>
                        <div class="stat-label">Tasa de Clic</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value">2,847</div>
                        <div class="stat-label">Usuarios Activos</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Rendimiento por Canal</div>
                        </div>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-pie" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Rendimiento por Canal</p>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Tendencia de Engagement</div>
                        </div>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-line" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Tendencia de Engagement</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-chart-bar"></i>
                    <strong>Insight:</strong> Las notificaciones push tienen un 15% más de engagement que las notificaciones por email.
                </div>
            </div>

            <!-- CONFIGURACIÓN -->
            <div id="settings" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Configuración del Sistema</h3>
                    <p>Configura las preferencias del sistema de notificaciones, límites de envío y configuraciones avanzadas.</p>
                </div>

                <form id="settingsForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="maxNotifications">
                                <i class="fas fa-bell"></i> Máximo de Notificaciones por Usuario
                            </label>
                            <select id="maxNotifications" name="maxNotifications">
                                <option value="1">1 por día</option>
                                <option value="3" selected>3 por día</option>
                                <option value="5">5 por día</option>
                                <option value="10">10 por día</option>
                                <option value="unlimited">Sin límite</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quietHours">
                                <i class="fas fa-moon"></i> Horas de Silencio
                            </label>
                            <div class="form-row">
                                <input type="time" id="quietStart" name="quietStart" value="22:00">
                                <input type="time" id="quietEnd" name="quietEnd" value="08:00">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="defaultLanguage">
                            <i class="fas fa-language"></i> Idioma Predeterminado
                        </label>
                        <select id="defaultLanguage" name="defaultLanguage">
                            <option value="es" selected>Español</option>
                            <option value="en">English</option>
                            <option value="pt">Português</option>
                            <option value="fr">Français</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-shield-alt"></i> Configuraciones de Seguridad</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="encryptData" name="security[]" value="encrypt" checked>
                                <label for="encryptData">Encriptar datos sensibles</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="logActivity" name="security[]" value="log" checked>
                                <label for="logActivity">Registrar actividad</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="rateLimit" name="security[]" value="rate" checked>
                                <label for="rateLimit">Límite de tasa de envío</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="retryAttempts">
                            <i class="fas fa-redo"></i> Intentos de Reenvío
                        </label>
                        <select id="retryAttempts" name="retryAttempts">
                            <option value="1">1 intento</option>
                            <option value="3" selected>3 intentos</option>
                            <option value="5">5 intentos</option>
                            <option value="10">10 intentos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notificationExpiry">
                            <i class="fas fa-clock"></i> Tiempo de Expiración
                        </label>
                        <select id="notificationExpiry" name="notificationExpiry">
                            <option value="1">1 día</option>
                            <option value="7" selected>7 días</option>
                            <option value="30">30 días</option>
                            <option value="90">90 días</option>
                            <option value="never">Nunca</option>
                        </select>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-save"></i> Guardar Configuración
                    </button>
                </form>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Configuración Guardada:</strong> Los cambios se aplicarán inmediatamente al sistema de notificaciones.
                </div>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura de Pantalla</h4>
            <p>Esta imagen muestra el Sistema de Notificaciones y Comunicaciones (RF9) con todas sus funcionalidades de gestión de notificaciones, plantillas, programación, analytics y configuración.</p>
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
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            document.getElementById('scheduleDate').value = tomorrow.toISOString().split('T')[0];
            document.getElementById('scheduleTime').value = '09:00';
        });

        // Simular envío de notificaciones
        document.getElementById('notificationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Notificación enviada exitosamente a todos los destinatarios seleccionados.');
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

        // Seleccionar slots de tiempo
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', function() {
                document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('scheduleTime').value = this.textContent;
            });
        });
    </script>
</body>
</html>
