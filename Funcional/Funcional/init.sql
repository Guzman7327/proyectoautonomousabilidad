CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    usuario TEXT UNIQUE NOT NULL,
    clave BYTEA NOT NULL
);

ALTER TABLE usuarios ALTER COLUMN clave TYPE BYTEA;
ALTER TABLE usuarios ADD COLUMN rol TEXT DEFAULT 'usuario';
ALTER TABLE usuarios ADD COLUMN email TEXT UNIQUE;
ALTER TABLE usuarios ADD COLUMN fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE usuarios ADD COLUMN ultimo_acceso TIMESTAMP;
ALTER TABLE usuarios ADD COLUMN activo BOOLEAN DEFAULT TRUE;
ALTER TABLE usuarios ADD COLUMN intentos_fallidos INTEGER DEFAULT 0;
ALTER TABLE usuarios ADD COLUMN bloqueado_hasta TIMESTAMP;

CREATE TABLE IF NOT EXISTS destinos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    provincia VARCHAR(50) NOT NULL,
    ciudad VARCHAR(50),
    latitud NUMERIC,
    longitud NUMERIC,
    direccion VARCHAR(200)
);

CREATE TABLE log_actividad (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id),
    accion TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address TEXT,
    detalles JSON
);

