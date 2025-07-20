# Portal Turístico Ecuador - Diseño Moderno y Accesible

## 🎨 Nuevo Diseño Implementado

Este proyecto ha sido completamente rediseñado con un enfoque moderno, atractivo y accesible. El nuevo diseño incluye:

### ✨ Características del Nuevo Diseño

#### 🎯 **Diseño Moderno y Profesional**
- **Paleta de colores moderna**: Azul primario (#2563eb) con acentos dorados
- **Tipografía elegante**: Inter para texto y Playfair Display para títulos
- **Gradientes atractivos**: Fondo degradado azul-púrpura
- **Sombras y efectos**: Sistema de sombras consistente y efectos hover suaves

#### 📱 **Responsive Design**
- **Mobile-first**: Optimizado para dispositivos móviles
- **Grid layouts**: Uso de CSS Grid para layouts flexibles
- **Breakpoints inteligentes**: Adaptación automática a diferentes tamaños de pantalla

#### ♿ **Accesibilidad Mejorada**
- **Alto contraste**: Modo de alto contraste para usuarios con discapacidad visual
- **Navegación por teclado**: Soporte completo para navegación sin mouse
- **Screen readers**: Etiquetas ARIA y estructura semántica
- **Zoom y texto**: Controles para aumentar/disminuir texto
- **Lupa puntual**: Herramienta de zoom para usuarios con baja visión

#### 🎬 **Elementos Interactivos**
- **Banner rotativo**: Carrusel automático con controles
- **Mapa interactivo**: Integración con Leaflet para mostrar destinos
- **Modales elegantes**: Ventanas emergentes con animaciones suaves
- **Formularios modernos**: Validación en tiempo real y feedback visual

### 🏗️ **Estructura del Proyecto**

```
Funcional/
├── backend/
│   ├── app.py                 # Aplicación Flask principal
│   ├── static/
│   │   ├── css/
│   │   │   └── style.css      # Estilos modernos completos
│   │   ├── js/
│   │   │   └── accesibilidad.js # Funciones de accesibilidad
│   │   └── img/               # Imágenes optimizadas
│   └── templates/             # Plantillas HTML modernas
│       ├── index.html         # Página principal rediseñada
│       ├── login.html         # Formulario de login moderno
│       ├── registro.html      # Formulario de registro mejorado
│       └── contacto.html      # Formulario de contacto elegante
└── README.md                  # Este archivo
```

### 🚀 **Enlaces de Formularios**

Los formularios principales están disponibles en las siguientes rutas:

| Formulario | URL | Descripción |
|------------|-----|-------------|
| **Registro de Usuario** | `/registro` | Crear cuenta nueva |
| **Inicio de Sesión** | `/login` | Acceder al sistema |
| **Contacto** | `/contacto` | Enviar mensajes |
| **Registro Admin** | `/registro_admin` | Crear cuenta de administrador |
| **Recuperar Contraseña** | `/recuperar` | Recuperar acceso |
| **Guardar Destino** | `/guardar_registro` | Añadir destino turístico |
| **Editar Destino** | `/editar_registro` | Modificar destino existente |
| **Búsqueda Avanzada** | `/busqueda_avanzada` | Buscar destinos |
| **Formularios Múltiples** | `/formularios` | Todos los formularios en una página |

### 🎨 **Componentes de Diseño**

#### **Header Moderno**
- Logo con tipografía elegante
- Navegación principal responsive
- Menú de accesibilidad integrado
- Botones de acción destacados

#### **Hero Section**
- Título impactante con tipografía serif
- Subtítulo descriptivo
- Botones de acción principales
- Animaciones de entrada

#### **Banner Rotativo**
- Carrusel automático de imágenes
- Controles de navegación
- Overlay con información
- Transiciones suaves

#### **Destinos Destacados**
- Cards modernos con hover effects
- Imágenes optimizadas
- Información estructurada
- Botones de acción

#### **Formularios Modernos**
- Diseño limpio y minimalista
- Validación en tiempo real
- Mensajes de error claros
- Campos accesibles

### 🛠️ **Tecnologías Utilizadas**

- **Backend**: Flask (Python)
- **Frontend**: HTML5, CSS3, JavaScript
- **Base de Datos**: PostgreSQL
- **Mapas**: Leaflet.js
- **Fuentes**: Google Fonts (Inter + Playfair Display)
- **Iconos**: Emoji nativos + CSS

### 🎯 **Características de Accesibilidad**

#### **Navegación**
- Skip links para saltar al contenido principal
- Navegación por teclado completa
- Indicadores de foco visibles
- Estructura semántica HTML5

#### **Visual**
- Alto contraste opcional
- Modo monocromático
- Control de tamaño de texto
- Espaciado ajustable

#### **Auditivo**
- Transcripciones de video
- Subtítulos integrados
- Alertas visuales
- Descripciones de audio

### 📱 **Responsive Breakpoints**

- **Mobile**: < 480px
- **Tablet**: 480px - 768px
- **Desktop**: > 768px

### 🚀 **Cómo Ejecutar**

1. **Instalar dependencias**:
   ```bash
   pip install flask flask-cors psycopg2-binary bcrypt PyJWT
   ```

2. **Configurar base de datos**:
   - Crear base de datos PostgreSQL
   - Ejecutar `init.sql`

3. **Ejecutar aplicación**:
   ```bash
   cd backend
   python app.py
   ```

4. **Acceder al portal**:
   - Abrir http://localhost:5000

### 🎨 **Personalización**

El diseño utiliza variables CSS para fácil personalización:

```css
:root {
  --primary-color: #2563eb;    /* Color principal */
  --accent-color: #f59e0b;     /* Color de acento */
  --font-sans: 'Inter';        /* Fuente sans-serif */
  --font-serif: 'Playfair Display'; /* Fuente serif */
}
```

### 📞 **Soporte**

Para soporte técnico o consultas sobre el diseño:
- 📧 Email: info@turismoecuador.com
- 📞 Teléfono: +593 2 234 5678

---

**© 2024 Portal Turístico Ecuador** - Diseño moderno y accesible para todos los usuarios. 

# ✅ Checklist de Usabilidad y Accesibilidad

---

## 1. ACCESIBILIDAD Y USABILIDAD DEL SISTEMA

| Buenas Prácticas | Cumplido (S/N) | Comentario |
|------------------|:-------------:|:-----------|
| Modo alto contraste | ✅ | Implementado |
| Modo monocromático/inversión de color | ✅ | Implementado |
| Aumentar/disminuir tamaño de texto | ✅ | Implementado |
| Ajustar espaciado (línea, palabra, carácter) | ✅ | Implementado |
| Tipografía dislexia-friendly | ✅ | Implementado |
| Zoom de página / lupa puntual | ✅ | Implementado |
| Resaltar foco y enlaces | ✅ | Implementado |
| Navegación por teclado y skiplinks | ✅ | Implementado |
| Pausar/ocultar animaciones | ✅ | Implementado |
| Lector de pantalla integrado (texto a voz) | ✅ | Implementado |
| Descripción de audio | ✅ | Implementado |
| Menú consistente y jerárquico | ✅ | Implementado |
| Iconos y texto en menú | ✅ | Implementado |
| Responsive y compatible con lectores de pantalla | ✅ | Implementado |
| Feedback visual (hover, foco, animaciones) | ✅ | Implementado |
| Soporte multilingüe | ✅ | Español/Inglés |

---

## 2. FORMULARIOS Y FLUJOS PRINCIPALES

| Buenas Prácticas | Cumplido (S/N) | Comentario |
|------------------|:-------------:|:-----------|
| Etiquetas claras y visibles en todos los campos | ✅ | Implementado |
| Validación en tiempo real y mensajes de error específicos | ✅ | Implementado |
| Botones claros y comprensibles (“Enviar”, “Cancelar”, etc.) | ✅ | Implementado |
| Feedback visual al guardar/enviar (mensajes, animaciones) | ✅ | Implementado |
| Confirmación previa en acciones críticas (editar email, etc.) | ✅ | Implementado |
| Prevención de intentos múltiples (captcha/alerta) | ✅ | Implementado |
| Autofocus en campos clave | ✅ | Implementado |
| Autocompletado y sugerencias | ✅ | Implementado |
| Agrupación lógica con fieldset/legend | ✅ | Implementado |
| Mensaje visible si no hay resultados | ✅ | Implementado |
| Diseño responsive y accesible | ✅ | Implementado |
| Captcha o verificación antispam en contacto/login | ✅ | Implementado |
| Mensaje de “procesando” en formularios | ✅ | Implementado |
| No pedir más datos de los necesarios | ✅ | Implementado |

---

**Este checklist demuestra el cumplimiento de las mejores prácticas de usabilidad y accesibilidad, alineado con ISO 9241-11 e ISO 25010:2011, y es ideal para auditorías, presentaciones y documentación de calidad del proyecto.** 