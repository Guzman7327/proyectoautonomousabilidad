/**
 * ACCESIBILIDAD AVANZADA
 * Funcionalidades de accesibilidad para el Portal de Turismo Inclusivo
 */

class AccessibilityManager {
    constructor() {
        this.settings = this.loadSettings();
        this.init();
    }
    
    init() {
        this.setupAccessibilitySidebar();
        this.setupKeyboardShortcuts();
        this.setupScreenReader();
        this.setupHighContrast();
        this.setupLargeText();
        this.setupMonochrome();
        this.setupDyslexiaFriendly();
        this.setupFocusHighlight();
        this.setupReducedMotion();
        this.setupLanguageSwitcher();
        this.setupAutoSave();
        this.applySettings();
    }
    
    // ========================================
    // 1. MENÚ DE ACCESIBILIDAD INTEGRADO
    // ========================================
    
    setupAccessibilitySidebar() {
        const menu = document.getElementById('accessibility-menu');
        const toggle = menu?.querySelector('.accessibility-toggle');
        const panel = menu?.querySelector('.accessibility-panel');
        
        if (!menu || !toggle || !panel) return;
        
        // Toggle del menú
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                toggle.setAttribute('aria-expanded', 'false');
                panel.setAttribute('aria-hidden', 'true');
            } else {
                toggle.setAttribute('aria-expanded', 'true');
                panel.setAttribute('aria-hidden', 'false');
            }
            
            this.announceToScreenReader(isExpanded ? 'Menú de accesibilidad cerrado' : 'Menú de accesibilidad abierto');
        });
        
        // Cerrar al hacer clic fuera del menú
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target)) {
                toggle.setAttribute('aria-expanded', 'false');
                panel.setAttribute('aria-hidden', 'true');
            }
        });
        
        // Cerrar con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
                toggle.setAttribute('aria-expanded', 'false');
                panel.setAttribute('aria-hidden', 'true');
                toggle.focus();
            }
        });
        
        // Configurar botones de accesibilidad
        this.setupAccessibilityButtons();
    }
    
    setupAccessibilityButtons() {
        const buttons = document.querySelectorAll('.accessibility-btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const setting = button.dataset.setting;
                const isPressed = button.getAttribute('aria-pressed') === 'true';
                
                // Toggle estado
                button.setAttribute('aria-pressed', !isPressed);
                
                // Aplicar configuración
                this.toggleSetting(setting, !isPressed);
                
                // Anunciar cambio
                const settingName = button.textContent.trim();
                const status = !isPressed ? 'activado' : 'desactivado';
                this.announceToScreenReader(`${settingName} ${status}`);
            });
        });
    }
    
    toggleSetting(setting, enabled) {
        switch (setting) {
            case 'high_contrast':
                this.toggleHighContrast(enabled);
                break;
            case 'large_text':
                this.toggleLargeText(enabled);
                break;
            case 'monochrome':
                this.toggleMonochrome(enabled);
                break;
            case 'dyslexia_friendly':
                this.toggleDyslexiaFriendly(enabled);
                break;
            case 'keyboard_navigation':
                this.toggleKeyboardNavigation(enabled);
                break;
            case 'focus_highlight':
                this.toggleFocusHighlight(enabled);
                break;
            case 'reduced_motion':
                this.toggleReducedMotion(enabled);
                break;
            case 'screen_reader':
                this.toggleScreenReader(enabled);
                break;
            case 'audio_description':
                this.toggleAudioDescription(enabled);
                break;
            case 'visual_alerts':
                this.toggleVisualAlerts(enabled);
                break;
        }
        
        // Guardar configuración
        this.settings[setting] = enabled;
        this.saveSettings();
    }
    
    // ========================================
    // 2. ATAJOS DE TECLADO
    // ========================================
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Alt + A: Abrir menú de accesibilidad
            if (e.altKey && e.key === 'a') {
                e.preventDefault();
                this.toggleAccessibilityMenu();
            }
            
            // Alt + 1: Ir al contenido principal
            if (e.altKey && e.key === '1') {
                e.preventDefault();
                this.focusMainContent();
            }
            
            // Alt + 2: Ir a la navegación
            if (e.altKey && e.key === '2') {
                e.preventDefault();
                this.focusNavigation();
            }
            
            // Alt + 3: Ir a la búsqueda
            if (e.altKey && e.key === '3') {
                e.preventDefault();
                this.focusSearch();
            }
            
            // Alt + 4: Ir al pie de página
            if (e.altKey && e.key === '4') {
                e.preventDefault();
                this.focusFooter();
            }
            
            // Escape: Cerrar menús
            if (e.key === 'Escape') {
                this.closeAllMenus();
            }
        });
    }
    
    toggleAccessibilityMenu() {
        const sidebar = document.getElementById('accessibility-sidebar');
        const toggle = sidebar?.querySelector('.accessibility-toggle');
        
        if (toggle) {
            toggle.click();
        }
    }
    
    focusMainContent() {
        const main = document.querySelector('main');
        if (main) {
            main.focus();
            this.announceToScreenReader('Contenido principal');
        }
    }
    
    focusNavigation() {
        const nav = document.querySelector('.main-navigation');
        if (nav) {
            nav.focus();
            this.announceToScreenReader('Navegación principal');
        }
    }
    
    focusSearch() {
        const search = document.getElementById('search-input');
        if (search) {
            search.focus();
            this.announceToScreenReader('Campo de búsqueda');
        }
    }
    
    focusFooter() {
        const footer = document.querySelector('.site-footer');
        if (footer) {
            footer.focus();
            this.announceToScreenReader('Pie de página');
        }
    }
    
    closeAllMenus() {
        const dropdowns = document.querySelectorAll('.dropdown-toggle[aria-expanded="true"]');
        dropdowns.forEach(dropdown => {
            dropdown.setAttribute('aria-expanded', 'false');
        });
        
        const sidebar = document.getElementById('accessibility-sidebar');
        if (sidebar?.classList.contains('expanded')) {
            sidebar.classList.remove('expanded');
            const toggle = sidebar.querySelector('.accessibility-toggle');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        }
    }
    
    // ========================================
    // 3. LECTOR DE PANTALLA
    // ========================================
    
    setupScreenReader() {
        // Crear elemento para anuncios
        this.screenReaderElement = document.createElement('div');
        this.screenReaderElement.setAttribute('aria-live', 'polite');
        this.screenReaderElement.setAttribute('aria-atomic', 'true');
        this.screenReaderElement.className = 'sr-only';
        document.body.appendChild(this.screenReaderElement);
    }
    
    announceToScreenReader(message) {
        if (!this.settings.screen_reader) return;
        
        if (this.screenReaderElement) {
            this.screenReaderElement.textContent = message;
            
            // Limpiar después de un tiempo
            setTimeout(() => {
                this.screenReaderElement.textContent = '';
            }, 1000);
        }
    }
    
    // ========================================
    // 4. ALTO CONTRASTE
    // ========================================
    
    setupHighContrast() {
        this.highContrastStyles = `
            .high-contrast {
                --primary-color: #000000 !important;
                --secondary-color: #ffffff !important;
                --accent-color: #ffff00 !important;
                --text-color: #000000 !important;
                --bg-color: #ffffff !important;
                --border-color: #000000 !important;
                --link-color: #0000ff !important;
                --visited-color: #800080 !important;
                --focus-color: #ffff00 !important;
            }
            
            .high-contrast * {
                background-color: var(--bg-color) !important;
                color: var(--text-color) !important;
                border-color: var(--border-color) !important;
            }
            
            .high-contrast a {
                color: var(--link-color) !important;
                text-decoration: underline !important;
            }
            
            .high-contrast a:visited {
                color: var(--visited-color) !important;
            }
            
            .high-contrast *:focus {
                outline: 3px solid var(--focus-color) !important;
                outline-offset: 2px !important;
                box-shadow: 0 0 0 3px rgba(255, 255, 0, 0.5) !important;
            }
        `;
    }
    
    toggleHighContrast(enabled) {
        if (enabled) {
            this.addStyles(this.highContrastStyles, 'high-contrast-styles');
            document.documentElement.classList.add('high-contrast');
        } else {
            this.removeStyles('high-contrast-styles');
            document.documentElement.classList.remove('high-contrast');
        }
    }
    
    // ========================================
    // 5. TEXTO GRANDE
    // ========================================
    
    toggleLargeText(enabled) {
        if (enabled) {
            document.documentElement.classList.add('large-text');
            document.documentElement.style.fontSize = '120%';
        } else {
            document.documentElement.classList.remove('large-text');
            document.documentElement.style.fontSize = '';
        }
    }
    
    // ========================================
    // 6. MODO MONOCROMÁTICO
    // ========================================
    
    toggleMonochrome(enabled) {
        if (enabled) {
            document.documentElement.classList.add('monochrome');
            document.documentElement.style.filter = 'grayscale(100%)';
        } else {
            document.documentElement.classList.remove('monochrome');
            document.documentElement.style.filter = '';
        }
    }
    
    // ========================================
    // 7. TIPOGRAFÍA PARA DISLEXIA
    // ========================================
    
    toggleDyslexiaFriendly(enabled) {
        if (enabled) {
            document.documentElement.classList.add('dyslexia-friendly');
            document.documentElement.style.fontFamily = "'OpenDyslexic', 'Comic Sans MS', 'Arial', sans-serif";
            document.documentElement.style.fontSize = '18px';
            document.documentElement.style.lineHeight = '1.8';
            document.documentElement.style.letterSpacing = '0.1em';
            document.documentElement.style.wordSpacing = '0.2em';
        } else {
            document.documentElement.classList.remove('dyslexia-friendly');
            document.documentElement.style.fontFamily = '';
            document.documentElement.style.fontSize = '';
            document.documentElement.style.lineHeight = '';
            document.documentElement.style.letterSpacing = '';
            document.documentElement.style.wordSpacing = '';
        }
    }
    
    // ========================================
    // 8. RESALTADO DE FOCO
    // ========================================
    
    toggleFocusHighlight(enabled) {
        if (enabled) {
            document.documentElement.classList.add('enhanced-focus');
        } else {
            document.documentElement.classList.remove('enhanced-focus');
        }
    }
    
    // ========================================
    // 9. REDUCCIÓN DE MOVIMIENTO
    // ========================================
    
    toggleReducedMotion(enabled) {
        if (enabled) {
            document.documentElement.classList.add('reduced-motion');
        } else {
            document.documentElement.classList.remove('reduced-motion');
        }
    }
    
    // ========================================
    // 10. NAVEGACIÓN POR TECLADO
    // ========================================
    
    toggleKeyboardNavigation(enabled) {
        if (enabled) {
            document.documentElement.classList.add('keyboard-navigation');
            this.setupKeyboardNavigation();
        } else {
            document.documentElement.classList.remove('keyboard-navigation');
            this.removeKeyboardNavigation();
        }
    }
    
    setupKeyboardNavigation() {
        // Hacer todos los elementos enfocables
        const interactiveElements = document.querySelectorAll('a, button, input, select, textarea, [tabindex]');
        interactiveElements.forEach(element => {
            if (!element.hasAttribute('tabindex')) {
                element.setAttribute('tabindex', '0');
            }
        });
    }
    
    removeKeyboardNavigation() {
        // Remover tabindex agregado dinámicamente
        const elements = document.querySelectorAll('[tabindex="0"]');
        elements.forEach(element => {
            if (!element.matches('a, button, input, select, textarea')) {
                element.removeAttribute('tabindex');
            }
        });
    }
    
    // ========================================
    // 11. DESCRIPCIÓN DE AUDIO
    // ========================================
    
    toggleAudioDescription(enabled) {
        if (enabled) {
            this.setupAudioDescription();
        } else {
            this.removeAudioDescription();
        }
    }
    
    setupAudioDescription() {
        // Implementar descripción de audio
        console.log('Descripción de audio activada');
    }
    
    removeAudioDescription() {
        // Remover descripción de audio
        console.log('Descripción de audio desactivada');
    }
    
    // ========================================
    // 12. ALERTAS VISUALES
    // ========================================
    
    toggleVisualAlerts(enabled) {
        if (enabled) {
            this.setupVisualAlerts();
        } else {
            this.removeVisualAlerts();
        }
    }
    
    setupVisualAlerts() {
        // Implementar alertas visuales
        console.log('Alertas visuales activadas');
    }
    
    removeVisualAlerts() {
        // Remover alertas visuales
        console.log('Alertas visuales desactivadas');
    }
    
    // ========================================
    // 13. CAMBIO DE IDIOMA
    // ========================================
    
    setupLanguageSwitcher() {
        const languageButtons = document.querySelectorAll('.language-btn');
        
        languageButtons.forEach(button => {
            button.addEventListener('click', () => {
                const lang = button.dataset.lang;
                this.changeLanguage(lang);
                
                // Actualizar botones activos
                languageButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                this.announceToScreenReader(`Idioma cambiado a ${button.textContent}`);
            });
        });
    }
    
    changeLanguage(lang) {
        // Implementar cambio de idioma
        console.log(`Cambiando idioma a: ${lang}`);
        
        // Aquí iría la lógica para cambiar el idioma
        // Por ahora solo guardamos la preferencia
        this.settings.language = lang;
        this.saveSettings();
    }
    
    // ========================================
    // 14. AUTO-GUARDADO
    // ========================================
    
    setupAutoSave() {
        // Configurar botones de guardar/restablecer
        const saveBtn = document.getElementById('save-accessibility');
        const resetBtn = document.getElementById('reset-accessibility');
        
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                this.saveSettings();
                this.announceToScreenReader('Preferencias guardadas');
                this.showNotification('Preferencias guardadas correctamente');
            });
        }
        
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                this.resetSettings();
                this.announceToScreenReader('Preferencias restablecidas');
                this.showNotification('Preferencias restablecidas');
            });
        }
    }
    
    // ========================================
    // 15. GESTIÓN DE CONFIGURACIONES
    // ========================================
    
    loadSettings() {
        const saved = localStorage.getItem('accessibilitySettings');
        return saved ? JSON.parse(saved) : {
            high_contrast: false,
            large_text: false,
            monochrome: false,
            dyslexia_friendly: false,
            keyboard_navigation: false,
            focus_highlight: false,
            reduced_motion: false,
            screen_reader: true,
            audio_description: false,
            visual_alerts: false,
            language: 'es'
        };
    }
    
    saveSettings() {
        localStorage.setItem('accessibilitySettings', JSON.stringify(this.settings));
    }
    
    resetSettings() {
        this.settings = {
            high_contrast: false,
            large_text: false,
            monochrome: false,
            dyslexia_friendly: false,
            keyboard_navigation: false,
            focus_highlight: false,
            reduced_motion: false,
            screen_reader: true,
            audio_description: false,
            visual_alerts: false,
            language: 'es'
        };
        
        this.saveSettings();
        this.applySettings();
    }
    
    applySettings() {
        // Aplicar todas las configuraciones guardadas
        Object.keys(this.settings).forEach(setting => {
            if (this.settings[setting]) {
                this.toggleSetting(setting, true);
            }
        });
    }
    
    // ========================================
    // 16. UTILIDADES
    // ========================================
    
    addStyles(css, id) {
        let style = document.getElementById(id);
        if (!style) {
            style = document.createElement('style');
            style.id = id;
            document.head.appendChild(style);
        }
        style.textContent = css;
    }
    
    removeStyles(id) {
        const style = document.getElementById(id);
        if (style) {
            style.remove();
        }
    }
    
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#059669' : '#ef4444'};
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            max-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.accessibility = new AccessibilityManager();
});

// Exportar para uso global
window.AccessibilityManager = AccessibilityManager;
