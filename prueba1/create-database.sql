-- Script para crear la base de datos y usuario
-- Ejecutar como usuario postgres

-- Crear la base de datos
CREATE DATABASE turismo_inclusivo_ecuador
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Spanish_Ecuador.1252'
    LC_CTYPE = 'Spanish_Ecuador.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;

-- Crear el usuario
CREATE USER turismo_user WITH PASSWORD 'turismo_password_2024';

-- Dar permisos al usuario
GRANT ALL PRIVILEGES ON DATABASE turismo_inclusivo_ecuador TO turismo_user;

-- Conectar a la base de datos recién creada
\c turismo_inclusivo_ecuador

-- Dar permisos adicionales
GRANT ALL ON SCHEMA public TO turismo_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO turismo_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO turismo_user;
