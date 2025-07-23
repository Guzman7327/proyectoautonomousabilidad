// ===== CONFIGURACIÓN GLOBAL =====
const CONFIG = {
    API_BASE_URL: window.location.origin,
    ANIMATION_DURATION: 300,
    SCROLL_THRESHOLD: 100
};

// ===== UTILIDADES =====
const Utils = {
    // Debounce function para optimizar eventos
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Animación suave para scroll
    smoothScrollTo(target, duration = 800) {
        const targetElement = typeof target === 'string' ? document.querySelector(target) : target;
        if (!targetElement) return;

        const targetPosition = targetElement.offsetTop - 80; // Offset para header fijo
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
    },

    // Formatear fecha
    formatDate(date) {
        return new Intl.DateTimeFormat('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    },

    // Validar email
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    // Mostrar/ocultar loading
    showLoading(element) {
        if (element) {
            element.innerHTML = '<span class="loading-spinner"></span> Cargando...';
            element.disabled = true;
        }
    },

    hideLoading(element, originalText = 'Enviar') {
        if (element) {
            element.innerHTML = originalText;
            element.disabled = false;
        }
    }
};

// ===== MANEJO DE NOTIFICACIONES =====
class NotificationManager {
    constructor() {
        this.container = this.createContainer();
    }

    createContainer() {
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }
        return container;
    }

    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.style.cssText = `
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            margin-bottom: 10px;
            cursor: pointer;
        `;
        notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="margin-left: auto; background: none; border: none; color: inherit; cursor: pointer; font-size: 18px;">&times;</button>
        `;

        this.container.appendChild(notification);

        // Animación de entrada
        requestAnimationFrame(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        });

        // Auto-remove
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, duration);
        }

        return notification;
    }

    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 7000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 6000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }
}

// ===== HEADER INTERACTIVO =====
class HeaderManager {
    constructor() {
        this.header = document.querySelector('.header');
        this.lastScrollY = window.scrollY;
        this.init();
    }

    init() {
        if (!this.header) return;

        // Scroll effect
        window.addEventListener('scroll', Utils.debounce(() => {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > CONFIG.SCROLL_THRESHOLD) {
                this.header.classList.add('scrolled');
            } else {
                this.header.classList.remove('scrolled');
            }

            this.lastScrollY = currentScrollY;
        }, 10));

        // Mobile menu toggle
        this.setupMobileMenu();
    }

    setupMobileMenu() {
        const mobileMenuButton = document.querySelector('.mobile-menu-toggle');
        const navLinks = document.querySelector('.nav-links');

        if (mobileMenuButton && navLinks) {
            mobileMenuButton.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                mobileMenuButton.classList.toggle('active');
            });
        }
    }
}

// ===== VALIDACIÓN DE FORMULARIOS =====
class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = {};
        this.init();
    }

    init() {
        if (!this.form) return;

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.validateForm();
        });

        // Validación en tiempo real
        const inputs = this.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        const fieldType = field.type;
        const isRequired = field.hasAttribute('required');

        // Limpiar error previo
        this.clearFieldError(field);

        // Validar campo requerido
        if (isRequired && !value) {
            this.setFieldError(field, 'Este campo es obligatorio');
            return false;
        }

        // Validaciones específicas por tipo
        switch (fieldType) {
            case 'email':
                if (value && !Utils.validateEmail(value)) {
                    this.setFieldError(field, 'Por favor ingresa un email válido');
                    return false;
                }
                break;
            case 'tel':
                if (value && !/^\+?[\d\s-()]+$/.test(value)) {
                    this.setFieldError(field, 'Por favor ingresa un teléfono válido');
                    return false;
                }
                break;
            case 'number':
                if (value && isNaN(value)) {
                    this.setFieldError(field, 'Por favor ingresa un número válido');
                    return false;
                }
                break;
        }

        // Validaciones personalizadas
        if (fieldName === 'password' && value.length < 6) {
            this.setFieldError(field, 'La contraseña debe tener al menos 6 caracteres');
            return false;
        }

        if (fieldName === 'confirm_password') {
            const password = this.form.querySelector('[name="password"]');
            if (password && value !== password.value) {
                this.setFieldError(field, 'Las contraseñas no coinciden');
                return false;
            }
        }

        return true;
    }

    setFieldError(field, message) {
        field.classList.add('error');
        this.errors[field.name] = message;

        // Mostrar mensaje de error
        let errorElement = field.parentElement.querySelector('.field-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            errorElement.style.cssText = `
                color: #ef4444;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            `;
            field.parentElement.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove('error');
        delete this.errors[field.name];

        const errorElement = field.parentElement.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    validateForm() {
        const inputs = this.form.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        if (isValid) {
            this.submitForm();
        } else {
            notifications.error('Por favor corrige los errores en el formulario');
        }
    }

    async submitForm() {
        const submitButton = this.form.querySelector('[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            Utils.showLoading(submitButton);

            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                notifications.success(result.message || 'Formulario enviado correctamente');
                
                // Redireccionar si se especifica
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    this.form.reset();
                }
            } else {
                notifications.error(result.message || 'Error al enviar el formulario');
            }
        } catch (error) {
            console.error('Error:', error);
            notifications.error('Error de conexión. Por favor intenta nuevamente.');
        } finally {
            Utils.hideLoading(submitButton, originalText);
        }
    }
}

// ===== BÚSQUEDA DINÁMICA =====
class SearchManager {
    constructor() {
        this.searchInputs = document.querySelectorAll('.search-input');
        this.init();
    }

    init() {
        this.searchInputs.forEach(input => {
            input.addEventListener('input', Utils.debounce((e) => {
                this.performSearch(e.target.value, e.target);
            }, 300));
        });
    }

    async performSearch(query, inputElement) {
        if (query.length < 2) return;

        const container = inputElement.closest('.search-container');
        const resultsContainer = container.querySelector('.search-results') || this.createResultsContainer(container);

        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}/search?q=${encodeURIComponent(query)}`);
            const results = await response.json();

            this.displayResults(results, resultsContainer);
        } catch (error) {
            console.error('Error en búsqueda:', error);
        }
    }

    createResultsContainer(container) {
        const resultsContainer = document.createElement('div');
        resultsContainer.className = 'search-results';
        resultsContainer.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        `;
        container.appendChild(resultsContainer);
        return resultsContainer;
    }

    displayResults(results, container) {
        if (!results || results.length === 0) {
            container.style.display = 'none';
            return;
        }

        container.innerHTML = results.map(result => `
            <div class="search-result-item" style="padding: 12px; border-bottom: 1px solid var(--gray-100); cursor: pointer;">
                <div style="font-weight: 500;">${result.title}</div>
                <div style="font-size: 0.875rem; color: var(--gray-600);">${result.description}</div>
            </div>
        `).join('');

        container.style.display = 'block';

        // Agregar event listeners a los resultados
        container.querySelectorAll('.search-result-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                this.selectResult(results[index]);
                container.style.display = 'none';
            });
        });
    }

    selectResult(result) {
        if (result.url) {
            window.location.href = result.url;
        }
    }
}

// ===== ANIMACIONES DE SCROLL =====
class ScrollAnimations {
    constructor() {
        this.observedElements = new Set();
        this.init();
    }

    init() {
        if (!('IntersectionObserver' in window)) return;

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        this.observeElements();
    }

    observeElements() {
        const elementsToAnimate = document.querySelectorAll(`
            .card,
            .destination-card,
            .formulario-card,
            .section-header,
            .hero-content
        `);

        elementsToAnimate.forEach((element, index) => {
            if (!this.observedElements.has(element)) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                
                this.observer.observe(element);
                this.observedElements.add(element);
            }
        });
    }

    animateElement(element) {
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
        this.observer.unobserve(element);
    }
}

// ===== GESTOR DE FILTROS =====
class FilterManager {
    constructor() {
        this.filterElements = document.querySelectorAll('[data-filter]');
        this.init();
    }

    init() {
        this.filterElements.forEach(filter => {
            filter.addEventListener('change', () => {
                this.applyFilters();
            });
        });
    }

    applyFilters() {
        const filters = {};
        this.filterElements.forEach(element => {
            const filterName = element.dataset.filter;
            const value = element.value;
            if (value) {
                filters[filterName] = value;
            }
        });

        this.filterContent(filters);
    }

    filterContent(filters) {
        const items = document.querySelectorAll('[data-filterable]');
        
        items.forEach(item => {
            let shouldShow = true;
            
            Object.entries(filters).forEach(([filterName, filterValue]) => {
                const itemValue = item.dataset[filterName];
                if (itemValue && itemValue !== filterValue) {
                    shouldShow = false;
                }
            });

            if (shouldShow) {
                item.style.display = '';
                item.style.opacity = '1';
            } else {
                item.style.opacity = '0';
                setTimeout(() => {
                    if (item.style.opacity === '0') {
                        item.style.display = 'none';
                    }
                }, 300);
            }
        });
    }
}

// ===== INICIALIZACIÓN =====
document.addEventListener('DOMContentLoaded', () => {
    // Instancias globales
    window.notifications = new NotificationManager();
    window.headerManager = new HeaderManager();
    window.scrollAnimations = new ScrollAnimations();
    window.searchManager = new SearchManager();
    window.filterManager = new FilterManager();

    // Inicializar validadores de formularios
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        new FormValidator(form);
    });

    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                Utils.smoothScrollTo(target);
            }
        });
    });

    // Lazy loading para imágenes
    if ('IntersectionObserver' in window) {
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

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Cerrar dropdowns al hacer click fuera
    document.addEventListener('click', (e) => {
        const dropdowns = document.querySelectorAll('.dropdown.active');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    });

    console.log('🚀 Aplicación turística inicializada correctamente');
});

// ===== FUNCIONES GLOBALES DE UTILIDAD =====
window.TurismoUtils = {
    ...Utils,
    showSuccess: (message) => window.notifications.success(message),
    showError: (message) => window.notifications.error(message),
    showWarning: (message) => window.notifications.warning(message),
    showInfo: (message) => window.notifications.info(message)
};
