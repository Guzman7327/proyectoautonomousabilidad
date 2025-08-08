<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Heurística de Nielsen - Portal Turístico</title>
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
            max-width: 1400px;
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

        .wcag-section {
            margin-bottom: 50px;
        }

        .wcag-section h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }

        .wcag-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .wcag-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
        }

        .wcag-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .wcag-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .wcag-header i {
            font-size: 2.5em;
            margin-bottom: 10px;
            display: block;
        }

        .wcag-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .wcag-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .wcag-body {
            padding: 20px;
        }

        .wcag-features {
            list-style: none;
            margin-bottom: 20px;
        }

        .wcag-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wcag-features li:last-child {
            border-bottom: none;
        }

        .wcag-features li i {
            color: #28a745;
            width: 20px;
        }

        .wcag-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-excellent {
            background: #d4edda;
            color: #155724;
        }

        .status-good {
            background: #fff3cd;
            color: #856404;
        }

        .status-warning {
            background: #f8d7da;
            color: #721c24;
        }

        .wcag-description {
            color: #6c757d;
            font-size: 0.9em;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .wcag-score {
            font-size: 1.2em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
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

        .heuristic-description {
            color: #6c757d;
            font-size: 0.9em;
            line-height: 1.5;
            margin-top: 5px;
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

        .evaluation-result.poor {
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
            .wcag-grid {
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
            <h1>🔍 Evaluación Heurística de Nielsen</h1>
            <p>Portal Turístico de Ecuador - Análisis de Usabilidad y Accesibilidad</p>
        </div>

        <div class="controls">
            <a href="index.php" class="btn btn-primary">🏠 Volver al Portal</a>
            <a href="accessibility_menu.php" class="btn btn-success">♿ Ver Accesibilidad</a>
            <a href="menu_design.php" class="btn btn-primary">🍽️ Ver Menú Principal</a>
        </div>

        <div class="stats-section">
            <h3>📊 Resumen de Evaluación</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">91.5%</div>
                    <div class="stat-label">Promedio WCAG 2.2</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10/10</div>
                    <div class="stat-label">Heurísticas Evaluadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Principios WCAG</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">A+</div>
                    <div class="stat-label">Calificación General</div>
                </div>
            </div>
        </div>

        <div class="evaluation-content">
            <!-- WCAG 2.2 EVALUATION -->
            <div class="wcag-section">
                <h2>🎯 WCAG 2.2 - Evaluación de Accesibilidad</h2>
                <div class="wcag-grid">
                    <div class="wcag-item">
                        <div class="wcag-header">
                            <i class="fas fa-eye"></i>
                            <div class="wcag-title">1. PERCEPTIBLE</div>
                            <div class="wcag-subtitle">Excelente (92%)</div>
                        </div>
                        <div class="wcag-body">
                            <div class="wcag-score">92% - Excelente</div>
                            <div class="wcag-description">
                                El contenido es presentado de manera que los usuarios puedan percibirlo independientemente de sus capacidades sensoriales.
                            </div>
                            <ul class="wcag-features">
                                <li><i class="fas fa-check"></i> 1.1 Contenido No Textual: Imágenes con alt, iconos con etiquetas</li>
                                <li><i class="fas fa-check"></i> 1.3 Información y Relaciones: HTML semántico, encabezados jerárquicos</li>
                                <li><i class="fas fa-check"></i> 1.4 Distinguible: Contraste adecuado, texto redimensionable</li>
                            </ul>
                            <div class="wcag-actions">
                                <span class="wcag-status status-excellent">Excelente</span>
                            </div>
                        </div>
                    </div>

                    <div class="wcag-item">
                        <div class="wcag-header">
                            <i class="fas fa-hand-pointer"></i>
                            <div class="wcag-title">2. OPERABLE</div>
                            <div class="wcag-subtitle">Excelente (95%)</div>
                        </div>
                        <div class="wcag-body">
                            <div class="wcag-score">95% - Excelente</div>
                            <div class="wcag-description">
                                Los componentes de la interfaz y la navegación deben ser operables por todos los usuarios.
                            </div>
                            <ul class="wcag-features">
                                <li><i class="fas fa-check"></i> 2.1 Accesible por Teclado: Navegación completa con Tab</li>
                                <li><i class="fas fa-check"></i> 2.2 Tiempo Suficiente: Sin límites automáticos</li>
                                <li><i class="fas fa-check"></i> 2.4 Navegable: Títulos descriptivos, enlaces claros</li>
                            </ul>
                            <div class="wcag-actions">
                                <span class="wcag-status status-excellent">Excelente</span>
                            </div>
                        </div>
                    </div>

                    <div class="wcag-item">
                        <div class="wcag-header">
                            <i class="fas fa-brain"></i>
                            <div class="wcag-title">3. COMPRENSIBLE</div>
                            <div class="wcag-subtitle">Excelente (94%)</div>
                        </div>
                        <div class="wcag-body">
                            <div class="wcag-score">94% - Excelente</div>
                            <div class="wcag-description">
                                La información y el funcionamiento de la interfaz deben ser comprensibles para todos los usuarios.
                            </div>
                            <ul class="wcag-features">
                                <li><i class="fas fa-check"></i> 3.1 Legible: Idioma especificado, cambios marcados</li>
                                <li><i class="fas fa-check"></i> 3.2 Predecible: Navegación consistente</li>
                                <li><i class="fas fa-check"></i> 3.3 Asistencia de Entrada: Identificación de errores</li>
                            </ul>
                            <div class="wcag-actions">
                                <span class="wcag-status status-excellent">Excelente</span>
                            </div>
                        </div>
                    </div>

                    <div class="wcag-item">
                        <div class="wcag-header">
                            <i class="fas fa-shield-alt"></i>
                            <div class="wcag-title">4. ROBUSTO</div>
                            <div class="wcag-subtitle">Bueno (85%)</div>
                        </div>
                        <div class="wcag-body">
                            <div class="wcag-score">85% - Bueno</div>
                            <div class="wcag-description">
                                El contenido debe ser lo suficientemente robusto para ser interpretado por una amplia variedad de tecnologías de asistencia.
                            </div>
                            <ul class="wcag-features">
                                <li><i class="fas fa-check"></i> 4.1 Compatible: HTML válido, ARIA labels</li>
                                <li><i class="fas fa-exclamation-triangle"></i> 4.2 Tecnologías de Asistencia: Parcial</li>
                                <li><i class="fas fa-times"></i> Faltante: Pruebas con múltiples lectores</li>
                            </ul>
                            <div class="wcag-actions">
                                <span class="wcag-status status-good">Bueno</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NIELSEN HEURISTICS EVALUATION -->
        <div class="nielsen-section">
            <h2>🎯 Heurísticas de Nielsen - Evaluación del Formulario</h2>
            <table class="nielsen-table">
                <thead>
                    <tr>
                        <th>Heurística</th>
                        <th>Descripción</th>
                        <th>Evaluación Aplicada al Formulario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="heuristic-name">1. Visibilidad del estado del sistema</div>
                            <div class="heuristic-description">El sistema siempre debe informar al usuario sobre lo que está sucediendo</div>
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
                            <div class="heuristic-description">El sistema debe hablar el lenguaje del usuario</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Terminología familiar (registro, inicio de sesión)<br>
                                • Iconos reconocibles (usuario, candado, email)<br>
                                • Mensajes en español claro y directo<br>
                                • Convenciones web estándar
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">3. Control y libertad del usuario</div>
                            <div class="heuristic-description">Los usuarios necesitan una "salida de emergencia" claramente marcada</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Botón "Cancelar" en formularios<br>
                                • Navegación de regreso clara<br>
                                • Posibilidad de editar datos enviados<br>
                                • Escape con tecla ESC
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">4. Consistencia y estándares</div>
                            <div class="heuristic-description">Los usuarios no deben preguntarse si diferentes palabras significan lo mismo</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Diseño consistente en todas las páginas<br>
                                • Terminología uniforme (registro/login)<br>
                                • Colores y estilos coherentes<br>
                                • Patrones de navegación estándar
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">5. Prevención de errores</div>
                            <div class="heuristic-description">Mejor que buenos mensajes de error es un diseño cuidadoso que previene errores</div>
                        </td>
                        <td>
                            <div class="evaluation-result excellent">
                                <strong>✅ Excelente</strong><br>
                                • Validación en tiempo real<br>
                                • Confirmación de contraseña<br>
                                • Campos obligatorios marcados<br>
                                • Prevención de envíos duplicados
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="heuristic-name">6. Reconocer mejor que recordar</div>
                            <div class="heuristic-description">Minimizar la carga de memoria del usuario</div>
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
                            <div class="heuristic-description">Aceleradores invisibles para el usuario experto</div>
                        </td>
                        <td>
                            <div class="evaluation-result good">
                                <strong>✅ Bueno</strong><br>
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
                            <div class="heuristic-description">Los diálogos no deben contener información irrelevante</div>
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
                            <div class="heuristic-description">Los mensajes de error deben expresarse en lenguaje simple</div>
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
                            <div class="heuristic-description">Aunque es mejor que el sistema sea fácil de usar</div>
                        </td>
                        <td>
                            <div class="evaluation-result good">
                                <strong>✅ Bueno</strong><br>
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
            const items = document.querySelectorAll('.wcag-item');
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
        document.querySelectorAll('.wcag-item').forEach(item => {
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
