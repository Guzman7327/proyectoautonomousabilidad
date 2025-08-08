<?php
// Tabla Resumen Heurísticas de Nielsen - Portal Turístico
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Tabla Heurísticas de Nielsen - Portal Turístico</title>
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
        .table-container {
            overflow-x: auto;
            margin: 20px 0;
        }
        .nielsen-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .nielsen-table th {
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 1.1em;
        }
        .nielsen-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        .nielsen-table tr:hover {
            background: #f5f5f5;
        }
        .nielsen-table tr:last-child td {
            border-bottom: none;
        }
        .heuristic-number {
            background: #2e7d32;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .heuristic-title {
            font-weight: bold;
            color: #2e7d32;
            font-size: 1.1em;
        }
        .status-excellent {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .status-good {
            background: #fff3e0;
            color: #f57c00;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .status-poor {
            background: #ffebee;
            color: #d32f2f;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .implementation-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .implementation-list li {
            margin: 5px 0;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .implementation-list li:last-child {
            border-bottom: none;
        }
        .implementation-list li:before {
            content: '✅';
            margin-right: 8px;
            color: #4caf50;
        }
        .implementation-list li.partial:before {
            content: '⚠️';
            color: #ff9800;
        }
        .implementation-list li.missing:before {
            content: '❌';
            color: #f44336;
        }
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat-card {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #4caf50;
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .stat-label {
            color: #666;
            font-size: 1.1em;
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
            <h1>📊 Tabla Heurísticas de Nielsen</h1>
            <p>Resumen de Evaluación de Usabilidad del Formulario - Portal Turístico</p>
        </div>

        <div class='summary-stats'>
            <div class='stat-card'>
                <div class='stat-number'>95%</div>
                <div class='stat-label'>Puntaje General</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>9</div>
                <div class='stat-label'>Heurísticas Excelentes</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>1</div>
                <div class='stat-label'>Heurística Buena</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>0</div>
                <div class='stat-label'>Heurísticas Pobres</div>
            </div>
        </div>

        <div class='table-container'>
            <table class='nielsen-table'>
                <thead>
                    <tr>
                        <th width='5%'>#</th>
                        <th width='20%'>Heurística</th>
                        <th width='25%'>Descripción</th>
                        <th width='15%'>Estado</th>
                        <th width='35%'>Implementación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class='heuristic-number'>1</div></td>
                        <td class='heuristic-title'>Visibilidad del Estado del Sistema</td>
                        <td>El sistema siempre debe mantener informados a los usuarios sobre lo que está pasando, mediante retroalimentación apropiada dentro de un tiempo razonable.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Validación en tiempo real de campos</li>
                                <li>Indicador de fortaleza de contraseña</li>
                                <li>Mensajes de progreso del formulario</li>
                                <li>Feedback visual inmediato</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>2</div></td>
                        <td class='heuristic-title'>Correspondencia entre el Sistema y el Mundo Real</td>
                        <td>El sistema debe hablar el lenguaje de los usuarios, con palabras, frases y conceptos familiares al usuario, siguiendo las convenciones del mundo real.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Terminología familiar (\"Registrarse\", \"Iniciar Sesión\")</li>
                                <li>Iconos reconocibles (ojo para contraseña)</li>
                                <li>Mensajes de error en lenguaje natural</li>
                                <li>Convenciones web estándar</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>3</div></td>
                        <td class='heuristic-title'>Control y Libertad del Usuario</td>
                        <td>Los usuarios a menudo eligen funciones por error y necesitan una \"salida de emergencia\" claramente marcada para dejar el estado no deseado.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Navegación por pestañas entre login/registro</li>
                                <li>Botones de acción claramente etiquetados</li>
                                <li>Formularios completamente editables</li>
                                <li>Libertad para cambiar de sección</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>4</div></td>
                        <td class='heuristic-title'>Consistencia y Estándares</td>
                        <td>Los usuarios no deben tener que preguntarse si diferentes palabras, situaciones o acciones significan la misma cosa.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Diseño visual unificado</li>
                                <li>Patrones de interacción consistentes</li>
                                <li>Terminología uniforme en toda la aplicación</li>
                                <li>Comportamiento predecible de elementos</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>5</div></td>
                        <td class='heuristic-title'>Prevención de Errores</td>
                        <td>Es mejor prevenir que ocurran errores que mostrar buenos mensajes de error.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Validación proactiva en tiempo real</li>
                                <li>Verificación de email único</li>
                                <li>Confirmación de contraseña</li>
                                <li>Prevención de envíos duplicados</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>6</div></td>
                        <td class='heuristic-title'>Reconocer Mejor que Recordar</td>
                        <td>Minimizar la carga de memoria del usuario haciendo objetos, acciones y opciones visibles.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Checkbox \"Recordar\" para credenciales</li>
                                <li>Campos requeridos claramente marcados</li>
                                <li>Navegación visual con pestañas</li>
                                <li>Opciones siempre visibles</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>7</div></td>
                        <td class='heuristic-title'>Flexibilidad y Eficiencia</td>
                        <td>Aceleradores invisibles para el usuario experto pueden acelerar la interacción para el usuario experto.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Navegación completa por teclado</li>
                                <li>Envío de formularios con Enter</li>
                                <li>Autocompletado del navegador</li>
                                <li>Accesos directos eficientes</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>8</div></td>
                        <td class='heuristic-title'>Estética y Diseño Minimalista</td>
                        <td>Los diálogos no deben contener información irrelevante o raramente necesaria.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Diseño limpio sin elementos decorativos</li>
                                <li>Solo información esencial visible</li>
                                <li>Espaciado adecuado y respiración visual</li>
                                <li>Enfoque en la funcionalidad</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>9</div></td>
                        <td class='heuristic-title'>Ayuda para Reconocer y Corregir Errores</td>
                        <td>Los mensajes de error deben expresarse en lenguaje simple, indicar precisamente el problema y sugerir constructivamente una solución.</td>
                        <td><span class='status-excellent'>EXCELENTE</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Mensajes de error específicos y claros</li>
                                <li>Sugerencias de corrección incluidas</li>
                                <li>Validación contextual cerca del campo</li>
                                <li>Lenguaje comprensible para el usuario</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><div class='heuristic-number'>10</div></td>
                        <td class='heuristic-title'>Ayuda y Documentación</td>
                        <td>Aunque es mejor que el sistema pueda ser usado sin documentación, puede ser necesario proporcionar ayuda.</td>
                        <td><span class='status-good'>BUENO</span></td>
                        <td>
                            <ul class='implementation-list'>
                                <li>Tooltips informativos en elementos</li>
                                <li class='partial'>Página de ayuda parcialmente implementada</li>
                                <li class='missing'>Tutorial interactivo faltante</li>
                                <li class='missing'>Documentación completa no disponible</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class='actions'>
            <h3>🔧 Acciones Disponibles</h3>
            <a href='index.php' class='btn'>🏠 Probar el Formulario</a>
            <a href='nielsen_heuristics.php' class='btn btn-secondary'>📊 Ver Evaluación Detallada</a>
            <a href='iso_evaluation.php' class='btn'>📈 Ver Evaluación ISO</a>
            <a href='database_design.php' class='btn'>🗄️ Ver Diseño de BD</a>
        </div>
    </div>
</body>
</html>";
?> 