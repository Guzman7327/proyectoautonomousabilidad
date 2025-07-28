-- Script simplificado para pgAdmin4
-- Portal de Turismo Inclusivo de Ecuador

-- Usar la base de datos turismo_inclusivo_ecuador
-- (Ya debería estar seleccionada en pgAdmin4)

-- Crear extensiones necesarias
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "unaccent";

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

-- Insertar datos de ejemplo para destinos de Ecuador
INSERT INTO destinations (
    name, description, region, type, latitude, longitude, 
    accessibility_features, contact_info, image, price_range
) VALUES
-- Costa
('Montañita', 'Famosa playa ecuatoriana conocida por el surf y la vida nocturna. Cuenta con rampas de acceso y servicios adaptados para personas con discapacidad.', 'Costa', 'Playa', -1.8312, -80.7425, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles', 'Tel: +593-4-123-4567, info@montanita.ec', '/static/images/montanita.jpg', 'Medio'),

('Salinas', 'Balneario costero con playas de arena blanca y servicios turísticos completos. Infraestructura completamente accesible.', 'Costa', 'Playa', -2.2142, -80.9558, 'Acceso en silla de ruedas, Estacionamiento accesible, Baños accesibles, Personal capacitado', 'Tel: +593-4-277-2391, turismo@salinas.gob.ec', '/static/images/salinas.jpg', 'Alto'),

('Guayaquil - Malecón 2000', 'Moderno paseo ribereño con jardines, museos y espacios recreativos completamente accesibles.', 'Costa', 'Ciudad', -2.1894, -79.8890, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles, Audio guía, Señalización táctil', 'Tel: +593-4-259-6909, info@malecon2000.org', '/static/images/malecon2000.jpg', 'Bajo'),

-- Sierra
('Quito - Centro Histórico', 'Patrimonio Cultural de la Humanidad con arquitectura colonial. Rutas accesibles disponibles.', 'Sierra', 'Cultura', -0.2201, -78.5123, 'Rampas de acceso, Audio guía, Guía en braille, Personal capacitado', 'Tel: +593-2-257-2445, turismo@quito.gob.ec', '/static/images/quito-centro.jpg', 'Bajo'),

('Otavalo', 'Famoso mercado indígena y hermosos paisajes andinos. Instalaciones adaptadas para todos.', 'Sierra', 'Cultura', 0.2352, -78.2617, 'Acceso en silla de ruedas, Información simplificada, Personal capacitado', 'Tel: +593-6-292-1994, info@otavalo.gob.ec', '/static/images/otavalo.jpg', 'Medio'),

('Baños de Agua Santa', 'Ciudad de aventura y aguas termales con servicios accesibles y adaptados.', 'Sierra', 'Termal', -1.3928, -78.4269, 'Acceso en silla de ruedas, Baños accesibles, Personal capacitado', 'Tel: +593-3-274-0483, turismo@banos.gob.ec', '/static/images/banos.jpg', 'Medio'),

('Cotopaxi - Parque Nacional', 'Parque nacional con el volcán Cotopaxi. Senderos adaptados y centro de visitantes accesible.', 'Sierra', 'Naturaleza', -0.6770, -78.4378, 'Rampas de acceso, Baños accesibles, Información simplificada, Personal capacitado', 'Tel: +593-3-271-9925, cotopaxi@ambiente.gob.ec', '/static/images/cotopaxi.jpg', 'Bajo'),

-- Amazonía
('Tena', 'Puerta de entrada a la Amazonía con actividades de aventura accesibles.', 'Amazonía', 'Naturaleza', -0.9939, -77.8142, 'Personal capacitado, Información simplificada', 'Tel: +593-6-288-6536, turismo@tena.gob.ec', '/static/images/tena.jpg', 'Medio'),

('Puyo', 'Ciudad amazónica con parques temáticos y experiencias culturales accesibles.', 'Amazonía', 'Cultura', -1.4885, -77.9975, 'Acceso en silla de ruedas, Rampas de acceso, Personal capacitado', 'Tel: +593-3-288-5120, info@puyo.gob.ec', '/static/images/puyo.jpg', 'Medio'),

-- Galápagos
('Puerto Ayora - Santa Cruz', 'Principal puerto de Galápagos con centro de interpretación accesible y senderos adaptados.', 'Galápagos', 'Naturaleza', -0.7442, -90.3064, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles, Audio guía, Personal capacitado', 'Tel: +593-5-252-6174, info@galapagos.gob.ec', '/static/images/puerto-ayora.jpg', 'Alto'),

('San Cristóbal - Centro de Interpretación', 'Moderno centro educativo sobre la evolución y biodiversidad de Galápagos.', 'Galápagos', 'Cultura', -0.9078, -89.6151, 'Acceso en silla de ruedas, Audio guía, Guía en braille, Señalización táctil, Personal capacitado', 'Tel: +593-5-252-0358, educacion@galapagos.gob.ec', '/static/images/san-cristobal.jpg', 'Medio')

ON CONFLICT (name) DO NOTHING;

-- Crear índices para mejor rendimiento
CREATE INDEX IF NOT EXISTS idx_destinations_region ON destinations(region);
CREATE INDEX IF NOT EXISTS idx_destinations_type ON destinations(type);
CREATE INDEX IF NOT EXISTS idx_destinations_location ON destinations(latitude, longitude);
CREATE INDEX IF NOT EXISTS idx_destinations_search ON destinations USING gin(to_tsvector('spanish', name || ' ' || description));

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

-- Otorgar permisos al usuario turismo_user
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO turismo_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO turismo_user;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO turismo_user;

-- Mensaje de confirmación
SELECT 'Base de datos configurada correctamente para Portal de Turismo Inclusivo Ecuador' as mensaje,
       COUNT(*) as destinos_creados FROM destinations;
