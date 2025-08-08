<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas y Pagos - Portal Turístico</title>
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
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
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
            font-size: 1.1em;
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
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
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

        .form-row-4 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
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
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .reservation-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .reservation-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }

        .payment-method.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .payment-method i {
            font-size: 2em;
            margin-bottom: 10px;
            color: #007bff;
        }

        .price-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .price-total {
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            font-weight: bold;
            font-size: 1.2em;
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

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 20px;
        }

        .calendar-day {
            padding: 10px;
            text-align: center;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .calendar-day:hover {
            background: #f8f9fa;
        }

        .calendar-day.selected {
            background: #007bff;
            color: white;
        }

        .calendar-day.available {
            background: #d4edda;
        }

        .calendar-day.unavailable {
            background: #f8d7da;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .form-row,
            .form-row-3,
            .form-row-4 {
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

            .payment-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF7</div>
        
        <div class="form-header">
            <h1>🌴 Portal Turístico Ecuador</h1>
            <p>Sistema de Reservas y Pagos - Prototipo RF7</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('reservations')">
                    <i class="fas fa-calendar-check"></i> Reservas
                </button>
                <button class="tab" onclick="showTab('payment')">
                    <i class="fas fa-credit-card"></i> Pagos
                </button>
                <button class="tab" onclick="showTab('history')">
                    <i class="fas fa-history"></i> Historial
                </button>
                <button class="tab" onclick="showTab('settings')">
                    <i class="fas fa-cog"></i> Configuración
                </button>
            </div>

            <!-- RESERVAS -->
            <div id="reservations" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Sistema de Reservas</h3>
                    <p>Gestiona reservas de alojamientos, tours y servicios turísticos con confirmación automática y notificaciones.</p>
                </div>

                <form id="reservationForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="serviceType">
                                <i class="fas fa-tag"></i> Tipo de Servicio
                            </label>
                            <select id="serviceType" name="serviceType" required>
                                <option value="">Selecciona tipo de servicio</option>
                                <option value="hotel">Hotel/Alojamiento</option>
                                <option value="tour">Tour Guiado</option>
                                <option value="transport">Transporte</option>
                                <option value="activity">Actividad Turística</option>
                                <option value="restaurant">Restaurante</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="destination">
                                <i class="fas fa-map-marker-alt"></i> Destino
                            </label>
                            <select id="destination" name="destination" required>
                                <option value="">Selecciona destino</option>
                                <option value="quito">Quito</option>
                                <option value="guayaquil">Guayaquil</option>
                                <option value="cuenca">Cuenca</option>
                                <option value="galapagos">Galápagos</option>
                                <option value="manta">Manta</option>
                                <option value="salinas">Salinas</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="checkIn">
                                <i class="fas fa-calendar-plus"></i> Fecha de Llegada
                            </label>
                            <input type="date" id="checkIn" name="checkIn" required>
                        </div>

                        <div class="form-group">
                            <label for="checkOut">
                                <i class="fas fa-calendar-minus"></i> Fecha de Salida
                            </label>
                            <input type="date" id="checkOut" name="checkOut" required>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="adults">
                                <i class="fas fa-user"></i> Adultos
                            </label>
                            <select id="adults" name="adults" required>
                                <option value="1">1</option>
                                <option value="2" selected>2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6+</option>
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
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="rooms">
                                <i class="fas fa-bed"></i> Habitaciones
                            </label>
                            <select id="rooms" name="rooms" required>
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specialRequests">
                            <i class="fas fa-comment"></i> Solicitudes Especiales
                        </label>
                        <textarea id="specialRequests" name="specialRequests" rows="3" placeholder="Habitación con vista, cuna para bebé, dieta especial..."></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Opciones Adicionales</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="breakfast" name="options[]" value="breakfast">
                                <label for="breakfast">Desayuno incluido</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="airport" name="options[]" value="airport">
                                <label for="airport">Traslado aeropuerto</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="insurance" name="options[]" value="insurance">
                                <label for="insurance">Seguro de viaje</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-search"></i> Buscar Disponibilidad
                    </button>
                </form>

                <!-- RESULTADOS DE BÚSQUEDA -->
                <div id="searchResults" style="display: none;">
                    <h3><i class="fas fa-list"></i> Resultados Disponibles</h3>
                    
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h4><i class="fas fa-hotel"></i> Hotel Plaza Grande - Quito</h4>
                            <span class="reservation-status status-confirmed">Disponible</span>
                        </div>
                        <p><strong>Ubicación:</strong> Centro Histórico de Quito</p>
                        <p><strong>Precio por noche:</strong> $120 USD</p>
                        <p><strong>Servicios incluidos:</strong> WiFi, Desayuno, Gym</p>
                        <button class="submit-btn" onclick="selectReservation('hotel1')">
                            <i class="fas fa-check"></i> Seleccionar
                        </button>
                    </div>

                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h4><i class="fas fa-hotel"></i> Hostal Quito Colonial</h4>
                            <span class="reservation-status status-confirmed">Disponible</span>
                        </div>
                        <p><strong>Ubicación:</strong> La Mariscal, Quito</p>
                        <p><strong>Precio por noche:</strong> $45 USD</p>
                        <p><strong>Servicios incluidos:</strong> WiFi, Cocina compartida</p>
                        <button class="submit-btn" onclick="selectReservation('hotel2')">
                            <i class="fas fa-check"></i> Seleccionar
                        </button>
                    </div>
                </div>
            </div>

            <!-- PAGOS -->
            <div id="payment" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Sistema de Pagos Seguro</h3>
                    <p>Procesa pagos de forma segura con múltiples métodos de pago y confirmación automática.</p>
                </div>

                <div class="price-summary">
                    <h4><i class="fas fa-calculator"></i> Resumen de Pago</h4>
                    <div class="price-row">
                        <span>Hotel Plaza Grande (3 noches)</span>
                        <span>$360.00</span>
                    </div>
                    <div class="price-row">
                        <span>Desayuno incluido</span>
                        <span>$45.00</span>
                    </div>
                    <div class="price-row">
                        <span>Traslado aeropuerto</span>
                        <span>$25.00</span>
                    </div>
                    <div class="price-row">
                        <span>Impuestos</span>
                        <span>$21.50</span>
                    </div>
                    <div class="price-row price-total">
                        <span>Total</span>
                        <span>$451.50</span>
                    </div>
                </div>

                <form id="paymentForm">
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Método de Pago</label>
                        <div class="payment-methods">
                            <div class="payment-method" onclick="selectPayment('credit')">
                                <i class="fas fa-credit-card"></i>
                                <p>Tarjeta de Crédito</p>
                            </div>
                            <div class="payment-method" onclick="selectPayment('debit')">
                                <i class="fas fa-credit-card"></i>
                                <p>Tarjeta de Débito</p>
                            </div>
                            <div class="payment-method" onclick="selectPayment('paypal')">
                                <i class="fab fa-paypal"></i>
                                <p>PayPal</p>
                            </div>
                            <div class="payment-method" onclick="selectPayment('transfer')">
                                <i class="fas fa-university"></i>
                                <p>Transferencia Bancaria</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cardNumber">
                                <i class="fas fa-credit-card"></i> Número de Tarjeta
                            </label>
                            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>

                        <div class="form-group">
                            <label for="cardHolder">
                                <i class="fas fa-user"></i> Titular de la Tarjeta
                            </label>
                            <input type="text" id="cardHolder" name="cardHolder" placeholder="Nombre completo">
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="expiryMonth">
                                <i class="fas fa-calendar"></i> Mes de Expiración
                            </label>
                            <select id="expiryMonth" name="expiryMonth">
                                <option value="">MM</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="expiryYear">
                                <i class="fas fa-calendar"></i> Año de Expiración
                            </label>
                            <select id="expiryYear" name="expiryYear">
                                <option value="">YYYY</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cvv">
                                <i class="fas fa-lock"></i> CVV
                            </label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="billingAddress">
                            <i class="fas fa-map-marker-alt"></i> Dirección de Facturación
                        </label>
                        <textarea id="billingAddress" name="billingAddress" rows="3" placeholder="Dirección completa para facturación"></textarea>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="saveCard" name="saveCard">
                        <label for="saveCard">Guardar tarjeta para futuras compras</label>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-shield-alt"></i> <strong>Pago Seguro:</strong> Todos los datos están protegidos con encriptación SSL de 256 bits.
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-lock"></i> Confirmar Pago
                    </button>
                </form>
            </div>

            <!-- HISTORIAL -->
            <div id="history" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Historial de Reservas</h3>
                    <p>Consulta el estado de tus reservas anteriores y futuras con detalles completos.</p>
                </div>

                <div class="reservation-card">
                    <div class="reservation-header">
                        <h4><i class="fas fa-hotel"></i> Hotel Plaza Grande - Quito</h4>
                        <span class="reservation-status status-confirmed">Confirmada</span>
                    </div>
                    <p><strong>Fechas:</strong> 15-18 Diciembre 2024</p>
                    <p><strong>Huéspedes:</strong> 2 adultos, 1 niño</p>
                    <p><strong>Total pagado:</strong> $451.50 USD</p>
                    <p><strong>Número de reserva:</strong> #RES-2024-001</p>
                    <div class="form-row">
                        <button class="submit-btn">
                            <i class="fas fa-print"></i> Imprimir Comprobante
                        </button>
                        <button class="submit-btn warning">
                            <i class="fas fa-edit"></i> Modificar Reserva
                        </button>
                    </div>
                </div>

                <div class="reservation-card">
                    <div class="reservation-header">
                        <h4><i class="fas fa-plane"></i> Tour Galápagos - Isla Isabela</h4>
                        <span class="reservation-status status-pending">Pendiente</span>
                    </div>
                    <p><strong>Fecha:</strong> 20 Enero 2025</p>
                    <p><strong>Participantes:</strong> 2 adultos</p>
                    <p><strong>Total:</strong> $280.00 USD</p>
                    <p><strong>Número de reserva:</strong> #RES-2024-002</p>
                    <div class="form-row">
                        <button class="submit-btn">
                            <i class="fas fa-credit-card"></i> Completar Pago
                        </button>
                        <button class="submit-btn danger">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>

                <div class="reservation-card">
                    <div class="reservation-header">
                        <h4><i class="fas fa-utensils"></i> Restaurante La Ronda - Cuenca</h4>
                        <span class="reservation-status status-cancelled">Cancelada</span>
                    </div>
                    <p><strong>Fecha:</strong> 10 Noviembre 2024</p>
                    <p><strong>Personas:</strong> 4 adultos</p>
                    <p><strong>Total reembolsado:</strong> $120.00 USD</p>
                    <p><strong>Número de reserva:</strong> #RES-2024-003</p>
                    <div class="form-row">
                        <button class="submit-btn">
                            <i class="fas fa-redo"></i> Reservar Nuevamente
                        </button>
                    </div>
                </div>
            </div>

            <!-- CONFIGURACIÓN -->
            <div id="settings" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Configuración de Cuenta</h3>
                    <p>Gestiona tus preferencias de pago, notificaciones y datos personales.</p>
                </div>

                <form id="settingsForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">
                                <i class="fas fa-user"></i> Nombre
                            </label>
                            <input type="text" id="firstName" name="firstName" value="María" required>
                        </div>

                        <div class="form-group">
                            <label for="lastName">
                                <i class="fas fa-user"></i> Apellido
                            </label>
                            <input type="text" id="lastName" name="lastName" value="González" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input type="email" id="email" name="email" value="maria.gonzalez@email.com" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i> Teléfono
                            </label>
                            <input type="tel" id="phone" name="phone" value="+593 99 123 4567">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">
                            <i class="fas fa-map-marker-alt"></i> Dirección
                        </label>
                        <textarea id="address" name="address" rows="3">Av. Amazonas N45-123, Quito, Ecuador</textarea>
                    </div>

                    <div class="form-group">
                        <label for="preferredCurrency">
                            <i class="fas fa-dollar-sign"></i> Moneda Preferida
                        </label>
                        <select id="preferredCurrency" name="preferredCurrency">
                            <option value="USD" selected>Dólar Estadounidense (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                            <option value="PEN">Sol Peruano (PEN)</option>
                            <option value="COP">Peso Colombiano (COP)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-bell"></i> Notificaciones</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="emailNotif" name="notifications[]" value="email" checked>
                                <label for="emailNotif">Notificaciones por email</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="smsNotif" name="notifications[]" value="sms">
                                <label for="smsNotif">Notificaciones por SMS</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="pushNotif" name="notifications[]" value="push" checked>
                                <label for="pushNotif">Notificaciones push</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-shield-alt"></i> Seguridad</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="twoFactor" name="security[]" value="2fa">
                                <label for="twoFactor">Autenticación de dos factores</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="savePayment" name="security[]" value="savePayment" checked>
                                <label for="savePayment">Guardar métodos de pago</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="autoLogin" name="security[]" value="autoLogin">
                                <label for="autoLogin">Inicio de sesión automático</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-save"></i> Guardar Configuración
                    </button>
                </form>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura de Pantalla</h4>
            <p><strong>Formulario:</strong> formulario_rf7.php</p>
            <p><strong>URL:</strong> http://localhost/registro/registro/formulario_rf7.php</p>
            <p><strong>Descripción:</strong> Sistema de reservas y pagos para el portal turístico</p>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Ocultar todos los contenidos de pestañas
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }

            // Desactivar todas las pestañas
            var tabs = document.getElementsByClassName('tab');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }

            // Mostrar el contenido de la pestaña seleccionada
            document.getElementById(tabName).classList.add('active');

            // Activar la pestaña seleccionada
            event.target.classList.add('active');
        }

        function selectPayment(method) {
            // Remover selección previa
            var methods = document.querySelectorAll('.payment-method');
            methods.forEach(function(method) {
                method.classList.remove('selected');
            });

            // Seleccionar método actual
            event.target.closest('.payment-method').classList.add('selected');
        }

        function selectReservation(reservationId) {
            alert('Reserva seleccionada: ' + reservationId);
            // Aquí se mostraría el formulario de pago
            showTab('payment');
        }

        // Simular funcionalidad de formularios
        document.addEventListener('DOMContentLoaded', function() {
            // Formulario de reserva
            document.getElementById('reservationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                document.getElementById('searchResults').style.display = 'block';
                alert('Búsqueda completada. Mostrando resultados disponibles.');
            });

            // Formulario de pago
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Pago procesado exitosamente. Reserva confirmada.');
            });

            // Formulario de configuración
            document.getElementById('settingsForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Configuración guardada exitosamente.');
            });
        });
    </script>
</body>
</html>
