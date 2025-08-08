// Elementos del DOM
const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signInForm = document.getElementById('signIn');
const signUpForm = document.getElementById('signUp');
const registerForm = document.getElementById('registerForm');
const loginForm = document.getElementById('loginForm');

// Clase principal para manejar la validación y funcionalidad de formularios
class FormValidator {
    constructor() {
        this.initializeTabs();
        this.initializeFormValidation();
        this.setupFormSubmissions();
        this.initializePasswordStrength();
    }

    // Inicializar sistema de pestañas
    initializeTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                
                // Remover clase active de todos los botones y paneles
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Agregar clase active al botón clickeado y su panel correspondiente
                button.classList.add('active');
                document.getElementById(`${targetTab}-tab`).classList.add('active');
                
                // Resetear formularios al cambiar de pestaña
                this.resetForms();
            });
        });
    }

    // Inicializar validación de formularios
    initializeFormValidation() {
        // Validación en tiempo real para campos requeridos
        const requiredInputs = document.querySelectorAll('input[required]');
        requiredInputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });

        // Validación especial para contraseña
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', () => {
                this.validatePassword(passwordInput);
                this.updatePasswordStrength(passwordInput.value);
            });
        }

        // Validación para confirmación de contraseña
        const confirmPasswordInput = document.getElementById('confirmPassword');
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', () => {
                this.validateConfirmPassword();
            });
        }

        // Validación para términos y condiciones
        const termsCheckbox = document.getElementById('terms');
        if (termsCheckbox) {
            termsCheckbox.addEventListener('change', () => {
                this.validateTerms();
            });
        }
    }

    // Configurar envío de formularios
    setupFormSubmissions() {
        // Formulario de login
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLoginSubmit(e));
        }

        // Formulario de registro
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegisterSubmit(e));
        }
    }

    // Inicializar indicador de fortaleza de contraseña
    initializePasswordStrength() {
        const passwordInput = document.getElementById('password');
        const strengthDiv = document.getElementById('passwordStrength');
        
        if (passwordInput && strengthDiv) {
            passwordInput.addEventListener('focus', () => {
                strengthDiv.style.display = 'block';
            });
        }
    }

    // Validar campo individual
    validateField(field) {
        const fieldId = field.id;
        const value = field.value.trim();
        const errorElement = document.getElementById(`${fieldId}Error`);
        
        if (!errorElement) return true;

        let isValid = true;
        let errorMessage = '';

        // Validaciones específicas por tipo de campo
        switch (field.type) {
            case 'email':
                if (!value) {
                    errorMessage = 'El correo electrónico es requerido';
                    isValid = false;
                } else if (!this.isValidEmail(value)) {
                    errorMessage = 'Ingrese un correo electrónico válido';
                    isValid = false;
                }
                break;
            
            case 'password':
                if (!value) {
                    errorMessage = 'La contraseña es requerida';
                    isValid = false;
                } else if (value.length < 8) {
                    errorMessage = 'La contraseña debe tener al menos 8 caracteres';
                    isValid = false;
                }
                break;
            
            default:
                if (!value) {
                    errorMessage = 'Este campo es requerido';
                    isValid = false;
                } else if (value.length < 2) {
                    errorMessage = 'Debe tener al menos 2 caracteres';
                    isValid = false;
                }
                break;
        }

        this.showFieldError(errorElement, errorMessage);
        return isValid;
    }

    // Validar contraseña
    validatePassword(passwordField) {
        const value = passwordField.value;
        const errorElement = document.getElementById('passwordError');
        
        if (!errorElement) return true;

        let isValid = true;
        let errorMessage = '';

        if (!value) {
            errorMessage = 'La contraseña es requerida';
            isValid = false;
        } else if (value.length < 8) {
            errorMessage = 'La contraseña debe tener al menos 8 caracteres';
            isValid = false;
        } else if (!this.isStrongPassword(value)) {
            errorMessage = 'La contraseña debe contener mayúsculas, minúsculas, números y caracteres especiales';
            isValid = false;
        }

        this.showFieldError(errorElement, errorMessage);
        return isValid;
    }

    // Validar confirmación de contraseña
    validateConfirmPassword() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const errorElement = document.getElementById('confirmPasswordError');
        
        if (!errorElement) return true;

        let isValid = true;
        let errorMessage = '';

        if (!confirmPassword) {
            errorMessage = 'Confirme su contraseña';
            isValid = false;
        } else if (password !== confirmPassword) {
            errorMessage = 'Las contraseñas no coinciden';
            isValid = false;
        }

        this.showFieldError(errorElement, errorMessage);
        return isValid;
    }

    // Validar términos y condiciones
    validateTerms() {
        const termsCheckbox = document.getElementById('terms');
        const errorElement = document.getElementById('termsError');
        
        if (!errorElement) return true;

        let isValid = true;
        let errorMessage = '';

        if (!termsCheckbox.checked) {
            errorMessage = 'Debe aceptar los términos y condiciones';
            isValid = false;
        }

        this.showFieldError(errorElement, errorMessage);
        return isValid;
    }

    // Actualizar indicador de fortaleza de contraseña
    updatePasswordStrength(password) {
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        if (!strengthFill || !strengthText) return;

        const strength = this.calculatePasswordStrength(password);
        
        strengthFill.className = 'strength-fill';
        strengthFill.classList.add(strength.level);
        
        strengthText.textContent = strength.message;
    }

    // Calcular fortaleza de contraseña
    calculatePasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        if (score < 2) return { level: 'weak', message: 'Contraseña débil' };
        if (score < 4) return { level: 'fair', message: 'Contraseña regular' };
        if (score < 5) return { level: 'good', message: 'Contraseña buena' };
        return { level: 'strong', message: 'Contraseña fuerte' };
    }

    // Manejar envío del formulario de login
    handleLoginSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        // Validar campos
        const email = formData.get('email');
        const password = formData.get('password');
        
        let isValid = true;
        
        if (!this.validateField(document.getElementById('loginEmail'))) isValid = false;
        if (!this.validateField(document.getElementById('loginPassword'))) isValid = false;
        
        if (!isValid) {
            this.showNotification('Por favor, corrija los errores en el formulario', 'error');
            return;
        }

        // Enviar formulario
        this.submitForm(form.action, formData, 'login');
    }

    // Manejar envío del formulario de registro
    handleRegisterSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        // Validar todos los campos
        let isValid = true;
        
        const requiredFields = ['firstName', 'lastName', 'email', 'password', 'confirmPassword'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !this.validateField(field)) isValid = false;
        });
        
        if (!this.validatePassword(document.getElementById('password'))) isValid = false;
        if (!this.validateConfirmPassword()) isValid = false;
        if (!this.validateTerms()) isValid = false;
        
        if (!isValid) {
            this.showNotification('Por favor, corrija los errores en el formulario', 'error');
            return;
        }

        // Enviar formulario
        this.submitForm(form.action, formData, 'register');
    }

    // Enviar formulario via AJAX
    submitForm(url, formData, type) {
        const submitBtn = type === 'login' ? 
            document.querySelector('#loginForm .btn-primary') : 
            document.querySelector('#registerForm .btn-primary');
        
        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSuccessMessage(data.message);
                if (type === 'register') {
                    setTimeout(() => {
                        // Cambiar a pestaña de login después del registro exitoso
                        document.querySelector('[data-tab="login"]').click();
                        this.resetForms();
                    }, 2000);
                } else {
                    // Redirigir según el rol del usuario
                    setTimeout(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    }, 1500);
                }
            } else {
                this.showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            this.showNotification('Error de conexión. Intente nuevamente.', 'error');
        })
        .finally(() => {
            // Restaurar botón
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
            submitBtn.innerHTML = type === 'login' ? 
                '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión' : 
                '<i class="fas fa-user-plus"></i> Crear cuenta';
        });
    }

    // Mostrar error de campo
    showFieldError(errorElement, message) {
        if (!errorElement) return;
        
        if (message) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        } else {
            errorElement.classList.remove('show');
        }
    }

    // Limpiar error de campo
    clearFieldError(field) {
        const fieldId = field.id;
        const errorElement = document.getElementById(`${fieldId}Error`);
        if (errorElement) {
            errorElement.classList.remove('show');
        }
    }

    // Mostrar mensaje de éxito
    showSuccessMessage(message) {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.querySelector('p').textContent = message;
            successMessage.style.display = 'block';
            
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    }

    // Mostrar notificación
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Estilos para la notificación
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.3s ease;
            background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Resetear formularios
    resetForms() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.reset();
            const errorMessages = form.querySelectorAll('.error-message');
            errorMessages.forEach(error => error.classList.remove('show'));
        });
        
        // Resetear indicador de fortaleza de contraseña
        const strengthDiv = document.getElementById('passwordStrength');
        if (strengthDiv) {
            strengthDiv.style.display = 'none';
        }
    }

    // Utilidades
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isStrongPassword(password) {
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);
        
        return hasLower && hasUpper && hasNumber && hasSpecial;
    }
}

// Función para mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggleBtn = input.nextElementSibling.nextElementSibling;
    const icon = toggleBtn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new FormValidator();
    
    // Agregar estilos CSS para animaciones
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});