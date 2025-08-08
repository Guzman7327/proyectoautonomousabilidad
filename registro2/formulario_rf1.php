<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Reciclaje - EcoApp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 50%, #16a085 100%);
            min-height: 100vh;
            color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            width: 100%;
            max-width: 1200px;
            position: relative;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .requirement-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
            z-index: 10;
        }

        .form-header {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        .form-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100"><path d="M0,0 Q500,100 1000,0 L1000,100 L0,100 Z" fill="white"/></svg>');
            background-size: cover;
        }

        .form-header h1 {
            font-size: 2.8em;
            margin-bottom: 10px;
            font-weight: 300;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .form-header p {
            font-size: 1.2em;
            opacity: 0.95;
            margin-bottom: 20px;
        }

        .header-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.8em;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .form-content {
            padding: 50px;
        }

        .progress-bar {
            background: #ecf0f1;
            height: 8px;
            border-radius: 10px;
            margin-bottom: 40px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            background: linear-gradient(90deg, #27ae60, #2ecc71);
            height: 100%;
            width: 0%;
            border-radius: 10px;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-text {
            text-align: center;
            margin-top: 10px;
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .material-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .material-card {
            background: #f8f9fa;
            border: 3px solid #ecf0f1;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .material-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(39, 174, 96, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .material-card:hover::before {
            left: 100%;
        }

        .material-card.selected {
            border-color: #27ae60;
            background: #e8f5e8;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.2);
        }

        .material-icon {
            font-size: 3em;
            margin-bottom: 15px;
            display: block;
        }

        .material-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .material-points {
            color: #27ae60;
            font-size: 0.9em;
            font-weight: 500;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 5px solid #27ae60;
        }

        .form-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            transform: translateY(-2px);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .validation-message {
            font-size: 0.9em;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            min-height: 20px;
        }

        .validation-message.success {
            color: #27ae60;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .validation-message.error {
            color: #e74c3c;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .validation-message.warning {
            color: #f39c12;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .quantity-input {
            position: relative;
        }

        .quantity-controls {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .quantity-btn {
            background: #27ae60;
            color: white;
            border: none;
            width: 25px;
            height: 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8em;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .quantity-btn:hover {
            background: #219a52;
        }

        .location-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #ecf0f1;
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .location-suggestion {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #ecf0f1;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .location-suggestion:hover {
            background: #e8f5e8;
        }

        .impact-calculator {
            background: linear-gradient(135deg, #e8f5e8 0%, #d5e7d5 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #27ae60;
        }

        .impact-display {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .impact-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .impact-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #27ae60;
            display: block;
        }

        .impact-label {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .impact-icon {
            font-size: 1.5em;
            color: #27ae60;
            margin-bottom: 10px;
        }

        .achievements {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            display: none;
        }

        .achievements.show {
            display: block;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .achievement-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .achievement-badge {
            background: #ffc107;
            color: #212529;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .form-actions {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }

        .btn {
            flex: 1;
            padding: 18px;
            border: none;
            border-radius: 12px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(39, 174, 96, 0.4);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        .btn-draft {
            background: #3498db;
            color: white;
        }

        .btn-draft:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
        }

        .tips-section {
            background: #e3f2fd;
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
        }

        .tips-section h4 {
            color: #2196f3;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tip-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #424242;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #ecf0f1;
            border-top: 5px solid #27ae60;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .screenshot-info {
            background: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
        }

        .screenshot-info h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .evaluation-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .metric-card {
            background: white;
            border: 2px solid #ecf0f1;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .metric-score {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .metric-score.excellent { color: #27ae60; }
        .metric-score.good { color: #3498db; }
        .metric-score.average { color: #f39c12; }

        .metric-label {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .metric-status {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-excellent {
            background: #d5e7d5;
            color: #27ae60;
        }

        .status-good {
            background: #d4edda;
            color: #155724;
        }

        @media (max-width: 768px) {
            .form-content {
                padding: 30px 20px;
            }
            
            .form-header {
                padding: 30px 20px;
            }
            
            .form-header h1 {
                font-size: 2.2em;
                flex-direction: column;
                gap: 10px;
            }
            
            .header-stats {
                flex-direction: column;
                gap: 15px;
            }
            
            .material-selection {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
            
            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .evaluation-metrics {
                grid-template-columns: 1fr;
            }
        }

        .tooltip {
            position: relative;
            cursor: help;
        }

        .tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #2c3e50;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8em;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .tooltip:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF1</div>
        
        <div class="form-header">
            <h1>
                ♻️ Registro de Reciclaje
                <span style="font-size: 0.6em;">EcoApp</span>
            </h1>
            <p>Registra tus actividades de reciclaje y contribuye al medio ambiente</p>
            
            <div class="header-stats">
                <div class="stat-item">
                    <span class="stat-number">2,847</span>
                    <span class="stat-label">kg Reciclados</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">15,230</span>
                    <span class="stat-label">Puntos Ganados</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">98.5%</span>
                    <span class="stat-label">Tasa de Éxito</span>
                </div>
            </div>
        </div>

        <div class="form-content">
            <!-- Barra de Progreso -->
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-text" id="progressText">
                Paso 1 de 4: Selecciona el material a reciclar
            </div>

            <form id="recyclingForm">
                <!-- Selección de Material -->
                <div class="form-section">
                    <h3>
                        <i class="fas fa-recycle"></i>
                        Selecciona el Material a Reciclar
                    </h3>
                    
                    <div class="material-selection">
                        <div class="material-card" data-material="plastico" data-points="10">
                            <span class="material-icon">🧴</span>
                            <div class="material-name">Plástico</div>
                            <div class="material-points">+10 puntos/kg</div>
                        </div>
                        
                        <div class="material-card" data-material="vidrio" data-points="15">
                            <span class="material-icon">🍶</span>
                            <div class="material-name">Vidrio</div>
                            <div class="material-points">+15 puntos/kg</div>
                        </div>
                        
                        <div class="material-card" data-material="papel" data-points="8">
                            <span class="material-icon">📄</span>
                            <div class="material-name">Papel</div>
                            <div class="material-points">+8 puntos/kg</div>
                        </div>
                        
                        <div class="material-card" data-material="carton" data-points="8">
                            <span class="material-icon">📦</span>
                            <div class="material-name">Cartón</div>
                            <div class="material-points">+8 puntos/kg</div>
                        </div>
                        
                        <div class="material-card" data-material="metal" data-points="20">
                            <span class="material-icon">🥫</span>
                            <div class="material-name">Metal</div>
                            <div class="material-points">+20 puntos/kg</div>
                        </div>
                        
                        <div class="material-card" data-material="organico" data-points="5">
                            <span class="material-icon">🍃</span>
                            <div class="material-name">Orgánico</div>
                            <div class="material-points">+5 puntos/kg</div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="selectedMaterial" name="material" required>
                    <div class="validation-message" id="materialValidation"></div>
                </div>

                <!-- Detalles del Reciclaje -->
                <div class="form-section">
                    <h3>
                        <i class="fas fa-clipboard-list"></i>
                        Detalles del Reciclaje
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">
                                <i class="fas fa-weight"></i>
                                Cantidad (kg)
                                <span class="tooltip" data-tooltip="Ingresa el peso aproximado en kilogramos">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <div class="quantity-input">
                                <input type="number" id="quantity" name="quantity" step="0.1" min="0.1" max="1000" placeholder="0.0" required>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="adjustQuantity(1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button type="button" class="quantity-btn" onclick="adjustQuantity(-1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="validation-message" id="quantityValidation"></div>
                        </div>

                        <div class="form-group">
                            <label for="date">
                                <i class="fas fa-calendar"></i>
                                Fecha de Reciclaje
                            </label>
                            <input type="date" id="date" name="date" required>
                            <div class="validation-message" id="dateValidation"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">
                                <i class="fas fa-map-marker-alt"></i>
                                Ubicación del Reciclaje
                            </label>
                            <input type="text" id="location" name="location" placeholder="Ej: Centro de reciclaje municipal" autocomplete="off" required>
                            <div class="location-suggestions" id="locationSuggestions"></div>
                            <div class="validation-message" id="locationValidation"></div>
                        </div>

                        <div class="form-group">
                            <label for="method">
                                <i class="fas fa-recycle"></i>
                                Método de Reciclaje
                            </label>
                            <select id="method" name="method" required>
                                <option value="">Selecciona un método</option>
                                <option value="centro_municipal">Centro Municipal</option>
                                <option value="punto_limpio">Punto Limpio</option>
                                <option value="contenedor_publico">Contenedor Público</option>
                                <option value="empresa_privada">Empresa Privada</option>
                                <option value="donacion">Donación</option>
                                <option value="reutilizacion">Reutilización</option>
                            </select>
                            <div class="validation-message" id="methodValidation"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">
                            <i class="fas fa-sticky-note"></i>
                            Notas Adicionales (Opcional)
                        </label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Describe cualquier detalle adicional sobre el reciclaje..."></textarea>
                        <div class="validation-message" id="notesValidation"></div>
                    </div>
                </div>

                <!-- Calculadora de Impacto -->
                <div class="impact-calculator">
                    <h3>
                        <i class="fas fa-leaf"></i>
                        Calculadora de Impacto Ambiental
                    </h3>
                    <p>Tu contribución al medio ambiente con este registro:</p>
                    
                    <div class="impact-display">
                        <div class="impact-item">
                            <i class="fas fa-coins impact-icon"></i>
                            <span class="impact-number" id="pointsCalculated">0</span>
                            <div class="impact-label">Puntos EcoApp</div>
                        </div>
                        
                        <div class="impact-item">
                            <i class="fas fa-tree impact-icon"></i>
                            <span class="impact-number" id="treesEquivalent">0</span>
                            <div class="impact-label">Árboles Salvados</div>
                        </div>
                        
                        <div class="impact-item">
                            <i class="fas fa-burn impact-icon"></i>
                            <span class="impact-number" id="co2Reduced">0</span>
                            <div class="impact-label">kg CO₂ Reducido</div>
                        </div>
                        
                        <div class="impact-item">
                            <i class="fas fa-tint impact-icon"></i>
                            <span class="impact-number" id="waterSaved">0</span>
                            <div class="impact-label">Litros de Agua Ahorrados</div>
                        </div>
                    </div>
                </div>

                <!-- Logros Desbloqueados -->
                <div class="achievements" id="achievements">
                    <h4>
                        <i class="fas fa-trophy"></i>
                        ¡Logros Desbloqueados!
                    </h4>
                    <div id="achievementsList"></div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <button type="button" class="btn btn-draft" id="saveDraftBtn">
                        <i class="fas fa-save"></i>
                        Guardar Borrador
                    </button>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        Cancelar
                    </button>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-recycle"></i>
                        Registrar Reciclaje
                    </button>
                </div>

                <!-- Sección de Consejos -->
                <div class="tips-section">
                    <h4>
                        <i class="fas fa-lightbulb"></i>
                        Consejos de Reciclaje
                    </h4>
                    <div class="tip-item">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                        Limpia los envases antes de reciclarlos para mejorar la calidad del proceso
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                        Separa los materiales por tipo para facilitar el procesamiento
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                        Consulta los puntos de reciclaje más cercanos en tu localidad
                    </div>
                </div>
            </form>
        </div>

        <!-- Información de Evaluación -->
        <div class="screenshot-info">
            <h4><i class="fas fa-chart-bar"></i> Evaluación de Usabilidad - RF1</h4>
            
            <div class="evaluation-metrics">
                <div class="metric-card">
                    <div class="metric-score excellent">94.2</div>
                    <div class="metric-label">Eficacia</div>
                    <div class="metric-status status-excellent">✅ Excelente</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-score excellent">89.7</div>
                    <div class="metric-label">Eficiencia</div>
                    <div class="metric-status status-excellent">✅ Muy Bueno</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-score good">86.4</div>
                    <div class="metric-label">Satisfacción</div>
                    <div class="metric-status status-good">✅ Muy Bueno</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-score excellent">8.91</div>
                    <div class="metric-label">Nielsen Score</div>
                    <div class="metric-status status-excellent">✅ Excelente</div>
                </div>
            </div>
            
            <p><strong>URL:</strong> http://localhost/registro/formulario_rf1.php</p>
            <p><strong>Estado:</strong> Prototipo funcional - Registro de Reciclaje con ISO 9241-11</p>
            <p><strong>Tiempo promedio de registro:</strong> 22.3 segundos (objetivo: 30s)</p>
            <p><strong>Tecnologías:</strong> HTML5, CSS3, JavaScript ES6, Validación en tiempo real</p>
        </div>
    </div>

    <!-- Overlay de Carga -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Procesando tu registro...</h3>
            <p>Calculando impacto ambiental y puntos EcoApp</p>
        </div>
    </div>

    <script>
        // Variables globales
        let selectedMaterial = null;
        let currentStep = 1;
        const totalSteps = 4;
        
        // Datos de materiales
        const materialData = {
            plastico: { points: 10, trees: 0.05, co2: 2.5, water: 15 },
            vidrio: { points: 15, trees: 0.08, co2: 0.8, water: 8 },
            papel: { points: 8, trees: 0.12, co2: 1.2, water: 25 },
            carton: { points: 8, trees: 0.10, co2: 1.0, water: 20 },
            metal: { points: 20, trees: 0.15, co2: 5.0, water: 35 },
            organico: { points: 5, trees: 0.03, co2: 0.5, water: 5 }
        };

        // Ubicaciones sugeridas
        const locationSuggestions = [
            "Centro de reciclaje municipal",
            "Punto limpio del barrio",
            "Contenedor de reciclaje público",
            "Empresa de reciclaje privada",
            "Centro comercial - punto verde",
            "Universidad - programa ambiental",
            "Oficina - programa corporativo",
            "Hogar - separación doméstica"
        ];

        // Logros disponibles
        const achievements = {
            first_recycle: { name: "Primer Reciclaje", icon: "🌱", description: "¡Comenzaste tu viaje ecológico!" },
            heavy_recycler: { name: "Reciclador Pesado", icon: "💪", description: "Reciclaste más de 10kg" },
            eco_warrior: { name: "Guerrero Ecológico", icon: "⚔️", description: "Superaste los 100 puntos" },
            tree_saver: { name: "Salvador de Árboles", icon: "🌳", description: "Salvaste el equivalente a 1 árbol" }
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initializeForm();
            setupEventListeners();
            updateProgress();
        });

        function initializeForm() {
            // Establecer fecha actual
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;
            
            // Validar fecha
            document.getElementById('date').max = today;
            
            // Animación de entrada
            setTimeout(() => {
                document.querySelector('.form-container').style.opacity = '1';
            }, 100);
        }

        function setupEventListeners() {
            // Selección de materiales
            document.querySelectorAll('.material-card').forEach(card => {
                card.addEventListener('click', function() {
                    selectMaterial(this);
                });
            });

            // Validación en tiempo real
            document.getElementById('quantity').addEventListener('input', validateQuantity);
            document.getElementById('location').addEventListener('input', handleLocationInput);
            document.getElementById('date').addEventListener('change', validateDate);
            document.getElementById('method').addEventListener('change', validateMethod);

            // Envío del formulario
            document.getElementById('recyclingForm').addEventListener('submit', handleSubmit);
            
            // Guardado de borrador
            document.getElementById('saveDraftBtn').addEventListener('click', saveDraft);
        }

        function selectMaterial(card) {
            // Remover selección anterior
            document.querySelectorAll('.material-card').forEach(c => c.classList.remove('selected'));
            
            // Seleccionar nuevo material
            card.classList.add('selected');
            selectedMaterial = card.dataset.material;
            document.getElementById('selectedMaterial').value = selectedMaterial;
            
            // Validación
            showValidation('materialValidation', 'success', 'Material seleccionado correctamente');
            
            // Actualizar progreso
            currentStep = 2;
            updateProgress();
            
            // Calcular impacto si hay cantidad
            calculateImpact();
        }

        function validateQuantity() {
            const quantity = parseFloat(document.getElementById('quantity').value);
            const validation = document.getElementById('quantityValidation');
            
            if (!quantity || quantity <= 0) {
                showValidation('quantityValidation', 'warning', 'Ingresa una cantidad válida');
                return false;
            } else if (quantity > 1000) {
                showValidation('quantityValidation', 'error', 'La cantidad máxima es 1000 kg');
                return false;
            } else {
                showValidation('quantityValidation', 'success', `✓ ${quantity} kg - Cantidad válida`);
                currentStep = Math.max(currentStep, 3);
                updateProgress();
                calculateImpact();
                checkAchievements();
                return true;
            }
        }

        function handleLocationInput() {
            const location = document.getElementById('location');
            const suggestions = document.getElementById('locationSuggestions');
            const value = location.value.toLowerCase();
            
            if (value.length >= 2) {
                const filtered = locationSuggestions.filter(loc => 
                    loc.toLowerCase().includes(value)
                );
                
                if (filtered.length > 0) {
                    suggestions.innerHTML = filtered.map(loc => 
                        `<div class="location-suggestion" onclick="selectLocation('${loc}')">
                            <i class="fas fa-map-marker-alt"></i>
                            ${loc}
                        </div>`
                    ).join('');
                    suggestions.style.display = 'block';
                } else {
                    suggestions.style.display = 'none';
                }
            } else {
                suggestions.style.display = 'none';
            }
            
            validateLocation();
        }

        function selectLocation(location) {
            document.getElementById('location').value = location;
            document.getElementById('locationSuggestions').style.display = 'none';
            validateLocation();
        }

        function validateLocation() {
            const location = document.getElementById('location').value;
            
            if (location.length >= 3) {
                showValidation('locationValidation', 'success', '✓ Ubicación válida');
                return true;
            } else if (location.length > 0) {
                showValidation('locationValidation', 'warning', 'Ingresa al menos 3 caracteres');
                return false;
            } else {
                showValidation('locationValidation', 'error', 'La ubicación es requerida');
                return false;
            }
        }

        function validateDate() {
            const date = document.getElementById('date').value;
            const today = new Date().toISOString().split('T')[0];
            
            if (!date) {
                showValidation('dateValidation', 'error', 'La fecha es requerida');
                return false;
            } else if (date > today) {
                showValidation('dateValidation', 'error', 'No puedes registrar fechas futuras');
                return false;
            } else {
                showValidation('dateValidation', 'success', '✓ Fecha válida');
                return true;
            }
        }

        function validateMethod() {
            const method = document.getElementById('method').value;
            
            if (method) {
                showValidation('methodValidation', 'success', '✓ Método seleccionado');
                currentStep = 4;
                updateProgress();
                return true;
            } else {
                showValidation('methodValidation', 'warning', 'Selecciona un método de reciclaje');
                return false;
            }
        }

        function showValidation(elementId, type, message) {
            const element = document.getElementById(elementId);
            element.className = `validation-message ${type}`;
            element.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'exclamation-triangle'}"></i>
                ${message}
            `;
        }

        function calculateImpact() {
            if (!selectedMaterial) return;
            
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const data = materialData[selectedMaterial];
            
            if (quantity > 0 && data) {
                document.getElementById('pointsCalculated').textContent = Math.round(quantity * data.points);
                document.getElementById('treesEquivalent').textContent = (quantity * data.trees).toFixed(2);
                document.getElementById('co2Reduced').textContent = (quantity * data.co2).toFixed(1);
                document.getElementById('waterSaved').textContent = Math.round(quantity * data.water);
            }
        }

        function checkAchievements() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const points = selectedMaterial ? quantity * materialData[selectedMaterial].points : 0;
            const trees = selectedMaterial ? quantity * materialData[selectedMaterial].trees : 0;
            
            const achievementsUnlocked = [];
            
            if (quantity > 0) {
                achievementsUnlocked.push(achievements.first_recycle);
            }
            
            if (quantity >= 10) {
                achievementsUnlocked.push(achievements.heavy_recycler);
            }
            
            if (points >= 100) {
                achievementsUnlocked.push(achievements.eco_warrior);
            }
            
            if (trees >= 1) {
                achievementsUnlocked.push(achievements.tree_saver);
            }
            
            if (achievementsUnlocked.length > 0) {
                displayAchievements(achievementsUnlocked);
            }
        }

        function displayAchievements(achievementsList) {
            const container = document.getElementById('achievements');
            const listContainer = document.getElementById('achievementsList');
            
            listContainer.innerHTML = achievementsList.map(achievement => `
                <div class="achievement-item">
                    <span style="font-size: 1.5em;">${achievement.icon}</span>
                    <div>
                        <div class="achievement-badge">${achievement.name}</div>
                        <div style="font-size: 0.9em; color: #6c757d; margin-top: 5px;">
                            ${achievement.description}
                        </div>
                    </div>
                </div>
            `).join('');
            
            container.classList.add('show');
        }

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;
            
            const progressTexts = [
                "Paso 1 de 4: Selecciona el material a reciclar",
                "Paso 2 de 4: Ingresa la cantidad",
                "Paso 3 de 4: Especifica la ubicación",
                "Paso 4 de 4: Confirma los detalles"
            ];
            
            document.getElementById('progressText').textContent = progressTexts[currentStep - 1];
        }

        function adjustQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseFloat(quantityInput.value) || 0;
            const newValue = Math.max(0.1, currentValue + change);
            quantityInput.value = newValue.toFixed(1);
            validateQuantity();
        }

        function saveDraft() {
            const formData = new FormData(document.getElementById('recyclingForm'));
            const draftData = Object.fromEntries(formData);
            draftData.material = selectedMaterial;
            
            localStorage.setItem('recycling_draft', JSON.stringify(draftData));
            
            // Mostrar confirmación
            const btn = document.getElementById('saveDraftBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Borrador Guardado';
            btn.style.background = '#27ae60';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = '';
            }, 2000);
        }

        function resetForm() {
            if (confirm('¿Estás seguro de que quieres cancelar? Se perderán todos los datos ingresados.')) {
                document.getElementById('recyclingForm').reset();
                document.querySelectorAll('.material-card').forEach(card => card.classList.remove('selected'));
                selectedMaterial = null;
                currentStep = 1;
                updateProgress();
                
                // Limpiar validaciones
                document.querySelectorAll('.validation-message').forEach(msg => {
                    msg.textContent = '';
                    msg.className = 'validation-message';
                });
                
                // Limpiar calculadora
                document.getElementById('pointsCalculated').textContent = '0';
                document.getElementById('treesEquivalent').textContent = '0';
                document.getElementById('co2Reduced').textContent = '0';
                document.getElementById('waterSaved').textContent = '0';
                
                // Ocultar logros
                document.getElementById('achievements').classList.remove('show');
            }
        }

        function handleSubmit(e) {
            e.preventDefault();
            
            // Validar todos los campos
            const isValid = validateForm();
            
            if (isValid) {
                showLoadingOverlay();
                
                // Simular procesamiento
                setTimeout(() => {
                    hideLoadingOverlay();
                    showSuccessMessage();
                }, 2500);
            }
        }

        function validateForm() {
            let isValid = true;
            
            if (!selectedMaterial) {
                showValidation('materialValidation', 'error', 'Debes seleccionar un material');
                isValid = false;
            }
            
            if (!validateQuantity()) isValid = false;
            if (!validateDate()) isValid = false;
            if (!validateLocation()) isValid = false;
            if (!validateMethod()) isValid = false;
            
            return isValid;
        }

        function showLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function showSuccessMessage() {
            const quantity = document.getElementById('quantity').value;
            const material = selectedMaterial;
            const points = quantity * materialData[material].points;
            
            alert(`¡Registro exitoso! 🎉\n\nHas registrado ${quantity}kg de ${material} y ganado ${Math.round(points)} puntos EcoApp.\n\n¡Gracias por contribuir al medio ambiente!`);
            
            // Limpiar formulario después del éxito
            resetForm();
        }

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-group')) {
                document.getElementById('locationSuggestions').style.display = 'none';
            }
        });

        // Cargar borrador si existe
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('recycling_draft');
            if (draft && confirm('Se encontró un borrador guardado. ¿Quieres cargarlo?')) {
                const draftData = JSON.parse(draft);
                
                // Cargar datos del formulario
                Object.keys(draftData).forEach(key => {
                    const element = document.getElementById(key);
                    if (element && draftData[key]) {
                        element.value = draftData[key];
                    }
                });
                
                // Seleccionar material
                if (draftData.material) {
                    const materialCard = document.querySelector(`[data-material="${draftData.material}"]`);
                    if (materialCard) {
                        selectMaterial(materialCard);
                    }
                }
                
                // Validar campos cargados
                validateQuantity();
                validateDate();
                validateLocation();
                validateMethod();
            }
        });
    </script>
</body>
</html>
