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

const updateUserSchema = Joi.object({
  nombre: Joi.string().min(2).max(100),
  apellido: Joi.string().min(2).max(100),
  tipo_discapacidad: Joi.object().optional(),
  preferencias: Joi.object().optional(),
  activo: Joi.boolean().optional()
});

const changePasswordSchema = Joi.object({
  oldPassword: Joi.string().required(),
  newPassword: Joi.string().min(8).required()
});

// =====================================================
// RUTAS DE USUARIOS
// =====================================================

// Listar usuarios (solo admin)
router.get('/', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  try {
    const result = await query('SELECT id, email, nombre, apellido, rol, activo, fecha_registro FROM usuarios ORDER BY fecha_registro DESC');
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar usuarios:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Obtener usuario por ID (admin o el propio usuario)
router.get('/:id', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    if (req.user.rol !== 'admin' && req.user.userId !== id) {
      return res.status(403).json({ success: false, message: 'No autorizado' });
    }
    const result = await query('SELECT id, email, nombre, apellido, rol, tipo_discapacidad, preferencias, activo, fecha_registro FROM usuarios WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Usuario no encontrado' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al obtener usuario:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Actualizar usuario (admin o el propio usuario)
router.put('/:id', authenticateToken, validateRequest(updateUserSchema), async (req, res) => {
  try {
    const { id } = req.params;
    if (req.user.rol !== 'admin' && req.user.userId !== id) {
      return res.status(403).json({ success: false, message: 'No autorizado' });
    }
    const fields = [];
    const values = [];
    let idx = 1;
    for (const key in req.body) {
      fields.push(`${key} = $${idx}`);
      values.push(req.body[key]);
      idx++;
    }
    if (fields.length === 0) {
      return res.status(400).json({ success: false, message: 'No hay datos para actualizar' });
    }
    values.push(id);
    const result = await query(`UPDATE usuarios SET ${fields.join(', ')}, updated_at = CURRENT_TIMESTAMP WHERE id = $${idx} RETURNING id, email, nombre, apellido, rol, tipo_discapacidad, preferencias, activo, fecha_registro`, values);
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al actualizar usuario:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Cambiar contraseña (usuario autenticado)
router.put('/:id/password', authenticateToken, validateRequest(changePasswordSchema), async (req, res) => {
  try {
    const { id } = req.params;
    const { oldPassword, newPassword } = req.body;
    if (req.user.userId !== id) {
      return res.status(403).json({ success: false, message: 'No autorizado' });
    }
    const result = await query('SELECT password_hash FROM usuarios WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Usuario no encontrado' });
    }
    const user = result.rows[0];
    const isValid = await require('bcryptjs').compare(oldPassword, user.password_hash);
    if (!isValid) {
      return res.status(401).json({ success: false, message: 'Contraseña actual incorrecta' });
    }
    const newHash = await require('bcryptjs').hash(newPassword, 12);
    await query('UPDATE usuarios SET password_hash = $1, updated_at = CURRENT_TIMESTAMP WHERE id = $2', [newHash, id]);
    res.json({ success: true, message: 'Contraseña actualizada correctamente' });
  } catch (error) {
    logger.error('Error al cambiar contraseña:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Eliminar usuario (solo admin)
router.delete('/:id', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  try {
    const { id } = req.params;
    await query('DELETE FROM usuarios WHERE id = $1', [id]);
    res.json({ success: true, message: 'Usuario eliminado correctamente' });
  } catch (error) {
    logger.error('Error al eliminar usuario:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

module.exports = router; 