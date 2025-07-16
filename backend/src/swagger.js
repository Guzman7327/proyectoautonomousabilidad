const swaggerJSDoc = require('swagger-jsdoc');
const swaggerUi = require('swagger-ui-express');

const options = {
  definition: {
    openapi: '3.0.0',
    info: {
      title: 'Portal de Turismo Inclusivo - API',
      version: '1.0.0',
      description: 'Documentación de la API RESTful para el Portal de Turismo Inclusivo',
      contact: {
        name: 'Equipo Turismo Inclusivo',
        email: 'contacto@turismoinclusivo.com',
        url: 'https://turismoinclusivo.com'
      }
    },
    servers: [
      {
        url: 'http://localhost:3001/api',
        description: 'Servidor local'
      }
    ],
    components: {
      securitySchemes: {
        bearerAuth: {
          type: 'http',
          scheme: 'bearer',
          bearerFormat: 'JWT'
        }
      }
    },
    security: [{ bearerAuth: [] }]
  },
  apis: ['./src/routes/*.js'],
};

const swaggerSpec = swaggerJSDoc(options);

function setupSwagger(app) {
  app.use('/api-docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));
}

module.exports = setupSwagger; 