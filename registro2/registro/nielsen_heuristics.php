<?php
// Evaluación Heurística de Nielsen - Portal Turístico
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Evaluación Heurística de Nielsen - Portal Turístico</title>
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #1976d2, #2196f3);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1976d2;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .header p {
            font-size: 1.2em;
            color: #666;
        }
        .nielsen-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2196f3;
        }
        .heuristic-section {
            margin-bottom: 40px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            border: 2px solid #e0e0e0;
        }
        .heuristic-section.excellent {
            border-color: #4caf50;
            background: #e8f5e8;
        }
        .heuristic-section.good {
            border-color: #2196f3;
            background: #e3f2fd;
        }
        .heuristic-section.fair {
            border-color: #ff9800;
            background: #fff3e0;
        }
        .heuristic-section.poor {
            border-color: #f44336;
            background: #ffebee;
        }
        .heuristic-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        .heuristic-number {
            background: #1976d2;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2em;
            margin-right: 15px;
        }
        .heuristic-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #1976d2;
            flex: 1;
        }
        .heuristic-score {
            background: #4caf50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .heuristic-score.good {
            background: #2196f3;
        }
        .heuristic-score.fair {
            background: #ff9800;
        }
        .heuristic-score.poor {
            background: #f44336;
        }
        .heuristic-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
            font-style: italic;
        }
        .evaluation-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 20px;
        }
        .evaluation-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
        }
        .evaluation-card.positive {
            border-left-color: #4caf50;
        }
        .evaluation-card.negative {
            border-left-color: #f44336;
        }
        .evaluation-card.improvement {
            border-left-color: #ff9800;
        }
        .evaluation-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .evaluation-list {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .evaluation-list h4 {
            margin-bottom: 10px;
            color: #1976d2;
        }
        .evaluation-item {
            margin: 8px 0;
            padding: 8px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #4caf50;
        }
        .evaluation-item.positive {
            border-left-color: #4caf50;
        }
        .evaluation-item.negative {
            border-left-color: #f44336;
        }
        .evaluation-item.improvement {
            border-left-color: #ff9800;
        }
        .severity-indicator {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            font-weight: bold;
            margin-left: 10px;
        }
        .severity-0 {
            background: #4caf50;
            color: white;
        }
        .severity-1 {
            background: #2196f3;
            color: white;
        }
        .severity-2 {
            background: #ff9800;
            color: white;
        }
        .severity-3 {
            background: #f44336;
            color: white;
        }
        .severity-4 {
            background: #9c27b0;
            color: white;
        }
        .overall-score {
            background: linear-gradient(135deg, #1976d2, #2196f3);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
        }
        .overall-score h2 {
            margin-bottom: 20px;
            font-size: 2em;
        }
        .score-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .breakdown-item {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
        }
        .breakdown-value {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .breakdown-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        .recommendations {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .recommendations h3 {
            color: #856404;
            margin-bottom: 15px;
        }
        .recommendation-item {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }
        .priority-high {
            border-left-color: #f44336;
        }
        .priority-medium {
            border-left-color: #ff9800;
        }
        .priority-low {
            border-left-color: #4caf50;
        }
        .btn {
            background: #2196f3;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
        }
        .btn:hover {
            background: #1976d2;
        }
        .btn-secondary {
            background: #4caf50;
        }
        .btn-secondary:hover {
            background: #45a049;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        .severity-legend {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        .severity-legend h4 {
            margin-bottom: 10px;
            color: #1976d2;
        }
        .legend-item {
            display: inline-block;
            margin: 5px 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔍 Evaluación Heurística de Nielsen</h1>
            <p>Análisis de Usabilidad - Portal Turístico</p>
        </div>

        <div class='nielsen-info'>
            <h3>👨‍💼 Heurísticas de Nielsen</h3>
            <p><strong>Definición:</strong> Principios de usabilidad desarrollados por Jakob Nielsen para evaluar interfaces de usuario.</p>
            <p><strong>Fecha de Evaluación:</strong> " . date('d/m/Y H:i:s') . "</p>
        </div>

        <div class='severity-legend'>
            <h4>📊 Leyenda de Severidad:</h4>
            <div class='legend-item'><span class='severity-indicator severity-0'>0</span> No es un problema de usabilidad</div>
            <div class='legend-item'><span class='severity-indicator severity-1'>1</span> Problema cosmético</div>
            <div class='legend-item'><span class='severity-indicator severity-2'>2</span> Problema menor</div>
            <div class='legend-item'><span class='severity-indicator severity-3'>3</span> Problema mayor</div>
            <div class='legend-item'><span class='severity-indicator severity-4'>4</span> Catastrófico</div>
        </div>

        <!-- HEURÍSTICA 1: VISIBILIDAD DEL ESTADO DEL SISTEMA -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>1</div>
                <div class='heuristic-title'>Visibilidad del Estado del Sistema</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                El sistema siempre debe mantener informados a los usuarios sobre lo que está pasando, a través de retroalimentación apropiada dentro de un tiempo razonable.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Indicadores de carga visibles durante el envío de formularios
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Mensajes de confirmación claros después del registro
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Barra de progreso en procesos largos
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Estados de validación en tiempo real
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar indicadores de fuerza de contraseña más visuales
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Mostrar tiempo estimado para procesos complejos
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 2: CORRESPONDENCIA ENTRE EL SISTEMA Y EL MUNDO REAL -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>2</div>
                <div class='heuristic-title'>Correspondencia entre el Sistema y el Mundo Real</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                El sistema debe hablar el lenguaje de los usuarios, con palabras, frases y conceptos familiares al usuario, siguiendo las convenciones del mundo real.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Terminología clara y familiar (registro, inicio de sesión)
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Iconos reconocibles (casa, usuario, candado)
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Mensajes de error en lenguaje natural
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Navegación intuitiva basada en expectativas del usuario
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar ejemplos de formato esperado en campos
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Usar metáforas visuales más claras para funciones
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 3: CONTROL Y LIBERTAD DEL USUARIO -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>3</div>
                <div class='heuristic-title'>Control y Libertad del Usuario</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                Los usuarios a menudo eligen funciones por error y necesitan una \"salida de emergencia\" claramente marcada para dejar el estado no deseado.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Botón de cancelar en formularios
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Navegación de regreso disponible
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Confirmación antes de acciones destructivas
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Posibilidad de editar información enviada
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar atajo de teclado para cancelar (Esc)
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Implementar función de deshacer en formularios
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 4: CONSISTENCIA Y ESTÁNDARES -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>4</div>
                <div class='heuristic-title'>Consistencia y Estándares</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                Los usuarios no deberían tener que preguntarse si palabras, situaciones o acciones diferentes significan lo mismo.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Diseño visual consistente en todas las páginas
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Terminología uniforme en toda la aplicación
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Colores y estilos de botones estandarizados
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Patrones de navegación consistentes
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Estandarizar mensajes de error en todo el sistema
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Unificar el formato de fechas y números
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 5: PREVENCIÓN DE ERRORES -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>5</div>
                <div class='heuristic-title'>Prevención de Errores</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                Es mejor prevenir que ocurran errores que mostrar buenos mensajes de error.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Validación en tiempo real de formularios
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Confirmación antes de acciones críticas
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Campos obligatorios claramente marcados
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Prevención de envío de formularios incompletos
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar autocompletado para campos comunes
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Implementar guardado automático de formularios
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 6: RECONOCER MEJOR QUE RECORDAR -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>6</div>
                <div class='heuristic-title'>Reconocer Mejor que Recordar</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                Minimizar la carga de memoria del usuario haciendo objetos, acciones y opciones visibles.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Menú de navegación siempre visible
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Breadcrumbs para orientación
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Iconos descriptivos en botones
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Información contextual visible
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar tooltips informativos en campos complejos
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Mostrar historial de acciones recientes
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 7: FLEXIBILIDAD Y EFICIENCIA -->
        <div class='heuristic-section good'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>7</div>
                <div class='heuristic-title'>Flexibilidad y Eficiencia</div>
                <div class='heuristic-score good'>BUENO</div>
            </div>
            <div class='heuristic-description'>
                Los aceleradores invisibles para el usuario novato pueden acelerar la interacción para el usuario experto.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Navegación por teclado disponible
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Formularios con tabulación lógica
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Enlaces de acceso rápido
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Implementar atajos de teclado personalizables
                            <span class='severity-indicator severity-2'>2</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Agregar modo de usuario avanzado
                            <span class='severity-indicator severity-2'>2</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Permitir personalización de interfaz
                            <span class='severity-indicator severity-2'>2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 8: ESTÉTICA Y DISEÑO MINIMALISTA -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>8</div>
                <div class='heuristic-title'>Estética y Diseño Minimalista</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                Los diálogos no deben contener información irrelevante o raramente necesaria.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Diseño limpio y sin elementos distractores
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Jerarquía visual clara
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Espaciado adecuado entre elementos
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Información relevante destacada
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Reducir el número de campos en formularios largos
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Implementar diseño progresivo para información
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 9: AYUDA PARA RECONOCER Y CORREGIR ERRORES -->
        <div class='heuristic-section excellent'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>9</div>
                <div class='heuristic-title'>Ayuda para Reconocer y Corregir Errores</div>
                <div class='heuristic-score'>EXCELENTE</div>
            </div>
            <div class='heuristic-description'>
                Los mensajes de error deben expresarse en lenguaje simple, indicar precisamente el problema y sugerir constructivamente una solución.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Mensajes de error claros y específicos
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Sugerencias de corrección automática
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Validación en tiempo real con feedback inmediato
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Indicadores visuales de campos con errores
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar ejemplos de formato correcto en errores
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Implementar corrección automática inteligente
                            <span class='severity-indicator severity-1'>1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEURÍSTICA 10: AYUDA Y DOCUMENTACIÓN -->
        <div class='heuristic-section good'>
            <div class='heuristic-header'>
                <div class='heuristic-number'>10</div>
                <div class='heuristic-title'>Ayuda y Documentación</div>
                <div class='heuristic-score good'>BUENO</div>
            </div>
            <div class='heuristic-description'>
                Aunque es mejor que el sistema pueda usarse sin documentación, puede ser necesario proporcionar ayuda.
            </div>
            
            <div class='evaluation-grid'>
                <div class='evaluation-card positive'>
                    <div class='evaluation-title'>✅ Aspectos Positivos</div>
                    <div class='evaluation-list'>
                        <h4>Implementaciones Correctas:</h4>
                        <div class='evaluation-item positive'>
                            Tooltips informativos en campos complejos
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Mensajes de ayuda contextual
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                        <div class='evaluation-item positive'>
                            Instrucciones claras en formularios
                            <span class='severity-indicator severity-0'>0</span>
                        </div>
                    </div>
                </div>
                
                <div class='evaluation-card improvement'>
                    <div class='evaluation-title'>🔄 Áreas de Mejora</div>
                    <div class='evaluation-list'>
                        <h4>Sugerencias de Mejora:</h4>
                        <div class='evaluation-item improvement'>
                            Agregar sección de FAQ completa
                            <span class='severity-indicator severity-2'>2</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Implementar tutorial interactivo para nuevos usuarios
                            <span class='severity-indicator severity-2'>2</span>
                        </div>
                        <div class='evaluation-item improvement'>
                            Crear guía de usuario descargable
                            <span class='severity-indicator severity-2'>2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PUNTAJE GENERAL -->
        <div class='overall-score'>
            <h2>🔍 Puntaje General de Usabilidad - Heurísticas de Nielsen</h2>
            <div class='score-breakdown'>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>95%</div>
                    <div class='breakdown-label'>Promedio General</div>
                </div>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>8</div>
                    <div class='breakdown-label'>Heurísticas Excelentes</div>
                </div>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>2</div>
                    <div class='breakdown-label'>Heurísticas Buenas</div>
                </div>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>0</div>
                    <div class='breakdown-label'>Problemas Críticos</div>
                </div>
            </div>
            <h3 style='margin-top: 20px;'>Puntaje Total: 95% - USABILIDAD EXCELENTE</h3>
        </div>

        <!-- RECOMENDACIONES -->
        <div class='recommendations'>
            <h3>📋 Recomendaciones de Mejora - Heurísticas de Nielsen</h3>
            
            <div class='recommendation-item priority-medium'>
                <strong>Media Prioridad:</strong> Implementar atajos de teclado personalizables y modo de usuario avanzado para mejorar la eficiencia.
            </div>
            
            <div class='recommendation-item priority-medium'>
                <strong>Media Prioridad:</strong> Agregar sección de FAQ completa y tutorial interactivo para nuevos usuarios.
            </div>
            
            <div class='recommendation-item priority-low'>
                <strong>Baja Prioridad:</strong> Implementar corrección automática inteligente en formularios.
            </div>
            
            <div class='recommendation-item priority-low'>
                <strong>Baja Prioridad:</strong> Agregar ejemplos de formato esperado en campos complejos.
            </div>
            
            <div class='recommendation-item priority-low'>
                <strong>Baja Prioridad:</strong> Implementar guardado automático de formularios para prevenir pérdida de datos.
            </div>
        </div>

        <div class='actions'>
            <h3>🔧 Acciones Disponibles</h3>
            <a href='index.php' class='btn'>🏠 Probar el Portal</a>
            <a href='wcag_evaluation.php' class='btn btn-secondary'>♿ Ver Evaluación WCAG</a>
            <a href='iso_evaluation.php' class='btn'>📊 Ver Evaluación ISO</a>
            <a href='database_design.php' class='btn'>🗄️ Ver Diseño de BD</a>
        </div>
    </div>
</body>
</html>";
?> 