const Joi = require('joi');

// =====================================================
// MIDDLEWARE DE VALIDACIÓN
// =====================================================

/**
 * Middleware para validar request usando Joi
 * @param {Object} schema - Esquema de validación Joi
 */
const validateRequest = (schema) => {
  return (req, res, next) => {
    const { error, value } = schema.validate(req.body, {
      abortEarly: false,
      stripUnknown: true
    });

    if (error) {
      const errorMessages = error.details.map(detail => ({
        field: detail.path.join('.'),
        message: detail.message
      }));

      return res.status(400).json({
        success: false,
        message: 'Datos de entrada inválidos',
        errors: errorMessages
      });
    }

    // Reemplazar req.body con los datos validados
    req.body = value;
    next();
  };
};

/**
 * Middleware para validar parámetros de query
 * @param {Object} schema - Esquema de validación Joi
 */
const validateQuery = (schema) => {
  return (req, res, next) => {
    const { error, value } = schema.validate(req.query, {
      abortEarly: false,
      stripUnknown: true
    });

    if (error) {
      const errorMessages = error.details.map(detail => ({
        field: detail.path.join('.'),
        message: detail.message
      }));

      return res.status(400).json({
        success: false,
        message: 'Parámetros de consulta inválidos',
        errors: errorMessages
      });
    }

    req.query = value;
    next();
  };
};

/**
 * Middleware para validar parámetros de URL
 * @param {Object} schema - Esquema de validación Joi
 */
const validateParams = (schema) => {
  return (req, res, next) => {
    const { error, value } = schema.validate(req.params, {
      abortEarly: false,
      stripUnknown: true
    });

    if (error) {
      const errorMessages = error.details.map(detail => ({
        field: detail.path.join('.'),
        message: detail.message
      }));

      return res.status(400).json({
        success: false,
        message: 'Parámetros de URL inválidos',
        errors: errorMessages
      });
    }

    req.params = value;
    next();
  };
};

module.exports = {
  validateRequest,
  validateQuery,
  validateParams
}; 