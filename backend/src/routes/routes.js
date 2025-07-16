const express = require('express');
const Joi = require('joi');
const { query, createLineString, findNearbyRoutes } = require('../config/database');
const { logger } = require('../utils/logger');
const { authenticateToken } = require('../middleware/auth');
const { validateRequest } = require('../middleware/validation');
const authRoutes = require('./auth');
const userRoutes = require('./users');
const routeRoutes = require('./routes');
const poiRoutes = require('./pointsOfInterest');
const auditRoutes = require('./audits');
const evaluationRoutes = require('./evaluations');
const reportRoutes = require('./reports');
const categoryRoutes = require('./categories');
const uploadRoutes = require('./upload');
const statsRoutes = require('./stats');
const notificationRoutes = require('./notifications');
const searchRoutes = require('./search');

const router = express.Router();

// =====================================================
// ESQUEMAS DE VALIDACIÓN
// =====================================================

const createRouteSchema = Joi.object({
  titulo: Joi.string().min(3).max(200).required(),
  descripcion: Joi.string().max(2000).optional(),
  categoria_id: Joi.string().uuid().required(),
  duracion_estimada: Joi.number().integer().min(1).optional(),
  distancia_km: Joi.number().min(0).optional(),
  dificultad: Joi.string().valid('facil', 'moderada', 'dificil').required(),
  coordenadas: Joi.array().items(
    Joi.array().ordered(Joi.number(), Joi.number())
  ).min(2).required(),
  puntos_acceso: Joi.object().optional(),
  nivel_accesibilidad: Joi.object().optional(),
  imagenes: Joi.array().items(Joi.string().uri()).optional()
});

// =====================================================
// RUTAS DE GESTIÓN DE RUTAS
// =====================================================

// Crear nueva ruta
router.post('/', authenticateToken, validateRequest(createRouteSchema), async (req, res) => {
  try {
    const { titulo, descripcion, categoria_id, duracion_estimada, distancia_km, dificultad, coordenadas, puntos_acceso, nivel_accesibilidad, imagenes } = req.body;
    const creador_id = req.user.userId;
    const lineString = createLineString(coordenadas);
    const result = await query(
      `INSERT INTO rutas (titulo, descripcion, categoria_id, duracion_estimada, distancia_km, dificultad, coordenadas, puntos_acceso, nivel_accesibilidad, imagenes, creador_id, estado)
       VALUES ($1, $2, $3, $4, $5, $6, ST_GeomFromText($7, 4326), $8, $9, $10, $11, 'borrador')
       RETURNING *`,
      [titulo, descripcion, categoria_id, duracion_estimada, distancia_km, dificultad, lineString, puntos_acceso || {}, nivel_accesibilidad || {}, imagenes || [], creador_id]
    );
    res.status(201).json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al crear ruta:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Listar rutas (paginado, filtro por categoría, búsqueda por texto)
router.get('/', async (req, res) => {
  try {
    const { page = 1, limit = 10, categoria_id, search } = req.query;
    let where = "WHERE estado = 'publicada'";
    const params = [];
    let idx = 1;
    if (categoria_id) {
      where += ` AND categoria_id = $${idx}`;
      params.push(categoria_id);
      idx++;
    }
    if (search) {
      where += ` AND (to_tsvector('spanish', titulo) @@ plainto_tsquery('spanish', $${idx}) OR to_tsvector('spanish', descripcion) @@ plainto_tsquery('spanish', $${idx}))`;
      params.push(search);
      idx++;
    }
    const offset = (parseInt(page) - 1) * parseInt(limit);
    const result = await query(
      `SELECT * FROM rutas ${where} ORDER BY created_at DESC LIMIT $${idx} OFFSET $${idx + 1}`,
      [...params, limit, offset]
    );
    res.json({ success: true, data: result.rows });
  } catch (error) {
    logger.error('Error al listar rutas:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Obtener ruta por ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const result = await query('SELECT * FROM rutas WHERE id = $1', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Ruta no encontrada' });
    }
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al obtener ruta:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Actualizar ruta (solo creador o admin)
router.put('/:id', authenticateToken, validateRequest(createRouteSchema), async (req, res) => {
  try {
    const { id } = req.params;
    const { titulo, descripcion, categoria_id, duracion_estimada, distancia_km, dificultad, coordenadas, puntos_acceso, nivel_accesibilidad, imagenes } = req.body;
    // Verificar permisos (solo creador o admin)
    const ruta = await query('SELECT creador_id FROM rutas WHERE id = $1', [id]);
    if (ruta.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Ruta no encontrada' });
    }
    if (req.user.rol !== 'admin' && req.user.userId !== ruta.rows[0].creador_id) {
      return res.status(403).json({ success: false, message: 'No autorizado' });
    }
    const lineString = createLineString(coordenadas);
    const result = await query(
      `UPDATE rutas SET titulo = $1, descripcion = $2, categoria_id = $3, duracion_estimada = $4, distancia_km = $5, dificultad = $6, coordenadas = ST_GeomFromText($7, 4326), puntos_acceso = $8, nivel_accesibilidad = $9, imagenes = $10, updated_at = CURRENT_TIMESTAMP WHERE id = $11 RETURNING *`,
      [titulo, descripcion, categoria_id, duracion_estimada, distancia_km, dificultad, lineString, puntos_acceso || {}, nivel_accesibilidad || {}, imagenes || [], id]
    );
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    logger.error('Error al actualizar ruta:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Eliminar ruta (solo creador o admin)
router.delete('/:id', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    const ruta = await query('SELECT creador_id FROM rutas WHERE id = $1', [id]);
    if (ruta.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Ruta no encontrada' });
    }
    if (req.user.rol !== 'admin' && req.user.userId !== ruta.rows[0].creador_id) {
      return res.status(403).json({ success: false, message: 'No autorizado' });
    }
    await query('DELETE FROM rutas WHERE id = $1', [id]);
    res.json({ success: true, message: 'Ruta eliminada correctamente' });
  } catch (error) {
    logger.error('Error al eliminar ruta:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Buscar rutas cercanas a un punto
router.get('/cercanas/:lat/:lng', async (req, res) => {
  try {
    const { lat, lng } = req.params;
    const { radio = 5000 } = req.query;
    const rutas = await findNearbyRoutes(parseFloat(lat), parseFloat(lng), parseInt(radio));
    res.json({ success: true, data: rutas });
  } catch (error) {
    logger.error('Error al buscar rutas cercanas:', error);
    res.status(500).json({ success: false, message: 'Error interno del servidor' });
  }
});

// Rutas de autenticación
router.use('/auth', authRoutes);

// Rutas de usuarios
router.use('/users', userRoutes);

// Rutas de rutas turísticas
router.use('/routes', routeRoutes);

// Rutas de puntos de interés
router.use('/poi', poiRoutes);

// Rutas de auditorías
router.use('/audits', auditRoutes);

// Rutas de evaluaciones
router.use('/evaluations', evaluationRoutes);

// Rutas de reportes
router.use('/reports', reportRoutes);

// Rutas de categorías
router.use('/categories', categoryRoutes);

// Rutas de subida de archivos
router.use('/upload', uploadRoutes);

// Rutas de estadísticas
router.use('/stats', statsRoutes);

// Rutas de notificaciones
router.use('/notifications', notificationRoutes);

// Rutas de búsqueda
router.use('/search', searchRoutes);

module.exports = router; 