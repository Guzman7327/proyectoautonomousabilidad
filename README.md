# 🏖️ Portal de Turismo Inclusivo

Un portal web completo para promover el turismo accesible e inclusivo, con enfoque en rutas accesibles y auditoría WCAG para personas con discapacidad.

## 🌟 Características Principales

### 🎯 Funcionalidades Core
- **Rutas Accesibles**: Gestión completa de rutas turísticas con información de accesibilidad
- **Puntos de Interés**: Catálogo de lugares accesibles con detalles específicos
- **Auditoría WCAG**: Sistema de evaluación de accesibilidad web
- **Evaluaciones de Usuarios**: Sistema de calificaciones y comentarios
- **Reportes**: Gestión de incidencias y mejoras

### 🔧 Tecnologías Utilizadas

#### Backend
- **Node.js** con **Express.js**
- **PostgreSQL** con **PostGIS** para datos geoespaciales
- **JWT** para autenticación
- **Swagger** para documentación API
- **Multer** para subida de archivos
- **Winston** para logging

#### Frontend
- **React 18** con **TypeScript**
- **Vite** como bundler
- **TailwindCSS** para estilos
- **React Hook Form** con **Yup** para validación
- **Axios** para comunicación con API
- **Mapbox** para mapas interactivos

#### DevOps & Infraestructura
- **Docker** y **Docker Compose**
- **GitHub Actions** para CI/CD
- **Nginx** como reverse proxy
- **PostGIS** para funcionalidades geoespaciales

## 🚀 Inicio Rápido

### Prerrequisitos
- **Node.js** 18+ 
- **Docker** y **Docker Compose**
- **Git**

### Instalación Automática
```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/portal-turismo-inclusivo.git
cd portal-turismo-inclusivo

# Ejecutar script de configuración automática
./scripts/setup.sh
```

### Instalación Manual

#### 1. Configurar Backend
```bash
cd backend
npm install
cp env.example .env
# Editar .env con tus configuraciones
npm run dev
```

#### 2. Configurar Frontend
```bash
cd frontend
npm install
cp env.example .env
# Editar .env con tu token de Mapbox
npm run dev
```

#### 3. Configurar Base de Datos
```bash
# Con Docker Compose
docker-compose up -d postgres

# O instalar PostgreSQL localmente y ejecutar
psql -U postgres -d turismo_inclusivo -f database/init.sql
```

## 🐳 Docker

### Iniciar todo el stack
```bash
docker-compose up -d
```

### Servicios disponibles
- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:3001
- **Base de datos**: localhost:5432
- **Documentación API**: http://localhost:3001/api-docs

### Comandos útiles
```bash
# Ver logs
docker-compose logs -f

# Reconstruir imágenes
docker-compose build --no-cache

# Detener servicios
docker-compose down

# Limpiar volúmenes
docker-compose down -v
```

## 📚 Documentación API

La documentación completa de la API está disponible en:
- **Swagger UI**: http://localhost:3001/api-docs
- **OpenAPI Spec**: http://localhost:3001/api-docs.json

### Endpoints Principales

#### Autenticación
- `POST /api/auth/register` - Registro de usuarios
- `POST /api/auth/login` - Inicio de sesión
- `POST /api/auth/refresh` - Renovar token

#### Rutas Turísticas
- `GET /api/routes` - Listar rutas
- `POST /api/routes` - Crear ruta
- `GET /api/routes/:id` - Obtener ruta específica
- `PUT /api/routes/:id` - Actualizar ruta
- `DELETE /api/routes/:id` - Eliminar ruta

#### Puntos de Interés
- `GET /api/poi` - Listar puntos de interés
- `POST /api/poi` - Crear punto de interés
- `GET /api/poi/:id` - Obtener punto específico

#### Auditorías WCAG
- `GET /api/audits` - Listar auditorías
- `POST /api/audits` - Crear auditoría
- `GET /api/audits/:id` - Obtener auditoría específica

#### Búsqueda Avanzada
- `GET /api/search` - Búsqueda general
- `GET /api/search/suggestions` - Sugerencias de búsqueda

## 🏗️ Estructura del Proyecto

```
proyecto/
├── backend/                 # API Node.js/Express
│   ├── src/
│   │   ├── config/         # Configuración de BD
│   │   ├── middleware/     # Middlewares personalizados
│   │   ├── routes/         # Rutas de la API
│   │   ├── utils/          # Utilidades
│   │   └── server.js       # Servidor principal
│   ├── uploads/            # Archivos subidos
│   └── Dockerfile
├── frontend/               # Aplicación React
│   ├── src/
│   │   ├── api/           # Cliente API
│   │   ├── components/    # Componentes React
│   │   ├── hooks/         # Custom hooks
│   │   ├── pages/         # Páginas de la aplicación
│   │   └── i18n/          # Internacionalización
│   └── Dockerfile
├── database/              # Scripts de BD
│   └── init.sql
├── scripts/               # Scripts de automatización
├── nginx/                 # Configuración Nginx
├── docker-compose.yml     # Orquestación Docker
└── README.md
```

## 🔒 Seguridad

### Características de Seguridad Implementadas
- **Helmet.js** para headers de seguridad
- **Rate Limiting** para prevenir ataques
- **CORS** configurado apropiadamente
- **JWT** con expiración configurable
- **Validación** de entrada con Joi
- **Sanitización** de datos
- **Logging** de seguridad

### Variables de Entorno Requeridas

#### Backend (.env)
```env
DATABASE_URL=postgresql://user:pass@localhost:5432/turismo_inclusivo
JWT_SECRET=your-super-secret-key
JWT_EXPIRES_IN=7d
PORT=3001
NODE_ENV=development
CORS_ORIGIN=http://localhost:5173
```

#### Frontend (.env)
```env
VITE_API_URL=http://localhost:3001/api
VITE_MAPBOX_TOKEN=your-mapbox-token
VITE_APP_NAME=Portal de Turismo Inclusivo
```

## 🧪 Testing

### Backend
```bash
cd backend
npm test              # Ejecutar tests
npm run test:watch    # Tests en modo watch
npm run test:coverage # Tests con cobertura
```

### Frontend
```bash
cd frontend
npm test              # Ejecutar tests
npm run test:coverage # Tests con cobertura
```

## 📊 Monitoreo y Logs

### Logs del Backend
- **Winston** para logging estructurado
- Logs en archivo y consola
- Diferentes niveles: error, warn, info, debug

### Health Checks
- **Backend**: `GET /health`
- **Frontend**: `GET /health`
- **Docker**: Health checks configurados

## 🚀 Despliegue

### Producción con Docker
```bash
# Construir y desplegar
docker-compose -f docker-compose.prod.yml up -d

# Con variables de entorno de producción
NODE_ENV=production docker-compose up -d
```

### Variables de Entorno de Producción
```env
NODE_ENV=production
DATABASE_URL=postgresql://user:pass@db:5432/turismo_inclusivo
JWT_SECRET=production-secret-key
CORS_ORIGIN=https://tu-dominio.com
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guías de Contribución
- Sigue las convenciones de código establecidas
- Añade tests para nuevas funcionalidades
- Actualiza la documentación según sea necesario
- Verifica que todos los tests pasen antes de hacer PR

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para detalles.

## 👥 Equipo

- **Desarrollador Principal**: [Tu Nombre]
- **Diseñador UX/UI**: [Nombre del Diseñador]
- **Especialista en Accesibilidad**: [Nombre del Especialista]

## 🙏 Agradecimientos

- **Mapbox** por proporcionar mapas accesibles
- **WCAG Guidelines** por los estándares de accesibilidad
- **Comunidad de desarrolladores** por las librerías utilizadas

## 📞 Soporte

- **Email**: soporte@turismo-inclusivo.com
- **Issues**: [GitHub Issues](https://github.com/tu-usuario/portal-turismo-inclusivo/issues)
- **Documentación**: [Wiki del Proyecto](https://github.com/tu-usuario/portal-turismo-inclusivo/wiki)

---

**¡Hagamos el turismo accesible para todos! 🌍♿** 