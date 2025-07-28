// Funcionalidades específicas de accesibilidad
class AccessibilityManager {
    constructor() {
        this.preferences = this.loadPreferences();
        this.init();
    }
    
    init() {
        this.createAccessibilityPanel();
        this.setupKeyboardShortcuts();
        this.setupFocusManagement();
        this.setupScreenReaderSupport();
        this.applyPreferences();
    }
    
    createAccessibilityPanel() {
        const panel = document.createElement('div');
        panel.className = 'accessibility-controls';
        panel.setAttribute('role', 'region');
        panel.setAttribute('aria-label', 'Controles de accesibilidad');
        
        panel.innerHTML = `
            <h6>Accesibilidad</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="contrastToggle" 
                    aria-pressed="${this.preferences.highContrast}">
                <i class="fas fa-adjust" aria-hidden="true"></i>
                Alto Contraste
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="textSizeToggle"
                    aria-pressed="${this.preferences.largeText}">
                <i class="fas fa-font" aria-hidden="true"></i>
                Texto Grande
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="audioToggle">
                <i class="fas fa-volume-up" aria-hidden="true"></i>
                Audio Guía
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="keyboardHelp">
                <i class="fas fa-keyboard" aria-hidden="true"></i>
                Ayuda Teclado
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" id="closePanel"
                    aria-label="Cerrar panel de accesibilidad">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        `;
        
        document.body.appendChild(panel);
        this.bindPanelEvents(panel);
    }
    
    bindPanelEvents(panel) {
        const contrastBtn = panel.querySelector('#contrastToggle');
        const textBtn = panel.querySelector('#textSizeToggle');
        const audioBtn = panel.querySelector('#audioToggle');
        const helpBtn = panel.querySelector('#keyboardHelp');
        const closeBtn = panel.querySelector('#closePanel');
        
        contrastBtn.addEventListener('click', () => this.toggleHighContrast());
        textBtn.addEventListener('click', () => this.toggleLargeText());
        audioBtn.addEventListener('click', () => this.toggleAudioGuide());
        helpBtn.addEventListener('click', () => this.showKeyboardHelp());
        closeBtn.addEventListener('click', () => this.togglePanel());
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Alt + A: Abrir panel de accesibilidad
            if (e.altKey && e.key === 'a') {
                e.preventDefault();
                this.togglePanel();
            }
            
            // Alt + C: Toggle contraste
            if (e.altKey && e.key === 'c') {
                e.preventDefault();
                this.toggleHighContrast();
            }
            
            // Alt + T: Toggle texto grande
            if (e.altKey && e.key === 't') {
                e.preventDefault();
                this.toggleLargeText();
            }
            
            // Escape: Cerrar modales o paneles
            if (e.key === 'Escape') {
                this.closeActiveElements();
            }
        });
    }
    
    setupFocusManagement() {
        // Indicador visual de foco mejorado
        document.addEventListener('focusin', (e) => {
            if (e.target.matches('a, button, input, select, textarea')) {
                e.target.classList.add('focused');
            }
        });
        
        document.addEventListener('focusout', (e) => {
            e.target.classList.remove('focused');
        });
        
        // Skip links
        this.createSkipLinks();
    }
    
    createSkipLinks() {
        const skipNav = document.createElement('a');
        skipNav.href = '#main-content';
        skipNav.className = 'skip-link';
        skipNav.textContent = 'Saltar al contenido principal';
        
        const skipToSearch = document.createElement('a');
        skipToSearch.href = '#search-section';
        skipToSearch.className = 'skip-link';
        skipToSearch.textContent = 'Ir a búsqueda';
        
        document.body.insertBefore(skipNav, document.body.firstChild);
        document.body.insertBefore(skipToSearch, document.body.firstChild);
    }
    
    setupScreenReaderSupport() {
        // Anuncios en vivo para cambios dinámicos
        this.liveRegion = document.createElement('div');
        this.liveRegion.setAttribute('aria-live', 'polite');
        this.liveRegion.setAttribute('aria-atomic', 'true');
        this.liveRegion.className = 'screen-reader-only';
        this.liveRegion.id = 'live-announcements';
        document.body.appendChild(this.liveRegion);
        
        // Mejorar elementos interactivos
        this.enhanceInteractiveElements();
    }
    
    enhanceInteractiveElements() {
        // Agregar roles y etiquetas faltantes
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            if (!card.getAttribute('role')) {
                card.setAttribute('role', 'article');
            }
            
            const heading = card.querySelector('h3, h4, h5');
            if (heading && !heading.id) {
                heading.id = `card-heading-${index}`;
                card.setAttribute('aria-labelledby', heading.id);
            }
        });
        
        // Mejorar botones de rating
        const ratingButtons = document.querySelectorAll('.rating button, .rating .star');
        ratingButtons.forEach((btn, index) => {
            if (!btn.getAttribute('aria-label')) {
                const rating = Math.floor(index / 5) + 1;
                btn.setAttribute('aria-label', `Calificar con ${rating} estrellas`);
            }
        });
    }
    
    togglePanel() {
        const panel = document.querySelector('.accessibility-controls');
        if (panel) {
            const isVisible = panel.style.display !== 'none';
            panel.style.display = isVisible ? 'none' : 'block';
            
            if (!isVisible) {
                panel.querySelector('button').focus();
                this.announce('Panel de accesibilidad abierto');
            } else {
                this.announce('Panel de accesibilidad cerrado');
            }
        }
    }
    
    toggleHighContrast() {
        const isActive = document.body.classList.toggle('high-contrast');
        this.preferences.highContrast = isActive;
        this.savePreferences();
        
        const button = document.getElementById('contrastToggle');
        if (button) {
            button.setAttribute('aria-pressed', isActive);
        }
        
        this.announce(isActive ? 'Contraste alto activado' : 'Contraste alto desactivado');
    }
    
    toggleLargeText() {
        const isActive = document.body.classList.toggle('large-text');
        this.preferences.largeText = isActive;
        this.savePreferences();
        
        const button = document.getElementById('textSizeToggle');
        if (button) {
            button.setAttribute('aria-pressed', isActive);
        }
        
        this.announce(isActive ? 'Texto grande activado' : 'Texto grande desactivado');
    }
    
    toggleAudioGuide() {
        // Implementar Text-to-Speech básico
        if ('speechSynthesis' in window) {
            if (speechSynthesis.speaking) {
                speechSynthesis.cancel();
                this.announce('Audio guía detenida');
            } else {
                this.startAudioGuide();
            }
        } else {
            this.announce('Audio guía no disponible en este navegador');
        }
    }
    
    startAudioGuide() {
        const text = this.getPageText();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'es-ES';
        utterance.rate = 0.8;
        
        utterance.onstart = () => this.announce('Audio guía iniciada');
        utterance.onend = () => this.announce('Audio guía finalizada');
        
        speechSynthesis.speak(utterance);
    }
    
    getPageText() {
        const mainContent = document.querySelector('#main-content, main, .main-content');
        if (mainContent) {
            // Extraer texto relevante, excluyendo navegación y elementos decorativos
            const textElements = mainContent.querySelectorAll('h1, h2, h3, p, .card-title, .card-text');
            return Array.from(textElements)
                .map(el => el.textContent.trim())
                .filter(text => text.length > 0)
                .join('. ');
        }
        return 'Contenido no disponible para audio guía';
    }
    
    showKeyboardHelp() {
        const helpText = `
            Atajos de teclado disponibles:
            Alt + A: Abrir/cerrar panel de accesibilidad
            Alt + C: Alternar contraste alto
            Alt + T: Alternar texto grande
            Tab: Navegar entre elementos
            Shift + Tab: Navegar hacia atrás
            Escape: Cerrar modales
            Flechas: Navegar en listas
        `;
        
        this.announce(helpText);
        
        // Crear modal de ayuda si no existe
        this.createHelpModal(helpText);
    }
    
    createHelpModal(helpText) {
        const existingModal = document.getElementById('keyboardHelpModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'keyboardHelpModal';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-labelledby', 'keyboardHelpModalLabel');
        modal.setAttribute('aria-hidden', 'true');
        
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="keyboardHelpModalLabel">
                            Ayuda de Navegación por Teclado
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                aria-label="Cerrar ayuda"></button>
                    </div>
                    <div class="modal-body">
                        <pre style="white-space: pre-line; font-family: inherit;">${helpText}</pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Mostrar modal si Bootstrap está disponible
        if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }
    
    closeActiveElements() {
        // Cerrar modales abiertos
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            if (typeof bootstrap !== 'undefined') {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }
        });
        
        // Cerrar dropdowns abiertos
        const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
        openDropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
    
    announce(message) {
        if (this.liveRegion) {
            this.liveRegion.textContent = message;
            
            // Limpiar después de un tiempo para evitar acumulación
            setTimeout(() => {
                this.liveRegion.textContent = '';
            }, 3000);
        }
    }
    
    loadPreferences() {
        try {
            const saved = localStorage.getItem('accessibility-preferences');
            return saved ? JSON.parse(saved) : {
                highContrast: false,
                largeText: false,
                audioGuide: false
            };
        } catch {
            return {
                highContrast: false,
                largeText: false,
                audioGuide: false
            };
        }
    }
    
    savePreferences() {
        try {
            localStorage.setItem('accessibility-preferences', JSON.stringify(this.preferences));
        } catch (e) {
            console.warn('No se pudieron guardar las preferencias de accesibilidad');
        }
    }
    
    applyPreferences() {
        if (this.preferences.highContrast) {
            document.body.classList.add('high-contrast');
        }
        
        if (this.preferences.largeText) {
            document.body.classList.add('large-text');
        }
    }
}

// Inicializar el gestor de accesibilidad
document.addEventListener('DOMContentLoaded', () => {
    window.accessibilityManager = new AccessibilityManager();
});

// Exportar para uso global
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AccessibilityManager;
}
