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

const createCategorySchema = Joi.object({
  nombre: Joi.string().min(2).max(100).required(),
  descripcion: Joi.string().max(500).optional(),
  icono: Joi.string().max(50).optional(),
  color: Joi.string().pattern(/^#[0-9A-F]{6}$/i).optional()
});

// =====================================================
// RUTAS DE CATEGORÍAS
// =====================================================

// Listar categorías
router.get('/', async (req, res) => {
  try {
    const result = await query('SELECT * FROM categorias_rutas ORDER BY nombre');
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar categorías:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Obtener categoría por ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const result = await query('SELECT * FROM categorias_rutas WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Categoría no encontrada' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al obtener categoría:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Crear categoría (solo admin)
router.post('/', authenticateToken, authorizeRoles('admin'), validateRequest(createCategorySchema), async (req, res) => {
  try {
    const { nombre, descripcion, icono, color } = req.body;
    const result = await query(
      'INSERT INTO categorias_rutas (nombre, descripcion, icono, color) VALUES ($1, $2, $3, $4) RETURNING *',
      [nombre, descripcion, icono, color]
    );
    res.status(201).json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al crear categoría:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Actualizar categoría (solo admin)
router.put('/:id', authenticateToken, authorizeRoles('admin'), validateRequest(createCategorySchema), async (req, res) => {
  try {
    const { id } = req.params;
    const { nombre, descripcion, icono, color } = req.body;
    const result = await query(
      'UPDATE categorias_rutas SET nombre = $1, descripcion = $2, icono = $3, color = $4 WHERE id = $5 RETURNING *',
      [nombre, descripcion, icono, color, id]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Categoría no encontrada' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al actualizar categoría:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Eliminar categoría (solo admin)
router.delete('/:id', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  try {
    const { id } = req.params;
    await query('DELETE FROM categorias_rutas WHERE id = $1', [id]);
    res.json({ success: true, message: 'Categoría eliminada correctamente' });
  } catch (error) {
    logger.error('Error al eliminar categoría:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

module.exports = router; 