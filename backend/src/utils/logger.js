const winston = require('winston');
const path = require('path');
const fs = require('fs');

// =====================================================
// CONFIGURACIÓN DE LOGGING
// =====================================================

// Crear directorio de logs si no existe
const logDir = path.join(__dirname, '../../logs');
if (!fs.existsSync(logDir)) {
  fs.mkdirSync(logDir, { recursive: true });
}

// Formato personalizado para los logs
const logFormat = winston.format.combine(
  winston.format.timestamp({
    format: 'YYYY-MM-DD HH:mm:ss'
  }),
  winston.format.errors({ stack: true }),
  winston.format.json(),
  winston.format.printf(({ timestamp, level, message, stack, ...meta }) => {
    let log = `${timestamp} [${level.toUpperCase()}]: ${message}`;
    
    if (Object.keys(meta).length > 0) {
      log += ` ${JSON.stringify(meta)}`;
    }
    
    if (stack) {
      log += `\n${stack}`;
    }
    
    return log;
  })
);

// Configuración de colores para consola
const colors = {
  error: 'red',
  warn: 'yellow',
  info: 'green',
  debug: 'blue'
};

winston.addColors(colors);

// =====================================================
// CONFIGURACIÓN DE TRANSPORTS
// =====================================================

const transports = [];

// Transport para consola
transports.push(
  new winston.transports.Console({
    format: winston.format.combine(
      winston.format.colorize({ all: true }),
      winston.format.simple(),
      winston.format.printf(({ timestamp, level, message, stack, ...meta }) => {
        let log = `${timestamp} [${level}]: ${message}`;
        
        if (Object.keys(meta).length > 0) {
          log += ` ${JSON.stringify(meta)}`;
        }
        
        if (stack) {
          log += `\n${stack}`;
        }
        
        return log;
      })
    )
  })
);

// Transport para archivo de logs general
transports.push(
  new winston.transports.File({
    filename: path.join(logDir, 'app.log'),
    format: logFormat,
    maxsize: 5242880, // 5MB
    maxFiles: 5,
    tailable: true
  })
);

// Transport para errores
transports.push(
  new winston.transports.File({
    filename: path.join(logDir, 'error.log'),
    level: 'error',
    format: logFormat,
    maxsize: 5242880, // 5MB
    maxFiles: 5,
    tailable: true
  })
);

// Transport para logs de acceso (solo en producción)
if (process.env.NODE_ENV === 'production') {
  transports.push(
    new winston.transports.File({
      filename: path.join(logDir, 'access.log'),
      format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.json()
      ),
      maxsize: 5242880, // 5MB
      maxFiles: 5,
      tailable: true
    })
  );
}

// =====================================================
// CREAR LOGGER
// =====================================================

const logger = winston.createLogger({
  level: process.env.LOG_LEVEL || 'info',
  format: logFormat,
  transports,
  exitOnError: false
});

// =====================================================
// FUNCIONES DE UTILIDAD
// =====================================================

/**
 * Log de inicio de aplicación
 */
function logAppStart() {
  logger.info('🚀 Aplicación iniciada', {
    environment: process.env.NODE_ENV,
    nodeVersion: process.version,
    platform: process.platform,
    memory: process.memoryUsage(),
    pid: process.pid
  });
}

/**
 * Log de cierre de aplicación
 */
function logAppShutdown() {
  logger.info('🛑 Aplicación cerrada', {
    uptime: process.uptime(),
    memory: process.memoryUsage()
  });
}

/**
 * Log de request HTTP
 * @param {Object} req - Request object
 * @param {Object} res - Response object
 * @param {number} responseTime - Tiempo de respuesta en ms
 */
function logHttpRequest(req, res, responseTime) {
  const logData = {
    method: req.method,
    url: req.url,
    statusCode: res.statusCode,
    responseTime: `${responseTime}ms`,
    userAgent: req.get('User-Agent'),
    ip: req.ip || req.connection.remoteAddress,
    userId: req.user?.id || 'anonymous'
  };

  if (res.statusCode >= 400) {
    logger.warn('HTTP Request', logData);
  } else {
    logger.info('HTTP Request', logData);
  }
}

/**
 * Log de error de base de datos
 * @param {Error} error - Error de base de datos
 * @param {string} query - Consulta que causó el error
 * @param {Array} params - Parámetros de la consulta
 */
function logDatabaseError(error, query, params = []) {
  logger.error('Database Error', {
    message: error.message,
    code: error.code,
    query: query.substring(0, 200) + (query.length > 200 ? '...' : ''),
    params: params.length > 0 ? params : undefined,
    stack: error.stack
  });
}

/**
 * Log de auditoría WCAG
 * @param {string} url - URL auditada
 * @param {Object} results - Resultados de la auditoría
 * @param {string} userId - ID del usuario que realizó la auditoría
 */
function logWcagAudit(url, results, userId) {
  logger.info('WCAG Audit', {
    url,
    userId,
    score: results.score,
    violations: results.violations?.length || 0,
    passes: results.passes?.length || 0,
    timestamp: new Date().toISOString()
  });
}

/**
 * Log de creación de ruta
 * @param {Object} route - Datos de la ruta creada
 * @param {string} userId - ID del usuario creador
 */
function logRouteCreation(route, userId) {
  logger.info('Route Created', {
    routeId: route.id,
    title: route.titulo,
    category: route.categoria_id,
    userId,
    coordinates: route.coordenadas ? 'present' : 'missing',
    timestamp: new Date().toISOString()
  });
}

/**
 * Log de evaluación de accesibilidad
 * @param {Object} evaluation - Datos de la evaluación
 * @param {string} userId - ID del usuario evaluador
 */
function logAccessibilityEvaluation(evaluation, userId) {
  logger.info('Accessibility Evaluation', {
    evaluationId: evaluation.id,
    routeId: evaluation.ruta_id,
    score: evaluation.puntuacion_accesibilidad,
    userId,
    timestamp: new Date().toISOString()
  });
}

// =====================================================
// MANEJO DE ERRORES NO CAPTURADOS
// =====================================================

// Capturar errores no manejados
process.on('uncaughtException', (error) => {
  logger.error('Uncaught Exception', {
    message: error.message,
    stack: error.stack,
    timestamp: new Date().toISOString()
  });
  process.exit(1);
});

process.on('unhandledRejection', (reason, promise) => {
  logger.error('Unhandled Rejection', {
    reason: reason?.message || reason,
    stack: reason?.stack,
    timestamp: new Date().toISOString()
  });
});

module.exports = {
  logger,
  logAppStart,
  logAppShutdown,
  logHttpRequest,
  logDatabaseError,
  logWcagAudit,
  logRouteCreation,
  logAccessibilityEvaluation
}; 