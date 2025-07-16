const express = require('express');
const { query } = require('../config/database');
const { authenticateToken } = require('../middleware/auth');
const { logger } = require('../utils/logger');

const router = express.Router();

// Obtener notificaciones del usuario
router.get('/', authenticateToken, async (req, res) => {
  try {
    const { page = 1, limit = 10, unread_only = false } = req.query;
    const offset = (page - 1) * limit;
    
    let whereClause = 'WHERE usuario_id = $1';
    const params = [req.user.userId];
    
    if (unread_only === 'true') {
      whereClause += ' AND leida = false';
    }
    
    const notifications = await query(`
      SELECT 
        id,
        titulo,
        mensaje,
        tipo,
        leida,
        created_at,
        data_adicional
      FROM notificaciones 
      ${whereClause}
      ORDER BY created_at DESC
      LIMIT $${params.length + 1} OFFSET $${params.length + 2}
    `, [...params, limit, offset]);
    
    // Contar total de notificaciones
    const totalCount = await query(`
      SELECT COUNT(*) as total
      FROM notificaciones 
      ${whereClause}
    `, params);
    
    res.json({
      success: true,
      data: {
        notifications: notifications.rows,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: parseInt(totalCount.rows[0].total),
          pages: Math.ceil(totalCount.rows[0].total / limit)
        }
      }
    });
  } catch (error) {
    logger.error('Error al obtener notificaciones:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

// Marcar notificación como leída
router.patch('/:id/read', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    
    const result = await query(`
      UPDATE notificaciones 
      SET leida = true, updated_at = NOW()
      WHERE id = $1 AND usuario_id = $2
      RETURNING id
    `, [id, req.user.userId]);
    
    if (result.rows.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Notificación no encontrada'
      });
    }
    
    res.json({
      success: true,
      message: 'Notificación marcada como leída'
    });
  } catch (error) {
    logger.error('Error al marcar notificación como leída:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

// Marcar todas las notificaciones como leídas
router.patch('/read-all', authenticateToken, async (req, res) => {
  try {
    await query(`
      UPDATE notificaciones 
      SET leida = true, updated_at = NOW()
      WHERE usuario_id = $1 AND leida = false
    `, [req.user.userId]);
    
    res.json({
      success: true,
      message: 'Todas las notificaciones marcadas como leídas'
    });
  } catch (error) {
    logger.error('Error al marcar todas las notificaciones como leídas:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

// Eliminar notificación
router.delete('/:id', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    
    const result = await query(`
      DELETE FROM notificaciones 
      WHERE id = $1 AND usuario_id = $2
      RETURNING id
    `, [id, req.user.userId]);
    
    if (result.rows.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Notificación no encontrada'
      });
    }
    
    res.json({
      success: true,
      message: 'Notificación eliminada'
    });
  } catch (error) {
    logger.error('Error al eliminar notificación:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

// Obtener contador de notificaciones no leídas
router.get('/unread-count', authenticateToken, async (req, res) => {
  try {
    const result = await query(`
      SELECT COUNT(*) as count
      FROM notificaciones 
      WHERE usuario_id = $1 AND leida = false
    `, [req.user.userId]);
    
    res.json({
      success: true,
      data: {
        unread_count: parseInt(result.rows[0].count)
      }
    });
  } catch (error) {
    logger.error('Error al obtener contador de notificaciones:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

module.exports = router; 