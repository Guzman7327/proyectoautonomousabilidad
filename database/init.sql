-- =====================================================
-- SCRIPT DE INICIALIZACIÓN - PORTAL DE TURISMO INCLUSIVO
-- Base de datos: PostgreSQL con PostGIS
-- =====================================================

-- Crear extensión PostGIS para geolocalización
CREATE EXTENSION IF NOT EXISTS postgis;

-- Crear extensión para UUID
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- TABLA: usuarios
-- =====================================================
CREATE TABLE usuarios (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    tipo_discapacidad JSONB, -- Almacena información de discapacidad
    rol VARCHAR(20) DEFAULT 'usuario' CHECK (rol IN ('admin', 'moderador', 'usuario', 'auditor')),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP,
    activo BOOLEAN DEFAULT true,
    preferencias JSONB DEFAULT '{}',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: categorias_rutas
-- =====================================================
CREATE TABLE categorias_rutas (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50),
    color VARCHAR(7), -- Código hexadecimal
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: rutas
-- =====================================================
CREATE TABLE rutas (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    categoria_id UUID REFERENCES categorias_rutas(id),
    duracion_estimada INTEGER, -- en minutos
    distancia_km DECIMAL(8,2),
    dificultad VARCHAR(20) CHECK (dificultad IN ('facil', 'moderada', 'dificil')),
    coordenadas GEOMETRY(LINESTRING, 4326), -- Ruta como línea en el mapa
    puntos_acceso JSONB, -- Puntos de entrada/salida
    nivel_accesibilidad JSONB, -- Detalles de accesibilidad
    imagenes JSONB, -- URLs de imágenes
    creador_id UUID REFERENCES usuarios(id),
    estado VARCHAR(20) DEFAULT 'borrador' CHECK (estado IN ('borrador', 'publicada', 'archivada')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: puntos_interes
-- =====================================================
CREATE TABLE puntos_interes (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    ruta_id UUID REFERENCES rutas(id) ON DELETE CASCADE,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo VARCHAR(50), -- restaurante, mirador, baño, etc.
    coordenadas GEOMETRY(POINT, 4326),
    nivel_accesibilidad JSONB, -- Detalles específicos de accesibilidad
    servicios_disponibles JSONB, -- Servicios disponibles
    horarios JSONB, -- Horarios de funcionamiento
    contacto JSONB, -- Información de contacto
    imagenes JSONB,
    orden INTEGER, -- Orden en la ruta
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: auditorias
-- =====================================================
CREATE TABLE auditorias (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    sitio_web VARCHAR(500) NOT NULL,
    nombre_sitio VARCHAR(200),
    auditor_id UUID REFERENCES usuarios(id),
    fecha_auditoria TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resultado_wcag JSONB, -- Resultados detallados de WCAG
    puntuacion_general INTEGER CHECK (puntuacion_general >= 0 AND puntuacion_general <= 100),
    nivel_conformidad VARCHAR(10) CHECK (nivel_conformidad IN ('A', 'AA', 'AAA', 'N/A')),
    problemas_encontrados JSONB, -- Lista de problemas encontrados
    recomendaciones JSONB, -- Recomendaciones de mejora
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'completada', 'revisada')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: evaluaciones
-- =====================================================
CREATE TABLE evaluaciones (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    ruta_id UUID REFERENCES rutas(id) ON DELETE CASCADE,
    usuario_id UUID REFERENCES usuarios(id),
    puntuacion_accesibilidad INTEGER CHECK (puntuacion_accesibilidad >= 1 AND puntuacion_accesibilidad <= 5),
    comentarios TEXT,
    aspectos_positivos JSONB,
    aspectos_negativos JSONB,
    fecha_evaluacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: reportes
-- =====================================================
CREATE TABLE reportes (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tipo VARCHAR(50) NOT NULL, -- 'ruta', 'punto_interes', 'auditoria'
    entidad_id UUID NOT NULL, -- ID de la entidad reportada
    usuario_id UUID REFERENCES usuarios(id),
    motivo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'revisado', 'resuelto', 'rechazado')),
    fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_resolucion TIMESTAMP,
    moderador_id UUID REFERENCES usuarios(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- ÍNDICES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices para búsquedas geográficas
CREATE INDEX idx_rutas_coordenadas ON rutas USING GIST (coordenadas);
CREATE INDEX idx_puntos_interes_coordenadas ON puntos_interes USING GIST (coordenadas);

-- Índices para búsquedas por texto
CREATE INDEX idx_rutas_titulo ON rutas USING GIN (to_tsvector('spanish', titulo));
CREATE INDEX idx_rutas_descripcion ON rutas USING GIN (to_tsvector('spanish', descripcion));

-- Índices para relaciones
CREATE INDEX idx_rutas_categoria ON rutas(categoria_id);
CREATE INDEX idx_rutas_creador ON rutas(creador_id);
CREATE INDEX idx_puntos_interes_ruta ON puntos_interes(ruta_id);
CREATE INDEX idx_auditorias_auditor ON auditorias(auditor_id);
CREATE INDEX idx_evaluaciones_ruta ON evaluaciones(ruta_id);
CREATE INDEX idx_evaluaciones_usuario ON evaluaciones(usuario_id);

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Insertar categorías de rutas
INSERT INTO categorias_rutas (nombre, descripcion, icono, color) VALUES
('Rutas Urbanas', 'Rutas accesibles por la ciudad con transporte público adaptado', 'city', '#3B82F6'),
('Rutas Naturales', 'Senderos en parques y reservas naturales con accesibilidad', 'tree', '#10B981'),
('Rutas Culturales', 'Museos, galerías y sitios históricos accesibles', 'museum', '#8B5CF6'),
('Rutas Gastronómicas', 'Restaurantes y cafés con opciones accesibles', 'utensils', '#F59E0B'),
('Rutas de Playa', 'Playas y costas con acceso para personas con discapacidad', 'umbrella-beach', '#06B6D4');

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (email, password_hash, nombre, apellido, rol) VALUES
('admin@turismoinclusivo.com', '$2b$10$rQZ8K9mN2pL1vX3yA6bC7dE8fG9hI0jK1lM2nO3pQ4rS5tU6vW7xY8zA9bC0dE1f', 'Administrador', 'Sistema', 'admin');

-- =====================================================
-- FUNCIONES Y TRIGGERS
-- =====================================================

-- Función para actualizar updated_at automáticamente
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Triggers para actualizar updated_at
CREATE TRIGGER update_usuarios_updated_at BEFORE UPDATE ON usuarios FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_rutas_updated_at BEFORE UPDATE ON rutas FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_auditorias_updated_at BEFORE UPDATE ON auditorias FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista para rutas con información completa
CREATE VIEW rutas_completas AS
SELECT 
    r.*,
    c.nombre as categoria_nombre,
    c.color as categoria_color,
    u.nombre as creador_nombre,
    u.apellido as creador_apellido,
    COUNT(pi.id) as total_puntos_interes,
    AVG(e.puntuacion_accesibilidad) as puntuacion_promedio,
    COUNT(e.id) as total_evaluaciones
FROM rutas r
LEFT JOIN categorias_rutas c ON r.categoria_id = c.id
LEFT JOIN usuarios u ON r.creador_id = u.id
LEFT JOIN puntos_interes pi ON r.id = pi.ruta_id
LEFT JOIN evaluaciones e ON r.id = e.ruta_id
WHERE r.estado = 'publicada'
GROUP BY r.id, c.nombre, c.color, u.nombre, u.apellido;

-- Vista para auditorías recientes
CREATE VIEW auditorias_recientes AS
SELECT 
    a.*,
    u.nombre as auditor_nombre,
    u.apellido as auditor_apellido
FROM auditorias a
LEFT JOIN usuarios u ON a.auditor_id = u.id
WHERE a.estado = 'completada'
ORDER BY a.fecha_auditoria DESC;

-- =====================================================
-- PERMISOS Y ROLES
-- =====================================================

-- Crear roles de base de datos (ejecutar como superusuario)
-- CREATE ROLE turismo_app WITH LOGIN PASSWORD 'password_seguro';
-- GRANT CONNECT ON DATABASE turismo_inclusivo TO turismo_app;
-- GRANT USAGE ON SCHEMA public TO turismo_app;
-- GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO turismo_app;
-- GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO turismo_app;

COMMIT; 