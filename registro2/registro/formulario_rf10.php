<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Contenido y SEO - Portal Turístico</title>
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

        .content-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .content-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .status-published {
            background: #d4edda;
            color: #155724;
        }

        .status-draft {
            background: #fff3cd;
            color: #856404;
        }

        .status-review {
            background: #d1ecf1;
            color: #0c5460;
        }

        .seo-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .seo-score {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .score-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1.2em;
        }

        .score-excellent {
            background: #28a745;
        }

        .score-good {
            background: #ffc107;
        }

        .score-poor {
            background: #dc3545;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .media-item {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .media-item:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }

        .media-item.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .media-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
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

        .keyword-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .keyword-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .keyword-tag:hover {
            background: #1976d2;
            color: white;
        }

        .keyword-tag.selected {
            background: #1976d2;
            color: white;
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

            .media-grid {
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
        <div class="requirement-badge">RF10</div>
        
        <div class="form-header">
            <h1>📝 Portal Turístico Ecuador</h1>
            <p>Sistema de Gestión de Contenido y SEO - Prototipo RF10</p>
        </div>

        <div class="form-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('content')">
                    <i class="fas fa-edit"></i> Contenido
                </button>
                <button class="tab" onclick="showTab('seo')">
                    <i class="fas fa-search"></i> SEO
                </button>
                <button class="tab" onclick="showTab('media')">
                    <i class="fas fa-images"></i> Multimedia
                </button>
                <button class="tab" onclick="showTab('analytics')">
                    <i class="fas fa-chart-bar"></i> Analytics
                </button>
                <button class="tab" onclick="showTab('settings')">
                    <i class="fas fa-cog"></i> Configuración
                </button>
            </div>

            <!-- CONTENIDO -->
            <div id="content" class="tab-content active">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Gestión de Contenido</h3>
                    <p>Crea y gestiona contenido optimizado para el portal turístico con herramientas de edición avanzadas y control de versiones.</p>
                </div>

                <form id="contentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contentType">
                                <i class="fas fa-tag"></i> Tipo de Contenido
                            </label>
                            <select id="contentType" name="contentType" required>
                                <option value="">Selecciona tipo de contenido</option>
                                <option value="article">Artículo</option>
                                <option value="destination">Destino Turístico</option>
                                <option value="hotel">Hotel/Alojamiento</option>
                                <option value="activity">Actividad Turística</option>
                                <option value="promotion">Promoción</option>
                                <option value="news">Noticia</option>
                                <option value="page">Página Estática</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contentStatus">
                                <i class="fas fa-toggle-on"></i> Estado
                            </label>
                            <select id="contentStatus" name="contentStatus" required>
                                <option value="draft">Borrador</option>
                                <option value="review">En Revisión</option>
                                <option value="published">Publicado</option>
                                <option value="archived">Archivado</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contentTitle">
                            <i class="fas fa-heading"></i> Título
                        </label>
                        <input type="text" id="contentTitle" name="contentTitle" placeholder="Título del contenido" required>
                    </div>

                    <div class="form-group">
                        <label for="contentSlug">
                            <i class="fas fa-link"></i> URL Amigable
                        </label>
                        <input type="text" id="contentSlug" name="contentSlug" placeholder="url-amigable-del-contenido" required>
                    </div>

                    <div class="form-group">
                        <label for="contentExcerpt">
                            <i class="fas fa-align-left"></i> Resumen
                        </label>
                        <textarea id="contentExcerpt" name="contentExcerpt" rows="3" placeholder="Breve descripción del contenido..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contentBody">
                            <i class="fas fa-edit"></i> Contenido Principal
                        </label>
                        <textarea id="contentBody" name="contentBody" rows="12" placeholder="Contenido principal del artículo o página..."></textarea>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="contentAuthor">
                                <i class="fas fa-user"></i> Autor
                            </label>
                            <input type="text" id="contentAuthor" name="contentAuthor" placeholder="Nombre del autor">
                        </div>

                        <div class="form-group">
                            <label for="contentCategory">
                                <i class="fas fa-folder"></i> Categoría
                            </label>
                            <select id="contentCategory" name="contentCategory" required>
                                <option value="">Selecciona categoría</option>
                                <option value="destinations">Destinos</option>
                                <option value="hotels">Hoteles</option>
                                <option value="activities">Actividades</option>
                                <option value="news">Noticias</option>
                                <option value="promotions">Promociones</option>
                                <option value="guides">Guías</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contentTags">
                                <i class="fas fa-tags"></i> Etiquetas
                            </label>
                            <input type="text" id="contentTags" name="contentTags" placeholder="etiqueta1, etiqueta2, etiqueta3">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contentImage">
                            <i class="fas fa-image"></i> Imagen Principal
                        </label>
                        <input type="file" id="contentImage" name="contentImage" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Opciones de Publicación</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="featured" name="options[]" value="featured">
                                <label for="featured">Contenido destacado</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="allowComments" name="options[]" value="comments" checked>
                                <label for="allowComments">Permitir comentarios</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="socialShare" name="options[]" value="social" checked>
                                <label for="socialShare">Compartir en redes sociales</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-save"></i> Guardar Contenido
                    </button>
                </form>

                <!-- CONTENIDO RECIENTE -->
                <div class="content-card">
                    <div class="content-header">
                        <h4><i class="fas fa-list"></i> Contenido Reciente</h4>
                    </div>
                    
                    <div class="content-card">
                        <div class="content-header">
                            <h5>Guía Completa: Qué hacer en Quito</h5>
                            <span class="content-status status-published">Publicado</span>
                        </div>
                        <p><strong>Autor:</strong> María González</p>
                        <p><strong>Categoría:</strong> Guías</p>
                        <p><strong>Vistas:</strong> 2,847</p>
                        <div class="form-row">
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="submit-btn warning">
                                <i class="fas fa-eye"></i> Vista Previa
                            </button>
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="content-header">
                            <h5>Los mejores hoteles en Galápagos</h5>
                            <span class="content-status status-review">En Revisión</span>
                        </div>
                        <p><strong>Autor:</strong> Carlos Ruiz</p>
                        <p><strong>Categoría:</strong> Hoteles</p>
                        <p><strong>Última edición:</strong> Hace 2 horas</p>
                        <div class="form-row">
                            <button class="submit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="submit-btn success">
                                <i class="fas fa-check"></i> Aprobar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div id="seo" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Optimización SEO</h3>
                    <p>Optimiza el contenido para motores de búsqueda con herramientas avanzadas de análisis y sugerencias de mejora.</p>
                </div>

                <div class="seo-section">
                    <h4><i class="fas fa-chart-line"></i> Análisis SEO</h4>
                    <div class="seo-score">
                        <div class="score-circle score-excellent">92</div>
                        <div>
                            <h5>Puntuación SEO Excelente</h5>
                            <p>El contenido está bien optimizado para motores de búsqueda</p>
                        </div>
                    </div>
                </div>

                <form id="seoForm">
                    <div class="form-group">
                        <label for="seoTitle">
                            <i class="fas fa-heading"></i> Título SEO (Meta Title)
                        </label>
                        <input type="text" id="seoTitle" name="seoTitle" placeholder="Título optimizado para SEO (máximo 60 caracteres)" maxlength="60">
                        <small style="color: #6c757d;">0/60 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="seoDescription">
                            <i class="fas fa-align-left"></i> Descripción SEO (Meta Description)
                        </label>
                        <textarea id="seoDescription" name="seoDescription" rows="3" placeholder="Descripción optimizada para SEO (máximo 160 caracteres)" maxlength="160"></textarea>
                        <small style="color: #6c757d;">0/160 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="seoKeywords">
                            <i class="fas fa-tags"></i> Palabras Clave
                        </label>
                        <input type="text" id="seoKeywords" name="seoKeywords" placeholder="palabra1, palabra2, palabra3">
                        <div class="keyword-tags">
                            <span class="keyword-tag selected">turismo ecuador</span>
                            <span class="keyword-tag">quito</span>
                            <span class="keyword-tag">galápagos</span>
                            <span class="keyword-tag">hoteles</span>
                            <span class="keyword-tag">viajes</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="seoCanonical">
                                <i class="fas fa-link"></i> URL Canónica
                            </label>
                            <input type="url" id="seoCanonical" name="seoCanonical" placeholder="https://turismoecuador.com/...">
                        </div>

                        <div class="form-group">
                            <label for="seoFocusKeyword">
                                <i class="fas fa-bullseye"></i> Palabra Clave Principal
                            </label>
                            <input type="text" id="seoFocusKeyword" name="seoFocusKeyword" placeholder="Palabra clave más importante">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Configuraciones SEO</label>
                        <div class="form-row-4">
                            <div class="form-checkbox">
                                <input type="checkbox" id="indexPage" name="seoOptions[]" value="index" checked>
                                <label for="indexPage">Permitir indexación</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="followLinks" name="seoOptions[]" value="follow" checked>
                                <label for="followLinks">Seguir enlaces</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="socialMeta" name="seoOptions[]" value="social" checked>
                                <label for="socialMeta">Meta tags sociales</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="schemaMarkup" name="seoOptions[]" value="schema">
                                <label for="schemaMarkup">Schema markup</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-search"></i> Optimizar SEO
                    </button>
                </form>

                <div class="alert alert-info">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Sugerencia SEO:</strong> Considera agregar más contenido relacionado con "turismo en Ecuador" para mejorar el ranking en búsquedas.
                </div>
            </div>

            <!-- MULTIMEDIA -->
            <div id="media" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Biblioteca Multimedia</h3>
                    <p>Gestiona imágenes, videos y archivos multimedia para enriquecer el contenido del portal turístico.</p>
                </div>

                <div class="form-group">
                    <label for="mediaUpload">
                        <i class="fas fa-cloud-upload-alt"></i> Subir Archivos
                    </label>
                    <input type="file" id="mediaUpload" name="mediaUpload" multiple accept="image/*,video/*">
                </div>

                <div class="form-group">
                    <label for="mediaSearch">
                        <i class="fas fa-search"></i> Buscar Multimedia
                    </label>
                    <input type="text" id="mediaSearch" name="mediaSearch" placeholder="Buscar por nombre, categoría o etiquetas...">
                </div>

                <div class="form-group">
                    <label for="mediaCategory">
                        <i class="fas fa-filter"></i> Filtrar por Categoría
                    </label>
                    <select id="mediaCategory" name="mediaCategory">
                        <option value="">Todas las categorías</option>
                        <option value="destinations">Destinos</option>
                        <option value="hotels">Hoteles</option>
                        <option value="activities">Actividades</option>
                        <option value="food">Gastronomía</option>
                        <option value="culture">Cultura</option>
                        <option value="nature">Naturaleza</option>
                    </select>
                </div>

                <div class="media-grid">
                    <div class="media-item selected">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGVlMmU2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiAxPC90ZXh0Pjwvc3ZnPg==" alt="Imagen 1">
                        <h5>Quito Centro Histórico</h5>
                        <p>Categoría: Destinos</p>
                        <p>Tamaño: 2.3 MB</p>
                    </div>

                    <div class="media-item">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGVlMmU2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiAyPC90ZXh0Pjwvc3ZnPg==" alt="Imagen 2">
                        <h5>Hotel Plaza Grande</h5>
                        <p>Categoría: Hoteles</p>
                        <p>Tamaño: 1.8 MB</p>
                    </div>

                    <div class="media-item">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGVlMmU2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiAzPC90ZXh0Pjwvc3ZnPg==" alt="Imagen 3">
                        <h5>Islas Galápagos</h5>
                        <p>Categoría: Naturaleza</p>
                        <p>Tamaño: 3.1 MB</p>
                    </div>

                    <div class="media-item">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGVlMmU2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiA0PC90ZXh0Pjwvc3ZnPg==" alt="Imagen 4">
                        <h5>Comida Ecuatoriana</h5>
                        <p>Categoría: Gastronomía</p>
                        <p>Tamaño: 2.7 MB</p>
                    </div>
                </div>

                <div class="form-row">
                    <button class="submit-btn">
                        <i class="fas fa-download"></i> Descargar Seleccionados
                    </button>
                    <button class="submit-btn danger">
                        <i class="fas fa-trash"></i> Eliminar Seleccionados
                    </button>
                </div>
            </div>

            <!-- ANALYTICS -->
            <div id="analytics" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Analytics de Contenido</h3>
                    <p>Analiza el rendimiento del contenido y obtén insights para mejorar la estrategia de contenido.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-value">45,230</div>
                        <div class="stat-label">Vistas Totales</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-value">127</div>
                        <div class="stat-label">Artículos Publicados</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="stat-value">8,945</div>
                        <div class="stat-label">Búsquedas Orgánicas</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-share"></i>
                        </div>
                        <div class="stat-value">2,847</div>
                        <div class="stat-label">Compartidos</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="content-card">
                        <h4><i class="fas fa-chart-pie"></i> Contenido Más Popular</h4>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-pie" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Contenido Popular</p>
                        </div>
                    </div>

                    <div class="content-card">
                        <h4><i class="fas fa-chart-line"></i> Tendencias de Tráfico</h4>
                        <div style="height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-chart-line" style="font-size: 3em;"></i>
                            <p style="margin-left: 15px;">Gráfico de Tendencias</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success">
                    <i class="fas fa-arrow-up"></i>
                    <strong>Mejora:</strong> El contenido sobre "Galápagos" ha aumentado un 25% en vistas este mes.
                </div>
            </div>

            <!-- CONFIGURACIÓN -->
            <div id="settings" class="tab-content">
                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Configuración del Sistema</h3>
                    <p>Configura las preferencias generales del sistema de gestión de contenido y SEO.</p>
                </div>

                <form id="settingsForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="defaultLanguage">
                                <i class="fas fa-language"></i> Idioma por Defecto
                            </label>
                            <select id="defaultLanguage" name="defaultLanguage" required>
                                <option value="es" selected>Español</option>
                                <option value="en">English</option>
                                <option value="fr">Français</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="timezone">
                                <i class="fas fa-clock"></i> Zona Horaria
                            </label>
                            <select id="timezone" name="timezone" required>
                                <option value="America/Guayaquil" selected>Ecuador (GMT-5)</option>
                                <option value="America/New_York">Nueva York (GMT-5)</option>
                                <option value="Europe/Madrid">Madrid (GMT+1)</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="maxFileSize">
                                <i class="fas fa-file-upload"></i> Tamaño Máximo de Archivo (MB)
                            </label>
                            <input type="number" id="maxFileSize" name="maxFileSize" value="10" min="1" max="50" required>
                        </div>

                        <div class="form-group">
                            <label for="allowedFormats">
                                <i class="fas fa-file-image"></i> Formatos Permitidos
                            </label>
                            <input type="text" id="allowedFormats" name="allowedFormats" value="jpg, jpeg, png, gif, pdf, doc, docx" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="siteTitle">
                            <i class="fas fa-globe"></i> Título del Sitio
                        </label>
                        <input type="text" id="siteTitle" name="siteTitle" value="Portal Turístico Ecuador" required>
                    </div>

                    <div class="form-group">
                        <label for="siteDescription">
                            <i class="fas fa-align-left"></i> Descripción del Sitio
                        </label>
                        <textarea id="siteDescription" name="siteDescription" rows="3" placeholder="Descripción general del portal turístico..."></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-square"></i> Configuraciones Avanzadas</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="autoSave" name="advanced[]" value="autoSave" checked>
                                <label for="autoSave">Guardado automático</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="versionControl" name="advanced[]" value="versionControl" checked>
                                <label for="versionControl">Control de versiones</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="seoAnalysis" name="advanced[]" value="seoAnalysis" checked>
                                <label for="seoAnalysis">Análisis SEO automático</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-shield-alt"></i> Configuraciones de Seguridad</label>
                        <div class="form-row-3">
                            <div class="form-checkbox">
                                <input type="checkbox" id="fileScanning" name="security[]" value="fileScanning" checked>
                                <label for="fileScanning">Escaneo de archivos</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="backupEnabled" name="security[]" value="backup" checked>
                                <label for="backupEnabled">Backup automático</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="accessLog" name="security[]" value="accessLog" checked>
                                <label for="accessLog">Registro de acceso</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn success">
                        <i class="fas fa-save"></i> Guardar Configuración
                    </button>
                </form>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Configuración Actualizada:</strong> Los cambios se han guardado exitosamente. El sistema está funcionando correctamente.
                </div>
            </div>
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

        // Simular guardado de contenido
        document.getElementById('contentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Contenido guardado exitosamente.');
        });

        // Simular optimización SEO
        document.getElementById('seoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('SEO optimizado exitosamente.');
        });

        // Simular guardado de configuración
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Configuración guardada exitosamente.');
        });

        // Contador de caracteres para SEO
        document.getElementById('seoTitle').addEventListener('input', function() {
            const length = this.value.length;
            this.nextElementSibling.textContent = length + '/60 caracteres';
        });

        document.getElementById('seoDescription').addEventListener('input', function() {
            const length = this.value.length;
            this.nextElementSibling.textContent = length + '/160 caracteres';
        });
    </script>
</body>
</html>
