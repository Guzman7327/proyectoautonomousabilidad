-- =====================================================
-- SCRIPT DE BASE DE DATOS - PORTAL TURÍSTICO ECUADOR
-- PostgreSQL - Optimizado para todos los formularios
-- Versión Completa y Actualizada
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
    aceptar_terminos BOOLEAN DEFAULT false,
    recibir_notificaciones BOOLEAN DEFAULT false,
    preferencias_accesibilidad JSONB,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP,
    activo BOOLEAN DEFAULT true,
    intentos_fallidos INTEGER DEFAULT 0,
    fecha_ultimo_intento TIMESTAMP,
    bloqueado_hasta TIMESTAMP,
    token_recuperacion VARCHAR(255),
    fecha_token_recuperacion TIMESTAMP
);

-- Agregar columnas que pueden no existir en tablas existentes
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS aceptar_terminos BOOLEAN DEFAULT false;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS recibir_notificaciones BOOLEAN DEFAULT false;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS preferencias_accesibilidad JSONB;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS intentos_fallidos INTEGER DEFAULT 0;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS fecha_ultimo_intento TIMESTAMP;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS bloqueado_hasta TIMESTAMP;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS token_recuperacion VARCHAR(255);
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS fecha_token_recuperacion TIMESTAMP;

-- Índices para mejorar rendimiento
CREATE INDEX IF NOT EXISTS idx_usuarios_usuario ON usuarios(usuario);
CREATE INDEX IF NOT EXISTS idx_usuarios_email ON usuarios(email);
CREATE INDEX IF NOT EXISTS idx_usuarios_rol ON usuarios(rol);
CREATE INDEX IF NOT EXISTS idx_usuarios_token_recuperacion ON usuarios(token_recuperacion);

-- =====================================================
-- TABLA DE DESTINOS TURÍSTICOS (Nueva Ruta, Búsqueda)
-- =====================================================
CREATE TABLE IF NOT EXISTS destinos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo VARCHAR(50) NOT NULL, -- 'playa', 'montaña', 'ciudad', 'selva', 'cultural', 'aventura'
    provincia VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100),
    direccion TEXT,
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    distancia_km DECIMAL(8, 2),
    duracion_horas DECIMAL(5, 2),
    dificultad VARCHAR(20) CHECK (dificultad IN ('facil', 'moderado', 'dificil', 'extremo')),
    caracteristicas JSONB, -- Array: ['accesible', 'familiar', 'mascotas', 'transporte_publico']
    recomendaciones TEXT,
    precio DECIMAL(10, 2),
    es_gratis BOOLEAN DEFAULT false,
    imagen_url VARCHAR(500),
    usuario_creador_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT true
);

-- Agregar columnas que pueden no existir en la tabla destinos
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS descripcion TEXT;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS distancia_km DECIMAL(8, 2);
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS duracion_horas DECIMAL(5, 2);
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS dificultad VARCHAR(20) CHECK (dificultad IN ('facil', 'moderado', 'dificil', 'extremo'));
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS caracteristicas JSONB;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS recomendaciones TEXT;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS imagen_url VARCHAR(500);
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS usuario_creador_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Índices para búsquedas rápidas
CREATE INDEX IF NOT EXISTS idx_destinos_tipo ON destinos(tipo);
CREATE INDEX IF NOT EXISTS idx_destinos_provincia ON destinos(provincia);
CREATE INDEX IF NOT EXISTS idx_destinos_ciudad ON destinos(ciudad);
CREATE INDEX IF NOT EXISTS idx_destinos_es_gratis ON destinos(es_gratis);
CREATE INDEX IF NOT EXISTS idx_destinos_coordenadas ON destinos(latitud, longitud);
CREATE INDEX IF NOT EXISTS idx_destinos_dificultad ON destinos(dificultad);
CREATE INDEX IF NOT EXISTS idx_destinos_precio ON destinos(precio);

-- =====================================================
-- TABLA DE MENSAJES DE CONTACTO
-- =====================================================
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    asunto VARCHAR(200),
    categoria VARCHAR(50), -- 'informacion', 'sugerencia', 'queja', 'soporte'
    mensaje TEXT NOT NULL,
    captcha_respuesta VARCHAR(10),
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT false,
    respondido BOOLEAN DEFAULT false,
    respuesta TEXT,
    fecha_respuesta TIMESTAMP,
    ip_origen INET,
    user_agent TEXT
);

-- Agregar columnas que pueden no existir en la tabla mensajes_contacto
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS categoria VARCHAR(50);
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS captcha_respuesta VARCHAR(10);
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS respondido BOOLEAN DEFAULT false;
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS respuesta TEXT;
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS fecha_respuesta TIMESTAMP;

-- Índices para gestión de mensajes
CREATE INDEX IF NOT EXISTS idx_mensajes_fecha ON mensajes_contacto(fecha_envio);
CREATE INDEX IF NOT EXISTS idx_mensajes_leido ON mensajes_contacto(leido);
CREATE INDEX IF NOT EXISTS idx_mensajes_email ON mensajes_contacto(email);
CREATE INDEX IF NOT EXISTS idx_mensajes_categoria ON mensajes_contacto(categoria);

-- =====================================================
-- TABLA DE REGISTROS GENERALES (Guardar/Editar Registro)
-- =====================================================
CREATE TABLE IF NOT EXISTS registros_generales (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    fecha DATE,
    ciudad VARCHAR(100),
    pais VARCHAR(100),
    suscripcion BOOLEAN DEFAULT false,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT true
);

-- Índices para registros generales
CREATE INDEX IF NOT EXISTS idx_registros_email ON registros_generales(email);
CREATE INDEX IF NOT EXISTS idx_registros_fecha ON registros_generales(fecha);
CREATE INDEX IF NOT EXISTS idx_registros_ciudad ON registros_generales(ciudad);
CREATE INDEX IF NOT EXISTS idx_registros_pais ON registros_generales(pais);

-- =====================================================
-- TABLA DE RUTAS TURÍSTICAS (Nueva Ruta detallada)
-- =====================================================
CREATE TABLE IF NOT EXISTS rutas_turisticas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    provincia VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100),
    tipo VARCHAR(50) NOT NULL, -- 'senderismo', 'ciclismo', 'automovilistico', 'cultural'
    dificultad VARCHAR(20) CHECK (dificultad IN ('facil', 'moderado', 'dificil', 'extremo')),
    distancia_km DECIMAL(8, 2),
    duracion_horas DECIMAL(5, 2),
    accesible BOOLEAN DEFAULT false,
    familiar BOOLEAN DEFAULT false,
    mascotas BOOLEAN DEFAULT false,
    transporte_publico BOOLEAN DEFAULT false,
    recomendaciones TEXT,
    imagen_url VARCHAR(500),
    precio DECIMAL(10, 2),
    es_gratis BOOLEAN DEFAULT true,
    usuario_creador_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT true,
    puntos_interes JSONB, -- Array de puntos de interés en la ruta
    coordenadas_inicio JSONB, -- {lat, lng}
    coordenadas_fin JSONB, -- {lat, lng}
    nivel_seguridad VARCHAR(20) DEFAULT 'medio' CHECK (nivel_seguridad IN ('bajo', 'medio', 'alto'))
);

-- Índices para rutas turísticas
CREATE INDEX IF NOT EXISTS idx_rutas_provincia ON rutas_turisticas(provincia);
CREATE INDEX IF NOT EXISTS idx_rutas_tipo ON rutas_turisticas(tipo);
CREATE INDEX IF NOT EXISTS idx_rutas_dificultad ON rutas_turisticas(dificultad);
CREATE INDEX IF NOT EXISTS idx_rutas_accesible ON rutas_turisticas(accesible);
CREATE INDEX IF NOT EXISTS idx_rutas_familiar ON rutas_turisticas(familiar);

-- =====================================================
-- TABLA DE BÚSQUEDAS GUARDADAS
-- =====================================================
CREATE TABLE IF NOT EXISTS busquedas_guardadas (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre_busqueda VARCHAR(100),
    filtros JSONB NOT NULL, -- Todos los filtros aplicados
    resultados_encontrados INTEGER,
    fecha_busqueda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    es_favorita BOOLEAN DEFAULT false
);

-- Índices para búsquedas
CREATE INDEX IF NOT EXISTS idx_busquedas_usuario ON busquedas_guardadas(usuario_id);
CREATE INDEX IF NOT EXISTS idx_busquedas_fecha ON busquedas_guardadas(fecha_busqueda);
CREATE INDEX IF NOT EXISTS idx_busquedas_favorita ON busquedas_guardadas(es_favorita);

-- =====================================================
-- TABLA DE LOG DE ACCESIBILIDAD
-- =====================================================
CREATE TABLE IF NOT EXISTS accesibilidad_log (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    accion VARCHAR(100) NOT NULL, -- 'alto_contraste', 'zoom', 'lupa', etc.
    detalles JSONB,
    sesion_id VARCHAR(255),
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_origen INET,
    user_agent TEXT
);

-- Índices para análisis de accesibilidad
CREATE INDEX IF NOT EXISTS idx_accesibilidad_usuario ON accesibilidad_log(usuario_id);
CREATE INDEX IF NOT EXISTS idx_accesibilidad_fecha ON accesibilidad_log(fecha_accion);
CREATE INDEX IF NOT EXISTS idx_accesibilidad_accion ON accesibilidad_log(accion);
CREATE INDEX IF NOT EXISTS idx_accesibilidad_sesion ON accesibilidad_log(sesion_id);

-- =====================================================
-- TABLA DE SESIONES
-- =====================================================
CREATE TABLE IF NOT EXISTS sesiones (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    token VARCHAR(255) UNIQUE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP NOT NULL,
    ip_origen INET,
    user_agent TEXT,
    activa BOOLEAN DEFAULT true,
    recordar_sesion BOOLEAN DEFAULT false
);

-- Índices para gestión de sesiones
CREATE INDEX IF NOT EXISTS idx_sesiones_token ON sesiones(token);
CREATE INDEX IF NOT EXISTS idx_sesiones_usuario ON sesiones(usuario_id);
CREATE INDEX IF NOT EXISTS idx_sesiones_expiracion ON sesiones(fecha_expiracion);

-- =====================================================
-- TABLA DE FAVORITOS DE USUARIOS
-- =====================================================
CREATE TABLE IF NOT EXISTS favoritos_usuarios (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    destino_id INTEGER REFERENCES destinos(id) ON DELETE CASCADE,
    ruta_id INTEGER REFERENCES rutas_turisticas(id) ON DELETE CASCADE,
    tipo_favorito VARCHAR(20) CHECK (tipo_favorito IN ('destino', 'ruta')),
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notas_personales TEXT,
    UNIQUE(usuario_id, destino_id, tipo_favorito),
    UNIQUE(usuario_id, ruta_id, tipo_favorito)
);

-- Índices para favoritos
CREATE INDEX IF NOT EXISTS idx_favoritos_usuario ON favoritos_usuarios(usuario_id);
CREATE INDEX IF NOT EXISTS idx_favoritos_destino ON favoritos_usuarios(destino_id);
CREATE INDEX IF NOT EXISTS idx_favoritos_ruta ON favoritos_usuarios(ruta_id);
CREATE INDEX IF NOT EXISTS idx_favoritos_tipo ON favoritos_usuarios(tipo_favorito);

-- =====================================================
-- TABLA DE CONFIGURACIONES DEL SISTEMA
-- =====================================================
CREATE TABLE IF NOT EXISTS configuraciones_sistema (
    id SERIAL PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descripcion TEXT,
    tipo VARCHAR(20) DEFAULT 'string' CHECK (tipo IN ('string', 'number', 'boolean', 'json')),
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA DE ESTADÍSTICAS DE USO
-- =====================================================
CREATE TABLE IF NOT EXISTS estadisticas_uso (
    id SERIAL PRIMARY KEY,
    tipo_evento VARCHAR(50) NOT NULL, -- 'login', 'busqueda', 'registro', etc.
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    detalles JSONB,
    fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_origen INET,
    user_agent TEXT
);

-- Índices para estadísticas
CREATE INDEX IF NOT EXISTS idx_estadisticas_tipo ON estadisticas_uso(tipo_evento);
CREATE INDEX IF NOT EXISTS idx_estadisticas_fecha ON estadisticas_uso(fecha_evento);
CREATE INDEX IF NOT EXISTS idx_estadisticas_usuario ON estadisticas_uso(usuario_id);

-- =====================================================
-- DATOS DE EJEMPLO
-- =====================================================

-- Insertar configuraciones del sistema
INSERT INTO configuraciones_sistema (clave, valor, descripcion, tipo) VALUES
('sitio_nombre', 'Portal Turístico Ecuador', 'Nombre del sitio web', 'string'),
('sitio_email', 'info@turismoecuador.com', 'Email de contacto principal', 'string'),
('sitio_telefono', '+593 2 234 5678', 'Teléfono de contacto', 'string'),
('max_intentos_login', '5', 'Máximo número de intentos de login fallidos', 'number'),
('tiempo_bloqueo_minutos', '30', 'Tiempo de bloqueo tras exceder intentos', 'number'),
('captcha_longitud', '5', 'Longitud del captcha matemático', 'number'),
('permitir_registro_publico', 'true', 'Permitir registro público de usuarios', 'boolean')
ON CONFLICT (clave) DO NOTHING;

-- Insertar usuario administrador de ejemplo
INSERT INTO usuarios (usuario, clave, rol, nombre, cedula, email, telefono, notificaciones, aceptar_terminos) 
VALUES (
    'admin',
    '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj/RK.s5u.Ge', -- 'admin123'
    'admin',
    'Administrador del Sistema',
    '1234567890',
    'admin@turismoecuador.com',
    '+593 2 234 5678',
    true,
    true
) ON CONFLICT (usuario) DO NOTHING;

-- Insertar destinos de ejemplo
INSERT INTO destinos (nombre, tipo, provincia, ciudad, direccion, latitud, longitud, descripcion, caracteristicas, precio, es_gratis, dificultad) VALUES
('Centro Histórico de Cuenca', 'ciudad', 'Azuay', 'Cuenca', 'Centro histórico de Cuenca', -2.9006, -79.0045, 'Ciudad patrimonio de la humanidad con arquitectura colonial y artesanías tradicionales.', '["patrimonio", "colonial", "artesanias", "accesible", "cultural"]', 0.00, true, 'facil'),
('Playa de Salinas', 'playa', 'Santa Elena', 'Salinas', 'Malecón de Salinas', -2.2167, -80.9667, 'Hermosa playa del Pacífico con excelente infraestructura turística y accesibilidad.', '["playa", "pacifico", "accesible", "familiar", "natacion"]', 0.00, true, 'facil'),
('Ciudad de Manta', 'ciudad', 'Manabí', 'Manta', 'Centro de Manta', -0.9500, -80.7167, 'Puerto pesquero con hermosas playas y deliciosa gastronomía marina.', '["puerto", "gastronomia", "playa", "pesca", "familiar"]', 0.00, true, 'facil'),
('Mitad del Mundo', 'cultural', 'Pichincha', 'Quito', 'Mitad del Mundo', -0.0022, -78.4555, 'Monumento que marca la línea ecuatorial del planeta.', '["monumento", "ecuador", "turistico", "familiar", "educativo"]', 5.00, false, 'facil'),
('Baños de Agua Santa', 'aventura', 'Tungurahua', 'Baños', 'Centro de Baños', -1.3964, -78.4247, 'Ciudad de aventura con aguas termales y deportes extremos.', '["aventura", "termales", "deportes", "naturaleza", "extremo"]', 0.00, true, 'moderado'),
('Islas Galápagos', 'naturaleza', 'Galápagos', 'Puerto Ayora', 'Isla Santa Cruz', -0.4500, -90.3500, 'Archipiélago único con biodiversidad excepcional.', '["naturaleza", "biodiversidad", "unico", "protegido", "cientifico"]', 100.00, false, 'moderado'),
('Quilotoa', 'naturaleza', 'Cotopaxi', 'Latacunga', 'Laguna del Quilotoa', -0.8500, -78.9000, 'Laguna volcánica de aguas turquesas en los Andes.', '["volcan", "laguna", "senderismo", "naturaleza", "aventura"]', 2.00, false, 'dificil'),
('Parque Nacional Yasuní', 'naturaleza', 'Orellana', 'Coca', 'Parque Nacional Yasuní', -1.0000, -76.5000, 'Una de las zonas con mayor biodiversidad del planeta.', '["biodiversidad", "amazonia", "cientifica", "naturaleza", "protegido"]', 25.00, false, 'moderado')
ON CONFLICT DO NOTHING;

-- Insertar rutas turísticas de ejemplo
INSERT INTO rutas_turisticas (nombre, descripcion, provincia, ciudad, tipo, dificultad, distancia_km, duracion_horas, accesible, familiar, recomendaciones) VALUES
('Ruta del Spondylus', 'Recorrido por la costa ecuatoriana siguiendo la antigua ruta de intercambio precolombino.', 'Manabí', 'Manta', 'automovilistico', 'facil', 500.0, 48.0, true, true, 'Llevar protector solar, agua y cámara fotográfica. Mejor época: junio a septiembre.'),
('Sendero Ecológico Mindo', 'Caminata por el bosque nublado con observación de aves y mariposas.', 'Pichincha', 'Mindo', 'senderismo', 'moderado', 8.5, 6.0, false, true, 'Usar repelente, ropa cómoda y botas de trekking. Contratar guía local recomendado.'),
('Ruta de las Iglesias Coloniales', 'Tour cultural por las iglesias históricas del centro de Quito.', 'Pichincha', 'Quito', 'cultural', 'facil', 5.0, 4.0, true, true, 'Respetar horarios de misa. Entrada gratuita en la mayoría. Usar ropa adecuada.'),
('Aventura en Baños', 'Circuito de deportes extremos: puenting, rafting y canopy.', 'Tungurahua', 'Baños', 'aventura', 'dificil', 15.0, 8.0, false, false, 'Solo para mayores de 16 años. Seguir todas las instrucciones de seguridad.')
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
DROP TRIGGER IF EXISTS trigger_ultimo_acceso ON usuarios;
CREATE TRIGGER trigger_ultimo_acceso
    BEFORE UPDATE ON usuarios
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_ultimo_acceso();

-- Función para actualizar fecha de actualización
CREATE OR REPLACE FUNCTION actualizar_fecha_actualizacion()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Triggers para actualizar fecha en varias tablas
DROP TRIGGER IF EXISTS trigger_fecha_destino ON destinos;
CREATE TRIGGER trigger_fecha_destino
    BEFORE UPDATE ON destinos
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_fecha_actualizacion();

DROP TRIGGER IF EXISTS trigger_fecha_ruta ON rutas_turisticas;
CREATE TRIGGER trigger_fecha_ruta
    BEFORE UPDATE ON rutas_turisticas
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_fecha_actualizacion();

DROP TRIGGER IF EXISTS trigger_fecha_registro ON registros_generales;
CREATE TRIGGER trigger_fecha_registro
    BEFORE UPDATE ON registros_generales
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- Función para limpiar sesiones expiradas
CREATE OR REPLACE FUNCTION limpiar_sesiones_expiradas()
RETURNS INTEGER AS $$
DECLARE
    sesiones_eliminadas INTEGER;
BEGIN
    DELETE FROM sesiones WHERE fecha_expiracion < CURRENT_TIMESTAMP;
    GET DIAGNOSTICS sesiones_eliminadas = ROW_COUNT;
    RETURN sesiones_eliminadas;
END;
$$ LANGUAGE plpgsql;

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista de destinos con información completa
CREATE OR REPLACE VIEW vista_destinos_completa AS
SELECT 
    d.*,
    u.usuario as creador_usuario,
    CASE 
        WHEN d.es_gratis THEN 'Gratis'
        ELSE CONCAT('$', d.precio)
    END as precio_formato,
    CASE 
        WHEN d.latitud IS NOT NULL AND d.longitud IS NOT NULL THEN true
        ELSE false
    END as tiene_coordenadas,
    (SELECT COUNT(*) FROM favoritos_usuarios f WHERE f.destino_id = d.id) as total_favoritos
FROM destinos d
LEFT JOIN usuarios u ON d.usuario_creador_id = u.id
WHERE d.activo = true;

-- Vista de usuarios activos con estadísticas
CREATE OR REPLACE VIEW vista_usuarios_activos AS
SELECT 
    u.id, u.usuario, u.rol, u.nombre, u.email, 
    u.fecha_registro, u.ultimo_acceso,
    CASE 
        WHEN u.ultimo_acceso > CURRENT_TIMESTAMP - INTERVAL '30 days' THEN 'Activo'
        WHEN u.ultimo_acceso > CURRENT_TIMESTAMP - INTERVAL '90 days' THEN 'Poco Activo'
        ELSE 'Inactivo'
    END as estado_actividad,
    (SELECT COUNT(*) FROM favoritos_usuarios f WHERE f.usuario_id = u.id) as total_favoritos,
    (SELECT COUNT(*) FROM destinos d WHERE d.usuario_creador_id = u.id) as destinos_creados
FROM usuarios u 
WHERE u.activo = true;

-- Vista de estadísticas del sistema
CREATE OR REPLACE VIEW vista_estadisticas_generales AS
SELECT 
    'usuarios_totales' as metrica,
    COUNT(*)::text as valor,
    'Total de usuarios registrados' as descripcion
FROM usuarios WHERE activo = true
UNION ALL
SELECT 
    'destinos_totales' as metrica,
    COUNT(*)::text as valor,
    'Total de destinos activos' as descripcion
FROM destinos WHERE activo = true
UNION ALL
SELECT 
    'mensajes_pendientes' as metrica,
    COUNT(*)::text as valor,
    'Mensajes de contacto sin leer' as descripcion
FROM mensajes_contacto WHERE leido = false
UNION ALL
SELECT 
    'usuarios_activos_mes' as metrica,
    COUNT(*)::text as valor,
    'Usuarios activos último mes' as descripcion
FROM usuarios 
WHERE activo = true AND ultimo_acceso > CURRENT_TIMESTAMP - INTERVAL '30 days';

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices compuestos para búsquedas complejas
CREATE INDEX IF NOT EXISTS idx_destinos_tipo_provincia ON destinos(tipo, provincia) WHERE activo = true;
CREATE INDEX IF NOT EXISTS idx_destinos_precio_tipo ON destinos(precio, tipo) WHERE activo = true;
CREATE INDEX IF NOT EXISTS idx_mensajes_leido_fecha ON mensajes_contacto(leido, fecha_envio);
CREATE INDEX IF NOT EXISTS idx_usuarios_rol_activo ON usuarios(rol, activo);

-- Índices para mejorar búsquedas de texto
CREATE INDEX IF NOT EXISTS idx_destinos_nombre_busqueda ON destinos USING gin(to_tsvector('spanish', nombre));
CREATE INDEX IF NOT EXISTS idx_destinos_descripcion_busqueda ON destinos USING gin(to_tsvector('spanish', descripcion));

-- =====================================================
-- PERMISOS (Opcional - para producción)
-- =====================================================

-- Crear usuario específico para la aplicación (recomendado para producción)
-- CREATE USER turismo_app WITH PASSWORD 'tu_password_seguro_aqui';
-- GRANT CONNECT ON DATABASE turismo TO turismo_app;
-- GRANT USAGE ON SCHEMA public TO turismo_app;
-- GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO turismo_app;
-- GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO turismo_app;
-- GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO turismo_app;

-- =====================================================
-- COMENTARIOS FINALES
-- =====================================================

/*
ESTRUCTURA DE LA BASE DE DATOS COMPLETADA

Esta base de datos está optimizada para:
✅ Todos los formularios del portal turístico
✅ Sistema de usuarios con roles y seguridad
✅ Gestión completa de destinos y rutas
✅ Sistema de contacto y mensajería
✅ Funcionalidades de accesibilidad
✅ Sistema de favoritos personalizado
✅ Estadísticas y análisis de uso
✅ Escalabilidad y rendimiento
✅ Seguridad y integridad de datos

FORMULARIOS SOPORTADOS:
- ✅ Login/Registro de usuarios
- ✅ Registro de administradores
- ✅ Creación de nuevas rutas
- ✅ Formulario de contacto
- ✅ Búsqueda avanzada
- ✅ Guardar/Editar registros
- ✅ Recuperación de contraseñas
- ✅ Gestión de favoritos

Para usar esta base de datos:
1. Crear la base de datos: CREATE DATABASE turismo;
2. Ejecutar este script completo
3. Configurar las credenciales en app.py
4. ¡Listo para usar!

Credenciales de ejemplo:
- Usuario: admin
- Contraseña: admin123
- Email: admin@turismoecuador.com

TABLAS CREADAS:
- usuarios: Gestión de usuarios y autenticación
- destinos: Destinos turísticos
- rutas_turisticas: Rutas detalladas
- mensajes_contacto: Sistema de contacto
- registros_generales: Registros varios
- busquedas_guardadas: Búsquedas de usuarios
- favoritos_usuarios: Sistema de favoritos
- accesibilidad_log: Log de funciones accesibles
- sesiones: Gestión de sesiones
- configuraciones_sistema: Configuración global
- estadisticas_uso: Métricas del sistema

VISTAS CREADAS:
- vista_destinos_completa: Destinos con información completa
- vista_usuarios_activos: Usuarios con estadísticas
- vista_estadisticas_generales: Métricas del sistema

FUNCIONES CREADAS:
- actualizar_ultimo_acceso(): Para timestamps automáticos
- actualizar_fecha_actualizacion(): Para fechas de modificación
- limpiar_sesiones_expiradas(): Mantenimiento de sesiones
*/


