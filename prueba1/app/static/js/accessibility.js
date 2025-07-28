/**
 * Funciones de Accesibilidad para Portal de Turismo Inclusivo
 * Implementa características avanzadas de accesibilidad web
 */

class AccessibilityManager {
    constructor() {
        this.settings = {
            highContrast: false,
            largeText: false,
            voiceEnabled: false,
            keyboardNavigation: false,
            screenReader: false
        };
        
        this.synth = window.speechSynthesis;
        this.voices = [];
        this.currentVoice = null;
        this.isReading = false;
        
        this.init();
    }
    
    init() {
        this.loadSettings();
        this.initializeVoices();
        this.bindEvents();
        this.setupKeyboardShortcuts();
        this.setupARIALiveRegions();
        this.applySettings();
    }
    
    /**
     * Cargar configuraciones del usuario
     */
    loadSettings() {
        // Cargar desde localStorage
        const saved = localStorage.getItem('accessibility-settings');
        if (saved) {
            this.settings = { ...this.settings, ...JSON.parse(saved) };
        }
        
        // Cargar configuraciones del servidor si el usuario está autenticado
        if (window.currentUser) {
            this.settings.highContrast = window.currentUser.high_contrast || false;
            this.settings.largeText = window.currentUser.large_text || false;
            this.settings.voiceEnabled = window.currentUser.voice_enabled || false;
            this.settings.keyboardNavigation = window.currentUser.keyboard_navigation || false;
            this.settings.screenReader = window.currentUser.screen_reader || false;
        }
    }
    
    /**
     * Guardar configuraciones
     */
    saveSettings() {
        localStorage.setItem('accessibility-settings', JSON.stringify(this.settings));
        
        // Enviar al servidor si el usuario está autenticado
        if (window.currentUser) {
            this.updateServerSettings();
        }
    }
    
    /**
     * Actualizar configuraciones en el servidor
     */
    async updateServerSettings() {
        try {
            const response = await fetch('/accessibility/user-settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRFToken': this.getCSRFToken()
                },
                body: JSON.stringify(this.settings)
            });
            
            if (!response.ok) {
                console.warn('Error updating server settings');
            }
        } catch (error) {
            console.error('Error saving accessibility settings:', error);
        }
    }
    
    /**
     * Obtener token CSRF
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }
    
    /**
     * Inicializar voces para síntesis de voz
     */
    initializeVoices() {
        if (!this.synth) return;
        
        const loadVoices = () => {
            this.voices = this.synth.getVoices();
            
            // Buscar voz en español
            this.currentVoice = this.voices.find(voice => 
                voice.lang.startsWith('es') || voice.name.toLowerCase().includes('spanish')
            ) || this.voices[0];
        };
        
        loadVoices();
        
        if (this.synth.onvoiceschanged !== undefined) {
            this.synth.onvoiceschanged = loadVoices;
        }
    }
    
    /**
     * Configurar eventos
     */
    bindEvents() {
        // Panel de accesibilidad
        const toggleBtn = document.getElementById('open-accessibility-panel');
        const closeBtn = document.getElementById('close-accessibility-panel');
        const panel = document.getElementById('accessibility-panel');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.togglePanel());
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closePanel());
        }
        
        // Cerrar panel con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && panel && panel.classList.contains('show')) {
                this.closePanel();
            }
        });
        
        // Botones de accesibilidad
        this.bindAccessibilityButtons();
        
        // Navegación por teclado mejorada
        this.setupFocusManagement();
        
        // Lectura automática de contenido nuevo
        this.setupContentReading();
    }
    
    /**
     * Configurar botones del panel de accesibilidad
     */
    bindAccessibilityButtons() {
        const contrastBtn = document.getElementById('toggle-contrast');
        const textSizeBtn = document.getElementById('toggle-text-size');
        const voiceBtn = document.getElementById('toggle-voice');
        const shortcutsBtn = document.getElementById('show-shortcuts');
        const resetBtn = document.getElementById('reset-accessibility');
        
        if (contrastBtn) {
            contrastBtn.addEventListener('click', () => this.toggleHighContrast());
        }
        
        if (textSizeBtn) {
            textSizeBtn.addEventListener('click', () => this.toggleLargeText());
        }
        
        if (voiceBtn) {
            voiceBtn.addEventListener('click', () => this.toggleVoice());
        }
        
        if (shortcutsBtn) {
            shortcutsBtn.addEventListener('click', () => this.showKeyboardShortcuts());
        }
        
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.resetSettings());
        }
    }
    
    /**
     * Configurar atajos de teclado
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Solo activar con Alt + número
            if (!e.altKey) return;
            
            switch (e.key) {
                case '1':
                    e.preventDefault();
                    this.toggleHighContrast();
                    break;
                case '2':
                    e.preventDefault();
                    this.toggleLargeText();
                    break;
                case '3':
                    e.preventDefault();
                    this.toggleVoice();
                    break;
                case '4':
                    e.preventDefault();
                    this.showKeyboardShortcuts();
                    break;
                case '0':
                    e.preventDefault();
                    this.resetSettings();
                    break;
                case 'h':
                    e.preventDefault();
                    window.location.href = '/';
                    break;
                case 'm':
                    e.preventDefault();
                    const mainNav = document.getElementById('main-navigation');
                    if (mainNav) mainNav.focus();
                    break;
                case 'c':
                    e.preventDefault();
                    const mainContent = document.getElementById('main-content');
                    if (mainContent) mainContent.focus();
                    break;
                case 's':
                    e.preventDefault();
                    const searchInput = document.getElementById('search-input');
                    if (searchInput) searchInput.focus();
                    break;
            }
        });
    }
    
    /**
     * Configurar regiones ARIA live para anuncios
     */
    setupARIALiveRegions() {
        // Crear región para anuncios importantes
        if (!document.getElementById('aria-live-assertive')) {
            const assertiveRegion = document.createElement('div');
            assertiveRegion.id = 'aria-live-assertive';
            assertiveRegion.setAttribute('aria-live', 'assertive');
            assertiveRegion.setAttribute('aria-atomic', 'true');
            assertiveRegion.className = 'sr-only';
            document.body.appendChild(assertiveRegion);
        }
        
        // Crear región para anuncios informativos
        if (!document.getElementById('aria-live-polite')) {
            const politeRegion = document.createElement('div');
            politeRegion.id = 'aria-live-polite';
            politeRegion.setAttribute('aria-live', 'polite');
            politeRegion.setAttribute('aria-atomic', 'true');
            politeRegion.className = 'sr-only';
            document.body.appendChild(politeRegion);
        }
    }
    
    /**
     * Anunciar mensaje a lectores de pantalla
     */
    announce(message, priority = 'polite') {
        const region = document.getElementById(`aria-live-${priority}`);
        if (region) {
            region.textContent = message;
            setTimeout(() => {
                region.textContent = '';
            }, 1000);
        }
    }
    
    /**
     * Alternar panel de accesibilidad
     */
    togglePanel() {
        const panel = document.getElementById('accessibility-panel');
        if (panel) {
            const isVisible = panel.classList.contains('show');
            
            if (isVisible) {
                this.closePanel();
            } else {
                panel.classList.add('show');
                panel.setAttribute('aria-hidden', 'false');
                
                // Enfocar el primer elemento
                const firstButton = panel.querySelector('button');
                if (firstButton) {
                    setTimeout(() => firstButton.focus(), 100);
                }
                
                this.announce('Panel de accesibilidad abierto');
            }
        }
    }
    
    /**
     * Cerrar panel de accesibilidad
     */
    closePanel() {
        const panel = document.getElementById('accessibility-panel');
        if (panel) {
            panel.classList.remove('show');
            panel.setAttribute('aria-hidden', 'true');
            
            // Devolver foco al botón que abrió el panel
            const toggleBtn = document.getElementById('open-accessibility-panel');
            if (toggleBtn) {
                toggleBtn.focus();
            }
            
            this.announce('Panel de accesibilidad cerrado');
        }
    }
    
    /**
     * Alternar alto contraste
     */
    toggleHighContrast() {
        this.settings.highContrast = !this.settings.highContrast;
        this.applyHighContrast();
        this.saveSettings();
        this.updateButtonState('toggle-contrast', this.settings.highContrast);
        
        const message = this.settings.highContrast ? 
            'Alto contraste activado' : 'Alto contraste desactivado';
        this.announce(message);
        this.showMessage(message);
    }
    
    /**
     * Alternar texto grande
     */
    toggleLargeText() {
        this.settings.largeText = !this.settings.largeText;
        this.applyLargeText();
        this.saveSettings();
        this.updateButtonState('toggle-text-size', this.settings.largeText);
        
        const message = this.settings.largeText ? 
            'Texto grande activado' : 'Texto grande desactivado';
        this.announce(message);
        this.showMessage(message);
    }
    
    /**
     * Alternar síntesis de voz
     */
    toggleVoice() {
        this.settings.voiceEnabled = !this.settings.voiceEnabled;
        this.saveSettings();
        this.updateButtonState('toggle-voice', this.settings.voiceEnabled);
        
        if (this.settings.voiceEnabled) {
            this.speak('Síntesis de voz activada. Haga clic en cualquier texto para escucharlo.');
            this.setupVoiceReading();
        } else {
            this.stopSpeaking();
            this.removeVoiceReading();
            this.announce('Síntesis de voz desactivada');
        }
    }
    
    /**
     * Aplicar configuraciones
     */
    applySettings() {
        this.applyHighContrast();
        this.applyLargeText();
        this.updateAllButtonStates();
        
        if (this.settings.voiceEnabled) {
            this.setupVoiceReading();
        }
        
        if (this.settings.keyboardNavigation) {
            document.body.classList.add('keyboard-navigation');
        }
    }
    
    /**
     * Aplicar alto contraste
     */
    applyHighContrast() {
        if (this.settings.highContrast) {
            document.body.classList.add('high-contrast');
        } else {
            document.body.classList.remove('high-contrast');
        }
    }
    
    /**
     * Aplicar texto grande
     */
    applyLargeText() {
        if (this.settings.largeText) {
            document.body.classList.add('large-text');
        } else {
            document.body.classList.remove('large-text');
        }
    }
    
    /**
     * Configurar lectura por voz
     */
    setupVoiceReading() {
        // Agregar eventos de clic para lectura
        document.addEventListener('click', this.handleVoiceClick.bind(this));
        
        // Agregar indicador visual
        document.body.classList.add('voice-enabled');
    }
    
    /**
     * Remover lectura por voz
     */
    removeVoiceReading() {
        document.removeEventListener('click', this.handleVoiceClick.bind(this));
        document.body.classList.remove('voice-enabled');
    }
    
    /**
     * Manejar clic para lectura por voz
     */
    handleVoiceClick(event) {
        if (!this.settings.voiceEnabled) return;
        
        // Ignorar clics en botones de control
        if (event.target.closest('.accessibility-panel') || 
            event.target.closest('.accessibility-toggle-btn')) {
            return;
        }
        
        let textToRead = '';
        
        // Priorizar texto seleccionado
        const selection = window.getSelection();
        if (selection.toString().trim()) {
            textToRead = selection.toString().trim();
        } else {
            // Leer el elemento clickeado
            const element = event.target;
            textToRead = this.extractTextFromElement(element);
        }
        
        if (textToRead) {
            this.speak(textToRead);
        }
    }
    
    /**
     * Extraer texto de un elemento
     */
    extractTextFromElement(element) {
        // Si es una imagen, usar alt text
        if (element.tagName === 'IMG') {
            return element.alt || element.title || 'Imagen sin descripción';
        }
        
        // Si es un enlace, incluir destino
        if (element.tagName === 'A') {
            const text = element.textContent.trim();
            const href = element.getAttribute('href');
            return href ? `${text}, enlace` : text;
        }
        
        // Si es un botón, incluir acción
        if (element.tagName === 'BUTTON' || element.getAttribute('role') === 'button') {
            return element.textContent.trim() + ', botón';
        }
        
        // Para otros elementos, usar textContent
        return element.textContent.trim() || element.innerText.trim();
    }
    
    /**
     * Hablar texto
     */
    speak(text) {
        if (!this.synth || !text) return;
        
        // Detener lectura actual
        this.stopSpeaking();
        
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.voice = this.currentVoice;
        utterance.rate = 0.8;
        utterance.pitch = 1;
        utterance.volume = 1;
        
        utterance.onstart = () => {
            this.isReading = true;
        };
        
        utterance.onend = () => {
            this.isReading = false;
        };
        
        utterance.onerror = () => {
            this.isReading = false;
            console.warn('Error en síntesis de voz');
        };
        
        this.synth.speak(utterance);
    }
    
    /**
     * Detener síntesis de voz
     */
    stopSpeaking() {
        if (this.synth) {
            this.synth.cancel();
            this.isReading = false;
        }
    }
    
    /**
     * Mostrar atajos de teclado
     */
    showKeyboardShortcuts() {
        const modal = document.getElementById('shortcuts-modal');
        if (modal) {
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            
            // Enfocar el modal
            const closeBtn = modal.querySelector('.close');
            if (closeBtn) {
                setTimeout(() => closeBtn.focus(), 100);
            }
            
            this.announce('Modal de atajos de teclado abierto');
        }
    }
    
    /**
     * Cerrar modal
     */
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        }
    }
    
    /**
     * Resetear configuraciones
     */
    resetSettings() {
        this.settings = {
            highContrast: false,
            largeText: false,
            voiceEnabled: false,
            keyboardNavigation: false,
            screenReader: false
        };
        
        this.stopSpeaking();
        this.removeVoiceReading();
        this.applySettings();
        this.saveSettings();
        
        this.announce('Configuraciones de accesibilidad restablecidas');
        this.showMessage('Configuraciones restablecidas');
    }
    
    /**
     * Actualizar estado de botón
     */
    updateButtonState(buttonId, isActive) {
        const button = document.getElementById(buttonId);
        if (button) {
            if (isActive) {
                button.classList.add('active');
                button.setAttribute('aria-pressed', 'true');
            } else {
                button.classList.remove('active');
                button.setAttribute('aria-pressed', 'false');
            }
        }
    }
    
    /**
     * Actualizar todos los estados de botones
     */
    updateAllButtonStates() {
        this.updateButtonState('toggle-contrast', this.settings.highContrast);
        this.updateButtonState('toggle-text-size', this.settings.largeText);
        this.updateButtonState('toggle-voice', this.settings.voiceEnabled);
    }
    
    /**
     * Mostrar mensaje temporal
     */
    showMessage(message) {
        // Crear o actualizar mensaje
        let messageEl = document.getElementById('accessibility-message');
        if (!messageEl) {
            messageEl = document.createElement('div');
            messageEl.id = 'accessibility-message';
            messageEl.className = 'accessibility-message';
            messageEl.setAttribute('role', 'status');
            messageEl.setAttribute('aria-live', 'polite');
            document.body.appendChild(messageEl);
        }
        
        messageEl.textContent = message;
        messageEl.classList.add('show');
        
        setTimeout(() => {
            messageEl.classList.remove('show');
        }, 3000);
    }
    
    /**
     * Configurar gestión de foco
     */
    setupFocusManagement() {
        // Detectar navegación por teclado
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
                this.settings.keyboardNavigation = true;
            }
        });
        
        // Detectar navegación por mouse
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Mejorar visibilidad del foco
        this.setupFocusTrapping();
    }
    
    /**
     * Configurar trampa de foco para modales
     */
    setupFocusTrapping() {
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(modal => {
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Tab' && modal.classList.contains('show')) {
                    this.trapFocus(e, modal);
                }
            });
        });
    }
    
    /**
     * Atrapar foco dentro de un contenedor
     */
    trapFocus(event, container) {
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (event.shiftKey) {
            if (document.activeElement === firstElement) {
                lastElement.focus();
                event.preventDefault();
            }
        } else {
            if (document.activeElement === lastElement) {
                firstElement.focus();
                event.preventDefault();
            }
        }
    }
    
    /**
     * Configurar lectura de contenido nuevo
     */
    setupContentReading() {
        // Observar cambios en el DOM para contenido dinámico
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            this.announceNewContent(node);
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    /**
     * Anunciar contenido nuevo
     */
    announceNewContent(element) {
        // Solo anunciar elementos importantes
        if (element.classList.contains('alert') || 
            element.getAttribute('role') === 'alert' ||
            element.classList.contains('flash-message')) {
            
            const text = element.textContent.trim();
            if (text) {
                this.announce(text, 'assertive');
                
                if (this.settings.voiceEnabled) {
                    setTimeout(() => this.speak(text), 500);
                }
            }
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.accessibilityManager = new AccessibilityManager();
    
    // Configurar eventos para cerrar modales
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('close') || 
            e.target.closest('.close')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            }
        }
        
        // Cerrar modal clickeando fuera
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('show');
            e.target.setAttribute('aria-hidden', 'true');
        }
    });
    
    // Configurar eventos para alertas
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('close') && 
            e.target.closest('.alert')) {
            const alert = e.target.closest('.alert');
            alert.style.display = 'none';
        }
    });
});

// Exportar para uso global
window.AccessibilityManager = AccessibilityManager;
