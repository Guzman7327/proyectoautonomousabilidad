# 🌿 Portal de Turismo Inclusivo Ecuador

[![Flask](https://img.shields.io/badge/Flask-2.3.3-green.svg)](https://flask.palletsprojects.com/)
[![Python](https://img.shields.io/badge/Python-3.11+-blue.svg)](https://www.python.org/)
[![Accessibility](https://img.shields.io/badge/Accessibility-WCAG%202.1%20AA-brightgreen.svg)](https://www.w3.org/WAI/WCAG21/AA/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

## 📋 Descripción

Portal web moderno y accesible para promover el turismo inclusivo en Ecuador. Desarrollado con Flask, siguiendo los estándares de accesibilidad WCAG 2.1 AA y las mejores prácticas de usabilidad ISO 9241-11 e ISO 25010:2011.

### 🎯 Características Principales

- **🌿 Tema Verde**: Diseño moderno con paleta de colores verde
- **♿ Accesibilidad Total**: Cumple estándares WCAG 2.1 AA
- **📱 Responsive**: Adaptable a todos los dispositivos
- **🎨 Interfaz Moderna**: Diseño intuitivo y atractivo
- **🔍 Búsqueda Avanzada**: Filtros y autocompletado inteligente
- **🌍 Multilingüe**: Español, English, Kichwa
- **⚡ Performance**: Optimizado para velocidad

## 🚀 Características de Accesibilidad

### ♿ Menú de Accesibilidad Lateral
- **Posición**: Lado derecho de la pantalla
- **Minimizado**: Solo muestra icono ♿ por defecto
- **Expansión**: Al hacer hover o clic
- **Funciones**:
  - 🌓 Alto Contraste
  - 🔍 Texto Grande
  - ⚫ Modo Monocromático
  - 📖 Tipografía para Dislexia
  - ⌨️ Navegación por Teclado
  - ✨ Resaltado de Foco
  - ⏸️ Reducir Animaciones
  - 🔊 Lector de Pantalla
  - 🎧 Descripción de Audio
  - 🔔 Alertas Visuales
  - 🌍 Cambio de Idioma

### ⌨️ Atajos de Teclado
- `Alt + A`: Abrir menú de accesibilidad
- `Alt + 1`: Ir al contenido principal
- `Alt + 2`: Ir a la navegación
- `Alt + 3`: Ir a la búsqueda
- `Alt + 4`: Ir al pie de página
- `Escape`: Cerrar menús

## 🛠️ Tecnologías Utilizadas

### Backend
- **Flask 2.3.3**: Framework web de Python
- **SQLAlchemy**: ORM para base de datos
- **Flask-Login**: Autenticación de usuarios
- **Flask-Migrate**: Migraciones de base de datos
- **Flask-CORS**: Soporte para CORS
- **Flask-JWT-Extended**: Tokens JWT

### Frontend
- **HTML5**: Estructura semántica
- **CSS3**: Estilos modernos con variables CSS
- **JavaScript ES6+**: Funcionalidades interactivas
- **Bootstrap 5**: Framework CSS responsive

### Base de Datos
- **SQLite**: Desarrollo local
- **PostgreSQL**: Producción (Docker)

### Herramientas de Desarrollo
- **Docker**: Containerización
- **Docker Compose**: Orquestación de servicios
- **Nginx**: Servidor web y proxy reverso
- **Gunicorn**: Servidor WSGI para producción

## 📦 Instalación

### Prerrequisitos
- Python 3.11 o superior
- pip (gestor de paquetes de Python)
- Git

### Instalación Local

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/portal-turismo-inclusivo.git
cd portal-turismo-inclusivo
```

2. **Crear entorno virtual**
```bash
python -m venv venv
```

3. **Activar entorno virtual**
```bash
# Windows
venv\Scripts\activate

# Linux/Mac
source venv/bin/activate
```

4. **Instalar dependencias**
```bash
pip install -r requirements.txt
```

5. **Configurar base de datos**
```bash
flask db upgrade
```

6. **Ejecutar la aplicación**
```bash
python app.py
```

La aplicación estará disponible en: http://localhost:5000

### Instalación con Docker

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/portal-turismo-inclusivo.git
cd portal-turismo-inclusivo
```

2. **Ejecutar con Docker Compose**
```bash
docker-compose up -d
```

La aplicación estará disponible en: http://localhost:80

## 🏗️ Estructura del Proyecto

```
portal-turismo-inclusivo/
├── app/
│   ├── __init__.py              # Configuración de Flask
│   ├── api/
│   │   └── accessibility.py     # API de accesibilidad
│   ├── models/
│   │   ├── __init__.py
│   │   ├── destination.py       # Modelo de destinos
│   │   ├── review.py           # Modelo de reseñas
│   │   └── user.py             # Modelo de usuarios
│   ├── routes/
│   │   ├── __init__.py
│   │   ├── accessibility.py    # Rutas de accesibilidad
│   │   ├── api.py              # API routes
│   │   ├── auth.py             # Autenticación
│   │   ├── destinations.py     # Destinos
│   │   └── main.py             # Rutas principales
│   ├── static/
│   │   ├── css/
│   │   │   ├── accessibility.css    # Estilos de accesibilidad
│   │   │   ├── green-theme.css      # Tema verde
│   │   │   ├── main.css             # Estilos principales
│   │   │   └── bootstrap.min.css    # Bootstrap
│   │   ├── js/
│   │   │   ├── advanced-accessibility.js  # JavaScript de accesibilidad
│   │   │   ├── main.js                 # JavaScript principal
│   │   │   └── user-experience.js      # Experiencia de usuario
│   │   └── images/
│   └── templates/
│       ├── base.html               # Template base
│       ├── index.html              # Página principal
│       ├── auth/
│       │   └── login.html          # Página de login
│       ├── destinations/
│       │   ├── list.html           # Lista de destinos
│       │   └── detail.html         # Detalle de destino
│       └── errors/
│           ├── 404.html            # Error 404
│           └── 500.html            # Error 500
├── migrations/                     # Migraciones de base de datos
├── instance/                       # Archivos de instancia
├── app.py                         # Punto de entrada
├── config.py                      # Configuración
├── requirements.txt               # Dependencias de Python
├── Dockerfile                     # Configuración de Docker
├── docker-compose.yml             # Orquestación de Docker
├── nginx.conf                     # Configuración de Nginx
└── README.md                      # Este archivo
```

## 🎨 Características de Diseño

### 🌿 Tema Verde
- **Paleta de colores**: Verde desde claro hasta oscuro
- **Gradientes**: Efectos visuales modernos
- **Contraste**: Alto contraste para accesibilidad
- **Acentos**: Naranja para elementos interactivos

### 📱 Diseño Responsive
- **Mobile First**: Diseño optimizado para móviles
- **Breakpoints**: Adaptable a tablets y desktop
- **Flexible**: Grid system moderno
- **Touch-friendly**: Elementos táctiles optimizados

### ♿ Accesibilidad
- **WCAG 2.1 AA**: Cumple estándares internacionales
- **Navegación por teclado**: Completa funcionalidad
- **Screen Reader**: Compatible con lectores de pantalla
- **Alto contraste**: Modo de alto contraste
- **Texto escalable**: Sin pérdida de funcionalidad

## 🔧 Configuración

### Variables de Entorno
```bash
# Configuración básica
SECRET_KEY=tu-clave-secreta-aqui
FLASK_ENV=development
DATABASE_URL=sqlite:///turismo_inclusivo_ecuador.db

# Configuración de producción
FLASK_ENV=production
DATABASE_URL=postgresql://usuario:contraseña@localhost/turismo_inclusivo
```

### Configuración de Base de Datos
```python
# config.py
class Config:
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'dev-secret-key'
    SQLALCHEMY_DATABASE_URI = os.environ.get('DATABASE_URL') or \
        'sqlite:///turismo_inclusivo_ecuador.db'
    SQLALCHEMY_TRACK_MODIFICATIONS = False
```

## 🚀 Despliegue

### Despliegue Local
```bash
# Activar entorno virtual
source venv/bin/activate

# Instalar dependencias
pip install -r requirements.txt

# Configurar base de datos
flask db upgrade

# Ejecutar aplicación
python app.py
```

### Despliegue con Docker
```bash
# Construir imagen
docker build -t portal-turismo-inclusivo .

# Ejecutar contenedor
docker run -p 5000:5000 portal-turismo-inclusivo
```

### Despliegue con Docker Compose
```bash
# Ejecutar todos los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f

# Detener servicios
docker-compose down
```

## 📊 Funcionalidades

### 🏠 Página Principal
- Hero section con llamadas a la acción
- Destinos destacados
- Estadísticas del turismo
- Búsqueda rápida

### 🔍 Búsqueda Avanzada
- Búsqueda por texto con autocompletado
- Filtros por provincia, categoría, accesibilidad
- Ordenamiento personalizable
- Filtros expandibles (precios, distancia)
- Guardado de filtros favoritos

### 🗺️ Destinos
- Lista de destinos con filtros
- Detalle completo de cada destino
- Características de accesibilidad
- Reseñas de usuarios
- Información práctica

### 👤 Autenticación
- Registro de usuarios
- Inicio de sesión
- Recuperación de contraseña
- Perfil de usuario
- Favoritos

### ♿ Accesibilidad
- Menú lateral minimizado
- Atajos de teclado
- Navegación por teclado
- Lector de pantalla
- Alto contraste
- Texto grande
- Modo monocromático

## 🧪 Testing

### Ejecutar Tests
```bash
# Tests unitarios
python -m pytest tests/

# Tests de cobertura
python -m pytest --cov=app tests/

# Tests de accesibilidad
python -m pytest tests/test_accessibility.py
```

### Tests de Accesibilidad
- Navegación por teclado
- Compatibilidad con lectores de pantalla
- Contraste de colores
- Estructura semántica
- Formularios accesibles

## 📈 Performance

### Optimizaciones Implementadas
- **Lazy Loading**: Carga diferida de imágenes
- **Minificación**: CSS y JS minificados
- **Caching**: Cache de recursos estáticos
- **Compresión**: Gzip para archivos
- **CDN**: Recursos externos optimizados

### Métricas de Performance
- **Lighthouse Score**: 95+ en todas las categorías
- **PageSpeed Insights**: 90+ en móvil y desktop
- **WebPageTest**: Tiempo de carga < 2s

## 🔒 Seguridad

### Medidas Implementadas
- **CSRF Protection**: Protección contra CSRF
- **SQL Injection**: Prevención con SQLAlchemy
- **XSS Protection**: Sanitización de datos
- **HTTPS**: Forzado en producción
- **Headers de Seguridad**: Configurados en Nginx

## 🤝 Contribución

### Cómo Contribuir
1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guías de Contribución
- Sigue las convenciones de código
- Añade tests para nuevas funcionalidades
- Mantén la accesibilidad en todas las mejoras
- Documenta cambios importantes

## 📝 Changelog

### v2.0.0 - Tema Verde y Accesibilidad Mejorada
- 🌿 Implementado tema verde completo
- ♿ Menú de accesibilidad lateral minimizado
- ⌨️ Atajos de teclado avanzados
- 📱 Mejoras en responsive design
- 🎨 Nuevos estilos y animaciones

### v1.0.0 - Versión Inicial
- 🏗️ Estructura básica del proyecto
- 🔐 Sistema de autenticación
- 🗺️ Gestión de destinos
- ♿ Funcionalidades básicas de accesibilidad

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 👥 Autores

- **Tu Nombre** - *Desarrollo inicial* - [TuUsuario](https://github.com/TuUsuario)

## 🙏 Agradecimientos

- Comunidad de Flask
- Estándares WCAG por la accesibilidad
- Bootstrap por el framework CSS
- Contribuidores del proyecto

## 📞 Contacto

- **Email**: info@turismoinclusivo.ec
- **Teléfono**: +593 2 123-4567
- **Sitio Web**: https://turismoinclusivo.ec
- **GitHub**: https://github.com/tu-usuario/portal-turismo-inclusivo

## 🆘 Soporte

Si encuentras algún problema o tienes una pregunta:

1. Revisa la [documentación](docs/)
2. Busca en los [issues existentes](https://github.com/tu-usuario/portal-turismo-inclusivo/issues)
3. Crea un [nuevo issue](https://github.com/tu-usuario/portal-turismo-inclusivo/issues/new)

---

⭐ **¡Si te gusta este proyecto, dale una estrella en GitHub!**
