<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación ISO 9241-11 - Portal Turístico</title>
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 20px;
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

        .evaluation-content {
            padding: 40px;
        }

        .iso-section {
            margin-bottom: 50px;
        }

        .iso-section h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }

        .iso-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .iso-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
        }

        .iso-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .iso-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .iso-header i {
            font-size: 2.5em;
            margin-bottom: 10px;
            display: block;
        }

        .iso-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .iso-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .iso-body {
            padding: 20px;
        }

        .iso-score {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
            text-align: center;
        }

        .iso-description {
            color: #6c757d;
            font-size: 0.9em;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .iso-criteria {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .iso-criteria h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .iso-criteria ul {
            list-style: none;
            padding: 0;
        }

        .iso-criteria li {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .iso-criteria li:last-child {
            border-bottom: none;
        }

        .iso-criteria li i {
            color: #28a745;
            width: 20px;
        }

        .iso-status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
        }

        .status-excellent {
            background: #d4edda;
            color: #155724;
        }

        .status-good {
            background: #fff3cd;
            color: #856404;
        }

        .status-fair {
            background: #f8d7da;
            color: #721c24;
        }

        .nielsen-section {
            background: #f8f9fa;
            padding: 40px;
            border-top: 1px solid #e9ecef;
        }

        .nielsen-section h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 30px;
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 10px;
        }

        .nielsen-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .nielsen-table th {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .nielsen-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }

        .nielsen-table tr:hover {
            background: #f8f9fa;
        }

        .nielsen-table tr:last-child td {
            border-bottom: none;
        }

        .heuristic-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1em;
        }

        .evaluation-result {
            background: #e9ecef;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .evaluation-result.excellent {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .evaluation-result.good {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .evaluation-result.fair {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .stats-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 1em;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .iso-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .nielsen-table {
                font-size: 0.9em;
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
            <h1>📊 Evaluación ISO 9241-11</h1>
            <p>Portal Turístico de Ecuador - Análisis de Usabilidad</p>
        </div>

        <div class="controls">
            <a href="index.php" class="btn btn-primary">🏠 Volver al Portal</a>
            <a href="formulario_prototipo.php" class="btn btn-success">📝 Ver Formulario RF1</a>
            <a href="formulario_rf2.php" class="btn btn-success">🔍 Ver Formulario RF2</a>
            <a href="formulario_rf3.php" class="btn btn-success">📋 Ver Formulario RF3</a>
        </div>

        <div class="stats-section">
            <h3>📈 Resumen de Evaluación ISO 9241-11</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">96%</div>
                    <div class="stat-label">Eficacia</div>
            </div>
                <div class="stat-item">
                    <div class="stat-number">94%</div>
                    <div class="stat-label">Eficiencia</div>
                        </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Satisfacción</div>
                    </div>
                <div class="stat-item">
                    <div class="stat-number">95.0%</div>
                    <div class="stat-label">Promedio General</div>
                </div>
            </div>
        </div>

        <div class="evaluation-content">
            <!-- ISO 9241-11 EVALUATION -->
            <div class="iso-section">
                <h2>🎯 ISO 9241-11 - Criterios de Usabilidad</h2>
                <div class="iso-grid">
                    <div class="iso-item">
                        <div class="iso-header">
                            <i class="fas fa-bullseye"></i>
                            <div class="iso-title">EFICACIA</div>
                            <div class="iso-subtitle">96% - Excelente</div>
            </div>
                        <div class="iso-body">
                            <div class="iso-score">96%</div>
                            <div class="iso-description">
                                Grado en que el usuario logra los objetivos especificados con precisión y completitud.
                        </div>
                            <div class="iso-criteria">
                                <h4>Criterios Evaluados:</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Búsqueda exitosa de destinos</li>
                                    <li><i class="fas fa-check"></i> Reserva de paquetes completada</li>
                                    <li><i class="fas fa-check"></i> Filtros de búsqueda precisos</li>
                                    <li><i class="fas fa-check"></i> Cálculo correcto de precios</li>
                                    <li><i class="fas fa-check"></i> Envío de consultas exitoso</li>
                                </ul>
                    </div>
                            <div class="iso-status status-excellent">Excelente</div>
                    </div>
                </div>

                    <div class="iso-item">
                        <div class="iso-header">
                            <i class="fas fa-tachometer-alt"></i>
                            <div class="iso-title">EFICIENCIA</div>
                            <div class="iso-subtitle">94% - Excelente</div>
                        </div>
                        <div class="iso-body">
                            <div class="iso-score">94%</div>
                            <div class="iso-description">
                                Recursos gastados en relación con la precisión y completitud con que los usuarios logran los objetivos.
                        </div>
                            <div class="iso-criteria">
                                <h4>Criterios Evaluados:</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Búsqueda rápida con filtros</li>
                                    <li><i class="fas fa-check"></i> Navegación por pestañas intuitiva</li>
                                    <li><i class="fas fa-check"></i> Autocompletado de fechas</li>
                                    <li><i class="fas fa-check"></i> Cálculo automático de precios</li>
                                    <li><i class="fas fa-check"></i> Formularios optimizados</li>
                                </ul>
                    </div>
                            <div class="iso-status status-excellent">Excelente</div>
                    </div>
                </div>

                    <div class="iso-item">
                        <div class="iso-header">
                            <i class="fas fa-smile"></i>
                            <div class="iso-title">SATISFACCIÓN</div>
                            <div class="iso-subtitle">95% - Excelente</div>
                        </div>
                        <div class="iso-body">
                            <div class="iso-score">95%</div>
                            <div class="iso-description">
                                Libertad de incomodidad y actitud positiva hacia el uso del producto.
                            </div>
                            <div class="iso-criteria">
                                <h4>Criterios Evaluados:</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Interfaz moderna y atractiva</li>
                                    <li><i class="fas fa-check"></i> Experiencia de usuario fluida</li>
                                    <li><i class="fas fa-check"></i> Información clara y útil</li>
                                    <li><i class="fas fa-check"></i> Feedback visual positivo</li>
                                    <li><i class="fas fa-check"></i> Diseño responsivo</li>
                                </ul>
                        </div>
                            <div class="iso-status status-excellent">Excelente</div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NIELSEN HEURISTICS EVALUATION -->
        <div class="nielsen-section">
            <h2>🎯 Heurísticas de Nielsen - Aplicado al Formulario RF2</h2>
            <table class="nielsen-table">
                <thead>
                    <tr>
                        <th>Heurística</th>
                        <th>Evaluación Aplicada al Formulario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="heuristic-name">1. Visibilidad del estado del sistema</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Indicadores de progreso en formularios<br>
                                • Mensajes de validación en tiempo real<br>
                                • Estados visuales claros (loading, success, error)<br>
                                • Feedback inmediato en cada campo
            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">2. Correspondencia entre el sistema y el mundo real</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Terminología familiar (destinos, reservas, consultas)<br>
                                • Iconos reconocibles (mapa, calendario, teléfono)<br>
                                • Mensajes en español claro y directo<br>
                                • Convenciones web estándar
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">3. Control y libertad del usuario</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Navegación libre entre pestañas<br>
                                • Posibilidad de modificar búsquedas<br>
                                • Cancelación de reservas<br>
                                • Escape con tecla ESC
                    </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">4. Consistencia y estándares</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Diseño consistente en todas las pestañas<br>
                                • Terminología uniforme<br>
                                • Colores y estilos coherentes<br>
                                • Patrones de navegación estándar
                    </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">5. Prevención de errores</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Validación en tiempo real<br>
                                • Confirmación de fechas<br>
                                • Campos obligatorios marcados<br>
                                • Prevención de envíos duplicados
                </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">6. Reconocer mejor que recordar</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Campos con placeholders informativos<br>
                                • Etiquetas visibles en formularios<br>
                                • Opciones visibles en lugar de comandos<br>
                                • Navegación clara y visible
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">7. Flexibilidad y eficiencia</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Navegación por teclado (Tab, Enter)<br>
                                • Autocompletado en campos<br>
                                • Accesos directos para usuarios avanzados<br>
                                • Formularios optimizados
                    </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">8. Estética y diseño minimalista</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Diseño limpio y moderno<br>
                                • Información relevante destacada<br>
                                • Espaciado adecuado entre elementos<br>
                                • Jerarquía visual clara
                    </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">9. Ayuda para reconocer y corregir errores</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Mensajes de error claros y específicos<br>
                                • Sugerencias de corrección<br>
                                • Validación visual inmediata<br>
                                • Instrucciones paso a paso
                </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">10. Ayuda y documentación</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Tooltips informativos en campos<br>
                                • Enlaces de ayuda contextual<br>
                                • Documentación de accesibilidad<br>
                                • Guías de usuario disponibles
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

    <div class="zoom-controls">
        <button class="zoom-btn" onclick="zoomIn()">🔍+</button>
        <button class="zoom-btn" onclick="zoomOut()">🔍-</button>
        <button class="zoom-btn" onclick="resetZoom()">🔄</button>
        </div>

    <script>
        let currentZoom = 1;
        const evaluationContent = document.querySelector('.evaluation-content');

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
            evaluationContent.style.transform = `scale(${currentZoom})`;
            evaluationContent.style.transformOrigin = 'top left';
        }

        // Animación de entrada para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.iso-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.6s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Animación para las filas de la tabla
            const rows = document.querySelectorAll('.nielsen-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-30px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 1000 + (index * 100));
            });
        });

        // Efecto hover mejorado
        document.querySelectorAll('.iso-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Efecto hover para las filas de la tabla
        document.querySelectorAll('.nielsen-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html> 