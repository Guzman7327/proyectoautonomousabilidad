<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Análisis y Reportes - Portal Turístico</title>
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
            max-width: 1600px;
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
            flex-wrap: wrap;
        }

        .tab {
            flex: 1;
            min-width: 150px;
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

        .form-row-4 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 20px;
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

        .chart-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #2c3e50;
        }

        .chart-legend {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9em;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
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

        .report-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .report-section h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1em;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #0056b3);
            transition: width 0.3s ease;
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

        .filter-section {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .export-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .export-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .export-btn.pdf {
            background: #dc3545;
            color: white;
        }

        .export-btn.excel {
            background: #28a745;
            color: white;
        }

        .export-btn.csv {
            background: #17a2b8;
            color: white;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .tabs {
                flex-direction: column;
            }

            .tab {
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF8</div>
        
        <div class="form-header">
            <h1>📊 Portal Turístico Ecuador</h1>
            <p>Sistema de Análisis y Reportes - Prototipo RF8</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('dashboard')">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </button>
                <button class="tab" onclick="showTab('analytics')">
                    <i class="fas fa-chart-line"></i> Analytics
                </button>
                <button class="tab" onclick="showTab('reports')">
                    <i class="fas fa-file-alt"></i> Reportes
                </button>
                <button class="tab" onclick="showTab('predictions')">
                    <i class="fas fa-crystal-ball"></i> Predicciones
                </button>
                <button class="tab" onclick="showTab('export')">
                    <i class="fas fa-download"></i> Exportar
                </button>
            </div>

            <!-- DASHBOARD -->
            <div id="dashboard" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Dashboard Principal</h3>
                    <p>Vista general de métricas clave y tendencias del portal turístico en tiempo real.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value">15,432</div>
                        <div class="stat-label">Usuarios Activos</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value">2,847</div>
                        <div class="stat-label">Reservas Hoy</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">$45,230</div>
                        <div class="stat-label">Ingresos Diarios</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-value">4.7</div>
                        <div class="stat-label">Rating Promedio</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Reservas por Destino</div>
                            <div class="chart-legend">
                                <div class="legend-item">
                                    <div class="legend-color" style="background: #007bff;"></div>
                                    <span>Quito</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background: #28a745;"></div>
                                    <span>Galápagos</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background: #ffc107;"></div>
                                    <span>Cuenca</span>
                                </div>
                            </div>
                        </div>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-pie" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Reservas por Destino</p>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Tendencia de Ingresos</div>
                        </div>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-line" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Tendencia de Ingresos</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Estado del Sistema:</strong> Todos los servicios funcionando correctamente. Última actualización: hace 5 minutos.
                </div>
            </div>

            <!-- ANALYTICS -->
            <div id="analytics" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Análisis Avanzado</h3>
                    <p>Análisis detallado de comportamiento de usuarios, patrones de reserva y métricas de rendimiento.</p>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-filter"></i> Filtros de Análisis</h4>
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="dateRange">
                                <i class="fas fa-calendar"></i> Rango de Fechas
                            </label>
                            <select id="dateRange" name="dateRange">
                                <option value="today">Hoy</option>
                                <option value="week" selected>Última Semana</option>
                                <option value="month">Último Mes</option>
                                <option value="quarter">Último Trimestre</option>
                                <option value="year">Último Año</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="destination">
                                <i class="fas fa-map-marker-alt"></i> Destino
                            </label>
                            <select id="destination" name="destination">
                                <option value="">Todos los Destinos</option>
                                <option value="quito">Quito</option>
                                <option value="galapagos">Galápagos</option>
                                <option value="cuenca">Cuenca</option>
                                <option value="guayaquil">Guayaquil</option>
                                <option value="manta">Manta</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="serviceType">
                                <i class="fas fa-tag"></i> Tipo de Servicio
                            </label>
                            <select id="serviceType" name="serviceType">
                                <option value="">Todos los Servicios</option>
                                <option value="hotel">Hoteles</option>
                                <option value="tour">Tours</option>
                                <option value="transport">Transporte</option>
                                <option value="activity">Actividades</option>
                            </select>
                        </div>
                    </div>

                    <button class="submit-btn">
                        <i class="fas fa-search"></i> Aplicar Filtros
                    </button>
                </div>

                <div class="form-row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Comportamiento de Usuarios</div>
                        </div>
                        <div style="height: 250px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-user-chart" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Análisis de Comportamiento</p>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Conversión de Reservas</div>
                        </div>
                        <div style="height: 250px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-bar" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Tasa de Conversión</p>
                        </div>
                    </div>
                </div>

                <div class="report-section">
                    <h4><i class="fas fa-table"></i> Métricas Clave</h4>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Métrica</th>
                                <th>Valor Actual</th>
                                <th>Cambio</th>
                                <th>Tendencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tasa de Conversión</td>
                                <td>3.2%</td>
                                <td style="color: #28a745;">+0.5%</td>
                                <td><i class="fas fa-arrow-up" style="color: #28a745;"></i></td>
                            </tr>
                            <tr>
                                <td>Tiempo Promedio en Sitio</td>
                                <td>4m 32s</td>
                                <td style="color: #28a745;">+12s</td>
                                <td><i class="fas fa-arrow-up" style="color: #28a745;"></i></td>
                            </tr>
                            <tr>
                                <td>Tasa de Rebote</td>
                                <td>42%</td>
                                <td style="color: #dc3545;">+2%</td>
                                <td><i class="fas fa-arrow-down" style="color: #dc3545;"></i></td>
                            </tr>
                            <tr>
                                <td>Valor Promedio por Reserva</td>
                                <td>$156.80</td>
                                <td style="color: #28a745;">+$8.50</td>
                                <td><i class="fas fa-arrow-up" style="color: #28a745;"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- REPORTES -->
            <div id="reports" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Generación de Reportes</h3>
                    <p>Crea reportes personalizados con datos detallados y análisis específicos para diferentes stakeholders.</p>
                </div>

                <form id="reportForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="reportType">
                                <i class="fas fa-file-alt"></i> Tipo de Reporte
                            </label>
                            <select id="reportType" name="reportType" required>
                                <option value="">Selecciona tipo de reporte</option>
                                <option value="sales">Reporte de Ventas</option>
                                <option value="performance">Reporte de Rendimiento</option>
                                <option value="user">Reporte de Usuarios</option>
                                <option value="destination">Reporte por Destino</option>
                                <option value="financial">Reporte Financiero</option>
                                <option value="custom">Reporte Personalizado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reportPeriod">
                                <i class="fas fa-calendar-alt"></i> Período
                            </label>
                            <select id="reportPeriod" name="reportPeriod" required>
                                <option value="">Selecciona período</option>
                                <option value="daily">Diario</option>
                                <option value="weekly">Semanal</option>
                                <option value="monthly" selected>Mensual</option>
                                <option value="quarterly">Trimestral</option>
                                <option value="yearly">Anual</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">
                                <i class="fas fa-calendar-plus"></i> Fecha de Inicio
                            </label>
                            <input type="date" id="startDate" name="startDate" required>
                        </div>

                        <div class="form-group">
                            <label for="endDate">
                                <i class="fas fa-calendar-minus"></i> Fecha de Fin
                            </label>
                            <input type="date" id="endDate" name="endDate" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Incluir en el Reporte</label>
                        <div class="form-row-4">
                            <div class="form-checkbox">
                                <input type="checkbox" id="includeCharts" name="include[]" value="charts" checked>
                                <label for="includeCharts">Gráficos</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="includeTables" name="include[]" value="tables" checked>
                                <label for="includeTables">Tablas de Datos</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="includeSummary" name="include[]" value="summary" checked>
                                <label for="includeSummary">Resumen Ejecutivo</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="includeRecommendations" name="include[]" value="recommendations">
                                <label for="includeRecommendations">Recomendaciones</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reportNotes">
                            <i class="fas fa-comment"></i> Notas Adicionales
                        </label>
                        <textarea id="reportNotes" name="reportNotes" rows="3" placeholder="Observaciones o comentarios adicionales para el reporte..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-magic"></i> Generar Reporte
                    </button>
                </form>

                <!-- REPORTES RECIENTES -->
                <div class="report-section">
                    <h4><i class="fas fa-history"></i> Reportes Recientes</h4>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre del Reporte</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Reporte de Ventas - Diciembre 2024</td>
                                <td>Ventas</td>
                                <td>01/12/2024</td>
                                <td><span style="color: #28a745;">Completado</span></td>
                                <td>
                                    <button class="export-btn pdf">PDF</button>
                                    <button class="export-btn excel">Excel</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Análisis de Usuarios - Noviembre 2024</td>
                                <td>Usuarios</td>
                                <td>01/12/2024</td>
                                <td><span style="color: #28a745;">Completado</span></td>
                                <td>
                                    <button class="export-btn pdf">PDF</button>
                                    <button class="export-btn excel">Excel</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Rendimiento por Destino - Q4 2024</td>
                                <td>Destino</td>
                                <td>30/11/2024</td>
                                <td><span style="color: #ffc107;">En Proceso</span></td>
                                <td>
                                    <button class="submit-btn warning" disabled>Generando...</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PREDICCIONES -->
            <div id="predictions" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Análisis Predictivo</h3>
                    <p>Utiliza machine learning para predecir tendencias futuras, demanda de servicios y comportamiento de usuarios.</p>
                </div>

                <div class="form-row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Predicción de Reservas - Próximos 3 Meses</div>
                        </div>
                        <div style="height: 250px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-line" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Predicción de Reservas</p>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Análisis de Estacionalidad</div>
                        </div>
                        <div style="height: 250px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-area" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Patrones Estacionales</p>
                        </div>
                    </div>
                </div>

                <div class="report-section">
                    <h4><i class="fas fa-lightbulb"></i> Insights Predictivos</h4>
                    <div class="form-row">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="stat-value">+15%</div>
                            <div class="stat-label">Crecimiento Esperado</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-value">Enero</div>
                            <div class="stat-label">Pico de Demanda</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-value">Galápagos</div>
                            <div class="stat-label">Destino Más Popular</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-value">+25%</div>
                            <div class="stat-label">Nuevos Usuarios</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-robot"></i>
                    <strong>Modelo de IA:</strong> Utilizando algoritmos de machine learning con 94% de precisión en predicciones de demanda.
                </div>
            </div>

            <!-- EXPORTAR -->
            <div id="export" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Exportación de Datos</h3>
                    <p>Exporta datos y reportes en múltiples formatos para análisis externo y presentaciones.</p>
                </div>

                <div class="form-group">
                    <label for="exportType">
                        <i class="fas fa-download"></i> Tipo de Exportación
                    </label>
                    <select id="exportType" name="exportType" required>
                        <option value="">Selecciona tipo de exportación</option>
                        <option value="dashboard">Dashboard Completo</option>
                        <option value="analytics">Datos de Analytics</option>
                        <option value="reports">Reportes Generados</option>
                        <option value="predictions">Datos Predictivos</option>
                        <option value="raw">Datos Sin Procesar</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="exportFormat">
                        <i class="fas fa-file"></i> Formato de Exportación
                    </label>
                    <select id="exportFormat" name="exportFormat" required>
                        <option value="">Selecciona formato</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                        <option value="xml">XML</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="exportDateRange">
                        <i class="fas fa-calendar-range"></i> Rango de Fechas
                    </label>
                    <div class="form-row">
                        <input type="date" id="exportStartDate" name="exportStartDate" placeholder="Fecha de inicio">
                        <input type="date" id="exportEndDate" name="exportEndDate" placeholder="Fecha de fin">
                    </div>
                </div>

                <div class="export-options">
                    <button class="export-btn pdf">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                    <button class="export-btn excel">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </button>
                    <button class="export-btn csv">
                        <i class="fas fa-file-csv"></i> Exportar CSV
                    </button>
                    <button class="submit-btn">
                        <i class="fas fa-cog"></i> Configuración Avanzada
                    </button>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Nota:</strong> Los archivos grandes pueden tardar varios minutos en procesarse. Se enviará una notificación cuando esté listo.
                </div>

                <!-- HISTORIAL DE EXPORTACIONES -->
                <div class="report-section">
                    <h4><i class="fas fa-history"></i> Historial de Exportaciones</h4>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Tamaño</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>reporte_ventas_dic2024.pdf</td>
                                <td>PDF</td>
                                <td>01/12/2024</td>
                                <td>2.3 MB</td>
                                <td><span style="color: #28a745;">Completado</span></td>
                                <td>
                                    <button class="export-btn pdf">Descargar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>analytics_usuarios_nov2024.xlsx</td>
                                <td>Excel</td>
                                <td>30/11/2024</td>
                                <td>1.8 MB</td>
                                <td><span style="color: #28a745;">Completado</span></td>
                                <td>
                                    <button class="export-btn excel">Descargar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>predicciones_2025.csv</td>
                                <td>CSV</td>
                                <td>29/11/2024</td>
                                <td>856 KB</td>
                                <td><span style="color: #ffc107;">En Proceso</span></td>
                                <td>
                                    <button class="submit-btn warning" disabled>Procesando...</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura de Pantalla</h4>
            <p>Esta imagen muestra el Sistema de Análisis y Reportes (RF8) con todas sus funcionalidades de dashboard, analytics, reportes, predicciones y exportación de datos.</p>
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

        // Configurar fechas por defecto
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
            
            document.getElementById('startDate').value = lastMonth.toISOString().split('T')[0];
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
            
            document.getElementById('exportStartDate').value = lastMonth.toISOString().split('T')[0];
            document.getElementById('exportEndDate').value = today.toISOString().split('T')[0];
        });

        // Simular generación de reportes
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Reporte generado exitosamente. Se enviará una notificación cuando esté listo para descargar.');
        });
    </script>
</body>
</html>
