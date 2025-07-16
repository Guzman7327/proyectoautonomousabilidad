const express = require('express');
const { query } = require('../config/database');
const { logger } = require('../utils/logger');

const router = express.Router();

// Búsqueda general
router.get('/', async (req, res) => {
  try {
    const { 
      q = '', 
      type = 'all', 
      category, 
      accessibility_level,
      distance_min,
      distance_max,
      duration_min,
      duration_max,
      lat,
      lng,
      radius = 50,
      page = 1,
      limit = 10
    } = req.query;

    const offset = (page - 1) * limit;
    let results = [];
    let totalCount = 0;

    // Búsqueda de rutas
    if (type === 'all' || type === 'routes') {
      let routeQuery = `
        SELECT 
          'route' as type,
          id,
          nombre,
          descripcion,
          distancia_km,
          duracion_estimada,
          nivel_dificultad,
          nivel_accesibilidad,
          estado,
          created_at,
          ST_AsGeoJSON(geom) as geometry
        FROM rutas 
        WHERE estado = 'publicada'
      `;
      
      const routeParams = [];
      let paramIndex = 1;

      if (q) {
        routeQuery += ` AND (nombre ILIKE $${paramIndex} OR descripcion ILIKE $${paramIndex})`;
        routeParams.push(`%${q}%`);
        paramIndex++;
      }

      if (category) {
        routeQuery += ` AND categoria_id = $${paramIndex}`;
        routeParams.push(category);
        paramIndex++;
      }

      if (accessibility_level) {
        routeQuery += ` AND nivel_accesibilidad = $${paramIndex}`;
        routeParams.push(accessibility_level);
        paramIndex++;
      }

      if (distance_min) {
        routeQuery += ` AND distancia_km >= $${paramIndex}`;
        routeParams.push(distance_min);
        paramIndex++;
      }

      if (distance_max) {
        routeQuery += ` AND distancia_km <= $${paramIndex}`;
        routeParams.push(distance_max);
        paramIndex++;
      }

      if (duration_min) {
        routeQuery += ` AND duracion_estimada >= $${paramIndex}`;
        routeParams.push(duration_min);
        paramIndex++;
      }

      if (duration_max) {
        routeQuery += ` AND duracion_estimada <= $${paramIndex}`;
        routeParams.push(duration_max);
        paramIndex++;
      }

      // Búsqueda por proximidad geográfica
      if (lat && lng) {
        routeQuery += ` AND ST_DWithin(
          geom, 
          ST_SetSRID(ST_MakePoint($${paramIndex}, $${paramIndex + 1}), 4326), 
          $${paramIndex + 2}
        )`;
        routeParams.push(lng, lat, radius * 1000); // Convertir km a metros
        paramIndex += 3;
      }

      routeQuery += ` ORDER BY created_at DESC LIMIT $${paramIndex} OFFSET $${paramIndex + 1}`;
      routeParams.push(limit, offset);

      const routeResults = await query(routeQuery, routeParams);
      results = results.concat(routeResults.rows);
    }

    // Búsqueda de puntos de interés
    if (type === 'all' || type === 'poi') {
      let poiQuery = `
        SELECT 
          'poi' as type,
          id,
          nombre,
          descripcion,
          tipo,
          nivel_accesibilidad,
          estado,
          created_at,
          ST_AsGeoJSON(geom) as geometry
        FROM puntos_interes 
        WHERE estado = 'activo'
      `;
      
      const poiParams = [];
      let paramIndex = 1;

      if (q) {
        poiQuery += ` AND (nombre ILIKE $${paramIndex} OR descripcion ILIKE $${paramIndex})`;
        poiParams.push(`%${q}%`);
        paramIndex++;
      }

      if (category) {
        poiQuery += ` AND categoria_id = $${paramIndex}`;
        poiParams.push(category);
        paramIndex++;
      }

      if (accessibility_level) {
        poiQuery += ` AND nivel_accesibilidad = $${paramIndex}`;
        poiParams.push(accessibility_level);
        paramIndex++;
      }

      // Búsqueda por proximidad geográfica
      if (lat && lng) {
        poiQuery += ` AND ST_DWithin(
          geom, 
          ST_SetSRID(ST_MakePoint($${paramIndex}, $${paramIndex + 1}), 4326), 
          $${paramIndex + 2}
        )`;
        poiParams.push(lng, lat, radius * 1000);
        paramIndex += 3;
      }

      poiQuery += ` ORDER BY created_at DESC LIMIT $${paramIndex} OFFSET $${paramIndex + 1}`;
      poiParams.push(limit, offset);

      const poiResults = await query(poiQuery, poiParams);
      results = results.concat(poiResults.rows);
    }

    // Contar total de resultados
    let countQuery = '';
    const countParams = [];

    if (type === 'routes') {
      countQuery = `
        SELECT COUNT(*) as total FROM rutas 
        WHERE estado = 'publicada'
      `;
    } else if (type === 'poi') {
      countQuery = `
        SELECT COUNT(*) as total FROM puntos_interes 
        WHERE estado = 'activo'
      `;
    } else {
      countQuery = `
        SELECT (
          (SELECT COUNT(*) FROM rutas WHERE estado = 'publicada') +
          (SELECT COUNT(*) FROM puntos_interes WHERE estado = 'activo')
        ) as total
      `;
    }

    const countResult = await query(countQuery, countParams);
    totalCount = parseInt(countResult.rows[0].total);

    res.json({
      success: true,
      data: {
        results,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: totalCount,
          pages: Math.ceil(totalCount / limit)
        },
        filters: {
          query: q,
          type,
          category,
          accessibility_level,
          distance_min,
          distance_max,
          duration_min,
          duration_max,
          lat,
          lng,
          radius
        }
      }
    });
  } catch (error) {
    logger.error('Error en búsqueda:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

// Búsqueda de sugerencias (autocompletado)
router.get('/suggestions', async (req, res) => {
  try {
    const { q = '', type = 'all' } = req.query;
    
    if (!q || q.length < 2) {
      return res.json({
        success: true,
        data: { suggestions: [] }
      });
    }

    let suggestions = [];

    if (type === 'all' || type === 'routes') {
      const routeSuggestions = await query(`
        SELECT DISTINCT nombre as text, 'route' as type
        FROM rutas 
        WHERE estado = 'publicada' AND nombre ILIKE $1
        LIMIT 5
      `, [`%${q}%`]);
      
      suggestions = suggestions.concat(routeSuggestions.rows);
    }

    if (type === 'all' || type === 'poi') {
      const poiSuggestions = await query(`
        SELECT DISTINCT nombre as text, 'poi' as type
        FROM puntos_interes 
        WHERE estado = 'activo' AND nombre ILIKE $1
        LIMIT 5
      `, [`%${q}%`]);
      
      suggestions = suggestions.concat(poiSuggestions.rows);
    }

    res.json({
      success: true,
      data: { suggestions }
    });
  } catch (error) {
    logger.error('Error en búsqueda de sugerencias:', error);
    res.status(500).json({
      success: false,
      message: 'Error interno del servidor'
    });
  }
});

module.exports = router; 