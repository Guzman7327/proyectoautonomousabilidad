-- Script para recrear tabla reviews que coincida con el modelo Flask
-- Portal de Turismo Inclusivo de Ecuador

-- ====================================
-- RECREAR TABLA REVIEWS COMPLETA
-- ====================================
DROP TABLE IF EXISTS reviews CASCADE;

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
-- RECREAR TABLA ACCESSIBILITY_FEEDBACK
-- ====================================
DROP TABLE IF EXISTS accessibility_feedback CASCADE;

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
CREATE TABLE IF NOT EXISTS favorites (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE NOT NULL,
    destination_id INTEGER REFERENCES destinations(id) ON DELETE CASCADE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, destination_id)
);

-- ====================================
-- CREAR ÍNDICES
-- ====================================
CREATE INDEX idx_reviews_user_id ON reviews(user_id);
CREATE INDEX idx_reviews_destination_id ON reviews(destination_id);
CREATE INDEX idx_reviews_is_approved ON reviews(is_approved);
CREATE INDEX idx_accessibility_feedback_destination_id ON accessibility_feedback(destination_id);
CREATE INDEX idx_favorites_user_id ON favorites(user_id);
CREATE INDEX idx_favorites_destination_id ON favorites(destination_id);

-- ====================================
-- INSERTAR DATOS DE MUESTRA PARA REVIEWS
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
GRANT ALL PRIVILEGES ON reviews TO turismo_user;
GRANT ALL PRIVILEGES ON accessibility_feedback TO turismo_user;
GRANT ALL PRIVILEGES ON favorites TO turismo_user;

-- ====================================
-- VERIFICACIÓN
-- ====================================
SELECT 'Tablas reviews recreadas correctamente' as status;
SELECT COUNT(*) as total_reviews FROM reviews;
SELECT COUNT(*) as reviews_aprobadas FROM reviews WHERE is_approved = true;
