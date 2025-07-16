const express = require('express');
const Joi = require('joi');
const { query } = require('../config/database');
const { logger } = require('../utils/logger');
const { authenticateToken, authorizeRoles } = require('../middleware/auth');
const { validateRequest } = require('../middleware/validation');

const router = express.Router();

// =====================================================
// ESQUEMAS DE VALIDACIÓN
// =====================================================

const createReportSchema = Joi.object({
  tipo: Joi.string().valid('ruta', 'punto_interes', 'auditoria').required(),
  entidad_id: Joi.string().uuid().required(),
  motivo: Joi.string().max(100).required(),
  descripcion: Joi.string().max(1000).optional()
});

const updateReportSchema = Joi.object({
  estado: Joi.string().valid('pendiente', 'revisado', 'resuelto', 'rechazado').required()
});

// =====================================================
// RUTAS DE REPORTES
// =====================================================

// Crear reporte
router.post('/', authenticateToken, validateRequest(createReportSchema), async (req, res) => {
  try {
    const { tipo, entidad_id, motivo, descripcion } = req.body;
    const usuario_id = req.user.userId;
    
    const result = await query(
      `INSERT INTO reportes (tipo, entidad_id, usuario_id, motivo, descripcion)
       VALUES ($1, $2, $3, $4, $5)
       RETURNING *`,
      [tipo, entidad_id, usuario_id, motivo, descripcion]
    );
    
    res.status(201).json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al crear reporte:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Listar reportes (solo moderadores y admins)
router.get('/', authenticateToken, authorizeRoles('admin', 'moderador'), async (req, res) => {
  try {
    const { estado, tipo } = req.query;
    let where = '';
    const params = [];
    let idx = 1;
    
    if (estado) {
      where += `WHERE estado = $${idx}`;
      params.push(estado);
      idx++;
    }
    
    if (tipo) {
      where += where ? ` AND tipo = $${idx}` : `WHERE tipo = $${idx}`;
      params.push(tipo);
      idx++;
    }
    
    const result = await query(
      `SELECT * FROM reportes ${where} ORDER BY fecha_reporte DESC`,
      params
    );
    
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar reportes:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Actualizar estado del reporte (solo moderadores y admins)
router.put('/:id', authenticateToken, authorizeRoles('admin', 'moderador'), validateRequest(updateReportSchema), async (req, res) => {
  try {
    const { id } = req.params;
    const { estado } = req.body;
    const moderador_id = req.user.userId;
    
    const result = await query(
      `UPDATE reportes SET estado = $1, moderador_id = $2, fecha_resolucion = CURRENT_TIMESTAMP WHERE id = $3 RETURNING *`,
      [estado, moderador_id, id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Reporte no encontrado' });
    }
    
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al actualizar reporte:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

module.exports = router; 