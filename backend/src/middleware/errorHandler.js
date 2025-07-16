const { logger } = require('../utils/logger');

// =====================================================
// MIDDLEWARE DE MANEJO DE ERRORES
// =====================================================

const errorHandler = (err, req, res, next) => {
  // Log del error
  logger.error('Error no manejado:', {
    message: err.message,
    stack: err.stack,
    url: req.url,
    method: req.method,
    ip: req.ip,
    userAgent: req.get('User-Agent')
  });

  // Error de validación de base de datos
  if (err.code === '23505') { // Unique violation
    return res.status(409).json({
      success: false,
      message: 'El recurso ya existe'
    });
  }

  if (err.code === '23503') { // Foreign key violation
    return res.status(400).json({
      success: false,
      message: 'Referencia inválida'
    });
  }

  if (err.code === '23502') { // Not null violation
    return res.status(400).json({
      success: false,
      message: 'Campo requerido faltante'
    });
  }

  // Error de JWT
  if (err.name === 'JsonWebTokenError') {
    return res.status(401).json({
      success: false,
      message: 'Token inválido'
    });
  }

  if (err.name === 'TokenExpiredError') {
    return res.status(401).json({
      success: false,
      message: 'Token expirado'
    });
  }

  // Error de validación
  if (err.isJoi) {
    return res.status(400).json({
      success: false,
      message: 'Datos de entrada inválidos',
      errors: err.details.map(detail => ({
        field: detail.path.join('.'),
        message: detail.message
      }))
    });
  }

  // Error de sintaxis JSON
  if (err instanceof SyntaxError && err.status === 400 && 'body' in err) {
    return res.status(400).json({
      success: false,
      message: 'JSON inválido'
    });
  }

  // Error de límite de archivo
  if (err.code === 'LIMIT_FILE_SIZE') {
    return res.status(413).json({
      success: false,
      message: 'Archivo demasiado grande'
    });
  }

  // Error de tipo de archivo
  if (err.code === 'LIMIT_UNEXPECTED_FILE') {
    return res.status(400).json({
      success: false,
      message: 'Tipo de archivo no permitido'
    });
  }

  // Error de rate limiting
  if (err.status === 429) {
    return res.status(429).json({
      success: false,
      message: 'Demasiadas solicitudes. Inténtalo de nuevo más tarde.'
    });
  }

  // Error de CORS
  if (err.code === 'CORS_ERROR') {
    return res.status(403).json({
      success: false,
      message: 'Acceso denegado por CORS'
    });
  }

  // Error de conexión a base de datos
  if (err.code === 'ECONNREFUSED' || err.code === 'ENOTFOUND') {
    return res.status(503).json({
      success: false,
      message: 'Servicio temporalmente no disponible'
    });
  }

  // Error genérico (no exponer detalles en producción)
  const isDevelopment = process.env.NODE_ENV === 'development';
  
  res.status(err.status || 500).json({
    success: false,
    message: isDevelopment ? err.message : 'Error interno del servidor',
    ...(isDevelopment && { stack: err.stack })
  });
};

module.exports = errorHandler; 