const { Pool } = require('pg');
const { logger } = require('../utils/logger');

// =====================================================
// CONFIGURACIÓN DE LA BASE DE DATOS
// =====================================================

const pool = new Pool({
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 5432,
  database: process.env.DB_NAME || 'turismo_inclusivo',
  user: process.env.DB_USER || 'turismo_app',
  password: process.env.DB_PASSWORD || 'password_seguro',
  ssl: process.env.DB_SSL === 'true' ? { rejectUnauthorized: false } : false,
  max: 20, // máximo número de conexiones en el pool
  idleTimeoutMillis: 30000, // tiempo máximo que una conexión puede estar inactiva
  connectionTimeoutMillis: 2000, // tiempo máximo para establecer una conexión
});

// =====================================================
// EVENTOS DEL POOL
// =====================================================

pool.on('connect', (client) => {
  logger.info('Nueva conexión a la base de datos establecida');
});

pool.on('error', (err, client) => {
  logger.error('Error inesperado en el pool de conexiones:', err);
});

pool.on('remove', (client) => {
  logger.info('Cliente removido del pool de conexiones');
});

// =====================================================
// FUNCIÓN DE CONEXIÓN
// =====================================================

async function connectDB() {
  try {
    // Probar la conexión
    const client = await pool.connect();
    
    // Verificar que PostGIS esté disponible
    const postgisResult = await client.query('SELECT PostGIS_Version()');
    logger.info(`✅ PostGIS conectado: ${postgisResult.rows[0].postgis_version}`);
    
    // Verificar que la extensión uuid-ossp esté disponible
    const uuidResult = await client.query('SELECT uuid_generate_v4()');
    logger.info('✅ Extensión UUID disponible');
    
    client.release();
    
    return true;
  } catch (error) {
    logger.error('❌ Error al conectar con la base de datos:', error);
    throw error;
  }
}

// =====================================================
// FUNCIONES DE UTILIDAD
// =====================================================

/**
 * Ejecutar una consulta con parámetros
 * @param {string} text - Consulta SQL
 * @param {Array} params - Parámetros de la consulta
 * @returns {Promise<Object>} - Resultado de la consulta
 */
async function query(text, params = []) {
  const start = Date.now();
  try {
    const result = await pool.query(text, params);
    const duration = Date.now() - start;
    
    logger.debug('Consulta ejecutada', {
      text,
      duration,
      rows: result.rowCount
    });
    
    return result;
  } catch (error) {
    logger.error('Error en consulta:', {
      text,
      params,
      error: error.message
    });
    throw error;
  }
}

/**
 * Obtener un cliente del pool para transacciones
 * @returns {Promise<Object>} - Cliente de la base de datos
 */
async function getClient() {
  return await pool.connect();
}

/**
 * Ejecutar una transacción
 * @param {Function} callback - Función que contiene las operaciones de la transacción
 * @returns {Promise<Object>} - Resultado de la transacción
 */
async function transaction(callback) {
  const client = await pool.connect();
  try {
    await client.query('BEGIN');
    const result = await callback(client);
    await client.query('COMMIT');
    return result;
  } catch (error) {
    await client.query('ROLLBACK');
    throw error;
  } finally {
    client.release();
  }
}

/**
 * Cerrar el pool de conexiones
 */
async function closePool() {
  await pool.end();
  logger.info('Pool de conexiones cerrado');
}

// =====================================================
// FUNCIONES ESPECÍFICAS PARA GEOLOCALIZACIÓN
// =====================================================

/**
 * Crear un punto geométrico
 * @param {number} longitude - Longitud
 * @param {number} latitude - Latitud
 * @returns {string} - Geometría en formato WKT
 */
function createPoint(longitude, latitude) {
  return `POINT(${longitude} ${latitude})`;
}

/**
 * Crear una línea geométrica
 * @param {Array} coordinates - Array de coordenadas [[lng, lat], [lng, lat], ...]
 * @returns {string} - Geometría en formato WKT
 */
function createLineString(coordinates) {
  const points = coordinates.map(coord => `${coord[0]} ${coord[1]}`).join(', ');
  return `LINESTRING(${points})`;
}

/**
 * Calcular distancia entre dos puntos
 * @param {number} lat1 - Latitud del primer punto
 * @param {number} lng1 - Longitud del primer punto
 * @param {number} lat2 - Latitud del segundo punto
 * @param {number} lng2 - Longitud del segundo punto
 * @returns {Promise<number>} - Distancia en metros
 */
async function calculateDistance(lat1, lng1, lat2, lng2) {
  const query = `
    SELECT ST_Distance(
      ST_SetSRID(ST_MakePoint($1, $2), 4326)::geography,
      ST_SetSRID(ST_MakePoint($3, $4), 4326)::geography
    ) as distance
  `;
  
  const result = await query(query, [lng1, lat1, lng2, lat2]);
  return result.rows[0].distance;
}

/**
 * Buscar rutas cercanas a un punto
 * @param {number} latitude - Latitud del punto de referencia
 * @param {number} longitude - Longitud del punto de referencia
 * @param {number} radius - Radio de búsqueda en metros
 * @returns {Promise<Array>} - Rutas encontradas
 */
async function findNearbyRoutes(latitude, longitude, radius = 5000) {
  const query = `
    SELECT 
      r.*,
      ST_Distance(
        r.coordenadas::geography,
        ST_SetSRID(ST_MakePoint($1, $2), 4326)::geography
      ) as distance
    FROM rutas r
    WHERE ST_DWithin(
      r.coordenadas::geography,
      ST_SetSRID(ST_MakePoint($1, $2), 4326)::geography,
      $3
    )
    AND r.estado = 'publicada'
    ORDER BY distance
  `;
  
  const result = await query(query, [longitude, latitude, radius]);
  return result.rows;
}

module.exports = {
  pool,
  connectDB,
  query,
  getClient,
  transaction,
  closePool,
  createPoint,
  createLineString,
  calculateDistance,
  findNearbyRoutes
}; 