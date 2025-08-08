// Script simplificado para el portal turístico
document.addEventListener('DOMContentLoaded', function() {
    
    // Manejo de pestañas
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remover clase active de todos los botones y paneles
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Agregar clase active al botón clickeado y su panel correspondiente
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
    
    // Función para mostrar/ocultar contraseña
    window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const button = input.parentNode.querySelector('.toggle-password');
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    };
    
    // Función para mostrar mensajes
    function showMessage(message, type = 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Insertar al inicio del contenedor principal
        const container = document.querySelector('.main-container');
        container.insertBefore(messageDiv, container.firstChild);
        
        // Remover después de 5 segundos
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
    
    // Función para manejar envío de formularios
    function handleFormSubmit(form, formData) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Mostrar estado de carga
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        submitBtn.disabled = true;
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.mensaje || data.message, 'success');
                
                // Si hay redirección, hacerla después de un breve delay
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                showMessage(data.mensaje || data.message || 'Error desconocido', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error de conexión. Intente nuevamente.', 'error');
        })
        .finally(() => {
            // Restaurar botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
    
    // Validación básica de formularios
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Limpiar errores previos
            form.querySelectorAll('.error-message').forEach(error => {
                error.textContent = '';
            });
            form.querySelectorAll('input').forEach(input => {
                input.style.borderColor = '#e1e5e9';
            });
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                    const errorElement = field.parentNode.querySelector('.error-message');
                    if (errorElement) {
                        errorElement.textContent = 'Este campo es requerido';
                    }
                }
            });
            
            // Validación especial para email
            const emailField = form.querySelector('input[type="email"]');
            if (emailField && emailField.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    isValid = false;
                    emailField.style.borderColor = '#dc3545';
                    const errorElement = emailField.parentNode.querySelector('.error-message');
                    if (errorElement) {
                        errorElement.textContent = 'Email inválido';
                    }
                }
            }
            
            // Validación especial para contraseñas en el formulario de registro
            if (form.id === 'registerForm') {
                const password = form.querySelector('#password');
                const confirmPassword = form.querySelector('#confirmPassword');
                const terms = form.querySelector('#terms');
                
                if (password && confirmPassword) {
                    if (password.value !== confirmPassword.value) {
                        isValid = false;
                        confirmPassword.style.borderColor = '#dc3545';
                        const errorElement = confirmPassword.parentNode.querySelector('.error-message');
                        if (errorElement) {
                            errorElement.textContent = 'Las contraseñas no coinciden';
                        }
                    }
                    
                    if (password.value.length < 8) {
                        isValid = false;
                        password.style.borderColor = '#dc3545';
                        const errorElement = password.parentNode.querySelector('.error-message');
                        if (errorElement) {
                            errorElement.textContent = 'La contraseña debe tener al menos 8 caracteres';
                        }
                    }
                }
                
                if (terms && !terms.checked) {
                    isValid = false;
                    terms.style.borderColor = '#dc3545';
                    showMessage('Debe aceptar los términos y condiciones', 'error');
                }
            }
            
            if (isValid) {
                const formData = new FormData(form);
                handleFormSubmit(form, formData);
            } else {
                showMessage('Por favor, complete todos los campos requeridos correctamente.', 'error');
            }
        });
    });
    
    // Mostrar mensaje de éxito si existe en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    }
    
    // Mostrar mensaje de error si existe en la URL
    if (urlParams.get('error')) {
        const errorMessage = urlParams.get('error');
        showMessage(decodeURIComponent(errorMessage), 'error');
    }
});

// Agregar estilos para los mensajes
const style = document.createElement('style');
style.textContent = `
    .message {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    }
    
    .message.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .message.error {
        background: linear-gradient(135deg, #dc3545, #e74c3c);
    }
    
    .message i {
        font-size: 18px;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
