-- Script final para recrear TODAS las tablas que coincidan EXACTAMENTE con los modelos Flask
-- Portal de Turismo Inclusivo de Ecuador

-- ====================================
-- ELIMINAR TODAS LAS TABLAS EXISTENTES
-- ====================================
DROP TABLE IF EXISTS accessibility_feedback CASCADE;
DROP TABLE IF EXISTS favorites CASCADE;
DROP TABLE IF EXISTS reviews CASCADE;
DROP TABLE IF EXISTS destinations CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ====================================
-- CREAR TABLA USERS COMPLETA
-- ====================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    public_id VARCHAR(50) UNIQUE DEFAULT gen_random_uuid()::text,
    email VARCHAR(120) UNIQUE NOT NULL,
    username VARCHAR(80) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    
    -- Preferencias de accesibilidad
    high_contrast BOOLEAN DEFAULT FALSE,
    large_text BOOLEAN DEFAULT FALSE,
    screen_reader BOOLEAN DEFAULT FALSE,
    keyboard_navigation BOOLEAN DEFAULT FALSE,
    voice_enabled BOOLEAN DEFAULT FALSE,
    preferred_language VARCHAR(10) DEFAULT 'es',
    
    -- Metadatos
    is_active BOOLEAN DEFAULT TRUE,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP
);

-- ====================================
-- CREAR TABLA DESTINATIONS COMPLETA
-- ====================================
CREATE TABLE destinations (
    id SERIAL PRIMARY KEY,
    public_id VARCHAR(50) UNIQUE DEFAULT gen_random_uuid()::text,
    
    -- Información básica
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(500),
    
    -- Ubicación
    province VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address VARCHAR(500),
    latitude FLOAT,
    longitude FLOAT,
    
    -- Información de accesibilidad
    wheelchair_accessible BOOLEAN DEFAULT FALSE,
    audio_guide_available BOOLEAN DEFAULT FALSE,
    braille_info_available BOOLEAN DEFAULT FALSE,
    sign_language_guide BOOLEAN DEFAULT FALSE,
    accessible_parking BOOLEAN DEFAULT FALSE,
    accessible_bathrooms BOOLEAN DEFAULT FALSE,
    tactile_paths BOOLEAN DEFAULT FALSE,
    accessibility_rating INTEGER DEFAULT 0,
    accessibility_notes TEXT,
    
    -- Categorías y actividades
    category VARCHAR(100) NOT NULL,
    activities JSONB,
    facilities JSONB,
    
    -- Información práctica
    opening_hours JSONB,
    admission_fee FLOAT DEFAULT 0.0,
    contact_phone VARCHAR(20),
    contact_email VARCHAR(120),
    website VARCHAR(200),
    
    -- Multimedia
    main_image VARCHAR(300),
    image_alt_text VARCHAR(500),
    images JSONB,
    virtual_tour_url VARCHAR(300),
    
    -- Metadatos
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    difficulty_level VARCHAR(20) DEFAULT 'easy',
    best_time_to_visit VARCHAR(100),
    average_duration INTEGER,
    
    -- Fechas
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================
-- CREAR TABLA REVIEWS COMPLETA
-- ====================================
CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE NOT NULL,
    destination_id INTEGER REFERENCES destinations(id) ON DELETE CASCADE NOT NULL,
    
    -- Contenido de la reseña
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    
    -- Evaluación específica de accesibilidad
    accessibility_rating INTEGER CHECK (accessibility_rating >= 1 AND accessibility_rating <= 5),
    accessibility_notes TEXT,
    
    -- Información específica de accesibilidad evaluada
    wheelchair_experience VARCHAR(20),
    visual_accessibility VARCHAR(20),
    hearing_accessibility VARCHAR(20),
    cognitive_accessibility VARCHAR(20),
    
    -- Metadatos
    is_approved BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    helpful_votes INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================
-- CREAR TABLA ACCESSIBILITY_FEEDBACK
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
-- CREAR TABLA FAVORITES
-- ====================================
CREATE TABLE favorites (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE NOT NULL,
    destination_id INTEGER REFERENCES destinations(id) ON DELETE CASCADE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, destination_id)
);

-- ====================================
-- CREAR ÍNDICES
-- ====================================
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_destinations_name ON destinations(name);
CREATE INDEX idx_destinations_slug ON destinations(slug);
CREATE INDEX idx_destinations_province ON destinations(province);
CREATE INDEX idx_destinations_category ON destinations(category);
CREATE INDEX idx_destinations_is_active ON destinations(is_active);
CREATE INDEX idx_destinations_is_featured ON destinations(is_featured);
CREATE INDEX idx_reviews_user_id ON reviews(user_id);
CREATE INDEX idx_reviews_destination_id ON reviews(destination_id);
CREATE INDEX idx_reviews_is_approved ON reviews(is_approved);
CREATE INDEX idx_accessibility_feedback_destination_id ON accessibility_feedback(destination_id);
CREATE INDEX idx_favorites_user_id ON favorites(user_id);
CREATE INDEX idx_favorites_destination_id ON favorites(destination_id);

-- ====================================
-- INSERTAR USUARIO ADMINISTRADOR
-- ====================================
INSERT INTO users (username, email, password_hash, first_name, last_name, is_admin, is_active) 
VALUES ('admin', 'admin@turismoinclusivo.ec', '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj1xPBSxUK1e', 'Administrador', 'Sistema', TRUE, TRUE);

-- ====================================
-- INSERTAR DESTINOS DE ECUADOR
-- ====================================

-- Costa
INSERT INTO destinations (
    name, slug, description, short_description, province, city, category, 
    latitude, longitude, wheelchair_accessible, audio_guide_available, 
    accessible_parking, accessible_bathrooms, is_featured, is_active,
    accessibility_rating, contact_phone, activities, facilities,
    main_image, difficulty_level, average_duration
) VALUES 
('Montañita', 'montanita', 
'Famosa playa ecuatoriana conocida por el surf y la vida nocturna. Cuenta con rampas de acceso y servicios adaptados para personas con discapacidad.',
'Playa para surf con servicios accesibles',
'Santa Elena', 'Montañita', 'playa',
-1.8312, -80.7425, true, false, true, true, true, true, 4,
'+593-4-206-0200', 
'["surf", "vida nocturna", "restaurantes", "compras"]'::jsonb,
'["rampas de acceso", "baños accesibles", "estacionamiento", "restaurantes"]'::jsonb,
'/static/images/montanita.jpg', 'easy', 240),

('Salinas', 'salinas',
'Balneario costero con playas de arena blanca y servicios turísticos completos. Infraestructura completamente accesible.',
'Balneario con infraestructura accesible',
'Santa Elena', 'Salinas', 'playa',
-2.2142, -80.9558, true, true, true, true, true, true, 5,
'+593-4-277-2391',
'["playa", "deportes acuáticos", "pesca", "gastronomía"]'::jsonb,
'["acceso en silla de ruedas", "personal capacitado", "audio guía", "baños accesibles"]'::jsonb,
'/static/images/salinas.jpg', 'easy', 360),

('Guayaquil - Malecón 2000', 'guayaquil-malecon-2000',
'Moderno paseo ribereño con jardines, museos y espacios recreativos completamente accesibles. Incluye señalización táctil.',
'Paseo ribereño moderno y accesible',
'Guayas', 'Guayaquil', 'cultural',
-2.1894, -79.8890, true, true, true, true, true, true, 5,
'+593-4-259-6909',
'["paseo", "museos", "jardines", "entretenimiento"]'::jsonb,
'["rampas", "audio guía", "señalización táctil", "baños accesibles"]'::jsonb,
'/static/images/malecon2000.jpg', 'easy', 180);

-- Sierra
INSERT INTO destinations (
    name, slug, description, short_description, province, city, category,
    latitude, longitude, wheelchair_accessible, audio_guide_available,
    accessible_parking, accessible_bathrooms, is_featured, is_active,
    accessibility_rating, contact_phone, activities, facilities,
    main_image, difficulty_level, average_duration
) VALUES
('Quito - Centro Histórico', 'quito-centro-historico',
'Patrimonio Cultural de la Humanidad con arquitectura colonial. Rutas accesibles disponibles con guías especializados.',
'Centro histórico con rutas accesibles',
'Pichincha', 'Quito', 'cultural',
-0.2201, -78.5123, true, true, false, true, true, true, 4,
'+593-2-257-2445',
'["historia", "arquitectura", "museos", "gastronomía"]'::jsonb,
'["rampas de acceso", "audio guía", "guía en braille", "personal capacitado"]'::jsonb,
'/static/images/quito-centro.jpg', 'moderate', 300),

('Otavalo', 'otavalo',
'Famoso mercado indígena y hermosos paisajes andinos. Instalaciones del mercado adaptadas para todos los visitantes.',
'Mercado indígena con instalaciones adaptadas',
'Imbabura', 'Otavalo', 'cultural',
0.2352, -78.2617, true, false, true, true, true, true, 3,
'+593-6-292-1994',
'["mercado", "artesanías", "cultura indígena", "paisajes"]'::jsonb,
'["acceso en silla de ruedas", "información simplificada", "personal capacitado"]'::jsonb,
'/static/images/otavalo.jpg', 'easy', 180),

('Baños de Agua Santa', 'banos-agua-santa',
'Ciudad de aventura y aguas termales con servicios accesibles y adaptados. Incluye baños termales accesibles.',
'Aventura y aguas termales accesibles',
'Tungurahua', 'Baños', 'aventura',
-1.3928, -78.4269, true, false, true, true, true, true, 4,
'+593-3-274-0483',
'["aguas termales", "aventura", "senderismo", "deportes extremos"]'::jsonb,
'["acceso en silla de ruedas", "baños accesibles", "personal capacitado"]'::jsonb,
'/static/images/banos.jpg', 'moderate', 420);

-- Amazonía
INSERT INTO destinations (
    name, slug, description, short_description, province, city, category,
    latitude, longitude, wheelchair_accessible, audio_guide_available,
    accessible_parking, accessible_bathrooms, is_featured, is_active,
    accessibility_rating, contact_phone, activities, facilities,
    main_image, difficulty_level, average_duration
) VALUES
('Tena', 'tena',
'Puerta de entrada a la Amazonía con actividades de aventura accesibles y tours adaptados.',
'Amazonía con tours adaptados',
'Napo', 'Tena', 'naturaleza',
-0.9939, -77.8142, false, false, false, true, false, true, 2,
'+593-6-288-6536',
'["naturaleza", "tours", "cultura amazónica", "aventura"]'::jsonb,
'["personal capacitado", "información simplificada"]'::jsonb,
'/static/images/tena.jpg', 'moderate', 480),

('Puyo', 'puyo',
'Ciudad amazónica con parques temáticos y experiencias culturales accesibles.',
'Ciudad amazónica con experiencias accesibles',
'Pastaza', 'Puyo', 'cultural',
-1.4885, -77.9975, true, false, true, true, false, true, 3,
'+593-3-288-5120',
'["parques temáticos", "cultura", "naturaleza", "educación"]'::jsonb,
'["acceso en silla de ruedas", "rampas de acceso", "personal capacitado"]'::jsonb,
'/static/images/puyo.jpg', 'easy', 240);

-- Galápagos
INSERT INTO destinations (
    name, slug, description, short_description, province, city, category,
    latitude, longitude, wheelchair_accessible, audio_guide_available,
    accessible_parking, accessible_bathrooms, is_featured, is_active,
    accessibility_rating, contact_phone, activities, facilities,
    main_image, difficulty_level, average_duration
) VALUES
('Puerto Ayora - Santa Cruz', 'puerto-ayora-santa-cruz',
'Principal puerto de Galápagos con centro de interpretación accesible y senderos adaptados.',
'Puerto principal con instalaciones accesibles',
'Galápagos', 'Puerto Ayora', 'naturaleza',
-0.7442, -90.3064, true, true, true, true, true, true, 5,
'+593-5-252-6174',
'["observación fauna", "senderos", "educación", "investigación"]'::jsonb,
'["acceso en silla de ruedas", "rampas", "baños accesibles", "audio guía", "personal capacitado"]'::jsonb,
'/static/images/puerto-ayora.jpg', 'moderate', 360),

('San Cristóbal - Centro de Interpretación', 'san-cristobal-centro-interpretacion',
'Moderno centro educativo sobre la evolución y biodiversidad de Galápagos con tecnología accesible.',
'Centro educativo completamente accesible',
'Galápagos', 'Puerto Baquerizo Moreno', 'educativo',
-0.9078, -89.6151, true, true, false, true, true, true, 5,
'+593-5-252-0358',
'["educación", "interpretación", "biodiversidad", "evolución"]'::jsonb,
'["acceso en silla de ruedas", "audio guía", "guía en braille", "señalización táctil", "personal capacitado"]'::jsonb,
'/static/images/san-cristobal.jpg', 'easy', 120);

-- ====================================
-- INSERTAR REVIEWS DE MUESTRA
-- ====================================
INSERT INTO reviews (
    user_id, destination_id, title, content, rating, 
    accessibility_rating, accessibility_notes, wheelchair_experience, 
    is_approved, is_featured
) VALUES 
(1, 1, 'Excelente experiencia en Montañita', 
'El acceso a la playa está muy bien adaptado. Las rampas funcionan perfectamente y el personal es muy servicial.', 
5, 5, 'Rampas en excelente estado, baños accesibles limpios', 'excellent', true, true),

(1, 2, 'Salinas muy accesible', 
'Quedé impresionado con la infraestructura accesible de Salinas. Todo está pensado para personas con discapacidad.', 
5, 5, 'Infraestructura modelo para accesibilidad turística', 'excellent', true, false),

(1, 3, 'Malecón 2000 - Espectacular', 
'El Malecón tiene señalización táctil y audio guías. Una experiencia completamente inclusiva.', 
5, 5, 'Tecnología accesible de vanguardia', 'excellent', true, true);

-- ====================================
-- DAR PERMISOS AL USUARIO
-- ====================================
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO turismo_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO turismo_user;
ALTER TABLE users OWNER TO turismo_user;
ALTER TABLE destinations OWNER TO turismo_user;
ALTER TABLE reviews OWNER TO turismo_user;
ALTER TABLE accessibility_feedback OWNER TO turismo_user;
ALTER TABLE favorites OWNER TO turismo_user;

-- ====================================
-- VERIFICACIÓN FINAL
-- ====================================
SELECT 'Base de datos completa recreada correctamente' as status;
SELECT COUNT(*) as total_destinos FROM destinations;
SELECT COUNT(*) as destinos_destacados FROM destinations WHERE is_featured = true;
SELECT COUNT(*) as total_reviews FROM reviews;
SELECT COUNT(*) as reviews_aprobadas FROM reviews WHERE is_approved = true;
SELECT COUNT(*) as total_usuarios FROM users;
