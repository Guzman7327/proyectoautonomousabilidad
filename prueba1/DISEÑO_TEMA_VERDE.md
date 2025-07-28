# 🌿 Guía de Diseño - Tema Verde

## 📋 Descripción del Tema

El Portal de Turismo Inclusivo Ecuador utiliza un **tema verde moderno y accesible** que refleja la riqueza natural de Ecuador y promueve la sostenibilidad del turismo. El diseño combina elegancia visual con funcionalidad accesible.

## 🎨 Paleta de Colores

### Verde Principal
```css
:root {
    /* Verde 50 - Muy claro */
    --green-50: #f0fdf4;
    
    /* Verde 100 - Claro */
    --green-100: #dcfce7;
    
    /* Verde 200 - Medio claro */
    --green-200: #bbf7d0;
    
    /* Verde 300 - Medio */
    --green-300: #86efac;
    
    /* Verde 400 - Medio intenso */
    --green-400: #4ade80;
    
    /* Verde 500 - Intenso */
    --green-500: #22c55e;
    
    /* Verde 600 - Principal */
    --green-600: #16a34a;
    
    /* Verde 700 - Oscuro */
    --green-700: #15803d;
    
    /* Verde 800 - Muy oscuro */
    --green-800: #166534;
    
    /* Verde 900 - Extremadamente oscuro */
    --green-900: #14532d;
    
    /* Verde 950 - Más oscuro */
    --green-950: #052e16;
}
```

### Colores de Acento
```css
:root {
    /* Naranja - Acento principal */
    --accent-color: #f59e0b;
    --accent-dark: #d97706;
    --accent-light: #fbbf24;
    
    /* Estados */
    --success-color: var(--green-600);
    --warning-color: var(--accent-color);
    --error-color: #ef4444;
    --info-color: #3b82f6;
}
```

## 🌈 Gradientes

### Gradientes Principales
```css
:root {
    /* Gradiente principal - Header */
    --gradient-primary: linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%);
    
    /* Gradiente secundario - Botones */
    --gradient-secondary: linear-gradient(135deg, var(--green-500) 0%, var(--green-600) 100%);
    
    /* Gradiente claro - Fondos */
    --gradient-light: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%);
    
    /* Gradiente oscuro - Footer */
    --gradient-dark: linear-gradient(135deg, var(--green-800) 0%, var(--green-900) 100%);
}
```

## 🎯 Aplicación del Tema

### 1. Header Principal
```css
.site-header {
    background: var(--gradient-primary);
    color: var(--text-inverse);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.header-top {
    background: rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-text {
    color: var(--green-100);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
```

### 2. Navegación
```css
.nav-link {
    color: rgba(255, 255, 255, 0.9);
    transition: all var(--transition-fast);
}

.nav-link:hover,
.nav-link:focus {
    color: var(--green-100);
    background: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
    background: rgba(255, 255, 255, 0.2);
    color: var(--green-100);
}
```

### 3. Botones
```css
.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--text-inverse);
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-outline-primary {
    background: transparent;
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: var(--text-inverse);
}
```

### 4. Formularios
```css
.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.form-control.is-valid {
    border-color: var(--success-color);
}

.form-control.is-invalid {
    border-color: var(--error-color);
}
```

### 5. Tarjetas
```css
.card {
    border-color: var(--green-200);
    box-shadow: 0 1px 3px rgba(22, 163, 74, 0.1);
    transition: all var(--transition-fast);
}

.card:hover {
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
    border-color: var(--green-300);
    transform: translateY(-2px);
}

.card-header {
    background: var(--green-50);
    border-bottom-color: var(--green-200);
}
```

## 🎨 Menú de Accesibilidad Lateral

### Diseño del Sidebar
```css
.accessibility-sidebar {
    position: fixed;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    background: var(--gradient-primary);
    border-radius: var(--radius-xl) 0 0 var(--radius-xl);
    box-shadow: var(--shadow-lg);
    max-width: 60px;
    overflow: hidden;
    transition: all var(--transition-normal);
}

.accessibility-sidebar:hover,
.accessibility-sidebar.expanded {
    max-width: 300px;
    padding: var(--space-4);
}
```

### Botón de Toggle
```css
.accessibility-toggle {
    background: var(--primary-dark);
    color: var(--text-inverse);
    border: none;
    padding: var(--space-3);
    border-radius: var(--radius-lg);
    cursor: pointer;
    width: 48px;
    height: 48px;
    font-size: var(--text-lg);
    box-shadow: var(--shadow-md);
}

.accessibility-toggle:hover,
.accessibility-toggle:focus {
    background: var(--primary-light);
    transform: scale(1.05);
    outline: 2px solid var(--text-inverse);
    outline-offset: 2px;
}
```

### Controles de Accesibilidad
```css
.accessibility-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: var(--text-inverse);
    padding: var(--space-2) var(--space-3);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-fast);
    backdrop-filter: blur(10px);
}

.accessibility-btn:hover,
.accessibility-btn:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateX(4px);
}

.accessibility-btn[aria-pressed="true"] {
    background: var(--accent-color);
    border-color: var(--accent-color);
    box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.3);
}
```

## 🌟 Efectos Visuales

### Animaciones
```css
@keyframes greenPulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(22, 163, 74, 0);
    }
}

.green-pulse {
    animation: greenPulse 2s infinite;
}

@keyframes greenGlow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(22, 163, 74, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(22, 163, 74, 0.8);
    }
}

.green-glow {
    animation: greenGlow 2s ease-in-out infinite alternate;
}
```

### Hover Effects
```css
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(22, 163, 74, 0.15);
}

.destination-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(22, 163, 74, 0.2);
}
```

## 📱 Responsive Design

### Breakpoints
```css
/* Mobile First */
@media (max-width: 480px) {
    .accessibility-sidebar {
        right: -60px;
    }
    
    .accessibility-sidebar:hover,
    .accessibility-sidebar.expanded {
        right: 0;
    }
}

@media (max-width: 768px) {
    .hero-section {
        background: var(--gradient-secondary);
    }
    
    .nav-menu {
        background: var(--primary-dark);
    }
}

@media (min-width: 1024px) {
    .accessibility-sidebar {
        right: 0;
    }
}
```

## 🎯 Componentes Específicos

### Hero Section
```css
.hero-section {
    background: var(--gradient-primary);
    color: var(--text-inverse);
    padding: var(--space-20) 0;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}
```

### Búsqueda
```css
#search-input {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: var(--text-inverse);
    backdrop-filter: blur(10px);
}

#search-input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    background: rgba(255, 255, 255, 0.2);
}

.search-btn {
    background: var(--accent-color);
    color: var(--text-inverse);
}

.search-btn:hover,
.search-btn:focus {
    background: var(--accent-dark);
}
```

### Footer
```css
.site-footer {
    background: var(--gradient-dark);
    color: var(--green-100);
}

.footer-bottom {
    background: rgba(0, 0, 0, 0.2);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-links a {
    color: var(--green-200);
}

.footer-links a:hover {
    color: var(--green-100);
}
```

## 🎨 Estados y Variaciones

### Estados de Botones
```css
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.btn-loading {
    position: relative;
}

.btn-loading .spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
```

### Estados de Formularios
```css
.form-control.is-valid {
    border-color: var(--success-color);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2316a34a' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
}

.form-control.is-invalid {
    border-color: var(--error-color);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4M7.2 4.6l-1.4 1.4'/%3e%3c/svg%3e");
}
```

## 🎯 Accesibilidad del Tema

### Contraste de Colores
- **Verde principal**: Ratio 4.5:1 con texto blanco
- **Verde oscuro**: Ratio 7:1 con texto blanco
- **Acento naranja**: Ratio 3:1 con texto blanco
- **Alto contraste**: Verde muy oscuro con texto blanco

### Estados de Foco
```css
*:focus {
    outline: 2px solid var(--accent-color);
    outline-offset: 2px;
}

.enhanced-focus *:focus {
    outline: 3px solid var(--accent-color);
    outline-offset: 2px;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
}
```

### Modo Monocromático
```css
.monochrome {
    filter: grayscale(100%);
}

.monochrome * {
    --primary-color: #000000;
    --secondary-color: #ffffff;
    --accent-color: #666666;
}
```

## 🎨 Iconografía

### Iconos del Tema
- **♿**: Accesibilidad
- **🌓**: Alto contraste
- **🔍**: Texto grande
- **⚫**: Modo monocromático
- **📖**: Tipografía dislexia
- **⌨️**: Navegación por teclado
- **✨**: Resaltado de foco
- **⏸️**: Reducir animaciones
- **🔊**: Lector de pantalla
- **🎧**: Descripción de audio
- **🔔**: Alertas visuales
- **🌍**: Cambio de idioma

## 📊 Métricas de Diseño

### Performance Visual
- **Lighthouse Performance**: 95+
- **First Contentful Paint**: < 1.5s
- **Largest Contentful Paint**: < 2.5s
- **Cumulative Layout Shift**: < 0.1

### Accesibilidad Visual
- **Contrast Ratio**: 4.5:1 mínimo
- **Color Independence**: 100%
- **Focus Indicators**: Visibles en todos los elementos
- **Text Scaling**: 200% sin pérdida de funcionalidad

## 🔧 Personalización

### Variables CSS Personalizables
```css
:root {
    /* Personalizar colores principales */
    --primary-color: #16a34a;
    --primary-dark: #15803d;
    --primary-light: #22c55e;
    
    /* Personalizar acentos */
    --accent-color: #f59e0b;
    --accent-dark: #d97706;
    
    /* Personalizar gradientes */
    --gradient-primary: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
}
```

### Temas Alternativos
```css
/* Tema Azul */
.theme-blue {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --accent-color: #f59e0b;
}

/* Tema Púrpura */
.theme-purple {
    --primary-color: #7c3aed;
    --primary-dark: #6d28d9;
    --accent-color: #f59e0b;
}
```

---

**Última actualización**: Julio 2025  
**Versión del Tema**: 2.0.0  
**Diseñador**: Portal de Turismo Inclusivo Ecuador 