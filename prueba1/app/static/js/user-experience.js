/**
 * MEJORAS DE EXPERIENCIA DE USUARIO
 * Funcionalidades avanzadas para mejorar la usabilidad
 */

class UserExperienceManager {
    constructor() {
        this.preferences = this.loadPreferences();
        this.initializeFeatures();
    }
    
    loadPreferences() {
        const saved = localStorage.getItem('userPreferences');
        return saved ? JSON.parse(saved) : {
            theme: 'auto',
            language: 'es',
            notifications: true,
            autoSave: true,
            animations: true,
            soundEffects: false
        };
    }
    
    savePreferences() {
        localStorage.setItem('userPreferences', JSON.stringify(this.preferences));
    }
    
    initializeFeatures() {
        this.setupTheme();
        this.setupNotifications();
        this.setupAutoSave();
        this.setupKeyboardShortcuts();
        this.setupProgressiveWebApp();
        this.setupOfflineSupport();
        this.setupPerformanceOptimizations();
    }
    
    // ========================================
    // 1. SISTEMA DE TEMAS
    // ========================================
    
    setupTheme() {
        const theme = this.preferences.theme;
        
        if (theme === 'auto') {
            this.setupAutoTheme();
        } else {
            this.applyTheme(theme);
        }
        
        // Botón de cambio de tema
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                this.cycleTheme();
            });
        }
    }
    
    setupAutoTheme() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        const handleChange = (e) => {
            this.applyTheme(e.matches ? 'dark' : 'light');
        };
        
        mediaQuery.addEventListener('change', handleChange);
        handleChange(mediaQuery);
    }
    
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        this.preferences.theme = theme;
        this.savePreferences();
        
        // Anunciar cambio
        if (window.accessibility) {
            window.accessibility.announceToScreenReader(`Tema ${theme} aplicado`);
        }
    }
    
    cycleTheme() {
        const themes = ['light', 'dark', 'auto'];
        const currentIndex = themes.indexOf(this.preferences.theme);
        const nextIndex = (currentIndex + 1) % themes.length;
        const nextTheme = themes[nextIndex];
        
        this.applyTheme(nextTheme);
    }
    
    // ========================================
    // 2. SISTEMA DE NOTIFICACIONES
    // ========================================
    
    setupNotifications() {
        if (!this.preferences.notifications) return;
        
        // Solicitar permisos
        if ('Notification' in window && Notification.permission === 'default') {
            this.requestNotificationPermission();
        }
        
        // Configurar notificaciones push
        this.setupPushNotifications();
    }
    
    async requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                this.showNotification('Notificaciones activadas', 'Recibirás actualizaciones sobre nuevos destinos y promociones');
            }
        } catch (error) {
            console.error('Error al solicitar permisos de notificación:', error);
        }
    }
    
    setupPushNotifications() {
        // Aquí iría la configuración de push notifications
        // con un servicio como Firebase Cloud Messaging
    }
    
    showNotification(title, body, options = {}) {
        if (!this.preferences.notifications) return;
        
        if ('Notification' in window && Notification.permission === 'granted') {
            const notification = new Notification(title, {
                icon: '/static/images/logo.png',
                badge: '/static/images/badge.png',
                ...options,
                body
            });
            
            notification.onclick = () => {
                window.focus();
                notification.close();
            };
        }
    }
    
    // ========================================
    // 3. AUTO-GUARDADO
    // ========================================
    
    setupAutoSave() {
        if (!this.preferences.autoSave) return;
        
        const forms = document.querySelectorAll('form[data-autosave]');
        forms.forEach(form => {
            this.setupFormAutoSave(form);
        });
    }
    
    setupFormAutoSave(form) {
        const formId = form.id || 'form-' + Math.random().toString(36).substr(2, 9);
        const storageKey = `autosave_${formId}`;
        
        // Cargar datos guardados
        const savedData = localStorage.getItem(storageKey);
        if (savedData) {
            this.restoreFormData(form, JSON.parse(savedData));
        }
        
        // Guardar en cambios
        const debouncedSave = debounce((formData) => {
            localStorage.setItem(storageKey, JSON.stringify(formData));
        }, 1000);
        
        form.addEventListener('input', () => {
            const formData = this.getFormData(form);
            debouncedSave(formData);
        });
        
        // Limpiar al enviar
        form.addEventListener('submit', () => {
            localStorage.removeItem(storageKey);
        });
    }
    
    getFormData(form) {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        return data;
    }
    
    restoreFormData(form, data) {
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                field.value = data[key];
            }
        });
    }
    
    // ========================================
    // 4. ATAJOS DE TECLADO AVANZADOS
    // ========================================
    
    setupKeyboardShortcuts() {
        const shortcuts = {
            'Ctrl+s': () => this.saveCurrentPage(),
            'Ctrl+f': () => this.focusSearch(),
            'Ctrl+b': () => this.toggleBookmarks(),
            'Ctrl+m': () => this.toggleMenu(),
            'Ctrl+t': () => this.toggleTheme(),
            'F1': () => this.showHelp(),
            'F5': () => this.refreshWithCache(),
            'Ctrl+Shift+R': () => this.hardRefresh()
        };
        
        document.addEventListener('keydown', (e) => {
            const key = this.getKeyCombo(e);
            
            if (shortcuts[key]) {
                e.preventDefault();
                shortcuts[key]();
            }
        });
    }
    
    getKeyCombo(e) {
        const keys = [];
        if (e.ctrlKey) keys.push('Ctrl');
        if (e.shiftKey) keys.push('Shift');
        if (e.altKey) keys.push('Alt');
        if (e.metaKey) keys.push('Meta');
        
        if (e.key !== 'Control' && e.key !== 'Shift' && e.key !== 'Alt' && e.key !== 'Meta') {
            keys.push(e.key.toLowerCase());
        }
        
        return keys.join('+');
    }
    
    saveCurrentPage() {
        // Implementar guardado de página actual
        console.log('Guardando página actual...');
    }
    
    focusSearch() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    toggleBookmarks() {
        // Implementar toggle de marcadores
        console.log('Toggle de marcadores...');
    }
    
    toggleMenu() {
        const navToggle = document.querySelector('.nav-toggle');
        if (navToggle) {
            navToggle.click();
        }
    }
    
    toggleTheme() {
        this.cycleTheme();
    }
    
    showHelp() {
        // Mostrar modal de ayuda
        this.showHelpModal();
    }
    
    refreshWithCache() {
        window.location.reload();
    }
    
    hardRefresh() {
        window.location.reload(true);
    }
    
    // ========================================
    // 5. PROGRESSIVE WEB APP
    // ========================================
    
    setupProgressiveWebApp() {
        // Detectar si la app está instalada
        if (window.matchMedia('(display-mode: standalone)').matches) {
            document.body.classList.add('pwa-installed');
        }
        
        // Mostrar prompt de instalación
        this.setupInstallPrompt();
        
        // Configurar splash screen
        this.setupSplashScreen();
    }
    
    setupInstallPrompt() {
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Mostrar botón de instalación
            this.showInstallButton();
        });
        
        window.addEventListener('appinstalled', () => {
            console.log('PWA instalada');
            this.hideInstallButton();
        });
    }
    
    showInstallButton() {
        const installBtn = document.createElement('button');
        installBtn.id = 'install-pwa';
        installBtn.className = 'btn btn-primary';
        installBtn.innerHTML = '📱 Instalar App';
        installBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            animation: slideInLeft 0.3s ease;
        `;
        
        installBtn.addEventListener('click', () => {
            this.installPWA();
        });
        
        document.body.appendChild(installBtn);
    }
    
    hideInstallButton() {
        const installBtn = document.getElementById('install-pwa');
        if (installBtn) {
            installBtn.remove();
        }
    }
    
    async installPWA() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.ready;
                await registration.update();
                console.log('PWA actualizada');
            } catch (error) {
                console.error('Error al actualizar PWA:', error);
            }
        }
    }
    
    setupSplashScreen() {
        // Configurar splash screen para PWA
        const splash = document.createElement('div');
        splash.id = 'splash-screen';
        splash.innerHTML = `
            <div class="splash-content">
                <img src="/static/images/logo.png" alt="Logo" class="splash-logo">
                <h1>Turismo Inclusivo Ecuador</h1>
                <div class="splash-spinner"></div>
            </div>
        `;
        splash.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            color: white;
            text-align: center;
        `;
        
        document.body.appendChild(splash);
        
        // Ocultar splash screen después de carga
        window.addEventListener('load', () => {
            setTimeout(() => {
                splash.style.opacity = '0';
                setTimeout(() => {
                    splash.remove();
                }, 300);
            }, 1000);
        });
    }
    
    // ========================================
    // 6. SOPORTE OFFLINE
    // ========================================
    
    setupOfflineSupport() {
        // Detectar estado de conexión
        window.addEventListener('online', () => {
            this.handleOnline();
        });
        
        window.addEventListener('offline', () => {
            this.handleOffline();
        });
        
        // Configurar cache offline
        this.setupOfflineCache();
    }
    
    handleOnline() {
        document.body.classList.remove('offline');
        this.showNotification('Conexión restaurada', 'Ya puedes acceder a todas las funciones');
        
        // Sincronizar datos pendientes
        this.syncPendingData();
    }
    
    handleOffline() {
        document.body.classList.add('offline');
        this.showNotification('Sin conexión', 'Algunas funciones pueden no estar disponibles');
    }
    
    setupOfflineCache() {
        // Configurar cache para recursos críticos
        const criticalResources = [
            '/static/css/main.css',
            '/static/js/main.js',
            '/static/images/logo.png'
        ];
        
        if ('caches' in window) {
            caches.open('critical-resources').then(cache => {
                cache.addAll(criticalResources);
            });
        }
    }
    
    syncPendingData() {
        // Sincronizar datos guardados offline
        const pendingData = localStorage.getItem('pendingData');
        if (pendingData) {
            // Enviar datos al servidor
            console.log('Sincronizando datos pendientes...');
            localStorage.removeItem('pendingData');
        }
    }
    
    // ========================================
    // 7. OPTIMIZACIONES DE PERFORMANCE
    // ========================================
    
    setupPerformanceOptimizations() {
        // Lazy loading de imágenes
        this.setupImageLazyLoading();
        
        // Prefetch de recursos
        this.setupResourcePrefetch();
        
        // Optimización de scroll
        this.setupScrollOptimization();
        
        // Compresión de datos
        this.setupDataCompression();
    }
    
    setupImageLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    setupResourcePrefetch() {
        // Prefetch de páginas comunes
        const prefetchLinks = [
            '/destinations/',
            '/auth/login',
            '/auth/register'
        ];
        
        prefetchLinks.forEach(href => {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = href;
            document.head.appendChild(link);
        });
    }
    
    setupScrollOptimization() {
        let ticking = false;
        
        function updateScroll() {
            // Optimizar operaciones de scroll
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateScroll);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    }
    
    setupDataCompression() {
        // Comprimir datos antes de enviar al servidor
        if (window.CompressionStream) {
            // Usar CompressionStream API si está disponible
        }
    }
    
    // ========================================
    // 8. FUNCIONES DE AYUDA
    // ========================================
    
    showHelpModal() {
        const helpModal = document.createElement('div');
        helpModal.className = 'modal';
        helpModal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Ayuda y Atajos</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <h3>Atajos de Teclado</h3>
                    <ul>
                        <li><strong>Ctrl + S:</strong> Guardar página</li>
                        <li><strong>Ctrl + F:</strong> Buscar</li>
                        <li><strong>Ctrl + T:</strong> Cambiar tema</li>
                        <li><strong>F1:</strong> Mostrar ayuda</li>
                    </ul>
                    
                    <h3>Funciones de Accesibilidad</h3>
                    <ul>
                        <li><strong>Alt + 1:</strong> Ir al contenido principal</li>
                        <li><strong>Alt + 2:</strong> Ir a la navegación</li>
                        <li><strong>Alt + A:</strong> Configuración de accesibilidad</li>
                    </ul>
                </div>
            </div>
        `;
        
        document.body.appendChild(helpModal);
        
        // Cerrar modal
        const closeBtn = helpModal.querySelector('.modal-close');
        closeBtn.addEventListener('click', () => {
            helpModal.remove();
        });
        
        helpModal.addEventListener('click', (e) => {
            if (e.target === helpModal) {
                helpModal.remove();
            }
        });
    }
    
    // ========================================
    // 9. ANALYTICS Y FEEDBACK
    // ========================================
    
    trackUserAction(action, data = {}) {
        const event = {
            action,
            timestamp: new Date().toISOString(),
            url: window.location.href,
            userAgent: navigator.userAgent,
            ...data
        };
        
        console.log('User action tracked:', event);
        
        // Aquí podrías enviar a Google Analytics, Mixpanel, etc.
    }
    
    showFeedbackForm() {
        // Mostrar formulario de feedback
        const feedbackModal = document.createElement('div');
        feedbackModal.className = 'modal';
        feedbackModal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Envíanos tu Feedback</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="feedback-form">
                        <div class="form-group">
                            <label for="feedback-type">Tipo de feedback</label>
                            <select id="feedback-type" name="type" required>
                                <option value="">Selecciona...</option>
                                <option value="bug">Reportar un error</option>
                                <option value="feature">Sugerir función</option>
                                <option value="improvement">Mejora</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="feedback-message">Mensaje</label>
                            <textarea id="feedback-message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Feedback</button>
                    </form>
                </div>
            </div>
        `;
        
        document.body.appendChild(feedbackModal);
        
        // Manejar envío
        const form = feedbackModal.querySelector('#feedback-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitFeedback(new FormData(form));
            feedbackModal.remove();
        });
    }
    
    submitFeedback(formData) {
        // Enviar feedback al servidor
        console.log('Feedback enviado:', Object.fromEntries(formData));
        this.showNotification('Feedback enviado', 'Gracias por tu comentario');
    }
}

// Función debounce
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

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.userExperience = new UserExperienceManager();
});

// Exportar para uso global
window.UserExperienceManager = UserExperienceManager; 