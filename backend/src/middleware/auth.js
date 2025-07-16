const jwt = require('jsonwebtoken');
const { query } = require('../config/database');
const { logger } = require('../utils/logger');

// =====================================================
// MIDDLEWARE DE AUTENTICACIÓN
// =====================================================

/**
 * Middleware para verificar el token JWT
 */
const authenticateToken = async (req, res, next) => {
  try {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1]; // Bearer TOKEN

    if (!token) {
      return res.status(401).json({
        success: false,
        message: 'Token de acceso requerido'
      });
    }

    // Verificar token
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    
    // Verificar que el usuario existe y está activo
    const result = await query(
      'SELECT id, email, rol, activo FROM usuarios WHERE id = $1',
      [decoded.userId]
    );

    if (result.rows.length === 0 || !result.rows[0].activo) {
      return res.status(401).json({
        success: false,
        message: 'Token inválido o usuario inactivo'
      });
    }

    // Agregar información del usuario al request
    req.user = {
      userId: decoded.userId,
      email: decoded.email,
      rol: decoded.rol
    };

    next();
  } catch (error) {
    logger.error('Error en autenticación:', error);
    
    if (error.name === 'TokenExpiredError') {
      return res.status(401).json({
        success: false,
        message: 'Token expirado'
      });
    }
    
    if (error.name === 'JsonWebTokenError') {
      return res.status(401).json({
        success: false,
        message: 'Token inválido'
      });
    }

    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
};

// =====================================================
// MIDDLEWARE DE AUTORIZACIÓN
// =====================================================

/**
 * Middleware para verificar roles específicos
 * @param {...string} roles - Roles permitidos
 */
const authorizeRoles = (...roles) => {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({
        success: false,
        message: 'No autorizado'
      });
    }

    if (!roles.includes(req.user.rol)) {
      return res.status(403).json({
        success: false,
        message: 'Acceso denegado. Rol insuficiente.'
      });
    }

    next();
  };
};

/**
 * Middleware para verificar si es el propietario del recurso o admin
 * @param {string} resourceTable - Tabla del recurso
 * @param {string} resourceIdField - Campo ID del recurso
 * @param {string} ownerField - Campo del propietario
 */
const authorizeOwner = (resourceTable, resourceIdField = 'id', ownerField = 'creador_id') => {
  return async (req, res, next) => {
    try {
      const resourceId = req.params[resourceIdField];
      
      if (!resourceId) {
        return res.status(400).json({
          success: false,
          message: 'ID del recurso requerido'
        });
      }

      // Si es admin, permitir acceso
      if (req.user.rol === 'admin') {
        return next();
      }

      // Verificar si es el propietario
      const result = await query(
        `SELECT ${ownerField} FROM ${resourceTable} WHERE ${resourceIdField} = $1`,
        [resourceId]
      );

      if (result.rows.length === 0) {
        return res.status(404).json({
          success: false,
          message: 'Recurso no encontrado'
        });
      }

      if (result.rows[0][ownerField] !== req.user.userId) {
        return res.status(403).json({
          success: false,
          message: 'No autorizado para acceder a este recurso'
        });
      }

      next();
    } catch (error) {
      logger.error('Error en autorización de propietario:', error);
      res.status(500).json({
        success: false,
        message: 'Error interno del servidor'
      });
    }
  };
};

// =====================================================
// MIDDLEWARE DE AUDITORÍA
// =====================================================

/**
 * Middleware para registrar acciones del usuario
 * @param {string} action - Acción realizada
 */
const auditAction = (action) => {
  return async (req, res, next) => {
    const originalSend = res.send;
    
    res.send = function(data) {
      // Registrar la acción después de que se complete
      try {
        const logData = {
          userId: req.user?.userId,
          action,
          method: req.method,
          url: req.originalUrl,
          statusCode: res.statusCode,
          timestamp: new Date().toISOString()
        };
        
        logger.info('User Action', logData);
      } catch (error) {
        logger.error('Error logging user action:', error);
      }
      
      originalSend.call(this, data);
    };
    
    next();
  };
};

module.exports = {
  authenticateToken,
  authorizeRoles,
  authorizeOwner,
  auditAction
}; 