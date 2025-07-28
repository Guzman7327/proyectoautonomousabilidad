// JavaScript principal para el portal de turismo accesible
document.addEventListener('DOMContentLoaded', function() {
    console.log('Portal de Turismo Inclusivo Ecuador cargado');
    
    // Inicializar componentes
    initializeAccessibilityControls();
    initializeDestinationFilters();
    initializeSearchFunctionality();
    
    // Configurar navegación por teclado
    setupKeyboardNavigation();
});

// Funcionalidad de búsqueda
function initializeSearchFunctionality() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `/destinations?search=${encodeURIComponent(query)}`;
            }
        });
    }
}

// Filtros de destinos
function initializeDestinationFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            filterDestinations(category);
            
            // Actualizar estado visual
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

function filterDestinations(category) {
    const destinationCards = document.querySelectorAll('.destination-card');
    
    destinationCards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
            card.setAttribute('aria-hidden', 'false');
        } else {
            card.style.display = 'none';
            card.setAttribute('aria-hidden', 'true');
        }
    });
    
    // Anunciar cambio para lectores de pantalla
    announceToScreenReader(`Mostrando destinos de categoría: ${category}`);
}

// Controles de accesibilidad
function initializeAccessibilityControls() {
    // Toggle de contraste alto
    const contrastToggle = document.getElementById('contrastToggle');
    if (contrastToggle) {
        contrastToggle.addEventListener('click', toggleHighContrast);
    }
    
    // Toggle de texto grande
    const textSizeToggle = document.getElementById('textSizeToggle');
    if (textSizeToggle) {
        textSizeToggle.addEventListener('click', toggleLargeText);
    }
    
    // Control de audio
    const audioToggle = document.getElementById('audioToggle');
    if (audioToggle) {
        audioToggle.addEventListener('click', toggleAudioGuide);
    }
    
    // Cargar preferencias guardadas
    loadAccessibilityPreferences();
}

function toggleHighContrast() {
    document.body.classList.toggle('high-contrast');
    const isActive = document.body.classList.contains('high-contrast');
    
    // Guardar preferencia
    localStorage.setItem('high-contrast', isActive);
    
    // Anunciar cambio
    announceToScreenReader(isActive ? 'Contraste alto activado' : 'Contraste alto desactivado');
}

function toggleLargeText() {
    document.body.classList.toggle('large-text');
    const isActive = document.body.classList.contains('large-text');
    
    // Guardar preferencia
    localStorage.setItem('large-text', isActive);
    
    // Anunciar cambio
    announceToScreenReader(isActive ? 'Texto grande activado' : 'Texto grande desactivado');
}

function toggleAudioGuide() {
    // Implementar funcionalidad de guía de audio
    console.log('Toggle audio guide');
    announceToScreenReader('Función de audio en desarrollo');
}

function loadAccessibilityPreferences() {
    // Cargar contraste alto
    if (localStorage.getItem('high-contrast') === 'true') {
        document.body.classList.add('high-contrast');
    }
    
    // Cargar texto grande
    if (localStorage.getItem('large-text') === 'true') {
        document.body.classList.add('large-text');
    }
}

// Navegación por teclado
function setupKeyboardNavigation() {
    // Trap focus en modales
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            trapFocus(modal);
        });
    });
    
    // Navegación con flechas en listas
    const destinationLists = document.querySelectorAll('.destination-grid');
    destinationLists.forEach(list => {
        list.addEventListener('keydown', handleArrowNavigation);
    });
}

function trapFocus(element) {
    const focusableElements = element.querySelectorAll(
        'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
    );
    
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    element.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }
    });
    
    firstElement.focus();
}

function handleArrowNavigation(e) {
    const items = Array.from(this.querySelectorAll('.destination-card'));
    const currentIndex = items.indexOf(document.activeElement.closest('.destination-card'));
    
    let newIndex;
    
    switch(e.key) {
        case 'ArrowDown':
        case 'ArrowRight':
            newIndex = (currentIndex + 1) % items.length;
            break;
        case 'ArrowUp':
        case 'ArrowLeft':
            newIndex = (currentIndex - 1 + items.length) % items.length;
            break;
        default:
            return;
    }
    
    e.preventDefault();
    const nextItem = items[newIndex].querySelector('a, button');
    if (nextItem) nextItem.focus();
}

// Utilidades de accesibilidad
function announceToScreenReader(message) {
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.className = 'screen-reader-only';
    announcement.textContent = message;
    
    document.body.appendChild(announcement);
    
    setTimeout(() => {
        document.body.removeChild(announcement);
    }, 1000);
}

// Funciones utilitarias para destinos
function toggleFavorite(destinationId) {
    fetch(`/api/favorites/${destinationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRFToken': getCsrfToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`[data-destination-id="${destinationId}"]`);
            if (button) {
                button.classList.toggle('favorited');
                const isFavorited = button.classList.contains('favorited');
                button.setAttribute('aria-pressed', isFavorited);
                announceToScreenReader(isFavorited ? 'Agregado a favoritos' : 'Removido de favoritos');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        announceToScreenReader('Error al actualizar favoritos');
    });
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Funciones de rating
function setRating(rating, type = 'general') {
    const stars = document.querySelectorAll(`.rating-${type} .star`);
    stars.forEach((star, index) => {
        star.classList.toggle('active', index < rating);
    });
    
    announceToScreenReader(`Calificación ${type}: ${rating} de 5 estrellas`);
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAccessibilityControls);
} else {
    initializeAccessibilityControls();
}
