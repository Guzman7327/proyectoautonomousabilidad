// ===== VARIABLES GLOBALES =====
let currentSlide = 0;
const totalSlides = 3;
let slideInterval;
let currentRegion = 'costa';

// ===== FUNCIONES DE INICIALIZACIÓN =====
document.addEventListener('DOMContentLoaded', function() {
    initializeCarousel();
    initializeNavigation();
    initializeAccessibility();
    initializeSearch();
    initializeTabs();
    initializeMap();
    
    // Mostrar indicador de accesibilidad brevemente
    showAccessibilityStatus('Portal cargado con funciones de accesibilidad');
});

// ===== CAROUSEL DEL HERO =====
function initializeCarousel() {
    // Auto-play del carousel
    slideInterval = setInterval(nextSlide, 5000);
    
    // Pausar en hover
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        heroSection.addEventListener('mouseenter', () => clearInterval(slideInterval));
        heroSection.addEventListener('mouseleave', () => {
            slideInterval = setInterval(nextSlide, 5000);
        });
    }
}

function nextSlide() {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator');
    
    if (slides.length === 0) return;
    
    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');
    
    currentSlide = (currentSlide + 1) % totalSlides;
    
    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

function previousSlide() {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator');
    
    if (slides.length === 0) return;
    
    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');
    
    currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
    
    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

function goToSlide(slideIndex) {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator');
    
    if (slides.length === 0) return;
    
    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');
    
    currentSlide = slideIndex;
    
    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

// ===== NAVEGACIÓN MÓVIL =====
function initializeNavigation() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', toggleMobileMenu);
    }
    
    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
            navMenu.classList.remove('active');
            navToggle.setAttribute('aria-expanded', 'false');
        }
    });
}

function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const navToggle = document.querySelector('.nav-toggle');
    
    navMenu.classList.toggle('active');
    const isOpen = navMenu.classList.contains('active');
    navToggle.setAttribute('aria-expanded', isOpen);
}

// ===== PESTAÑAS DE REGIONES =====
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const region = button.textContent.trim().toLowerCase();
            switchRegion(region);
        });
    });
}

function switchRegion(region) {
    // Actualizar pestañas
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
        if (btn.textContent.trim().toLowerCase() === region) {
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');
        }
    });
    
    // Actualizar paneles
    const panels = document.querySelectorAll('.region-panel');
    panels.forEach(panel => {
        panel.classList.remove('active');
    });
    
    const targetPanel = document.getElementById(`${region}-panel`);
    if (targetPanel) {
        targetPanel.classList.add('active');
    }
    
    currentRegion = region;
    showAccessibilityStatus(`Región ${region} seleccionada`);
}

// ===== FUNCIONES DE BÚSQUEDA =====
function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    
    if (searchInput && searchBtn) {
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', debounce(liveSearch, 300));
    }
}

function performSearch() {
    const query = document.querySelector('.search-input').value;
    const region = document.querySelector('.filter-select').value;
    const type = document.querySelectorAll('.filter-select')[1].value;
    
    if (query.trim()) {
        showAccessibilityStatus(`Buscando: ${query}`);
        // Aquí iría la lógica de búsqueda real
        console.log('Búsqueda:', { query, region, type });
    }
}

function liveSearch() {
    const query = document.querySelector('.search-input').value;
    if (query.length > 2) {
        // Lógica de búsqueda en vivo
        console.log('Búsqueda en vivo:', query);
    }
}

// ===== FUNCIONES DEL MAPA =====
function initializeMap() {
    // Aquí se inicializaría el mapa de Leaflet
    if (typeof L !== 'undefined') {
        const map = L.map('map').setView([-1.831239, -78.183406], 7);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Agregar marcadores de destinos
        addDestinationMarkers(map);
    }
}

function addDestinationMarkers(map) {
    const destinations = [
        { name: 'Cuenca', lat: -2.9001, lng: -79.0059, region: 'sierra' },
        { name: 'Salinas', lat: -2.2108, lng: -80.9558, region: 'costa' },
        { name: 'Manta', lat: -0.9677, lng: -80.7088, region: 'costa' },
        { name: 'Quito', lat: -0.1807, lng: -78.4678, region: 'sierra' },
        { name: 'Baños', lat: -1.3928, lng: -78.4269, region: 'sierra' }
    ];
    
    destinations.forEach(dest => {
        const marker = L.marker([dest.lat, dest.lng]).addTo(map);
        marker.bindPopup(`<strong>${dest.name}</strong><br>Región: ${dest.region}`);
    });
}

function centrarMapa() {
    // Centrar mapa en Ecuador
    if (window.map) {
        window.map.setView([-1.831239, -78.183406], 7);
        showAccessibilityStatus('Mapa centrado en Ecuador');
    }
}

function mostrarTodosDestinos() {
    // Mostrar todos los marcadores
    showAccessibilityStatus('Mostrando todos los destinos en el mapa');
}

// ===== FUNCIONES DE FAVORITOS =====
function addToFavorites(destinationId) {
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    if (!favorites.includes(destinationId)) {
        favorites.push(destinationId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        showAccessibilityStatus(`${destinationId} agregado a favoritos`);
        
        // Actualizar UI
        const button = event.target.closest('.card-btn.secondary');
        if (button) {
            button.style.background = 'var(--secondary-color)';
            button.style.color = 'var(--white)';
            button.innerHTML = '<span aria-hidden="true">❤️</span>';
        }
    } else {
        showAccessibilityStatus(`${destinationId} ya está en favoritos`);
    }
}

// ===== FUNCIONES DE ACCESIBILIDAD =====
function initializeAccessibility() {
    // Cargar preferencias guardadas
    loadAccessibilityPreferences();
    
    // Configurar menú de accesibilidad
    const accToggle = document.querySelector('.accessibility-toggle');
    const accDropdown = document.querySelector('.accessibility-dropdown');
    
    if (accToggle && accDropdown) {
        accToggle.addEventListener('click', toggleAccMenu);
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!accToggle.contains(e.target) && !accDropdown.contains(e.target)) {
                accDropdown.classList.add('oculto');
                accToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
    
    // Navegación por teclado
    document.addEventListener('keydown', handleKeyboardNavigation);
}

function toggleAccMenu() {
    const dropdown = document.querySelector('.accessibility-dropdown');
    const toggle = document.querySelector('.accessibility-toggle');
    
    dropdown.classList.toggle('oculto');
    const isOpen = !dropdown.classList.contains('oculto');
    toggle.setAttribute('aria-expanded', isOpen);
}

function handleKeyboardNavigation(e) {
    // Escape para cerrar menús
    if (e.key === 'Escape') {
        document.querySelector('.accessibility-dropdown')?.classList.add('oculto');
        document.querySelector('.nav-menu')?.classList.remove('active');
    }
    
    // Accesos rápidos
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case '1':
                e.preventDefault();
                activarContraste();
                break;
            case '2':
                e.preventDefault();
                aumentarTexto();
                break;
            case '3':
                e.preventDefault();
                resaltarEnlaces();
                break;
        }
    }
}

// ===== FUNCIONES DE ACCESIBILIDAD ESPECÍFICAS =====
let contrastMode = false;
let textSize = 100;
let linksHighlighted = false;

function activarContraste() {
    contrastMode = !contrastMode;
    document.body.classList.toggle('alto-contraste', contrastMode);
    
    saveAccessibilityPreference('contrast', contrastMode);
    showAccessibilityStatus(contrastMode ? 'Alto contraste activado' : 'Alto contraste desactivado');
}

function activarModoMonocromatico() {
    const isActive = document.body.classList.toggle('monocromatico');
    saveAccessibilityPreference('monochrome', isActive);
    showAccessibilityStatus(isActive ? 'Modo monocromático activado' : 'Modo monocromático desactivado');
}

function aumentarTexto() {
    textSize = Math.min(textSize + 10, 150);
    document.documentElement.style.fontSize = `${textSize}%`;
    
    saveAccessibilityPreference('textSize', textSize);
    showAccessibilityStatus(`Tamaño de texto: ${textSize}%`);
}

function reducirTexto() {
    textSize = Math.max(textSize - 10, 80);
    document.documentElement.style.fontSize = `${textSize}%`;
    
    saveAccessibilityPreference('textSize', textSize);
    showAccessibilityStatus(`Tamaño de texto: ${textSize}%`);
}

function ajustarEspaciado() {
    const isActive = document.body.classList.toggle('espaciado-ajustado');
    saveAccessibilityPreference('spacing', isActive);
    showAccessibilityStatus(isActive ? 'Espaciado ajustado activado' : 'Espaciado normal restaurado');
}

function activarZoom() {
    const isActive = document.body.classList.toggle('zoom-pagina');
    saveAccessibilityPreference('zoom', isActive);
    showAccessibilityStatus(isActive ? 'Zoom de página activado' : 'Zoom de página desactivado');
}

function resaltarEnlaces() {
    linksHighlighted = !linksHighlighted;
    document.body.classList.toggle('resaltar-enlaces', linksHighlighted);
    
    saveAccessibilityPreference('highlightLinks', linksHighlighted);
    showAccessibilityStatus(linksHighlighted ? 'Enlaces resaltados' : 'Resaltado de enlaces desactivado');
}

function leerTexto() {
    if ('speechSynthesis' in window) {
        const text = getSelectedText() || 'Función de lectura de texto activada. Seleccione texto para escucharlo.';
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'es-ES';
        speechSynthesis.speak(utterance);
        
        showAccessibilityStatus('Leyendo texto seleccionado');
    } else {
        showAccessibilityStatus('Síntesis de voz no disponible en este navegador');
    }
}

function activarSubtitulos() {
    showAccessibilityStatus('Función de subtítulos activada');
    // Lógica para mostrar subtítulos en videos
}

function descargarTranscripcion() {
    // Crear y descargar archivo de transcripción
    const transcripcion = "TRANSCRIPCIÓN DEL VIDEO - PORTAL TURÍSTICO ECUADOR\\n" +
        "Contenido del video promocional con descripción detallada...";
    
    const blob = new Blob([transcripcion], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'transcripcion_portal_turistico.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showAccessibilityStatus('Transcripción descargada');
}

function restablecerAccesibilidad() {
    // Resetear todas las preferencias
    document.body.className = '';
    document.documentElement.style.fontSize = '';
    
    // Limpiar localStorage
    localStorage.removeItem('accessibilityPreferences');
    
    // Resetear variables
    contrastMode = false;
    textSize = 100;
    linksHighlighted = false;
    
    showAccessibilityStatus('Configuración de accesibilidad restablecida');
}

// ===== GESTIÓN DE PREFERENCIAS =====
function saveAccessibilityPreference(key, value) {
    const preferences = JSON.parse(localStorage.getItem('accessibilityPreferences') || '{}');
    preferences[key] = value;
    localStorage.setItem('accessibilityPreferences', JSON.stringify(preferences));
}

function loadAccessibilityPreferences() {
    const preferences = JSON.parse(localStorage.getItem('accessibilityPreferences') || '{}');
    
    if (preferences.contrast) activarContraste();
    if (preferences.monochrome) activarModoMonocromatico();
    if (preferences.textSize && preferences.textSize !== 100) {
        textSize = preferences.textSize;
        document.documentElement.style.fontSize = `${textSize}%`;
    }
    if (preferences.spacing) ajustarEspaciado();
    if (preferences.zoom) activarZoom();
    if (preferences.highlightLinks) resaltarEnlaces();
}

// ===== UTILIDADES =====
function showAccessibilityStatus(message) {
    const statusElement = document.getElementById('accessibility-status');
    if (statusElement) {
        statusElement.querySelector('#status-text').textContent = message;
        statusElement.classList.add('show');
        
        setTimeout(() => {
            statusElement.classList.remove('show');
        }, 3000);
    }
}

function getSelectedText() {
    if (window.getSelection) {
        return window.getSelection().toString();
    }
    return '';
}

function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId) || document.querySelector(`[aria-labelledby="${sectionId}-titulo"]`);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        showAccessibilityStatus(`Navegando a sección: ${sectionId}`);
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== FUNCIONES PARA MODALES (si existen) =====
function mostrarModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        
        // Focus en el primer elemento focusable
        const firstFocusable = modal.querySelector('input, button, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
        
        showAccessibilityStatus('Modal abierto');
    }
}

function cerrarModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        showAccessibilityStatus('Modal cerrado');
    }
}

// ===== GESTIÓN DE ERRORES =====
window.addEventListener('error', function(e) {
    console.error('Error en la aplicación:', e.error);
    showAccessibilityStatus('Se produjo un error. Por favor, recargue la página.');
});

// ===== COMPATIBILIDAD =====
// Polyfill para navegadores más antiguos
if (!Element.prototype.closest) {
    Element.prototype.closest = function(selector) {
        let element = this;
        while (element && element.nodeType === 1) {
            if (element.matches(selector)) {
                return element;
            }
            element = element.parentNode;
        }
        return null;
    };
}
