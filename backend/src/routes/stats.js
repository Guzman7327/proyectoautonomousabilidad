const express = require('express');
const { query } = require('../config/database');
const { authenticateToken, authorizeRoles } = require('../middleware/auth');
const { logger } = require('../utils/logger');

const router = express.Router();

// Obtener estadísticas generales (solo admin)
router.get('/', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  try {
    // Estadísticas de usuarios
    const userStats = await query(`
      SELECT 
        COUNT(*) as total_usuarios,
        COUNT(CASE WHEN activo = true THEN 1 END) as usuarios_activos,
        COUNT(CASE WHEN rol = 'admin' THEN 1 END) as admins,
        COUNT(CASE WHEN rol = 'moderador' THEN 1 END) as moderadores
      FROM usuarios
    `);

    // Estadísticas de rutas
    const routeStats = await query(`
      SELECT 
        COUNT(*) as total_rutas,
        COUNT(CASE WHEN estado = 'publicada' THEN 1 END) as rutas_publicadas,
        COUNT(CASE WHEN estado = 'borrador' THEN 1 END) as rutas_borrador,
        AVG(duracion_estimada) as duracion_promedio,
        AVG(distancia_km) as distancia_promedio
      FROM rutas
    `);

    // Estadísticas de auditorías
    const auditStats = await query(`
      SELECT 
        COUNT(*) as total_auditorias,
        COUNT(CASE WHEN estado = 'completada' THEN 1 END) as auditorias_completadas,
        AVG(puntuacion_general) as puntuacion_promedio,
        COUNT(CASE WHEN nivel_conformidad = 'AA' THEN 1 END) as conformidad_aa,
        COUNT(CASE WHEN nivel_conformidad = 'AAA' THEN 1 END) as conformidad_aaa
      FROM auditorias
    `);

    // Estadísticas de evaluaciones
    const evaluationStats = await query(`
      SELECT 
        COUNT(*) as total_evaluaciones,
        AVG(puntuacion_accesibilidad) as puntuacion_promedio
      FROM evaluaciones
    `);

    // Estadísticas de reportes
    const reportStats = await query(`
      SELECT 
        COUNT(*) as total_reportes,
        COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as reportes_pendientes,
        COUNT(CASE WHEN estado = 'resuelto' THEN 1 END) as reportes_resueltos
      FROM reportes
    `);

    res.json({
      success: true,
      data: {
        usuarios: userStats.rows[0],
        rutas: routeStats.rows[0],
        auditorias: auditStats.rows[0],
        evaluaciones: evaluationStats.rows[0],
        reportes: reportStats.rows[0]
      }
    });
  } catch (error) {
    logger.error('Error al obtener estadísticas:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

// Obtener estadísticas de actividad reciente
router.get('/activity', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  try {
    const { days = 30 } = req.query;
    
    // Usuarios registrados en los últimos días
    const recentUsers = await query(`
      SELECT COUNT(*) as nuevos_usuarios
      FROM usuarios 
      WHERE fecha_registro >= NOW() - INTERVAL '${days} days'
    `);

    // Rutas creadas en los últimos días
    const recentRoutes = await query(`
      SELECT COUNT(*) as nuevas_rutas
      FROM rutas 
      WHERE created_at >= NOW() - INTERVAL '${days} days'
    `);

    // Auditorías realizadas en los últimos días
    const recentAudits = await query(`
      SELECT COUNT(*) as nuevas_auditorias
      FROM auditorias 
      WHERE fecha_auditoria >= NOW() - INTERVAL '${days} days'
    `);

    res.json({
      success: true,
      data: {
        periodo_dias: parseInt(days),
        nuevos_usuarios: recentUsers.rows[0].nuevos_usuarios,
        nuevas_rutas: recentRoutes.rows[0].nuevas_rutas,
        nuevas_auditorias: recentAudits.rows[0].nuevas_auditorias
      }
    });
  } catch (error) {
    logger.error('Error al obtener estadísticas de actividad:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

module.exports = router; 