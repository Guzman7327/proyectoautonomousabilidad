-- Script corregido para pgAdmin4
-- Portal de Turismo Inclusivo de Ecuador
-- EJECUTAR SECCIÓN POR SECCIÓN

-- ====================================
-- PASO 1: CREAR EXTENSIONES
-- ====================================
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ====================================
-- PASO 2: ELIMINAR TABLAS EXISTENTES
-- ====================================
DROP TABLE IF EXISTS accessibility_feedback CASCADE;
DROP TABLE IF EXISTS reviews CASCADE;
DROP TABLE IF EXISTS destinations CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ====================================
-- PASO 3: CREAR TABLA USERS
-- ====================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(80) UNIQUE NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    is_admin BOOLEAN DEFAULT FALSE,
    high_contrast BOOLEAN DEFAULT FALSE,
    large_text BOOLEAN DEFAULT FALSE,
    voice_enabled BOOLEAN DEFAULT FALSE,
    keyboard_navigation BOOLEAN DEFAULT FALSE,
    screen_reader BOOLEAN DEFAULT FALSE,
    preferred_language VARCHAR(10) DEFAULT 'es',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================
-- PASO 4: CREAR TABLA DESTINATIONS
-- ====================================
CREATE TABLE destinations (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) UNIQUE NOT NULL,
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

-- ====================================
-- PASO 5: CREAR TABLA REVIEWS
-- ====================================
CREATE TABLE reviews (
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

-- ====================================
-- PASO 6: CREAR TABLA ACCESSIBILITY_FEEDBACK
-- ====================================
CREATE TABLE accessibility_feedback (
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

-- ====================================
-- PASO 7: INSERTAR USUARIO ADMINISTRADOR
-- ====================================
INSERT INTO users (username, email, password_hash, first_name, last_name, is_admin, is_active) 
VALUES ('admin', 'admin@turismoinclusivo.ec', '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj1xPBSxUK1e', 'Administrador', 'Sistema', TRUE, TRUE);

-- ====================================
-- PASO 8: INSERTAR DESTINOS - COSTA
-- ====================================
INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Montañita', 'Famosa playa ecuatoriana conocida por el surf y la vida nocturna. Cuenta con rampas de acceso y servicios adaptados.', 'Costa', 'Playa', -1.8312, -80.7425, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles', 'Tel: +593-4-123-4567, info@montanita.ec', '/static/images/montanita.jpg', 'Medio');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Salinas', 'Balneario costero con playas de arena blanca y servicios turísticos completos. Infraestructura accesible.', 'Costa', 'Playa', -2.2142, -80.9558, 'Acceso en silla de ruedas, Estacionamiento accesible, Baños accesibles, Personal capacitado', 'Tel: +593-4-277-2391, turismo@salinas.gob.ec', '/static/images/salinas.jpg', 'Alto');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Guayaquil - Malecón 2000', 'Moderno paseo ribereño con jardines, museos y espacios recreativos completamente accesibles.', 'Costa', 'Ciudad', -2.1894, -79.8890, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles, Audio guía, Señalización táctil', 'Tel: +593-4-259-6909, info@malecon2000.org', '/static/images/malecon2000.jpg', 'Bajo');

-- ====================================
-- PASO 9: INSERTAR DESTINOS - SIERRA
-- ====================================
INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Quito - Centro Histórico', 'Patrimonio Cultural de la Humanidad con arquitectura colonial. Rutas accesibles disponibles.', 'Sierra', 'Cultura', -0.2201, -78.5123, 'Rampas de acceso, Audio guía, Guía en braille, Personal capacitado', 'Tel: +593-2-257-2445, turismo@quito.gob.ec', '/static/images/quito-centro.jpg', 'Bajo');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Otavalo', 'Famoso mercado indígena y hermosos paisajes andinos. Instalaciones adaptadas para todos.', 'Sierra', 'Cultura', 0.2352, -78.2617, 'Acceso en silla de ruedas, Información simplificada, Personal capacitado', 'Tel: +593-6-292-1994, info@otavalo.gob.ec', '/static/images/otavalo.jpg', 'Medio');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Baños de Agua Santa', 'Ciudad de aventura y aguas termales con servicios accesibles y adaptados.', 'Sierra', 'Termal', -1.3928, -78.4269, 'Acceso en silla de ruedas, Baños accesibles, Personal capacitado', 'Tel: +593-3-274-0483, turismo@banos.gob.ec', '/static/images/banos.jpg', 'Medio');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Cotopaxi - Parque Nacional', 'Parque nacional con el volcán Cotopaxi. Senderos adaptados y centro de visitantes accesible.', 'Sierra', 'Naturaleza', -0.6770, -78.4378, 'Rampas de acceso, Baños accesibles, Información simplificada, Personal capacitado', 'Tel: +593-3-271-9925, cotopaxi@ambiente.gob.ec', '/static/images/cotopaxi.jpg', 'Bajo');

-- ====================================
-- PASO 10: INSERTAR DESTINOS - AMAZONÍA
-- ====================================
INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Tena', 'Puerta de entrada a la Amazonía con actividades de aventura accesibles.', 'Amazonía', 'Naturaleza', -0.9939, -77.8142, 'Personal capacitado, Información simplificada', 'Tel: +593-6-288-6536, turismo@tena.gob.ec', '/static/images/tena.jpg', 'Medio');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Puyo', 'Ciudad amazónica con parques temáticos y experiencias culturales accesibles.', 'Amazonía', 'Cultura', -1.4885, -77.9975, 'Acceso en silla de ruedas, Rampas de acceso, Personal capacitado', 'Tel: +593-3-288-5120, info@puyo.gob.ec', '/static/images/puyo.jpg', 'Medio');

-- ====================================
-- PASO 11: INSERTAR DESTINOS - GALÁPAGOS
-- ====================================
INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('Puerto Ayora - Santa Cruz', 'Principal puerto de Galápagos con centro de interpretación accesible y senderos adaptados.', 'Galápagos', 'Naturaleza', -0.7442, -90.3064, 'Acceso en silla de ruedas, Rampas de acceso, Baños accesibles, Audio guía, Personal capacitado', 'Tel: +593-5-252-6174, info@galapagos.gob.ec', '/static/images/puerto-ayora.jpg', 'Alto');

INSERT INTO destinations (name, description, region, type, latitude, longitude, accessibility_features, contact_info, image, price_range) VALUES 
('San Cristóbal - Centro de Interpretación', 'Moderno centro educativo sobre la evolución y biodiversidad de Galápagos.', 'Galápagos', 'Cultura', -0.9078, -89.6151, 'Acceso en silla de ruedas, Audio guía, Guía en braille, Señalización táctil, Personal capacitado', 'Tel: +593-5-252-0358, educacion@galapagos.gob.ec', '/static/images/san-cristobal.jpg', 'Medio');

-- ====================================
-- PASO 12: CREAR ÍNDICES
-- ====================================
CREATE INDEX idx_destinations_region ON destinations(region);
CREATE INDEX idx_destinations_type ON destinations(type);
CREATE INDEX idx_users_email ON users(email);

-- ====================================
-- VERIFICACIÓN FINAL
-- ====================================
SELECT 'Tablas creadas correctamente' as status;
SELECT COUNT(*) as total_destinos FROM destinations;
SELECT COUNT(*) as total_usuarios FROM users;
