/**
 * PORTAL DE TURISMO INCLUSIVO - JAVASCRIPT PRINCIPAL
 * Funcionalidades modernas y accesibles
 */

// ========================================
// 1. INICIALIZACIÓN Y CONFIGURACIÓN
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Portal de Turismo Inclusivo iniciado');
    
    // Inicializar todos los módulos
    initializeNavigation();
    initializeSearch();
    initializeAnimations();
    initializeLazyLoading();
    initializeScrollEffects();
    initializeInteractiveElements();
    
    // Configurar observadores
    setupIntersectionObserver();
    setupResizeObserver();
    
    // Anunciar carga completa
    if (window.accessibility) {
        window.accessibility.announceToScreenReader('Página cargada completamente');
    }
});

// ========================================
// 2. NAVEGACIÓN MEJORADA
// ========================================

function initializeNavigation() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    // Toggle de navegación móvil
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            this.setAttribute('aria-expanded', !isExpanded);
            navMenu.classList.toggle('active');
            
            // Anunciar cambio
            const message = isExpanded ? 'Menú cerrado' : 'Menú abierto';
            if (window.accessibility) {
                window.accessibility.announceToScreenReader(message);
            }
        });
    }
    
    // Dropdowns
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Cerrar otros dropdowns
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== this) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                }
            });
            
            this.setAttribute('aria-expanded', !isExpanded);
        });
    });
    
    // Cerrar menús al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown') && !e.target.closest('.nav-toggle')) {
            dropdownToggles.forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
            });
        }
    });
    
    // Navegación por teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdownToggles.forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
            });
            
            if (navMenu && navMenu.classList.contains('active')) {
                navToggle.click();
            }
        }
    });
}

// ========================================
// 3. BÚSQUEDA AVANZADA
// ========================================

function initializeSearch() {
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('search-suggestions');
    
    if (!searchInput || !suggestionsContainer) return;
    
    let searchTimeout;
    let currentSuggestions = [];
    let selectedIndex = -1;
    
    // Búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
    
    // Navegación por teclado
    searchInput.addEventListener('keydown', function(e) {
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                navigateSuggestions(1);
                break;
            case 'ArrowUp':
                e.preventDefault();
                navigateSuggestions(-1);
                break;
            case 'Enter':
                e.preventDefault();
                selectCurrentSuggestion();
                break;
            case 'Escape':
                hideSuggestions();
                break;
        }
    });
    
    // Cerrar sugerencias al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-input-group')) {
            hideSuggestions();
        }
    });
    
    async function performSearch(query) {
        try {
            // Simular búsqueda (reemplazar con llamada real a la API)
            const suggestions = await mockSearchAPI(query);
            displaySuggestions(suggestions);
        } catch (error) {
            console.error('Error en búsqueda:', error);
            hideSuggestions();
        }
    }
    
    function displaySuggestions(suggestions) {
        currentSuggestions = suggestions;
        selectedIndex = -1;
        
        if (suggestions.length === 0) {
            hideSuggestions();
            return;
        }
        
        suggestionsContainer.innerHTML = '';
        
        suggestions.forEach((suggestion, index) => {
            const item = document.createElement('div');
            item.className = 'search-suggestion';
            item.setAttribute('role', 'option');
            item.setAttribute('aria-selected', 'false');
            item.textContent = suggestion.name;
            
            item.addEventListener('click', () => {
                selectSuggestion(suggestion);
            });
            
            item.addEventListener('mouseenter', () => {
                selectedIndex = index;
                updateSelection();
            });
            
            suggestionsContainer.appendChild(item);
        });
        
        showSuggestions();
    }
    
    function navigateSuggestions(direction) {
        if (currentSuggestions.length === 0) return;
        
        selectedIndex += direction;
        
        if (selectedIndex < 0) {
            selectedIndex = currentSuggestions.length - 1;
        } else if (selectedIndex >= currentSuggestions.length) {
            selectedIndex = 0;
        }
        
        updateSelection();
    }
    
    function updateSelection() {
        const items = suggestionsContainer.querySelectorAll('.search-suggestion');
        
        items.forEach((item, index) => {
            const isSelected = index === selectedIndex;
            item.setAttribute('aria-selected', isSelected);
            item.classList.toggle('selected', isSelected);
        });
    }
    
    function selectCurrentSuggestion() {
        if (selectedIndex >= 0 && currentSuggestions[selectedIndex]) {
            selectSuggestion(currentSuggestions[selectedIndex]);
        }
    }
    
    function selectSuggestion(suggestion) {
        searchInput.value = suggestion.name;
        hideSuggestions();
        
        // Anunciar selección
        if (window.accessibility) {
            window.accessibility.announceToScreenReader(`Seleccionado: ${suggestion.name}`);
        }
        
        // Opcional: enviar formulario automáticamente
        // searchInput.form.submit();
    }
    
    function showSuggestions() {
        suggestionsContainer.setAttribute('aria-hidden', 'false');
        searchInput.setAttribute('aria-expanded', 'true');
    }
    
    function hideSuggestions() {
        suggestionsContainer.setAttribute('aria-hidden', 'true');
        searchInput.setAttribute('aria-expanded', 'false');
        currentSuggestions = [];
        selectedIndex = -1;
    }
    
    // API mock para pruebas
    async function mockSearchAPI(query) {
        const mockData = [
            { name: 'Galápagos', type: 'destination' },
            { name: 'Quito', type: 'city' },
            { name: 'Cuenca', type: 'city' },
            { name: 'Baños', type: 'destination' },
            { name: 'Otavalo', type: 'destination' },
            { name: 'Mindo', type: 'destination' },
            { name: 'Guayaquil', type: 'city' },
            { name: 'Manta', type: 'city' }
        ];
        
        // Simular delay de red
        await new Promise(resolve => setTimeout(resolve, 100));
        
        return mockData.filter(item => 
            item.name.toLowerCase().includes(query.toLowerCase())
        ).slice(0, 5);
    }
}

// ========================================
// 4. ANIMACIONES Y EFECTOS
// ========================================

function initializeAnimations() {
    // Animaciones de entrada
    const animatedElements = document.querySelectorAll('.fade-in, .slide-in');
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
    });
    
    // Lazy loading de animaciones
    const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'all 0.6s ease-out';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                animationObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });
    
    animatedElements.forEach(element => {
        animationObserver.observe(element);
    });
    
    // Efectos hover en tarjetas
    const cards = document.querySelectorAll('.card, .destination-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// ========================================
// 5. LAZY LOADING
// ========================================

function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });
    
    images.forEach(img => {
        imageObserver.observe(img);
    });
}

// ========================================
// 6. EFECTOS DE SCROLL
// ========================================

function initializeScrollEffects() {
    // Header con efecto de scroll
    const header = document.querySelector('.site-header');
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Efecto de ocultar/mostrar header
        if (currentScrollY > lastScrollY && currentScrollY > 200) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
    });
    
    // Botón "Volver arriba"
    const backToTopBtn = document.createElement('button');
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.innerHTML = '↑';
    backToTopBtn.setAttribute('aria-label', 'Volver arriba');
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        border: none;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        font-size: 20px;
        font-weight: bold;
    `;
    
    document.body.appendChild(backToTopBtn);
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.style.opacity = '1';
            backToTopBtn.style.visibility = 'visible';
        } else {
            backToTopBtn.style.opacity = '0';
            backToTopBtn.style.visibility = 'hidden';
        }
    });
    
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ========================================
// 7. ELEMENTOS INTERACTIVOS
// ========================================

function initializeInteractiveElements() {
    // Tooltips
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = element.dataset.tooltip;
        tooltip.style.cssText = `
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            white-space: nowrap;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            pointer-events: none;
        `;
        
        document.body.appendChild(tooltip);
        
        element.addEventListener('mouseenter', () => {
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            tooltip.style.opacity = '1';
            tooltip.style.visibility = 'visible';
        });
        
        element.addEventListener('mouseleave', () => {
            tooltip.style.opacity = '0';
            tooltip.style.visibility = 'hidden';
        });
    });
    
    // Modales
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = trigger.dataset.modal;
            const modal = document.getElementById(modalId);
            
            if (modal) {
                openModal(modal);
            }
        });
    });
    
    // Cerrar modales
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal') || e.target.classList.contains('modal-close')) {
            closeModal(e.target.closest('.modal'));
        }
    });
    
    // Favoritos
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    
    favoriteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const destinationId = this.dataset.destinationId;
            const isFavorite = this.dataset.isFavorite === 'true';
            
            // Toggle estado
            this.dataset.isFavorite = !isFavorite;
            
            // Cambiar icono
            const icon = this.querySelector('.icon');
            if (icon) {
                icon.textContent = !isFavorite ? '❤️' : '🤍';
            }
            
            // Anunciar cambio
            const message = !isFavorite ? 'Agregado a favoritos' : 'Removido de favoritos';
            if (window.accessibility) {
                window.accessibility.announceToScreenReader(message);
            }
            
            // Aquí iría la lógica para guardar en el servidor
            console.log(`${!isFavorite ? 'Agregado' : 'Removido'} destino ${destinationId} de favoritos`);
        });
    });
}

// ========================================
// 8. FUNCIONES DE MODAL
// ========================================

function openModal(modal) {
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    
    // Enfocar primer elemento enfocable
    const firstFocusable = modal.querySelector('button, input, select, textarea, a[href]');
    if (firstFocusable) {
        firstFocusable.focus();
    }
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    // Anunciar apertura
    if (window.accessibility) {
        window.accessibility.announceToScreenReader('Modal abierto');
    }
}

function closeModal(modal) {
    if (!modal) return;
    
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    
    // Restaurar scroll del body
    document.body.style.overflow = '';
    
    // Anunciar cierre
    if (window.accessibility) {
        window.accessibility.announceToScreenReader('Modal cerrado');
    }
}

// ========================================
// 9. OBSERVADORES
// ========================================

function setupIntersectionObserver() {
    // Observer para elementos que aparecen en viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });
    
    // Observar elementos con clase 'observe'
    document.querySelectorAll('.observe').forEach(el => {
        observer.observe(el);
    });
}

function setupResizeObserver() {
    // Observer para cambios de tamaño
    const resizeObserver = new ResizeObserver((entries) => {
        entries.forEach(entry => {
            // Aquí puedes manejar cambios de tamaño
            console.log('Elemento redimensionado:', entry.target);
        });
    });
    
    // Observar elementos importantes
    const mainContent = document.querySelector('main');
    if (mainContent) {
        resizeObserver.observe(mainContent);
    }
}

// ========================================
// 10. UTILIDADES
// ========================================

// Debounce function
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

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Formatear números
function formatNumber(num) {
    return new Intl.NumberFormat('es-EC').format(num);
}

// Formatear fechas
function formatDate(date) {
    return new Intl.DateTimeFormat('es-EC', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

// Validar email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Generar ID único
function generateId() {
    return Math.random().toString(36).substr(2, 9);
}

// ========================================
// 11. MANEJO DE ERRORES
// ========================================

window.addEventListener('error', function(e) {
    console.error('Error capturado:', e.error);
    
    // Mostrar mensaje de error amigable
    showErrorMessage('Ha ocurrido un error inesperado. Por favor, intenta nuevamente.');
});

window.addEventListener('unhandledrejection', function(e) {
    console.error('Promesa rechazada:', e.reason);
    
    // Mostrar mensaje de error amigable
    showErrorMessage('Error de conexión. Verifica tu conexión a internet.');
});

function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--error-color);
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        max-width: 300px;
        animation: slideInRight 0.3s ease;
    `;
    errorDiv.textContent = message;
    
    document.body.appendChild(errorDiv);
    
    // Remover después de 5 segundos
    setTimeout(() => {
        errorDiv.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 300);
    }, 5000);
}

// ========================================
// 12. PERFORMANCE Y OPTIMIZACIÓN
// ========================================

// Preload de recursos críticos
function preloadCriticalResources() {
    const criticalImages = [
        '/static/images/logo.png',
        '/static/images/hero-bg.jpg'
    ];
    
    criticalImages.forEach(src => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = src;
        document.head.appendChild(link);
    });
}

// Service Worker para cache
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registrado:', registration);
            })
            .catch(error => {
                console.log('SW error:', error);
            });
    });
}

// ========================================
// 13. ANALYTICS Y MONITORING
// ========================================

// Performance monitoring
if ('PerformanceObserver' in window) {
    const perfObserver = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
            if (entry.entryType === 'largest-contentful-paint') {
                console.log('LCP:', entry.startTime);
                
                if (entry.startTime > 4000) {
                    console.warn('LCP performance issue detected');
                }
            }
            
            if (entry.entryType === 'first-input') {
                console.log('FID:', entry.processingStart - entry.startTime);
                
                if (entry.processingStart - entry.startTime > 100) {
                    console.warn('FID performance issue detected');
                }
            }
        }
    });
    
    perfObserver.observe({ entryTypes: ['largest-contentful-paint', 'first-input'] });
}

// Error tracking
function trackError(error, context = {}) {
    console.error('Error tracked:', error, context);
    
    // Aquí podrías enviar el error a un servicio de tracking
    // como Sentry, LogRocket, etc.
}

// ========================================
// 14. EXPORTAR FUNCIONES GLOBALES
// ========================================

window.TurismoInclusivo = {
    utils: {
        formatNumber,
        formatDate,
        isValidEmail,
        generateId,
        debounce,
        throttle
    },
    ui: {
        openModal,
        closeModal,
        showErrorMessage
    },
    trackError
};

// ========================================
// 15. INICIALIZACIÓN FINAL
// ========================================

// Preload recursos críticos
preloadCriticalResources();

// Configurar manejo de errores global
window.addEventListener('load', () => {
    console.log('✅ Portal de Turismo Inclusivo completamente cargado');
    
    // Anunciar carga completa
    if (window.accessibility) {
        window.accessibility.announceToScreenReader('Página completamente cargada y lista para usar');
    }
});
