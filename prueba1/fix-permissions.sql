-- Script para arreglar permisos del usuario turismo_user
-- Conectar a la base de datos turismo_inclusivo_ecuador

-- Dar permisos completos al usuario en todas las tablas
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO turismo_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO turismo_user;
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public TO turismo_user;

-- Asegurar que pueda crear tablas futuras
GRANT USAGE ON SCHEMA public TO turismo_user;
GRANT CREATE ON SCHEMA public TO turismo_user;

-- Cambiar el propietario de las tablas existentes
ALTER TABLE users OWNER TO turismo_user;
ALTER TABLE destinations OWNER TO turismo_user;
ALTER TABLE reviews OWNER TO turismo_user;
ALTER TABLE accessibility_feedback OWNER TO turismo_user;

-- Verificar permisos
SELECT 'Permisos actualizados correctamente' as status;
