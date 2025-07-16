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

const createEvaluationSchema = Joi.object({
  ruta_id: Joi.string().uuid().required(),
  puntuacion_accesibilidad: Joi.number().min(1).max(5).required(),
  comentarios: Joi.string().max(1000).optional(),
  aspectos_positivos: Joi.array().items(Joi.string()).optional(),
  aspectos_negativos: Joi.array().items(Joi.string()).optional()
});

// =====================================================
// RUTAS DE EVALUACIONES
// =====================================================

// Crear evaluación
router.post('/', authenticateToken, validateRequest(createEvaluationSchema), async (req, res) => {
  try {
    const { ruta_id, puntuacion_accesibilidad, comentarios, aspectos_positivos, aspectos_negativos } = req.body;
    const usuario_id = req.user.userId;
    
    const result = await query(
      `INSERT INTO evaluaciones (ruta_id, usuario_id, puntuacion_accesibilidad, comentarios, aspectos_positivos, aspectos_negativos)
       VALUES ($1, $2, $3, $4, $5, $6)
       RETURNING *`,
      [ruta_id, usuario_id, puntuacion_accesibilidad, comentarios, aspectos_positivos || [], aspectos_negativos || []]
    );
    
    res.status(201).json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al crear evaluación:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Listar evaluaciones por ruta
router.get('/ruta/:ruta_id', async (req, res) => {
  try {
    const { ruta_id } = req.params;
    const result = await query(
      'SELECT * FROM evaluaciones WHERE ruta_id = $1 ORDER BY fecha_evaluacion DESC',
      [ruta_id]
    );
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar evaluaciones:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Obtener evaluación por ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const result = await query('SELECT * FROM evaluaciones WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Evaluación no encontrada' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al obtener evaluación:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

module.exports = router; 