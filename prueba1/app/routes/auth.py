from flask import Blueprint, render_template, request, redirect, url_for, flash, session
from flask_login import login_user, logout_user, login_required, current_user
from app import db
from app.models import User
from werkzeug.security import check_password_hash
import re

auth_bp = Blueprint('auth', __name__)

def validate_email(email):
    """Validar formato de email"""
    pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    return re.match(pattern, email) is not None

def validate_password(password):
    """Validar fortaleza de contraseña"""
    if len(password) < 8:
        return False, "La contraseña debe tener al menos 8 caracteres"
    
    if not re.search(r'[A-Z]', password):
        return False, "La contraseña debe contener al menos una letra mayúscula"
    
    if not re.search(r'[a-z]', password):
        return False, "La contraseña debe contener al menos una letra minúscula"
    
    if not re.search(r'\d', password):
        return False, "La contraseña debe contener al menos un número"
    
    return True, "Contraseña válida"

@auth_bp.route('/login', methods=['GET', 'POST'])
def login():
    """Página de inicio de sesión"""
    
    if current_user.is_authenticated:
        return redirect(url_for('main.index'))
    
    if request.method == 'POST':
        email_or_username = request.form.get('email_or_username', '').strip()
        password = request.form.get('password', '')
        remember_me = request.form.get('remember_me') == 'on'
        
        # Validaciones básicas
        if not email_or_username:
            flash('Por favor, ingresa tu email o nombre de usuario.', 'error')
            return render_template('auth/login.html')
        
        if not password:
            flash('Por favor, ingresa tu contraseña.', 'error')
            return render_template('auth/login.html')
        
        # Buscar usuario por email o username
        user = None
        if validate_email(email_or_username):
            user = User.query.filter_by(email=email_or_username).first()
        else:
            user = User.query.filter_by(username=email_or_username).first()
        
        # Verificar credenciales
        if user and user.check_password(password):
            if not user.is_active:
                flash('Tu cuenta está desactivada. Contacta al administrador.', 'error')
                return render_template('auth/login.html')
            
            # Iniciar sesión
            login_user(user, remember=remember_me)
            
            # Actualizar último login
            from datetime import datetime
            user.last_login = datetime.utcnow()
            db.session.commit()
            
            # Redirigir a la página solicitada o al inicio
            next_page = request.args.get('next')
            if next_page:
                return redirect(next_page)
            
            flash(f'¡Bienvenido de vuelta, {user.get_full_name()}!', 'success')
            return redirect(url_for('main.index'))
        else:
            flash('Email/usuario o contraseña incorrectos.', 'error')
    
    return render_template('auth/login.html')

@auth_bp.route('/register', methods=['GET', 'POST'])
def register():
    """Página de registro"""
    
    if current_user.is_authenticated:
        return redirect(url_for('main.index'))
    
    if request.method == 'POST':
        # Obtener datos del formulario
        email = request.form.get('email', '').strip().lower()
        username = request.form.get('username', '').strip()
        first_name = request.form.get('first_name', '').strip()
        last_name = request.form.get('last_name', '').strip()
        password = request.form.get('password', '')
        confirm_password = request.form.get('confirm_password', '')
        
        # Obtener configuraciones de accesibilidad
        accessibility_settings = {
            'high_contrast': request.form.get('high_contrast') == 'on',
            'large_text': request.form.get('large_text') == 'on',
            'screen_reader': request.form.get('screen_reader') == 'on',
            'keyboard_navigation': request.form.get('keyboard_navigation') == 'on',
            'voice_enabled': request.form.get('voice_enabled') == 'on',
            'preferred_language': request.form.get('preferred_language', 'es')
        }
        
        # Validaciones
        errors = []
        
        if not email:
            errors.append('El email es obligatorio.')
        elif not validate_email(email):
            errors.append('El email no tiene un formato válido.')
        elif User.query.filter_by(email=email).first():
            errors.append('Este email ya está registrado.')
        
        if not username:
            errors.append('El nombre de usuario es obligatorio.')
        elif len(username) < 3:
            errors.append('El nombre de usuario debe tener al menos 3 caracteres.')
        elif User.query.filter_by(username=username).first():
            errors.append('Este nombre de usuario ya está en uso.')
        
        if not first_name:
            errors.append('El nombre es obligatorio.')
        
        if not last_name:
            errors.append('El apellido es obligatorio.')
        
        if not password:
            errors.append('La contraseña es obligatoria.')
        else:
            is_valid, message = validate_password(password)
            if not is_valid:
                errors.append(message)
        
        if password != confirm_password:
            errors.append('Las contraseñas no coinciden.')
        
        # Si hay errores, mostrarlos
        if errors:
            for error in errors:
                flash(error, 'error')
            return render_template('auth/register.html')
        
        try:
            # Crear nuevo usuario
            user = User(
                email=email,
                username=username,
                password=password,
                first_name=first_name,
                last_name=last_name,
                **accessibility_settings
            )
            
            db.session.add(user)
            db.session.commit()
            
            # Iniciar sesión automáticamente
            login_user(user)
            
            flash(f'¡Registro exitoso! Bienvenido, {user.get_full_name()}!', 'success')
            return redirect(url_for('main.index'))
            
        except Exception as e:
            db.session.rollback()
            flash('Ocurrió un error durante el registro. Inténtalo de nuevo.', 'error')
            current_app.logger.error(f'Error en registro: {str(e)}')
    
    return render_template('auth/register.html')

@auth_bp.route('/logout')
@login_required
def logout():
    """Cerrar sesión"""
    username = current_user.username
    logout_user()
    flash(f'Hasta pronto, {username}!', 'info')
    return redirect(url_for('main.index'))

@auth_bp.route('/profile')
@login_required
def profile():
    """Página de perfil de usuario"""
    
    # Obtener estadísticas del usuario
    user_stats = {
        'reviews_count': current_user.reviews.filter_by(is_approved=True).count(),
        'favorites_count': current_user.favorites.count(),
        'recent_reviews': current_user.reviews.filter_by(is_approved=True).order_by(
            'created_at desc'
        ).limit(5).all(),
        'recent_favorites': current_user.favorites.order_by(
            'created_at desc'
        ).limit(5).all()
    }
    
    return render_template('auth/profile.html', user_stats=user_stats)

@auth_bp.route('/edit-profile', methods=['GET', 'POST'])
@login_required
def edit_profile():
    """Editar perfil de usuario"""
    
    if request.method == 'POST':
        # Obtener datos del formulario
        first_name = request.form.get('first_name', '').strip()
        last_name = request.form.get('last_name', '').strip()
        email = request.form.get('email', '').strip().lower()
        
        # Configuraciones de accesibilidad
        accessibility_settings = {
            'high_contrast': request.form.get('high_contrast') == 'on',
            'large_text': request.form.get('large_text') == 'on',
            'screen_reader': request.form.get('screen_reader') == 'on',
            'keyboard_navigation': request.form.get('keyboard_navigation') == 'on',
            'voice_enabled': request.form.get('voice_enabled') == 'on',
            'preferred_language': request.form.get('preferred_language', 'es')
        }
        
        # Validaciones
        errors = []
        
        if not first_name:
            errors.append('El nombre es obligatorio.')
        
        if not last_name:
            errors.append('El apellido es obligatorio.')
        
        if not email:
            errors.append('El email es obligatorio.')
        elif not validate_email(email):
            errors.append('El email no tiene un formato válido.')
        elif email != current_user.email:
            # Verificar que el nuevo email no esté en uso
            existing_user = User.query.filter_by(email=email).first()
            if existing_user:
                errors.append('Este email ya está en uso por otro usuario.')
        
        if errors:
            for error in errors:
                flash(error, 'error')
            return render_template('auth/edit_profile.html')
        
        try:
            # Actualizar información del usuario
            current_user.first_name = first_name
            current_user.last_name = last_name
            current_user.email = email
            
            # Actualizar configuraciones de accesibilidad
            current_user.update_accessibility_settings(accessibility_settings)
            
            flash('Perfil actualizado exitosamente.', 'success')
            return redirect(url_for('auth.profile'))
            
        except Exception as e:
            db.session.rollback()
            flash('Ocurrió un error al actualizar el perfil.', 'error')
            current_app.logger.error(f'Error actualizando perfil: {str(e)}')
    
    return render_template('auth/edit_profile.html')

@auth_bp.route('/change-password', methods=['GET', 'POST'])
@login_required
def change_password():
    """Cambiar contraseña"""
    
    if request.method == 'POST':
        current_password = request.form.get('current_password', '')
        new_password = request.form.get('new_password', '')
        confirm_password = request.form.get('confirm_password', '')
        
        # Validaciones
        if not current_password:
            flash('La contraseña actual es obligatoria.', 'error')
            return render_template('auth/change_password.html')
        
        if not current_user.check_password(current_password):
            flash('La contraseña actual es incorrecta.', 'error')
            return render_template('auth/change_password.html')
        
        if not new_password:
            flash('La nueva contraseña es obligatoria.', 'error')
            return render_template('auth/change_password.html')
        
        is_valid, message = validate_password(new_password)
        if not is_valid:
            flash(message, 'error')
            return render_template('auth/change_password.html')
        
        if new_password != confirm_password:
            flash('Las contraseñas no coinciden.', 'error')
            return render_template('auth/change_password.html')
        
        if current_password == new_password:
            flash('La nueva contraseña debe ser diferente a la actual.', 'error')
            return render_template('auth/change_password.html')
        
        try:
            # Cambiar contraseña
            current_user.set_password(new_password)
            db.session.commit()
            
            flash('Contraseña cambiada exitosamente.', 'success')
            return redirect(url_for('auth.profile'))
            
        except Exception as e:
            db.session.rollback()
            flash('Ocurrió un error al cambiar la contraseña.', 'error')
            current_app.logger.error(f'Error cambiando contraseña: {str(e)}')
    
    return render_template('auth/change_password.html')
