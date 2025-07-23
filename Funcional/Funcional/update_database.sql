-- =====================================================
-- SCRIPT DE ACTUALIZACIÓN DE BASE DE DATOS EXISTENTE
-- Portal Turístico Ecuador - PostgreSQL
-- =====================================================

-- Este script actualiza una base de datos existente sin perder datos
-- Ejecutar cuando ya existe una base de datos con tablas creadas

\echo 'Iniciando actualización de base de datos existente...'

-- =====================================================
-- ACTUALIZAR TABLA USUARIOS
-- =====================================================

\echo 'Actualizando tabla usuarios...'

-- Agregar columnas nuevas que pueden no existir
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS aceptar_terminos BOOLEAN DEFAULT false;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS recibir_notificaciones BOOLEAN DEFAULT false;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS preferencias_accesibilidad JSONB;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS intentos_fallidos INTEGER DEFAULT 0;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS fecha_ultimo_intento TIMESTAMP;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS bloqueado_hasta TIMESTAMP;
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS token_recuperacion VARCHAR(255);
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS fecha_token_recuperacion TIMESTAMP;

-- Crear índices que pueden no existir
CREATE INDEX IF NOT EXISTS idx_usuarios_token_recuperacion ON usuarios(token_recuperacion);

-- =====================================================
-- ACTUALIZAR TABLA DESTINOS
-- =====================================================

\echo 'Actualizando tabla destinos...'

-- Agregar columnas nuevas que pueden no existir
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS descripcion TEXT;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS distancia_km DECIMAL(8, 2);
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS duracion_horas DECIMAL(5, 2);
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS caracteristicas JSONB;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS recomendaciones TEXT;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS imagen_url VARCHAR(500);
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS usuario_creador_id INTEGER;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Agregar constraint de dificultad si no existe
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.table_constraints 
        WHERE constraint_name = 'destinos_dificultad_check' 
        AND table_name = 'destinos'
    ) THEN
        ALTER TABLE destinos ADD COLUMN IF NOT EXISTS dificultad VARCHAR(20);
        ALTER TABLE destinos ADD CONSTRAINT destinos_dificultad_check 
        CHECK (dificultad IN ('facil', 'moderado', 'dificil', 'extremo'));
    END IF;
END $$;

-- Agregar foreign key si no existe
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.table_constraints 
        WHERE constraint_name = 'destinos_usuario_creador_id_fkey' 
        AND table_name = 'destinos'
    ) THEN
        ALTER TABLE destinos ADD CONSTRAINT destinos_usuario_creador_id_fkey 
        FOREIGN KEY (usuario_creador_id) REFERENCES usuarios(id) ON DELETE SET NULL;
    END IF;
END $$;

-- Crear índices adicionales
CREATE INDEX IF NOT EXISTS idx_destinos_dificultad ON destinos(dificultad);
CREATE INDEX IF NOT EXISTS idx_destinos_precio ON destinos(precio);

-- =====================================================
-- ACTUALIZAR TABLA MENSAJES_CONTACTO
-- =====================================================

\echo 'Actualizando tabla mensajes_contacto...'

-- Agregar columnas nuevas que pueden no existir
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS categoria VARCHAR(50);
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS captcha_respuesta VARCHAR(10);
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS respondido BOOLEAN DEFAULT false;
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS respuesta TEXT;
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS fecha_respuesta TIMESTAMP;

-- Crear índices adicionales
CREATE INDEX IF NOT EXISTS idx_mensajes_categoria ON mensajes_contacto(categoria);

-- =====================================================
-- CREAR TABLAS NUEVAS QUE PUEDEN NO EXISTIR
-- =====================================================

\echo 'Creando tablas nuevas...'

-- Tabla de registros generales
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

-- Tabla de rutas turísticas
CREATE TABLE IF NOT EXISTS rutas_turisticas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    provincia VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100),
    tipo VARCHAR(50) NOT NULL,
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
    puntos_interes JSONB,
    coordenadas_inicio JSONB,
    coordenadas_fin JSONB,
    nivel_seguridad VARCHAR(20) DEFAULT 'medio' CHECK (nivel_seguridad IN ('bajo', 'medio', 'alto'))
);

-- Tabla de búsquedas guardadas
CREATE TABLE IF NOT EXISTS busquedas_guardadas (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre_busqueda VARCHAR(100),
    filtros JSONB NOT NULL,
    resultados_encontrados INTEGER,
    fecha_busqueda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    es_favorita BOOLEAN DEFAULT false
);

-- Tabla de favoritos de usuarios
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

-- Tabla de configuraciones del sistema
CREATE TABLE IF NOT EXISTS configuraciones_sistema (
    id SERIAL PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descripcion TEXT,
    tipo VARCHAR(20) DEFAULT 'string' CHECK (tipo IN ('string', 'number', 'boolean', 'json')),
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de estadísticas de uso
CREATE TABLE IF NOT EXISTS estadisticas_uso (
    id SERIAL PRIMARY KEY,
    tipo_evento VARCHAR(50) NOT NULL,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    detalles JSONB,
    fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_origen INET,
    user_agent TEXT
);

-- Actualizar tabla de sesiones si ya existe
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'sesiones') THEN
        ALTER TABLE sesiones ADD COLUMN IF NOT EXISTS recordar_sesion BOOLEAN DEFAULT false;
    END IF;
END $$;

-- =====================================================
-- CREAR ÍNDICES ADICIONALES
-- =====================================================

\echo 'Creando índices adicionales...'

-- Índices para registros generales
CREATE INDEX IF NOT EXISTS idx_registros_email ON registros_generales(email);
CREATE INDEX IF NOT EXISTS idx_registros_fecha ON registros_generales(fecha);
CREATE INDEX IF NOT EXISTS idx_registros_ciudad ON registros_generales(ciudad);
CREATE INDEX IF NOT EXISTS idx_registros_pais ON registros_generales(pais);

-- Índices para rutas turísticas
CREATE INDEX IF NOT EXISTS idx_rutas_provincia ON rutas_turisticas(provincia);
CREATE INDEX IF NOT EXISTS idx_rutas_tipo ON rutas_turisticas(tipo);
CREATE INDEX IF NOT EXISTS idx_rutas_dificultad ON rutas_turisticas(dificultad);
CREATE INDEX IF NOT EXISTS idx_rutas_accesible ON rutas_turisticas(accesible);
CREATE INDEX IF NOT EXISTS idx_rutas_familiar ON rutas_turisticas(familiar);

-- Índices para búsquedas
CREATE INDEX IF NOT EXISTS idx_busquedas_usuario ON busquedas_guardadas(usuario_id);
CREATE INDEX IF NOT EXISTS idx_busquedas_fecha ON busquedas_guardadas(fecha_busqueda);
CREATE INDEX IF NOT EXISTS idx_busquedas_favorita ON busquedas_guardadas(es_favorita);

-- Índices para favoritos
CREATE INDEX IF NOT EXISTS idx_favoritos_usuario ON favoritos_usuarios(usuario_id);
CREATE INDEX IF NOT EXISTS idx_favoritos_destino ON favoritos_usuarios(destino_id);
CREATE INDEX IF NOT EXISTS idx_favoritos_ruta ON favoritos_usuarios(ruta_id);
CREATE INDEX IF NOT EXISTS idx_favoritos_tipo ON favoritos_usuarios(tipo_favorito);

-- Índices para estadísticas
CREATE INDEX IF NOT EXISTS idx_estadisticas_tipo ON estadisticas_uso(tipo_evento);
CREATE INDEX IF NOT EXISTS idx_estadisticas_fecha ON estadisticas_uso(fecha_evento);
CREATE INDEX IF NOT EXISTS idx_estadisticas_usuario ON estadisticas_uso(usuario_id);

-- Índices compuestos para optimización
CREATE INDEX IF NOT EXISTS idx_destinos_tipo_provincia ON destinos(tipo, provincia) WHERE activo = true;
CREATE INDEX IF NOT EXISTS idx_destinos_precio_tipo ON destinos(precio, tipo) WHERE activo = true;
CREATE INDEX IF NOT EXISTS idx_mensajes_leido_fecha ON mensajes_contacto(leido, fecha_envio);
CREATE INDEX IF NOT EXISTS idx_usuarios_rol_activo ON usuarios(rol, activo);

-- Índices para búsquedas de texto (solo si la extensión está disponible)
DO $$
BEGIN
    CREATE INDEX IF NOT EXISTS idx_destinos_nombre_busqueda ON destinos USING gin(to_tsvector('spanish', nombre));
EXCEPTION
    WHEN feature_not_supported THEN
        RAISE NOTICE 'Extensión de búsqueda de texto no disponible. Omitiendo índices GIN.';
END $$;

DO $$
BEGIN
    CREATE INDEX IF NOT EXISTS idx_destinos_descripcion_busqueda ON destinos USING gin(to_tsvector('spanish', descripcion));
EXCEPTION
    WHEN feature_not_supported THEN
        RAISE NOTICE 'Extensión de búsqueda de texto no disponible. Omitiendo índices GIN.';
END $$;

-- =====================================================
-- INSERTAR CONFIGURACIONES DEL SISTEMA
-- =====================================================

\echo 'Insertando configuraciones del sistema...'

INSERT INTO configuraciones_sistema (clave, valor, descripcion, tipo) VALUES
('sitio_nombre', 'Portal Turístico Ecuador', 'Nombre del sitio web', 'string'),
('sitio_email', 'info@turismoecuador.com', 'Email de contacto principal', 'string'),
('sitio_telefono', '+593 2 234 5678', 'Teléfono de contacto', 'string'),
('max_intentos_login', '5', 'Máximo número de intentos de login fallidos', 'number'),
('tiempo_bloqueo_minutos', '30', 'Tiempo de bloqueo tras exceder intentos', 'number'),
('captcha_longitud', '5', 'Longitud del captcha matemático', 'number'),
('permitir_registro_publico', 'true', 'Permitir registro público de usuarios', 'boolean')
ON CONFLICT (clave) DO NOTHING;

-- =====================================================
-- CREAR/ACTUALIZAR FUNCIONES Y TRIGGERS
-- =====================================================

\echo 'Creando funciones y triggers...'

-- Función para actualizar fecha de último acceso
CREATE OR REPLACE FUNCTION actualizar_ultimo_acceso()
RETURNS TRIGGER AS $$
BEGIN
    NEW.ultimo_acceso = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Función para actualizar fecha de actualización
CREATE OR REPLACE FUNCTION actualizar_fecha_actualizacion()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Crear triggers (eliminando primero si existen)
DROP TRIGGER IF EXISTS trigger_ultimo_acceso ON usuarios;
CREATE TRIGGER trigger_ultimo_acceso
    BEFORE UPDATE ON usuarios
    FOR EACH ROW
    EXECUTE FUNCTION actualizar_ultimo_acceso();

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
-- CREAR/ACTUALIZAR VISTAS
-- =====================================================

\echo 'Creando vistas...'

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
    COALESCE((SELECT COUNT(*) FROM favoritos_usuarios f WHERE f.destino_id = d.id), 0) as total_favoritos
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
    COALESCE((SELECT COUNT(*) FROM favoritos_usuarios f WHERE f.usuario_id = u.id), 0) as total_favoritos,
    COALESCE((SELECT COUNT(*) FROM destinos d WHERE d.usuario_creador_id = u.id), 0) as destinos_creados
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

\echo '¡Actualización de base de datos completada exitosamente!'

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

\echo 'Verificando estructura de la base de datos:'

-- Mostrar tablas existentes
SELECT 'Tablas existentes:' as info;
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
ORDER BY table_name;

-- Mostrar vistas existentes
SELECT 'Vistas existentes:' as info;
SELECT table_name as view_name FROM information_schema.views 
WHERE table_schema = 'public'
ORDER BY table_name;

\echo 'Actualización completada. La base de datos está lista para usar.'
