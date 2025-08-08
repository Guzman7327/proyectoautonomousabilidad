<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda y Reserva - Portal Turístico</title>
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
            max-width: 1000px;
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
            font-size: 1.1em;
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
            margin-bottom: 20px;
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

        .submit-btn:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .validation-message {
            font-size: 0.9em;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .validation-message.success {
            color: #28a745;
        }

        .validation-message.error {
            color: #dc3545;
        }

        .validation-message.warning {
            color: #ffc107;
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

        .screenshot-info {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }

        .screenshot-info h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .screenshot-info p {
            color: #6c757d;
            font-size: 0.9em;
        }

        .requirement-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .price-display {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }

        .price-amount {
            font-size: 2.5em;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }

        .price-breakdown {
            font-size: 0.9em;
            color: #6c757d;
        }

        .destination-preview {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .destination-preview h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .destination-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-item i {
            color: #007bff;
            width: 20px;
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
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF2</div>
        
        <div class="form-header">
            <h1>🌴 Portal Turístico Ecuador</h1>
            <p>Búsqueda y Reserva de Destinos - Prototipo RF2</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('search')">
                    <i class="fas fa-search"></i> Búsqueda
                </button>
                <button class="tab" onclick="showTab('booking')">
                    <i class="fas fa-calendar-check"></i> Reserva
                </button>
                <button class="tab" onclick="showTab('contact')">
                    <i class="fas fa-phone"></i> Contacto
                </button>
            </div>

            <!-- FORMULARIO DE BÚSQUEDA -->
            <div id="search" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Búsqueda de Destinos</h3>
                    <p>Encuentra los mejores destinos turísticos de Ecuador. Filtra por ubicación, tipo de viaje y fechas.</p>
                </div>

                <form id="searchForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="destination">
                                <i class="fas fa-map-marker-alt"></i> Destino
                            </label>
                            <select id="destination" name="destination" required>
                                <option value="">Selecciona un destino</option>
                                <option value="quito">Quito - Capital</option>
                                <option value="guayaquil">Guayaquil - Puerto Principal</option>
                                <option value="cuenca">Cuenca - Patrimonio Cultural</option>
                                <option value="galapagos">Galápagos - Islas Encantadas</option>
                                <option value="banos">Baños - Aventura</option>
                                <option value="manta">Manta - Playa</option>
                                <option value="salinas">Salinas - Costa</option>
                            </select>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Destino seleccionado
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tripType">
                                <i class="fas fa-plane"></i> Tipo de Viaje
                            </label>
                            <select id="tripType" name="tripType" required>
                                <option value="">Selecciona tipo de viaje</option>
                                <option value="cultural">Cultural</option>
                                <option value="aventura">Aventura</option>
                                <option value="playa">Playa</option>
                                <option value="naturaleza">Naturaleza</option>
                                <option value="gastronomia">Gastronomía</option>
                                <option value="romantico">Romántico</option>
                            </select>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Tipo seleccionado
                            </div>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="checkIn">
                                <i class="fas fa-calendar-plus"></i> Fecha de Llegada
                            </label>
                            <input type="date" id="checkIn" name="checkIn" required>
                            <div class="validation-message warning">
                                <i class="fas fa-exclamation-triangle"></i> Selecciona fecha
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="checkOut">
                                <i class="fas fa-calendar-minus"></i> Fecha de Salida
                            </label>
                            <input type="date" id="checkOut" name="checkOut" required>
                            <div class="validation-message warning">
                                <i class="fas fa-exclamation-triangle"></i> Selecciona fecha
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="travelers">
                                <i class="fas fa-users"></i> Número de Viajeros
                            </label>
                            <select id="travelers" name="travelers" required>
                                <option value="">Selecciona</option>
                                <option value="1">1 persona</option>
                                <option value="2">2 personas</option>
                                <option value="3">3 personas</option>
                                <option value="4">4 personas</option>
                                <option value="5">5 personas</option>
                                <option value="6+">6+ personas</option>
                            </select>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Cantidad seleccionada
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="budget">
                                <i class="fas fa-dollar-sign"></i> Presupuesto (USD)
                            </label>
                            <select id="budget" name="budget">
                                <option value="">Sin límite</option>
                                <option value="500">Hasta $500</option>
                                <option value="1000">Hasta $1,000</option>
                                <option value="2000">Hasta $2,000</option>
                                <option value="5000">Hasta $5,000</option>
                                <option value="10000">Hasta $10,000</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="accommodation">
                                <i class="fas fa-bed"></i> Tipo de Alojamiento
                            </label>
                            <select id="accommodation" name="accommodation">
                                <option value="">Cualquiera</option>
                                <option value="hotel">Hotel</option>
                                <option value="hostal">Hostal</option>
                                <option value="cabin">Cabaña</option>
                                <option value="resort">Resort</option>
                                <option value="apartment">Apartamento</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="includeFlights" name="includeFlights">
                        <label for="includeFlights">Incluir vuelos en la búsqueda</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="includeActivities" name="includeActivities">
                        <label for="includeActivities">Incluir actividades turísticas</label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-search"></i> Buscar Destinos
                    </button>
                </form>

                <!-- PREVIEW DE DESTINO -->
                <div class="destination-preview">
                    <h4><i class="fas fa-eye"></i> Vista Previa del Destino</h4>
                    <div class="destination-details">
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><strong>Quito, Ecuador</strong></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-mountain"></i>
                            <span>Altura: 2,850m</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-thermometer-half"></i>
                            <span>Clima: 15-25°C</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-star"></i>
                            <span>Calificación: 4.8/5</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <span>Visitantes: 2.5M/año</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>Mejor época: Todo el año</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO DE RESERVA -->
            <div id="booking" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Reserva de Paquete Turístico</h3>
                    <p>Confirma tu reserva y proporciona los datos necesarios para completar la transacción.</p>
                </div>

                <form id="bookingForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="packageName">
                                <i class="fas fa-box"></i> Paquete Seleccionado
                            </label>
                            <input type="text" id="packageName" name="packageName" value="Quito Cultural - 3 días" readonly>
                        </div>

                        <div class="form-group">
                            <label for="packageCode">
                                <i class="fas fa-barcode"></i> Código de Paquete
                            </label>
                            <input type="text" id="packageCode" name="packageCode" value="QUI-CUL-001" readonly>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="bookingDate">
                                <i class="fas fa-calendar"></i> Fecha de Reserva
                            </label>
                            <input type="date" id="bookingDate" name="bookingDate" required>
                        </div>

                        <div class="form-group">
                            <label for="adults">
                                <i class="fas fa-user"></i> Adultos
                            </label>
                            <select id="adults" name="adults" required>
                                <option value="">Selecciona</option>
                                <option value="1">1</option>
                                <option value="2" selected>2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="children">
                                <i class="fas fa-child"></i> Niños
                            </label>
                            <select id="children" name="children">
                                <option value="0" selected>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specialRequests">
                            <i class="fas fa-comment"></i> Solicitudes Especiales
                        </label>
                        <textarea id="specialRequests" name="specialRequests" rows="3" placeholder="Alimentación especial, accesibilidad, etc."></textarea>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="travelInsurance" name="travelInsurance">
                        <label for="travelInsurance">Incluir seguro de viaje (+$45 por persona)</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="airportTransfer" name="airportTransfer">
                        <label for="airportTransfer">Incluir traslado al aeropuerto (+$25 por persona)</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="guideService" name="guideService">
                        <label for="guideService">Incluir guía turístico (+$80 por día)</label>
                    </div>

                    <!-- DISPLAY DE PRECIO -->
                    <div class="price-display">
                        <div class="price-amount">$1,250 USD</div>
                        <div class="price-breakdown">
                            Paquete base: $1,200 | Seguro: $90 | Traslado: $50 | Total: $1,250
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-credit-card"></i> Confirmar Reserva
                    </button>
                </form>
            </div>

            <!-- FORMULARIO DE CONTACTO -->
            <div id="contact" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Contacto y Consultas</h3>
                    <p>¿Tienes preguntas específicas? Nuestro equipo de expertos te ayudará a planificar tu viaje perfecto.</p>
                </div>

                <form id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactName">
                                <i class="fas fa-user"></i> Nombre Completo
                            </label>
                            <input type="text" id="contactName" name="contactName" placeholder="Tu nombre completo" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Campo válido
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contactEmail">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input type="email" id="contactEmail" name="contactEmail" placeholder="tu@email.com" required>
                            <div class="validation-message success">
                                <i class="fas fa-check-circle"></i> Email válido
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactPhone">
                                <i class="fas fa-phone"></i> Teléfono
                            </label>
                            <input type="tel" id="contactPhone" name="contactPhone" placeholder="+593 99 123 4567" required>
                        </div>

                        <div class="form-group">
                            <label for="contactSubject">
                                <i class="fas fa-tag"></i> Asunto
                            </label>
                            <select id="contactSubject" name="contactSubject" required>
                                <option value="">Selecciona un asunto</option>
                                <option value="consulta">Consulta General</option>
                                <option value="reserva">Modificar Reserva</option>
                                <option value="cancelacion">Cancelación</option>
                                <option value="reembolso">Reembolso</option>
                                <option value="problema">Reportar Problema</option>
                                <option value="sugerencia">Sugerencia</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contactMessage">
                            <i class="fas fa-comment-alt"></i> Mensaje
                        </label>
                        <textarea id="contactMessage" name="contactMessage" rows="5" placeholder="Describe tu consulta o solicitud..." required></textarea>
                        <div class="validation-message warning">
                            <i class="fas fa-exclamation-triangle"></i> Mínimo 10 caracteres
                        </div>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="newsletterContact" name="newsletterContact">
                        <label for="newsletterContact">Suscribirme al boletín de ofertas turísticas</label>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="urgentContact" name="urgentContact">
                        <label for="urgentContact">Marca como urgente (respuesta en 2 horas)</label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Enviar Consulta
                    </button>
                </form>

                <div class="form-footer">
                    <p><i class="fas fa-clock"></i> Horario de atención: Lunes a Domingo 8:00 AM - 8:00 PM</p>
                    <p><i class="fas fa-phone"></i> Teléfono: +593 2 234 5678 | WhatsApp: +593 99 123 4567</p>
                </div>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura del Formulario RF2</h4>
            <p><strong>URL:</strong> http://localhost/registro/registro/formulario_rf2.php</p>
            <p><strong>Estado:</strong> Prototipo funcional - Búsqueda y Reserva de Destinos</p>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Búsqueda de destinos enviada (Simulación)');
        });

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Reserva confirmada (Simulación)');
        });

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Consulta enviada (Simulación)');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const formContainer = document.querySelector('.form-container');
            formContainer.style.opacity = '0';
            formContainer.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                formContainer.style.transition = 'all 0.6s ease';
                formContainer.style.opacity = '1';
                formContainer.style.transform = 'translateY(0)';
            }, 100);

            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            const nextWeek = new Date(today);
            nextWeek.setDate(nextWeek.getDate() + 7);

            document.getElementById('checkIn').value = tomorrow.toISOString().split('T')[0];
            document.getElementById('checkOut').value = nextWeek.toISOString().split('T')[0];
            document.getElementById('bookingDate').value = today.toISOString().split('T')[0];
        });
    </script>
</body>
</html>
