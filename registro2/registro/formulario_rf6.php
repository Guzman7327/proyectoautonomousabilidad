<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contenido y Multimedia - Portal Turístico</title>
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

        .media-preview {
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            margin-bottom: 20px;
        }

        .media-preview i {
            font-size: 3em;
            color: #007bff;
            margin-bottom: 15px;
        }

        .media-preview p {
            color: #6c757d;
            font-size: 1.1em;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .content-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .content-card h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .content-card p {
            color: #6c757d;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .content-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-view {
            background: #007bff;
            color: white;
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

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-icon {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
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

            .dashboard-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="requirement-badge">RF6</div>
        
        <div class="form-header">
            <h1>🌴 Portal Turístico Ecuador</h1>
            <p>Gestión de Contenido y Multimedia - Prototipo RF6</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('dashboard')">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </button>
                <button class="tab" onclick="showTab('content')">
                    <i class="fas fa-file-alt"></i> Contenido
                </button>
                <button class="tab" onclick="showTab('media')">
                    <i class="fas fa-photo-video"></i> Multimedia
                </button>
                <button class="tab" onclick="showTab('gallery')">
                    <i class="fas fa-images"></i> Galería
                </button>
            </div>

            <!-- DASHBOARD -->
            <div id="dashboard" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Panel de Gestión de Contenido</h3>
                    <p>Administra todo el contenido multimedia del portal turístico desde este panel central.</p>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="stat-number">156</div>
                        <div class="stat-label">Artículos Publicados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-photo-video"></i></div>
                        <div class="stat-number">2,847</div>
                        <div class="stat-label">Archivos Multimedia</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-eye"></i></div>
                        <div class="stat-number">45.2K</div>
                        <div class="stat-label">Visualizaciones</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-download"></i></div>
                        <div class="stat-number">8,934</div>
                        <div class="stat-label">Descargas</div>
                    </div>
                </div>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <strong>Sistema funcionando correctamente:</strong> Todos los archivos multimedia están sincronizados.
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Atención:</strong> 23 archivos requieren optimización de tamaño.
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contentSearch">
                            <i class="fas fa-search"></i> Búsqueda de Contenido
                        </label>
                        <input type="text" id="contentSearch" name="contentSearch" placeholder="Buscar artículos, imágenes, videos...">
                    </div>

                    <div class="form-group">
                        <label for="contentFilter">
                            <i class="fas fa-filter"></i> Filtro por Tipo
                        </label>
                        <select id="contentFilter" name="contentFilter">
                            <option value="all">Todos los tipos</option>
                            <option value="articles">Artículos</option>
                            <option value="images">Imágenes</option>
                            <option value="videos">Videos</option>
                            <option value="documents">Documentos</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="submit-btn">
                    <i class="fas fa-sync-alt"></i> Actualizar Dashboard
                </button>
            </div>

            <!-- GESTIÓN DE CONTENIDO -->
            <div id="content" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Gestión de Contenido Editorial</h3>
                    <p>Crea, edita y administra artículos, descripciones y contenido textual del portal.</p>
                </div>

                <form id="contentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contentTitle">
                                <i class="fas fa-heading"></i> Título del Contenido
                            </label>
                            <input type="text" id="contentTitle" name="contentTitle" placeholder="Ej: Guía Completa de Galápagos" required>
                        </div>

                        <div class="form-group">
                            <label for="contentCategory">
                                <i class="fas fa-tags"></i> Categoría
                            </label>
                            <select id="contentCategory" name="contentCategory" required>
                                <option value="">Selecciona categoría</option>
                                <option value="destinos">Destinos</option>
                                <option value="guias">Guías de Viaje</option>
                                <option value="cultura">Cultura</option>
                                <option value="gastronomia">Gastronomía</option>
                                <option value="aventura">Aventura</option>
                                <option value="noticias">Noticias</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="contentAuthor">
                                <i class="fas fa-user"></i> Autor
                            </label>
                            <input type="text" id="contentAuthor" name="contentAuthor" placeholder="Nombre del autor" required>
                        </div>

                        <div class="form-group">
                            <label for="contentDate">
                                <i class="fas fa-calendar"></i> Fecha de Publicación
                            </label>
                            <input type="date" id="contentDate" name="contentDate" required>
                        </div>

                        <div class="form-group">
                            <label for="contentStatus">
                                <i class="fas fa-toggle-on"></i> Estado
                            </label>
                            <select id="contentStatus" name="contentStatus" required>
                                <option value="draft">Borrador</option>
                                <option value="published">Publicado</option>
                                <option value="archived">Archivado</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contentSummary">
                            <i class="fas fa-align-left"></i> Resumen
                        </label>
                        <textarea id="contentSummary" name="contentSummary" rows="3" placeholder="Breve descripción del contenido..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contentBody">
                            <i class="fas fa-file-alt"></i> Contenido Completo
                        </label>
                        <textarea id="contentBody" name="contentBody" rows="10" placeholder="Escribe el contenido completo del artículo..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contentTags">
                            <i class="fas fa-hashtag"></i> Etiquetas
                        </label>
                        <input type="text" id="contentTags" name="contentTags" placeholder="galápagos, islas, tortugas, ecoturismo">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Opciones de Publicación</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="featured" name="options[]" value="featured">
                                <label for="featured">Destacar en portada</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="seo" name="options[]" value="seo">
                                <label for="seo">Optimizar para SEO</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="social" name="options[]" value="social">
                                <label for="social">Compartir en redes sociales</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-save"></i> Guardar Contenido
                    </button>
                </form>
            </div>

            <!-- GESTIÓN DE MULTIMEDIA -->
            <div id="media" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Gestión de Archivos Multimedia</h3>
                    <p>Sube, organiza y administra imágenes, videos y documentos multimedia.</p>
                </div>

                <div class="media-preview">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Arrastra archivos aquí o haz clic para seleccionar</p>
                    <p><small>Formatos soportados: JPG, PNG, GIF, MP4, PDF, DOC</small></p>
                </div>

                <form id="mediaForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mediaTitle">
                                <i class="fas fa-heading"></i> Título del Archivo
                            </label>
                            <input type="text" id="mediaTitle" name="mediaTitle" placeholder="Ej: Galápagos Tortugas Gigantes" required>
                        </div>

                        <div class="form-group">
                            <label for="mediaType">
                                <i class="fas fa-file"></i> Tipo de Archivo
                            </label>
                            <select id="mediaType" name="mediaType" required>
                                <option value="">Selecciona tipo</option>
                                <option value="image">Imagen</option>
                                <option value="video">Video</option>
                                <option value="document">Documento</option>
                                <option value="audio">Audio</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mediaDescription">
                                <i class="fas fa-align-left"></i> Descripción
                            </label>
                            <textarea id="mediaDescription" name="mediaDescription" rows="3" placeholder="Describe el contenido del archivo..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="mediaTags">
                                <i class="fas fa-hashtag"></i> Etiquetas
                            </label>
                            <input type="text" id="mediaTags" name="mediaTags" placeholder="galápagos, tortugas, naturaleza">
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="mediaQuality">
                                <i class="fas fa-star"></i> Calidad
                            </label>
                            <select id="mediaQuality" name="mediaQuality">
                                <option value="low">Baja</option>
                                <option value="medium" selected>Media</option>
                                <option value="high">Alta</option>
                                <option value="ultra">Ultra HD</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mediaLicense">
                                <i class="fas fa-copyright"></i> Licencia
                            </label>
                            <select id="mediaLicense" name="mediaLicense">
                                <option value="own">Propiedad propia</option>
                                <option value="creative">Creative Commons</option>
                                <option value="commercial">Comercial</option>
                                <option value="free">Libre uso</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mediaAccess">
                                <i class="fas fa-lock"></i> Acceso
                            </label>
                            <select id="mediaAccess" name="mediaAccess">
                                <option value="public">Público</option>
                                <option value="private">Privado</option>
                                <option value="restricted">Restringido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Opciones de Procesamiento</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="optimize" name="processing[]" value="optimize">
                                <label for="optimize">Optimizar automáticamente</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="watermark" name="processing[]" value="watermark">
                                <label for="watermark">Agregar marca de agua</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="compress" name="processing[]" value="compress">
                                <label for="compress">Comprimir archivo</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-upload"></i> Subir Archivo
                    </button>
                </form>
            </div>

            <!-- GALERÍA -->
            <div id="gallery" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Galería de Contenido</h3>
                    <p>Visualiza y organiza todos los archivos multimedia del portal.</p>
                </div>

                <div class="content-grid">
                    <div class="content-card">
                        <h4><i class="fas fa-image"></i> Galápagos - Tortugas</h4>
                        <p>Imagen de tortugas gigantes en las islas Galápagos</p>
                        <div class="content-actions">
                            <button class="btn-sm btn-view">Ver</button>
                            <button class="btn-sm btn-edit">Editar</button>
                            <button class="btn-sm btn-delete">Eliminar</button>
                        </div>
                    </div>

                    <div class="content-card">
                        <h4><i class="fas fa-video"></i> Quito - Centro Histórico</h4>
                        <p>Video turístico del centro histórico de Quito</p>
                        <div class="content-actions">
                            <button class="btn-sm btn-view">Ver</button>
                            <button class="btn-sm btn-edit">Editar</button>
                            <button class="btn-sm btn-delete">Eliminar</button>
                        </div>
                    </div>

                    <div class="content-card">
                        <h4><i class="fas fa-file-alt"></i> Guía Cuenca</h4>
                        <p>Artículo completo sobre la ciudad de Cuenca</p>
                        <div class="content-actions">
                            <button class="btn-sm btn-view">Ver</button>
                            <button class="btn-sm btn-edit">Editar</button>
                            <button class="btn-sm btn-delete">Eliminar</button>
                        </div>
                    </div>

                    <div class="content-card">
                        <h4><i class="fas fa-image"></i> Manta - Playa</h4>
                        <p>Fotografía de la playa de Manta</p>
                        <div class="content-actions">
                            <button class="btn-sm btn-view">Ver</button>
                            <button class="btn-sm btn-edit">Editar</button>
                            <button class="btn-sm btn-delete">Eliminar</button>
                        </div>
                    </div>

                    <div class="content-card">
                        <h4><i class="fas fa-video"></i> Salinas - Surf</h4>
                        <p>Video de surfistas en Salinas</p>
                        <div class="content-actions">
                            <button class="btn-sm btn-view">Ver</button>
                            <button class="btn-sm btn-edit">Editar</button>
                            <button class="btn-sm btn-delete">Eliminar</button>
                        </div>
                    </div>

                    <div class="content-card">
                        <h4><i class="fas fa-file-alt"></i> Gastronomía Andina</h4>
                        <p>Artículo sobre la gastronomía de la sierra</p>
                        <div class="content-actions">
                            <button class="btn-sm btn-view">Ver</button>
                            <button class="btn-sm btn-edit">Editar</button>
                            <button class="btn-sm btn-delete">Eliminar</button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Total de archivos:</strong> 2,847 archivos multimedia organizados en 156 categorías.
                </div>

                <button type="button" class="submit-btn">
                    <i class="fas fa-download"></i> Exportar Galería
                </button>
            </div>
        </div>

        <div class="screenshot-info">
            <h4><i class="fas fa-camera"></i> Captura de Pantalla</h4>
            <p><strong>Formulario:</strong> formulario_rf6.php</p>
            <p><strong>URL:</strong> http://localhost/registro/registro/formulario_rf6.php</p>
            <p><strong>Descripción:</strong> Sistema de gestión de contenido y multimedia para el portal turístico</p>
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

        // Simular funcionalidad de formularios
        document.addEventListener('DOMContentLoaded', function() {
            // Formulario de contenido
            document.getElementById('contentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Contenido guardado exitosamente');
            });

            // Formulario de multimedia
            document.getElementById('mediaForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Archivo subido exitosamente');
            });

            // Botones de acción
            var actionButtons = document.querySelectorAll('.btn-sm');
            actionButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var action = this.textContent;
                    var cardTitle = this.closest('.content-card').querySelector('h4').textContent;
                    alert('Acción: ' + action + ' en ' + cardTitle);
                });
            });
        });
    </script>
</body>
</html>
