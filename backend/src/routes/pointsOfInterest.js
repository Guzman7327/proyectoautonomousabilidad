const express = require('express');
const Joi = require('joi');
const { query, createPoint } = require('../config/database');
const { logger } = require('../utils/logger');
const { authenticateToken } = require('../middleware/auth');
const { validateRequest } = require('../middleware/validation');

const router = express.Router();

// =====================================================
// ESQUEMAS DE VALIDACIÓN
// =====================================================

const createPoiSchema = Joi.object({
  ruta_id: Joi.string().uuid().required(),
  nombre: Joi.string().min(3).max(200).required(),
  descripcion: Joi.string().max(2000).optional(),
  tipo: Joi.string().max(50).optional(),
  coordenadas: Joi.array().ordered(Joi.number(), Joi.number()).length(2).required(),
  nivel_accesibilidad: Joi.object().optional(),
  servicios_disponibles: Joi.object().optional(),
  horarios: Joi.object().optional(),
  contacto: Joi.object().optional(),
  imagenes: Joi.array().items(Joi.string().uri()).optional(),
  orden: Joi.number().integer().optional()
});

// =====================================================
// RUTAS DE PUNTOS DE INTERÉS
// =====================================================

// Crear punto de interés
router.post('/', authenticateToken, validateRequest(createPoiSchema), async (req, res) => {
  try {
    const { ruta_id, nombre, descripcion, tipo, coordenadas, nivel_accesibilidad, servicios_disponibles, horarios, contacto, imagenes, orden } = req.body;
    const point = createPoint(coordenadas[0], coordenadas[1]);
    const result = await query(
      `INSERT INTO puntos_interes (ruta_id, nombre, descripcion, tipo, coordenadas, nivel_accesibilidad, servicios_disponibles, horarios, contacto, imagenes, orden)
       VALUES ($1, $2, $3, $4, ST_GeomFromText($5, 4326), $6, $7, $8, $9, $10, $11)
       RETURNING *`,
      [ruta_id, nombre, descripcion, tipo, point, nivel_accesibilidad || {}, servicios_disponibles || {}, horarios || {}, contacto || {}, imagenes || [], orden]
    );
    res.status(201).json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al crear punto de interés:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Listar puntos de interés por ruta
router.get('/por-ruta/:ruta_id', async (req, res) => {
  try {
    const { ruta_id } = req.params;
    const result = await query('SELECT * FROM puntos_interes WHERE ruta_id = $1 ORDER BY orden ASC, created_at ASC', [ruta_id]);
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar puntos de interés:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Obtener punto de interés por ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const result = await query('SELECT * FROM puntos_interes WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Punto de interés no encontrado' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al obtener punto de interés:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Actualizar punto de interés
router.put('/:id', authenticateToken, validateRequest(createPoiSchema), async (req, res) => {
  try {
    const { id } = req.params;
    const { ruta_id, nombre, descripcion, tipo, coordenadas, nivel_accesibilidad, servicios_disponibles, horarios, contacto, imagenes, orden } = req.body;
    const point = createPoint(coordenadas[0], coordenadas[1]);
    const result = await query(
      `UPDATE puntos_interes SET ruta_id = $1, nombre = $2, descripcion = $3, tipo = $4, coordenadas = ST_GeomFromText($5, 4326), nivel_accesibilidad = $6, servicios_disponibles = $7, horarios = $8, contacto = $9, imagenes = $10, orden = $11, updated_at = CURRENT_TIMESTAMP WHERE id = $12 RETURNING *`,
      [ruta_id, nombre, descripcion, tipo, point, nivel_accesibilidad || {}, servicios_disponibles || {}, horarios || {}, contacto || {}, imagenes || [], orden, id]
    );
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al actualizar punto de interés:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Eliminar punto de interés
router.delete('/:id', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    await query('DELETE FROM puntos_interes WHERE id = $1', [id]);
    res.json({ success: true, message: 'Punto de interés eliminado correctamente' });
  } catch (error) {
    logger.error('Error al eliminar punto de interés:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

module.exports = router; 