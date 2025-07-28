-- Script de inicialización de la base de datos
-- Portal de Turismo Inclusivo de Ecuador
-- Para ejecutar en PostgreSQL nativo

-- Conectarse como superusuario (postgres) y ejecutar este script

-- Crear base de datos si no existe
SELECT 'CREATE DATABASE turismo_inclusivo_ecuador'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'turismo_inclusivo_ecuador')\gexec

-- Conectarse a la base de datos
\c turismo_inclusivo_ecuador;

-- Crear extensiones necesarias
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "unaccent";

-- Crear usuario para la aplicación si no existe
DO $$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = 'turismo_user') THEN
        CREATE ROLE turismo_user LOGIN PASSWORD 'turismo_password_2024';
    END IF;
END
$$;

-- Otorgar permisos básicos
GRANT CONNECT ON DATABASE turismo_inclusivo_ecuador TO turismo_user;
GRANT USAGE ON SCHEMA public TO turismo_user;
GRANT CREATE ON SCHEMA public TO turismo_user;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(80) UNIQUE NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    is_admin BOOLEAN DEFAULT FALSE,
    -- Preferencias de accesibilidad
    high_contrast BOOLEAN DEFAULT FALSE,
    large_text BOOLEAN DEFAULT FALSE,
    voice_enabled BOOLEAN DEFAULT FALSE,
    keyboard_navigation BOOLEAN DEFAULT FALSE,
    screen_reader BOOLEAN DEFAULT FALSE,
    preferred_language VARCHAR(10) DEFAULT 'es',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de destinos turísticos
CREATE TABLE IF NOT EXISTS destinations (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    region VARCHAR(100),
    type VARCHAR(100),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    accessibility_features TEXT,
    contact_info TEXT,
    image VARCHAR(255),
    price_range VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de reseñas
CREATE TABLE IF NOT EXISTS reviews (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    destination_id INTEGER REFERENCES destinations(id) ON DELETE CASCADE,
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    content TEXT,
    accessibility_rating INTEGER CHECK (accessibility_rating >= 1 AND accessibility_rating <= 5),
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de retroalimentación de accesibilidad
CREATE TABLE IF NOT EXISTS accessibility_feedback (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    destination_id INTEGER REFERENCES destinations(id) ON DELETE CASCADE,
    feedback_type VARCHAR(100),
    description TEXT,
    suggestion TEXT,
    contact_email VARCHAR(120),
    is_resolved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar regiones de Ecuador
INSERT INTO regions (name, description) VALUES
('Costa', 'Región costera del Pacífico con playas, manglares y clima tropical'),
('Sierra', 'Región montañosa de los Andes con volcanes, páramos y clima templado'),
('Amazonía', 'Región amazónica con selva tropical, ríos y biodiversidad única'),
('Galápagos', 'Archipiélago volcánico con especies endémicas y ecosistemas únicos')
ON CONFLICT (name) DO NOTHING;

-- Tabla de tipos de destinos
CREATE TABLE IF NOT EXISTS destination_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar tipos de destinos
INSERT INTO destination_types (name, description, icon) VALUES
('Playa', 'Destinos costeros con playas y actividades acuáticas', 'beach'),
('Montaña', 'Destinos montañosos con senderismo y naturaleza', 'mountain'),
('Ciudad', 'Centros urbanos con cultura, historia y servicios', 'city'),
('Naturaleza', 'Parques nacionales, reservas y áreas protegidas', 'nature'),
('Cultura', 'Sitios históricos, museos y patrimonio cultural', 'culture'),
('Aventura', 'Actividades de aventura y deportes extremos', 'adventure'),
('Termal', 'Aguas termales y spas naturales', 'spa'),
('Arqueología', 'Sitios arqueológicos y patrimonio ancestral', 'archaeology')
ON CONFLICT (name) DO NOTHING;

-- Tabla de características de accesibilidad
CREATE TABLE IF NOT EXISTS accessibility_features (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    category VARCHAR(50), -- physical, visual, hearing, cognitive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar características de accesibilidad
INSERT INTO accessibility_features (name, description, icon, category) VALUES
('Acceso en silla de ruedas', 'Rutas y espacios accesibles para sillas de ruedas', 'wheelchair', 'physical'),
('Rampas de acceso', 'Rampas para superar desniveles y escalones', 'ramp', 'physical'),
('Baños accesibles', 'Servicios sanitarios adaptados', 'accessible-toilet', 'physical'),
('Estacionamiento accesible', 'Espacios de estacionamiento reservados', 'parking', 'physical'),
('Guía en braille', 'Información táctil en braille', 'braille', 'visual'),
('Audio guía', 'Descripciones auditivas del lugar', 'audio', 'visual'),
('Señalización táctil', 'Señales con texturas y relieve', 'touch', 'visual'),
('Contraste visual alto', 'Señalización con alto contraste', 'contrast', 'visual'),
('Intérprete de señas', 'Servicio de interpretación en lengua de señas', 'sign-language', 'hearing'),
('Bucle de inducción', 'Sistema de amplificación para audífonos', 'hearing-loop', 'hearing'),
('Información simplificada', 'Contenido en lenguaje fácil de entender', 'simple', 'cognitive'),
('Personal capacitado', 'Staff entrenado en atención a personas con discapacidad', 'staff', 'general')
ON CONFLICT (name) DO NOTHING;

-- Tabla de destinos turísticos (datos de ejemplo)
INSERT INTO destinations (
    name, description, region, type, latitude, longitude, 
    accessibility_features, contact_info, image, price_range,
    created_at
) VALUES
-- Costa
('Montañita', 'Famosa playa ecuatoriana conocida por el surf y la vida nocturna. Cuenta con rampas de acceso y servicios adaptados.', 'Costa', 'Playa', -1.8312, -80.7425, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles', 'Tel: +593-4-123-4567, info@montanita.ec', '/static/images/montanita.jpg', 'Medio', NOW()),

('Salinas', 'Balneario costero con playas de arena blanca y servicios turísticos completos. Infraestructura accesible.', 'Costa', 'Playa', -2.2142, -80.9558, 'Acceso en silla de ruedas, Estacionamiento accesible, Baños accesibles, Personal capacitado', 'Tel: +593-4-277-2391, turismo@salinas.gob.ec', '/static/images/salinas.jpg', 'Alto', NOW()),

('Guayaquil - Malecón 2000', 'Moderno paseo ribereño con jardines, museos y espacios recreativos completamente accesibles.', 'Costa', 'Ciudad', -2.1894, -79.8890, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles, Audio guía, Señalización táctil', 'Tel: +593-4-259-6909, info@malecon2000.org', '/static/images/malecon2000.jpg', 'Bajo', NOW()),

-- Sierra
('Quito - Centro Histórico', 'Patrimonio Cultural de la Humanidad con arquitectura colonial. Rutas accesibles disponibles.', 'Sierra', 'Cultura', -0.2201, -78.5123, 'Rampas de acceso, Audio guía, Guía en braille, Personal capacitado', 'Tel: +593-2-257-2445, turismo@quito.gob.ec', '/static/images/quito-centro.jpg', 'Bajo', NOW()),

('Otavalo', 'Famoso mercado indígena y hermosos paisajes andinos. Instalaciones adaptadas para todos.', 'Sierra', 'Cultura', 0.2352, -78.2617, 'Acceso en silla de ruedas, Información simplificada, Personal capacitado', 'Tel: +593-6-292-1994, info@otavalo.gob.ec', '/static/images/otavalo.jpg', 'Medio', NOW()),

('Baños de Agua Santa', 'Ciudad de aventura y aguas termales con servicios accesibles y adaptados.', 'Sierra', 'Termal', -1.3928, -78.4269, 'Acceso en silla de ruedas, Baños accesibles, Personal capacitado', 'Tel: +593-3-274-0483, turismo@banos.gob.ec', '/static/images/banos.jpg', 'Medio', NOW()),

('Cotopaxi - Parque Nacional', 'Parque nacional con el volcán Cotopaxi. Senderos adaptados y centro de visitantes accesible.', 'Sierra', 'Naturaleza', -0.6770, -78.4378, 'Rampas de acceso, Baños accesibles, Información simplificada, Personal capacitado', 'Tel: +593-3-271-9925, cotopaxi@ambiente.gob.ec', '/static/images/cotopaxi.jpg', 'Bajo', NOW()),

-- Amazonía
('Tena', 'Puerta de entrada a la Amazonía con actividades de aventura accesibles.', 'Amazonía', 'Naturaleza', -0.9939, -77.8142, 'Personal capacitado, Información simplificada', 'Tel: +593-6-288-6536, turismo@tena.gob.ec', '/static/images/tena.jpg', 'Medio', NOW()),

('Puyo', 'Ciudad amazónica con parques temáticos y experiencias culturales accesibles.', 'Amazonía', 'Cultura', -1.4885, -77.9975, 'Acceso en silla de ruedas, Rampas de acceso, Personal capacitado', 'Tel: +593-3-288-5120, info@puyo.gob.ec', '/static/images/puyo.jpg', 'Medio', NOW()),

-- Galápagos
('Puerto Ayora - Santa Cruz', 'Principal puerto de Galápagos con centro de interpretación accesible y senderos adaptados.', 'Galápagos', 'Naturaleza', -0.7442, -90.3064, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles, Audio guía, Personal capacitado', 'Tel: +593-5-252-6174, info@galapagos.gob.ec', '/static/images/puerto-ayora.jpg', 'Alto', NOW()),

('San Cristóbal - Centro de Interpretación', 'Moderno centro educativo sobre la evolución y biodiversidad de Galápagos.', 'Galápagos', 'Cultura', -0.9078, -89.6151, 'Acceso en silla de ruedas, Audio guía, Guía en braille, Señalización táctil, Personal capacitado', 'Tel: +593-5-252-0358, educacion@galapagos.gob.ec', '/static/images/san-cristobal.jpg', 'Medio', NOW())

ON CONFLICT (name) DO NOTHING;

-- Crear índices para mejor rendimiento
CREATE INDEX IF NOT EXISTS idx_destinations_region ON destinations(region);
CREATE INDEX IF NOT EXISTS idx_destinations_type ON destinations(type);
CREATE INDEX IF NOT EXISTS idx_destinations_location ON destinations(latitude, longitude);
CREATE INDEX IF NOT EXISTS idx_destinations_accessibility ON destinations USING gin(to_tsvector('spanish', accessibility_features));
CREATE INDEX IF NOT EXISTS idx_destinations_search ON destinations USING gin(to_tsvector('spanish', name || ' ' || description));

-- Crear vista para destinos accesibles
CREATE OR REPLACE VIEW accessible_destinations AS
SELECT 
    d.*,
    CASE 
        WHEN d.accessibility_features IS NOT NULL AND d.accessibility_features != '' THEN true
        ELSE false
    END as is_accessible,
    array_length(string_to_array(d.accessibility_features, ','), 1) as accessibility_count
FROM destinations d
WHERE d.accessibility_features IS NOT NULL AND d.accessibility_features != '';

-- Función para búsqueda de destinos
CREATE OR REPLACE FUNCTION search_destinations(
    search_query TEXT DEFAULT '',
    region_filter TEXT DEFAULT '',
    type_filter TEXT DEFAULT '',
    accessibility_filter TEXT DEFAULT '',
    price_filter TEXT DEFAULT ''
)
RETURNS TABLE (
    id INTEGER,
    name VARCHAR,
    description TEXT,
    region VARCHAR,
    type VARCHAR,
    latitude DECIMAL,
    longitude DECIMAL,
    accessibility_features TEXT,
    contact_info TEXT,
    image VARCHAR,
    price_range VARCHAR,
    relevance REAL
) 
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        d.id,
        d.name,
        d.description,
        d.region,
        d.type,
        d.latitude,
        d.longitude,
        d.accessibility_features,
        d.contact_info,
        d.image,
        d.price_range,
        CASE 
            WHEN search_query = '' THEN 1.0
            ELSE ts_rank(to_tsvector('spanish', d.name || ' ' || d.description), plainto_tsquery('spanish', search_query))
        END as relevance
    FROM destinations d
    WHERE 
        (search_query = '' OR to_tsvector('spanish', d.name || ' ' || d.description) @@ plainto_tsquery('spanish', search_query))
        AND (region_filter = '' OR d.region ILIKE '%' || region_filter || '%')
        AND (type_filter = '' OR d.type ILIKE '%' || type_filter || '%')
        AND (accessibility_filter = '' OR d.accessibility_features ILIKE '%' || accessibility_filter || '%')
        AND (price_filter = '' OR d.price_range ILIKE '%' || price_filter || '%')
    ORDER BY relevance DESC, d.name ASC;
END;
$$;

-- Función para obtener destinos cercanos
CREATE OR REPLACE FUNCTION get_nearby_destinations(
    lat DECIMAL,
    lng DECIMAL,
    radius_km INTEGER DEFAULT 50
)
RETURNS TABLE (
    id INTEGER,
    name VARCHAR,
    distance_km REAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        d.id,
        d.name,
        (6371 * acos(
            cos(radians(lat)) * 
            cos(radians(d.latitude)) * 
            cos(radians(d.longitude) - radians(lng)) + 
            sin(radians(lat)) * 
            sin(radians(d.latitude))
        ))::REAL as distance_km
    FROM destinations d
    WHERE (6371 * acos(
        cos(radians(lat)) * 
        cos(radians(d.latitude)) * 
        cos(radians(d.longitude) - radians(lng)) + 
        sin(radians(lat)) * 
        sin(radians(d.latitude))
    )) <= radius_km
    ORDER BY distance_km ASC;
END;
$$;

-- Crear triggers para auditoría
CREATE OR REPLACE FUNCTION audit_trigger()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.created_at = NOW();
        RETURN NEW;
    ELSIF TG_OP = 'UPDATE' THEN
        NEW.updated_at = NOW();
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- Aplicar trigger a las tablas principales
DROP TRIGGER IF EXISTS destinations_audit ON destinations;
CREATE TRIGGER destinations_audit
    BEFORE INSERT OR UPDATE ON destinations
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

DROP TRIGGER IF EXISTS users_audit ON users;
CREATE TRIGGER users_audit
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();

-- Comentarios en tablas
COMMENT ON TABLE destinations IS 'Tabla principal de destinos turísticos de Ecuador';
COMMENT ON TABLE users IS 'Tabla de usuarios del sistema con preferencias de accesibilidad';
COMMENT ON TABLE reviews IS 'Reseñas y comentarios de usuarios sobre destinos';
COMMENT ON TABLE accessibility_feedback IS 'Retroalimentación específica sobre accesibilidad';

-- Permisos para el usuario de la aplicación
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO turismo_user;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO turismo_user;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO turismo_user;

-- Mensaje final
DO $$
BEGIN
    RAISE NOTICE '==============================================';
    RAISE NOTICE 'Base de datos "turismo_inclusivo_ecuador" inicializada correctamente';
    RAISE NOTICE 'Usuario: turismo_user creado con permisos necesarios';
    RAISE NOTICE 'Se han creado % destinos de ejemplo', (SELECT COUNT(*) FROM destinations);
    RAISE NOTICE '==============================================';
    RAISE NOTICE '';
    RAISE NOTICE 'Para conectar la aplicación Flask, use:';
    RAISE NOTICE 'DATABASE_URL=postgresql://turismo_user:turismo_password_2024@localhost:5432/turismo_inclusivo_ecuador';
    RAISE NOTICE '';
    RAISE NOTICE 'Para ejecutar este script:';
    RAISE NOTICE 'psql -U postgres -f init-db.sql';
    RAISE NOTICE '==============================================';
END
$$;

python -m venv venv
venv\Scripts\activate  # Windows
pip install -r requirements.txt
