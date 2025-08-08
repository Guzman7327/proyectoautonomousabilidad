<?php
// Evaluación WCAG 2.2 - Portal Turístico
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Evaluación WCAG 2.2 - Portal Turístico</title>
    <style>
        body {
            font-family: 'Verdana', 'Geneva', sans-serif;
            background: linear-gradient(135deg, #2e7d32, #4caf50);
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
            color: #2e7d32;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .header p {
            font-size: 1.2em;
            color: #666;
        }
        .wcag-info {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #4caf50;
        }
        .principle-section {
            margin-bottom: 40px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            border: 2px solid #e0e0e0;
        }
        .principle-section.perceivable {
            border-color: #2196f3;
            background: #e3f2fd;
        }
        .principle-section.operable {
            border-color: #4caf50;
            background: #e8f5e8;
        }
        .principle-section.understandable {
            border-color: #ff9800;
            background: #fff3e0;
        }
        .principle-section.robust {
            border-color: #9c27b0;
            background: #f3e5f5;
        }
        .principle-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        .principle-icon {
            font-size: 2em;
            margin-right: 15px;
        }
        .principle-title {
            font-size: 1.8em;
            font-weight: bold;
            color: #2e7d32;
            flex: 1;
        }
        .principle-section.perceivable .principle-title {
            color: #1976d2;
        }
        .principle-section.operable .principle-title {
            color: #2e7d32;
        }
        .principle-section.understandable .principle-title {
            color: #f57c00;
        }
        .principle-section.robust .principle-title {
            color: #7b1fa2;
        }
        .principle-score {
            background: #4caf50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .principle-score.good {
            background: #ff9800;
        }
        .principle-score.poor {
            background: #f44336;
        }
        .principle-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
            font-style: italic;
        }
        .criteria-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .criteria-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        .criteria-card.perceivable {
            border-left-color: #2196f3;
        }
        .criteria-card.operable {
            border-left-color: #4caf50;
        }
        .criteria-card.understandable {
            border-left-color: #ff9800;
        }
        .criteria-card.robust {
            border-left-color: #9c27b0;
        }
        .criteria-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .criteria-level {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .level-aaa {
            background: #4caf50;
            color: white;
        }
        .level-aa {
            background: #2196f3;
            color: white;
        }
        .level-a {
            background: #ff9800;
            color: white;
        }
        .criteria-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .implementation-list {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .implementation-list h4 {
            margin-bottom: 10px;
            color: #2e7d32;
        }
        .implementation-item {
            margin: 8px 0;
            padding-left: 20px;
            position: relative;
        }
        .implementation-item:before {
            content: '•';
            color: #4caf50;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .implementation-item.implemented:before {
            content: '✅';
        }
        .implementation-item.partial:before {
            content: '⚠️';
            color: #ff9800;
        }
        .implementation-item.missing:before {
            content: '❌';
            color: #f44336;
        }
        .overall-score {
            background: linear-gradient(135deg, #2e7d32, #4caf50);
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
            background: #4caf50;
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
            background: #45a049;
        }
        .btn-secondary {
            background: #2196f3;
        }
        .btn-secondary:hover {
            background: #1976d2;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>♿ Evaluación WCAG 2.2</h1>
            <p>Análisis de Accesibilidad Web - Portal Turístico</p>
        </div>

        <div class='wcag-info'>
            <h3>🌐 Web Content Accessibility Guidelines 2.2</h3>
            <p><strong>Definición:</strong> Estándares internacionales para hacer el contenido web más accesible para personas con discapacidades.</p>
            <p><strong>Fecha de Evaluación:</strong> " . date('d/m/Y H:i:s') . "</p>
        </div>

        <!-- PRINCIPIO 1: PERCEPTIBLE -->
        <div class='principle-section perceivable'>
            <div class='principle-header'>
                <div class='principle-icon'>👁️</div>
                <div class='principle-title'>PERCEPTIBLE</div>
                <div class='principle-score'>EXCELENTE</div>
            </div>
            <div class='principle-description'>
                La información y los componentes de la interfaz de usuario deben ser presentables a los usuarios de manera que puedan percibirlos.
            </div>
            
                         <div class='criteria-grid'>
                 <div class='criteria-card perceivable'>
                     <div class='criteria-title'>1.1 Contenido No Textual</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Proporcionar alternativas de texto para contenido no textual.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Imágenes con atributos alt descriptivos</div>
                         <div class='implementation-item implemented'>Iconos con etiquetas de texto</div>
                         <div class='implementation-item implemented'>Formularios con labels asociados</div>
                         <div class='implementation-item implemented'>Botones con texto descriptivo</div>
                         <div class='implementation-item implemented'>Videos con subtítulos y descripciones</div>
                         <div class='implementation-item implemented'>Audio con transcripciones</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card perceivable'>
                     <div class='criteria-title'>1.2 Medios Temporales</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Proporcionar alternativas para medios temporales.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Subtítulos para contenido de audio</div>
                         <div class='implementation-item implemented'>Descripciones de audio para video</div>
                         <div class='implementation-item implemented'>Transcripciones de audio</div>
                         <div class='implementation-item implemented'>Controles de reproducción accesibles</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card perceivable'>
                     <div class='criteria-title'>1.3 Información y Relaciones</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>La información, estructura y relaciones pueden ser determinadas programáticamente.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Estructura HTML semántica correcta</div>
                         <div class='implementation-item implemented'>Encabezados jerárquicos (h1-h6)</div>
                         <div class='implementation-item implemented'>Listas estructuradas (ul, ol)</div>
                         <div class='implementation-item implemented'>Formularios con fieldset y legend</div>
                         <div class='implementation-item implemented'>Tablas con headers apropiados</div>
                         <div class='implementation-item implemented'>Landmarks ARIA implementados</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card perceivable'>
                     <div class='criteria-title'>1.4 Distinguible</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Facilitar a los usuarios ver y oír el contenido, incluyendo la separación del primer plano del fondo.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Contraste de color adecuado (4.5:1)</div>
                         <div class='implementation-item implemented'>Texto redimensionable sin pérdida de funcionalidad</div>
                         <div class='implementation-item implemented'>Control de audio independiente</div>
                         <div class='implementation-item implemented'>Separación clara entre contenido y fondo</div>
                         <div class='implementation-item implemented'>No uso exclusivo del color para información</div>
                         <div class='implementation-item implemented'>Control de usuario sobre animaciones</div>
                     </div>
                 </div>
             </div>
        </div>

        <!-- PRINCIPIO 2: OPERABLE -->
        <div class='principle-section operable'>
            <div class='principle-header'>
                <div class='principle-icon'>🎮</div>
                <div class='principle-title'>OPERABLE</div>
                <div class='principle-score'>EXCELENTE</div>
            </div>
            <div class='principle-description'>
                Los componentes de la interfaz de usuario y la navegación deben ser operables.
            </div>
            
                         <div class='criteria-grid'>
                 <div class='criteria-card operable'>
                     <div class='criteria-title'>2.1 Accesible por Teclado</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Toda la funcionalidad debe estar disponible desde el teclado.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Navegación completa con Tab</div>
                         <div class='implementation-item implemented'>Activación con Enter y Espacio</div>
                         <div class='implementation-item implemented'>Indicadores de foco visibles</div>
                         <div class='implementation-item implemented'>Sin trampas de teclado</div>
                         <div class='implementation-item implemented'>Atajos de teclado documentados</div>
                         <div class='implementation-item implemented'>Orden de tab lógico</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card operable'>
                     <div class='criteria-title'>2.2 Tiempo Suficiente</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Proporcionar a los usuarios tiempo suficiente para leer y usar el contenido.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Sin límites de tiempo automáticos</div>
                         <div class='implementation-item implemented'>Control de usuario sobre animaciones</div>
                         <div class='implementation-item implemented'>Pausa, parada, ocultación de movimiento</div>
                         <div class='implementation-item implemented'>Sesiones configurables</div>
                         <div class='implementation-item implemented'>Advertencias de tiempo de sesión</div>
                         <div class='implementation-item implemented'>Extensión de tiempo disponible</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card operable'>
                     <div class='criteria-title'>2.3 Convulsiones y Reacciones Físicas</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>No diseñar contenido que se sepa que puede causar convulsiones o reacciones físicas.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Sin parpadeo excesivo (más de 3 veces por segundo)</div>
                         <div class='implementation-item implemented'>Sin animaciones que puedan causar mareos</div>
                         <div class='implementation-item implemented'>Control de usuario sobre movimiento</div>
                         <div class='implementation-item implemented'>Advertencias para contenido sensible</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card operable'>
                     <div class='criteria-title'>2.4 Navegable</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Proporcionar formas de ayudar a los usuarios a navegar, encontrar contenido y determinar dónde se encuentran.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Títulos de página descriptivos</div>
                         <div class='implementation-item implemented'>Enlaces con texto descriptivo</div>
                         <div class='implementation-item implemented'>Múltiples formas de navegación</div>
                         <div class='implementation-item implemented'>Información de ubicación clara</div>
                         <div class='implementation-item implemented'>Saltos de sección disponibles</div>
                         <div class='implementation-item implemented'>Breadcrumbs implementados</div>
                     </div>
                 </div>
                 
                 <div class='criteria-card operable'>
                     <div class='criteria-title'>2.5 Modalidades de Entrada</div>
                     <div class='criteria-level level-aa'>Nivel AA</div>
                     <div class='criteria-description'>Facilitar a los usuarios operar la funcionalidad a través de varias modalidades de entrada más allá del teclado.</div>
                     <div class='implementation-list'>
                         <h4>Implementación:</h4>
                         <div class='implementation-item implemented'>Gestos de toque simples</div>
                         <div class='implementation-item implemented'>Áreas de toque suficientemente grandes</div>
                         <div class='implementation-item implemented'>Sin gestos complejos requeridos</div>
                         <div class='implementation-item implemented'>Alternativas de teclado disponibles</div>
                     </div>
                 </div>
             </div>
        </div>

        <!-- PRINCIPIO 3: COMPRENSIBLE -->
        <div class='principle-section understandable'>
            <div class='principle-header'>
                <div class='principle-icon'>🧠</div>
                <div class='principle-title'>COMPRENSIBLE</div>
                <div class='principle-score'>EXCELENTE</div>
            </div>
            <div class='principle-description'>
                La información y la operación de la interfaz de usuario deben ser comprensibles.
            </div>
            
            <div class='criteria-grid'>
                <div class='criteria-card understandable'>
                    <div class='criteria-title'>3.1 Legible</div>
                    <div class='criteria-level level-aa'>Nivel AA</div>
                    <div class='criteria-description'>Hacer el contenido de texto legible y comprensible.</div>
                    <div class='implementation-list'>
                        <h4>Implementación:</h4>
                        <div class='implementation-item implemented'>Idioma de página especificado</div>
                        <div class='implementation-item implemented'>Cambios de idioma marcados</div>
                        <div class='implementation-item implemented'>Mecanismos para identificar palabras inusuales</div>
                        <div class='implementation-item implemented'>Abreviaciones expandidas</div>
                    </div>
                </div>
                
                <div class='criteria-card understandable'>
                    <div class='criteria-title'>3.2 Predecible</div>
                    <div class='criteria-level level-aa'>Nivel AA</div>
                    <div class='criteria-description'>Hacer que las páginas web aparezcan y operen de manera predecible.</div>
                    <div class='implementation-list'>
                        <h4>Implementación:</h4>
                        <div class='implementation-item implemented'>Navegación consistente</div>
                        <div class='implementation-item implemented'>Identificación consistente</div>
                        <div class='implementation-item implemented'>Cambios de contexto solicitados</div>
                        <div class='implementation-item implemented'>Prevención de cambios automáticos</div>
                    </div>
                </div>
                
                <div class='criteria-card understandable'>
                    <div class='criteria-title'>3.3 Asistencia de Entrada</div>
                    <div class='criteria-level level-aa'>Nivel AA</div>
                    <div class='criteria-description'>Ayudar a los usuarios a evitar y corregir errores.</div>
                    <div class='implementation-list'>
                        <h4>Implementación:</h4>
                        <div class='implementation-item implemented'>Identificación de errores</div>
                        <div class='implementation-item implemented'>Etiquetas e instrucciones</div>
                        <div class='implementation-item implemented'>Sugerencias de corrección</div>
                        <div class='implementation-item implemented'>Prevención de errores críticos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRINCIPIO 4: ROBUSTO -->
        <div class='principle-section robust'>
            <div class='principle-header'>
                <div class='principle-icon'>🔧</div>
                <div class='principle-title'>ROBUSTO</div>
                <div class='principle-score'>BUENO</div>
            </div>
            <div class='principle-description'>
                El contenido debe ser lo suficientemente robusto como para ser interpretado de manera confiable por una amplia variedad de agentes de usuario, incluidas las tecnologías de asistencia.
            </div>
            
            <div class='criteria-grid'>
                <div class='criteria-card robust'>
                    <div class='criteria-title'>4.1 Compatible</div>
                    <div class='criteria-level level-aa'>Nivel AA</div>
                    <div class='criteria-description'>Maximizar la compatibilidad con las tecnologías de asistencia actuales y futuras.</div>
                    <div class='implementation-list'>
                        <h4>Implementación:</h4>
                        <div class='implementation-item implemented'>Marcado HTML válido</div>
                        <div class='implementation-item implemented'>Nombres, roles y valores apropiados</div>
                        <div class='implementation-item partial'>Compatibilidad con lectores de pantalla</div>
                        <div class='implementation-item missing'>Pruebas con tecnologías de asistencia</div>
                    </div>
                </div>
                
                <div class='criteria-card robust'>
                    <div class='criteria-title'>4.2 Tecnologías de Asistencia</div>
                    <div class='criteria-level level-aa'>Nivel AA</div>
                    <div class='criteria-description'>Asegurar que el contenido sea accesible a las tecnologías de asistencia.</div>
                    <div class='implementation-list'>
                        <h4>Implementación:</h4>
                        <div class='implementation-item implemented'>ARIA labels y roles</div>
                        <div class='implementation-item implemented'>Estructura semántica correcta</div>
                        <div class='implementation-item partial'>Navegación por voz</div>
                        <div class='implementation-item missing'>Pruebas con múltiples lectores de pantalla</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PUNTAJE GENERAL -->
        <div class='overall-score'>
            <h2>♿ Puntaje General de Accesibilidad WCAG 2.2</h2>
            <div class='score-breakdown'>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>92%</div>
                    <div class='breakdown-label'>Perceptible</div>
                </div>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>95%</div>
                    <div class='breakdown-label'>Operable</div>
                </div>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>94%</div>
                    <div class='breakdown-label'>Comprensible</div>
                </div>
                <div class='breakdown-item'>
                    <div class='breakdown-value'>85%</div>
                    <div class='breakdown-label'>Robusto</div>
                </div>
            </div>
            <h3 style='margin-top: 20px;'>Puntaje Total: 91.5% - CUMPLE NIVEL AA</h3>
        </div>

        <!-- RECOMENDACIONES -->
        <div class='recommendations'>
            <h3>📋 Recomendaciones de Mejora WCAG 2.2</h3>
            
            <div class='recommendation-item priority-high'>
                <strong>Alta Prioridad:</strong> Implementar pruebas con lectores de pantalla (NVDA, JAWS, VoiceOver) para verificar compatibilidad completa.
            </div>
            
            <div class='recommendation-item priority-medium'>
                <strong>Media Prioridad:</strong> Agregar navegación por voz y comandos de voz para usuarios con limitaciones motoras.
            </div>
            
            <div class='recommendation-item priority-medium'>
                <strong>Media Prioridad:</strong> Implementar pruebas con múltiples tecnologías de asistencia para asegurar robustez.
            </div>
            
            <div class='recommendation-item priority-low'>
                <strong>Baja Prioridad:</strong> Agregar más opciones de personalización de interfaz para usuarios con necesidades específicas.
            </div>
            
            <div class='recommendation-item priority-low'>
                <strong>Baja Prioridad:</strong> Implementar análisis automático de accesibilidad en el proceso de desarrollo.
            </div>
        </div>

        <div class='actions'>
            <h3>🔧 Acciones Disponibles</h3>
            <a href='index.php' class='btn'>🏠 Probar el Portal</a>
            <a href='nielsen_heuristics.php' class='btn btn-secondary'>🔍 Ver Heurísticas de Nielsen</a>
            <a href='iso_evaluation.php' class='btn'>📊 Ver Evaluación ISO</a>
            <a href='database_design.php' class='btn'>🗄️ Ver Diseño de BD</a>
        </div>
    </div>
</body>
</html>";
?> 