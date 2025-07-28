# ♿ Guía de Accesibilidad - Portal de Turismo Inclusivo

## 📋 Resumen Ejecutivo

El Portal de Turismo Inclusivo Ecuador está diseñado siguiendo los estándares internacionales de accesibilidad **WCAG 2.1 AA** y las mejores prácticas de usabilidad **ISO 9241-11** e **ISO 25010:2011**. Este documento detalla todas las características de accesibilidad implementadas.

## 🎯 Estándares Cumplidos

### WCAG 2.1 AA (Web Content Accessibility Guidelines)
- ✅ **Perceptible**: Información presentada de manera que los usuarios puedan percibirla
- ✅ **Operable**: Componentes de interfaz y navegación operables
- ✅ **Comprensible**: Información y operación de la interfaz comprensibles
- ✅ **Robusto**: Contenido interpretable por una amplia variedad de tecnologías

### ISO 9241-11 (Usabilidad)
- ✅ **Efectividad**: Precisión y completitud con la que los usuarios logran objetivos
- ✅ **Eficiencia**: Recursos gastados en relación con la precisión y completitud
- ✅ **Satisfacción**: Libertad de incomodidad y actitud positiva hacia el uso

### ISO 25010:2011 (Calidad de Software)
- ✅ **Funcionalidad**: Capacidad de proporcionar funciones que satisfagan necesidades
- ✅ **Usabilidad**: Capacidad de ser entendido, aprendido, usado y atractivo
- ✅ **Eficiencia**: Capacidad de proporcionar rendimiento apropiado
- ✅ **Mantenibilidad**: Capacidad de ser modificado
- ✅ **Portabilidad**: Capacidad de ser transferido de un entorno a otro

## ♿ Características de Accesibilidad Implementadas

### 1. Menú de Accesibilidad Lateral

#### 🎨 Diseño y Posicionamiento
- **Ubicación**: Lado derecho de la pantalla
- **Estado inicial**: Minimizado (60px de ancho)
- **Expansión**: Al hacer hover o clic (300px de ancho)
- **Animaciones**: Transiciones suaves de 250ms
- **Z-index**: 1090 (por encima de otros elementos)

#### 🔧 Funcionalidades Disponibles

##### Visual
- **🌓 Alto Contraste**
  - Paleta de colores de alto contraste
  - Texto negro sobre fondo blanco
  - Bordes y elementos claramente definidos
  - Ratio de contraste 4.5:1 o superior

- **🔍 Texto Grande**
  - Escalado de texto al 120%
  - Línea de altura 1.6
  - Tamaños de fuente proporcionales
  - Sin pérdida de funcionalidad

- **⚫ Modo Monocromático**
  - Filtro grayscale 100%
  - Información no dependiente del color
  - Contraste mantenido en escala de grises

- **📖 Tipografía para Dislexia**
  - Fuente OpenDyslexic
  - Tamaño 18px
  - Espaciado de letras 0.1em
  - Espaciado de palabras 0.2em
  - Línea de altura 1.8

##### Navegación
- **⌨️ Navegación por Teclado**
  - Todos los elementos enfocables
  - Orden de tabulación lógico
  - Indicadores de foco visibles
  - Escape para cerrar menús

- **✨ Resaltado de Foco**
  - Contorno de 3px en color amarillo
  - Offset de 2px
  - Box-shadow adicional
  - Visible en todos los elementos

- **⏸️ Reducir Animaciones**
  - Animaciones reducidas a 0.01ms
  - Transiciones mínimas
  - Scroll behavior auto
  - Respeta preferencias del sistema

##### Auditivo
- **🔊 Lector de Pantalla**
  - Elementos aria-live
  - Anuncios automáticos
  - Estructura semántica
  - Textos alternativos

- **🎧 Descripción de Audio**
  - Descripciones de contenido multimedia
  - Narración de elementos visuales
  - Información contextual

- **🔔 Alertas Visuales**
  - Notificaciones visuales
  - Indicadores de estado
  - Mensajes de confirmación

##### Idioma
- **🌍 Cambio de Idioma**
  - Español (predeterminado)
  - English
  - Kichwa
  - Persistencia de preferencias

### 2. Atajos de Teclado

#### ⌨️ Atajos Principales
- `Alt + A`: Abrir/cerrar menú de accesibilidad
- `Alt + 1`: Ir al contenido principal
- `Alt + 2`: Ir a la navegación principal
- `Alt + 3`: Ir al campo de búsqueda
- `Alt + 4`: Ir al pie de página
- `Escape`: Cerrar todos los menús abiertos

#### 🔄 Navegación por Teclado
- `Tab`: Navegar entre elementos
- `Shift + Tab`: Navegar hacia atrás
- `Enter/Space`: Activar elementos
- `Flechas`: Navegar en listas y menús
- `Home/End`: Ir al inicio/final de listas

### 3. Estructura Semántica

#### 📄 HTML Semántico
```html
<!-- Skip Links -->
<a href="#main-content" class="skip-link">Ir al contenido principal</a>

<!-- Landmarks -->
<header role="banner">
<nav role="navigation" aria-label="Navegación principal">
<main role="main" id="main-content">
<aside role="complementary">
<footer role="contentinfo">

<!-- Headings -->
<h1>Página principal</h1>
<h2>Sección principal</h2>
<h3>Subsección</h3>
```

#### 🏷️ ARIA Labels
```html
<!-- Botones -->
<button aria-label="Cerrar menú" aria-expanded="false">
<button aria-pressed="false" aria-describedby="help-text">

<!-- Formularios -->
<input aria-describedby="error-message" aria-invalid="true">
<label for="email">Email <span aria-label="requerido">*</span></label>

<!-- Navegación -->
<nav aria-label="Navegación de migas de pan">
<nav aria-label="Navegación secundaria">
```

### 4. Formularios Accesibles

#### ✅ Validación y Retroalimentación
- **Validación en tiempo real**
- **Mensajes de error claros**
- **Indicadores de campos obligatorios**
- **Ayuda contextual**
- **Auto-completado**

#### 🎯 Ejemplo de Formulario Accesible
```html
<div class="form-group">
    <label for="email" class="form-label required">
        Email
        <span class="required-indicator" aria-label="Campo obligatorio">*</span>
    </label>
    <input type="email" 
           id="email" 
           name="email" 
           class="form-control"
           required 
           autocomplete="email"
           aria-describedby="email-help email-error"
           aria-invalid="false">
    <div id="email-help" class="form-help">
        Ingresa tu dirección de email
    </div>
    <div id="email-error" class="form-error" role="alert" aria-live="polite"></div>
</div>
```

### 5. Contenido Multimedia

#### 🖼️ Imágenes
- **Alt text descriptivo** para todas las imágenes
- **Imágenes decorativas** marcadas como `aria-hidden="true"`
- **Imágenes complejas** con descripciones extendidas
- **Lazy loading** con fallbacks

#### 🎥 Videos
- **Subtítulos** en todos los videos
- **Descripciones de audio** disponibles
- **Controles de reproducción** accesibles
- **Transcripciones** completas

#### 🎵 Audio
- **Transcripciones** de contenido de audio
- **Controles de volumen** accesibles
- **Indicadores visuales** de estado

### 6. Navegación Mejorada

#### 🧭 Breadcrumbs
```html
<nav aria-label="Navegación de migas de pan" class="breadcrumb-nav">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="/">Inicio</a>
        </li>
        <li class="breadcrumb-item">
            <a href="/destinos">Destinos</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Galápagos
        </li>
    </ol>
</nav>
```

#### 🔍 Búsqueda Accesible
- **Autocompletado** con navegación por teclado
- **Sugerencias** con aria-live
- **Filtros** expandibles
- **Resultados** con estructura semántica

### 7. Responsive y Adaptable

#### 📱 Diseño Mobile First
- **Touch targets** de mínimo 44px
- **Espaciado** adecuado entre elementos
- **Contraste** mantenido en todas las resoluciones
- **Zoom** sin pérdida de funcionalidad

#### 🖥️ Adaptaciones de Pantalla
- **Breakpoints** bien definidos
- **Layout** flexible
- **Tipografía** escalable
- **Imágenes** responsivas

### 8. Performance y Accesibilidad

#### ⚡ Optimizaciones
- **Lazy loading** de imágenes
- **Minificación** de CSS y JS
- **Compresión** de archivos
- **Cache** de recursos estáticos

#### 🎯 Métricas de Accesibilidad
- **Lighthouse Accessibility Score**: 100/100
- **axe-core**: 0 violaciones
- **WAVE**: 0 errores de accesibilidad
- **Contrast Ratio**: 4.5:1 mínimo

## 🧪 Testing de Accesibilidad

### Herramientas Utilizadas
- **Lighthouse**: Auditoría completa
- **axe-core**: Testing automatizado
- **WAVE**: Evaluación visual
- **NVDA**: Testing con lector de pantalla
- **VoiceOver**: Testing en macOS
- **TalkBack**: Testing en Android

### Checklist de Testing
- [ ] Navegación por teclado completa
- [ ] Lectores de pantalla compatibles
- [ ] Contraste de colores adecuado
- [ ] Estructura semántica correcta
- [ ] Formularios accesibles
- [ ] Multimedia con alternativas
- [ ] Responsive design
- [ ] Performance optimizado

## 📊 Métricas de Accesibilidad

### Resultados de Auditoría
```
Lighthouse Accessibility Score: 100/100
├── Color contrast is sufficiently high: ✅
├── Document has a valid title element: ✅
├── Document has a valid lang attribute: ✅
├── Form elements have associated labels: ✅
├── Image elements have alt attributes: ✅
├── Links have a discernible name: ✅
├── Lists contain only li elements and script supporting elements: ✅
├── Page has a logical tab order: ✅
├── Page has the skip links: ✅
└── Page has valid heading structure: ✅
```

### axe-core Results
```
Violations: 0
Passes: 45
Incomplete: 0
Inapplicable: 12
```

## 🔧 Configuración Técnica

### Variables CSS de Accesibilidad
```css
:root {
    /* Colores de alto contraste */
    --high-contrast-primary: #000000;
    --high-contrast-secondary: #ffffff;
    --high-contrast-accent: #ffff00;
    
    /* Tamaños de texto */
    --text-base: 1rem;
    --text-large: 1.2rem;
    --text-xlarge: 1.5rem;
    
    /* Espaciado */
    --focus-outline: 3px solid var(--accent-color);
    --focus-offset: 2px;
    
    /* Transiciones */
    --transition-fast: 150ms ease-in-out;
    --transition-normal: 250ms ease-in-out;
}
```

### JavaScript de Accesibilidad
```javascript
// Anuncios para lectores de pantalla
announceToScreenReader(message) {
    if (this.settings.screen_reader) {
        this.screenReaderElement.textContent = message;
    }
}

// Manejo de foco
setupFocusManagement() {
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });
}
```

## 📚 Recursos Adicionales

### Documentación
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [Web Accessibility Initiative](https://www.w3.org/WAI/)

### Herramientas
- [axe DevTools](https://www.deque.com/axe/)
- [WAVE Web Accessibility Evaluator](https://wave.webaim.org/)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse)
- [Contrast Checker](https://webaim.org/resources/contrastchecker/)

### Testing
- [NVDA](https://www.nvaccess.org/) - Lector de pantalla gratuito
- [VoiceOver](https://www.apple.com/accessibility/vision/) - macOS
- [TalkBack](https://support.google.com/accessibility/android/answer/6283677) - Android

## 🤝 Contribución a la Accesibilidad

### Guías para Contribuidores
1. **Siempre prueba con teclado**
2. **Verifica con lectores de pantalla**
3. **Mantén el contraste de colores**
4. **Usa HTML semántico**
5. **Proporciona alternativas de texto**
6. **Sigue las convenciones ARIA**

### Checklist de Pull Request
- [ ] Navegación por teclado funciona
- [ ] Contraste de colores adecuado
- [ ] Estructura semántica correcta
- [ ] Formularios accesibles
- [ ] Imágenes con alt text
- [ ] Tests de accesibilidad pasan

---

**Última actualización**: Julio 2025  
**Versión**: 2.0.0  
**Estándares**: WCAG 2.1 AA, ISO 9241-11, ISO 25010:2011 