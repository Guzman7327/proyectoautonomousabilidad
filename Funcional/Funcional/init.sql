-- =====================================================
-- SCRIPT DE BASE DE DATOS - PORTAL TURÍSTICO ECUADOR
-- PostgreSQL - Optimizado para todos los formularios
-- =====================================================

-- Crear base de datos (ejecutar como superusuario)
-- CREATE DATABASE turismo WITH ENCODING 'UTF8' LC_COLLATE='es_ES.UTF-8' LC_CTYPE='es_ES.UTF-8';

-- Conectar a la base de datos
-- \c turismo;

-- =====================================================
-- TABLA DE USUARIOS (Login, Registro, Admin)
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    clave BYTEA NOT NULL, -- Para bcrypt
    rol VARCHAR(20) DEFAULT 'usuario' CHECK (rol IN ('usuario', 'admin')),
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(15) UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    notificaciones BOOLEAN DEFAULT false,
    preferencias_accesibilidad JSONB,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP,
    activo BOOLEAN DEFAULT true
);

-- Índices para mejorar rendimiento
CREATE INDEX IF NOT EXISTS idx_usuarios_usuario ON usuarios(usuario);
CREATE INDEX IF NOT EXISTS idx_usuarios_email ON usuarios(email);
CREATE INDEX IF NOT EXISTS idx_usuarios_rol ON usuarios(rol);

-- =====================================================
-- TABLA DE DESTINOS TURÍSTICOS
-- =====================================================
CREATE TABLE IF NOT EXISTS destinos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    tipo VARCHAR(50) NOT NULL, -- 'playa', 'montaña', 'ciudad', 'selva', etc.
    provincia VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100),
    direccion TEXT,
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    descripcion TEXT,
    caracteristicas JSONB, -- Array de características: ['accesible', 'gratis', 'familiar']
    precio DECIMAL(10, 2),
    es_gratis BOOLEAN DEFAULT false,
    imagen_url VARCHAR(500),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT true
);

-- Índices para búsquedas rápidas
CREATE INDEX IF NOT EXISTS idx_destinos_tipo ON destinos(tipo);
CREATE INDEX IF NOT EXISTS idx_destinos_provincia ON destinos(provincia);
CREATE INDEX IF NOT EXISTS idx_destinos_ciudad ON destinos(ciudad);
CREATE INDEX IF NOT EXISTS idx_destinos_es_gratis ON destinos(es_gratis);
CREATE INDEX IF NOT EXISTS idx_destinos_coordenadas ON destinos(latitud, longitud);

-- =====================================================
-- TABLA DE MENSAJES DE CONTACTO
-- =====================================================
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    asunto VARCHAR(200),
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT false,
    respondido BOOLEAN DEFAULT false,
    ip_origen INET,
    user_agent TEXT
);

-- Índices para gestión de mensajes
CREATE INDEX IF NOT EXISTS idx_mensajes_fecha ON mensajes_contacto(fecha_envio);
CREATE INDEX IF NOT EXISTS idx_mensajes_leido ON mensajes_contacto(leido);
CREATE INDEX IF NOT EXISTS idx_mensajes_email ON mensajes_contacto(email);

-- =====================================================
-- TABLA DE LOG DE ACCESIBILIDAD
-- =====================================================
CREATE TABLE IF NOT EXISTS accesibilidad_log (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    accion VARCHAR(100) NOT NULL, -- 'alto_contraste', 'zoom', 'lupa', etc.
    detalles JSONB,
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_origen INET,
    user_agent TEXT
);

-- Índices para análisis de accesibilidad
CREATE INDEX IF NOT EXISTS idx_accesibilidad_usuario ON accesibilidad_log(usuario_id);
CREATE INDEX IF NOT EXISTS idx_accesibilidad_fecha ON accesibilidad_log(fecha_accion);
CREATE INDEX IF NOT EXISTS idx_accesibilidad_accion ON accesibilidad_log(accion);

-- =====================================================
-- TABLA DE SESIONES (Opcional - para mejor seguridad)
-- =====================================================
CREATE TABLE IF NOT EXISTS sesiones (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    token VARCHAR(255) UNIQUE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP NOT NULL,
    ip_origen INET,
    user_agent TEXT,
    activa BOOLEAN DEFAULT true
);

-- Índices para gestión de sesiones
CREATE INDEX IF NOT EXISTS idx_sesiones_token ON sesiones(token);
CREATE INDEX IF NOT EXISTS idx_sesiones_usuario ON sesiones(usuario_id);
CREATE INDEX IF NOT EXISTS idx_sesiones_expiracion ON sesiones(fecha_expiracion);

-- =====================================================
-- TABLA DE RESERVAS (Futuro - para funcionalidad de reservas)
-- =====================================================
CREATE TABLE IF NOT EXISTS reservas (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    destino_id INTEGER REFERENCES destinos(id) ON DELETE CASCADE,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    num_personas INTEGER DEFAULT 1,
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'confirmada', 'cancelada')),
    precio_total DECIMAL(10, 2),
    notas TEXT,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para reservas
CREATE INDEX IF NOT EXISTS idx_reservas_usuario ON reservas(usuario_id);
CREATE INDEX IF NOT EXISTS idx_reservas_destino ON reservas(destino_id);
CREATE INDEX IF NOT EXISTS idx_reservas_fechas ON reservas(fecha_inicio, fecha_fin);
CREATE INDEX IF NOT EXISTS idx_reservas_estado ON reservas(estado);

-- =====================================================
-- DATOS DE EJEMPLO
-- =====================================================

-- Insertar usuario administrador de ejemplo
INSERT INTO usuarios (usuario, clave, rol, nombre, cedula, email, telefono, notificaciones) 
VALUES (
    'admin',
    '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj/RK.s5u.Ge', -- 'admin123'
    'admin',
    'Administrador del Sistema',
    '1234567890',
    'admin@turismoecuador.com',
    '+593 2 234 5678',
    true
) ON CONFLICT (usuario) DO NOTHING;

-- Insertar destinos de ejemplo
INSERT INTO destinos (nombre, tipo, provincia, ciudad, direccion, latitud, longitud, descripcion, caracteristicas, precio, es_gratis) VALUES
('Centro Histórico de Cuenca', 'ciudad', 'Azuay', 'Cuenca', 'Centro histórico de Cuenca', -2.9006, -79.0045, 'Ciudad patrimonio de la humanidad con arquitectura colonial y artesanías tradicionales.', '["patrimonio", "colonial", "artesanias", "accesible"]', 0.00, true),
('Playa de Salinas', 'playa', 'Santa Elena', 'Salinas', 'Malecón de Salinas', -2.2167, -80.9667, 'Hermosa playa del Pacífico con excelente infraestructura turística y accesibilidad.', '["playa", "pacifico", "accesible", "familiar"]', 0.00, true),
('Ciudad de Manta', 'ciudad', 'Manabí', 'Manta', 'Centro de Manta', -0.9500, -80.7167, 'Puerto pesquero con hermosas playas y deliciosa gastronomía marina.', '["puerto", "gastronomia", "playa", "pesca"]', 0.00, true),
('Mitad del Mundo', 'monumento', 'Pichincha', 'Quito', 'Mitad del Mundo', -0.0022, -78.4555, 'Monumento que marca la línea ecuatorial del planeta.', '["monumento", "ecuador", "turismo", "familiar"]', 5.00, false),
('Baños de Agua Santa', 'aventura', 'Tungurahua', 'Baños', 'Centro de Baños', -1.3964, -78.4247, 'Ciudad de aventura con aguas termales y deportes extremos.', '["aventura", "termales", "deportes", "naturaleza"]', 0.00, true),
('Islas Galápagos', 'naturaleza', 'Galápagos', 'Puerto Ayora', 'Isla Santa Cruz', -0.4500, -90.3500, 'Archipiélago único con biodiversidad excepcional.', '["naturaleza", "biodiversidad", "unico", "protegido"]', 100.00, false)
ON CONFLICT DO NOTHING;

-- =====================================================
-- FUNCIONES ÚTILES
-- =====================================================

-- Función para actualizar fecha de último acceso
CREATE OR REPLACE FUNCTION actualizar_ultimo_acceso()
RETURNS TRIGGER AS $$
BEGIN
    NEW.ultimo_acceso = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para actualizar último acceso
CREATE TRIGGER trigger_ultimo_acceso
    BEFORE UPDATE ON usuarios
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_ultimo_acceso();

-- Función para actualizar fecha de actualización en destinos
CREATE OR REPLACE FUNCTION actualizar_fecha_destino()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para actualizar fecha en destinos
CREATE TRIGGER trigger_fecha_destino
    BEFORE UPDATE ON destinos
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_fecha_destino();

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista de destinos con información completa
CREATE OR REPLACE VIEW vista_destinos_completa AS
SELECT 
    d.*,
    CASE 
        WHEN d.es_gratis THEN 'Gratis'
        ELSE CONCAT('$', d.precio)
    END as precio_formato,
    CASE 
        WHEN d.latitud IS NOT NULL AND d.longitud IS NOT NULL THEN true
        ELSE false
    END as tiene_coordenadas
FROM destinos d
WHERE d.activo = true;

-- Vista de usuarios activos
CREATE OR REPLACE VIEW vista_usuarios_activos AS
SELECT 
    id, usuario, rol, nombre, email, 
    fecha_registro, ultimo_acceso,
    CASE 
        WHEN ultimo_acceso > CURRENT_TIMESTAMP - INTERVAL '30 days' THEN 'Activo'
        ELSE 'Inactivo'
    END as estado_actividad
FROM usuarios 
WHERE activo = true;

-- =====================================================
-- PERMISOS (Opcional - para producción)
-- =====================================================

-- Crear usuario específico para la aplicación (recomendado para producción)
-- CREATE USER turismo_app WITH PASSWORD 'tu_password_seguro';
-- GRANT CONNECT ON DATABASE turismo TO turismo_app;
-- GRANT USAGE ON SCHEMA public TO turismo_app;
-- GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO turismo_app;
-- GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO turismo_app;

-- =====================================================
-- COMENTARIOS FINALES
-- =====================================================

/*
ESTRUCTURA DE LA BASE DE DATOS COMPLETADA

Esta base de datos está optimizada para:
✅ Todos los formularios del portal turístico
✅ Escalabilidad y rendimiento
✅ Seguridad y integridad de datos
✅ Funcionalidades futuras (reservas, análisis, etc.)

Para usar esta base de datos:
1. Crear la base de datos: CREATE DATABASE turismo;
2. Ejecutar este script completo
3. Configurar las credenciales en app.py
4. ¡Listo para usar!

Credenciales de ejemplo:
- Usuario: admin
- Contraseña: admin123
*/

