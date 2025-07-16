const express = require('express');
const Joi = require('joi');
const { query } = require('../config/database');
const { logger } = require('../utils/logger');
const { authenticateToken } = require('../middleware/auth');
const { validateRequest } = require('../middleware/validation');

const router = express.Router();

// =====================================================
// ESQUEMAS DE VALIDACIÓN
// =====================================================

const createAuditSchema = Joi.object({
  sitio_web: Joi.string().uri().required(),
  nombre_sitio: Joi.string().max(200).optional(),
  resultado_wcag: Joi.object().required(),
  puntuacion_general: Joi.number().min(0).max(100).required(),
  nivel_conformidad: Joi.string().valid('A', 'AA', 'AAA', 'N/A').required(),
  problemas_encontrados: Joi.array().items(Joi.object()).optional(),
  recomendaciones: Joi.array().items(Joi.object()).optional()
});

// =====================================================
// RUTAS DE AUDITORÍAS
// =====================================================

// Crear auditoría
router.post('/', authenticateToken, validateRequest(createAuditSchema), async (req, res) => {
  try {
    const { sitio_web, nombre_sitio, resultado_wcag, puntuacion_general, nivel_conformidad, problemas_encontrados, recomendaciones } = req.body;
    const auditor_id = req.user.userId;
    const result = await query(
      `INSERT INTO auditorias (sitio_web, nombre_sitio, auditor_id, resultado_wcag, puntuacion_general, nivel_conformidad, problemas_encontrados, recomendaciones, estado)
       VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'completada')
       RETURNING *`,
      [sitio_web, nombre_sitio, auditor_id, resultado_wcag, puntuacion_general, nivel_conformidad, problemas_encontrados || [], recomendaciones || []]
    );
    res.status(201).json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al crear auditoría:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Listar auditorías (paginado, filtro por usuario)
router.get('/', authenticateToken, async (req, res) => {
  try {
    const { page = 1, limit = 10, auditor_id } = req.query;
    let where = '';
    const params = [];
    let idx = 1;
    if (auditor_id) {
      where += `WHERE auditor_id = $${idx}`;
      params.push(auditor_id);
      idx++;
    }
    const offset = (parseInt(page) - 1) * parseInt(limit);
    const result = await query(
      `SELECT * FROM auditorias ${where} ORDER BY fecha_auditoria DESC LIMIT $${idx} OFFSET $${idx + 1}`,
      [...params, limit, offset]
    );
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar auditorías:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Obtener auditoría por ID
router.get('/:id', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    const result = await query('SELECT * FROM auditorias WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Auditoría no encontrada' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al obtener auditoría:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

module.exports = router; 