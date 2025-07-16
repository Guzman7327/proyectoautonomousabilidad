const notFoundHandler = (req, res, next) => {
  res.status(404).json({
    success: false,
    message: `Ruta ${req.originalUrl} no encontrada`,
    method: req.method,
    timestamp: new Date().toISOString()
  });
};

module.exports = notFoundHandler; 