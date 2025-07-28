from flask import Blueprint, render_template, request, jsonify, current_app
from flask_login import current_user
from app import db
from app.models import AccessibilityFeedback, User
import json

accessibility_bp = Blueprint('accessibility', __name__)

@accessibility_bp.route('/')
def accessibility_info():
    """Página principal de información de accesibilidad"""
    
    # Estadísticas de accesibilidad
    stats = {
        'users_with_settings': User.query.filter(
            User.is_active == True,
            (User.high_contrast == True) |
            (User.large_text == True) |
            (User.screen_reader == True) |
            (User.keyboard_navigation == True) |
            (User.voice_enabled == True)
        ).count(),
        'total_feedback': AccessibilityFeedback.query.count(),
        'resolved_feedback': AccessibilityFeedback.query.filter_by(is_resolved=True).count()
    }
    
    return render_template('accessibility/info.html', stats=stats)

@accessibility_bp.route('/features')
def accessibility_features():
    """Características de accesibilidad del sitio"""
    
    features = {
        'navigation': {
            'name': 'Navegación Accesible',
            'description': 'El sitio está completamente navegable usando solo el teclado',
            'details': [
                'Navegación con Tab y Shift+Tab',
                'Atajos de teclado disponibles',
                'Skip links para contenido principal',
                'Focus visible en todos los elementos interactivos',
                'Orden lógico de navegación'
            ]
        },
        'visual': {
            'name': 'Accesibilidad Visual',
            'description': 'Opciones para usuarios con discapacidades visuales',
            'details': [
                'Modo de alto contraste',
                'Texto escalable hasta 200%',
                'Textos alternativos descriptivos en imágenes',
                'Descripciones de contenido visual',
                'Colores con suficiente contraste (WCAG AA)'
            ]
        },
        'audio': {
            'name': 'Accesibilidad Auditiva',
            'description': 'Soporte para síntesis de voz y lectores de pantalla',
            'details': [
                'Síntesis de voz con Web Speech API',
                'Compatibilidad con lectores de pantalla',
                'Transcripciones de contenido multimedia',
                'Subtítulos en videos',
                'Señales visuales para alertas de audio'
            ]
        },
        'cognitive': {
            'name': 'Accesibilidad Cognitiva',
            'description': 'Diseño claro y comprensible',
            'details': [
                'Navegación consistente y predecible',
                'Lenguaje claro y sencillo',
                'Ayuda contextual en formularios',
                'Tiempo suficiente para leer contenido',
                'Opciones para controlar animaciones'
            ]
        },
        'motor': {
            'name': 'Accesibilidad Motora',
            'description': 'Facilidades para usuarios con discapacidades motoras',
            'details': [
                'Áreas de clic grandes (mínimo 44px)',
                'Tiempo extendido para interacciones',
                'Navegación completa por teclado',
                'Sin dependencia del mouse',
                'Gestos simples en dispositivos táctiles'
            ]
        }
    }
    
    return render_template('accessibility/features.html', features=features)

@accessibility_bp.route('/keyboard-shortcuts')
def keyboard_shortcuts():
    """Información sobre atajos de teclado"""
    
    shortcuts = {
        'navigation': [
            {'keys': 'Alt + H', 'description': 'Ir al inicio/página principal'},
            {'keys': 'Alt + M', 'description': 'Ir al menú principal'},
            {'keys': 'Alt + C', 'description': 'Ir al contenido principal'},
            {'keys': 'Alt + S', 'description': 'Ir al formulario de búsqueda'},
            {'keys': 'Alt + F', 'description': 'Ir al pie de página'}
        ],
        'content': [
            {'keys': 'Tab', 'description': 'Navegar al siguiente elemento'},
            {'keys': 'Shift + Tab', 'description': 'Navegar al elemento anterior'},
            {'keys': 'Enter', 'description': 'Activar enlace o botón'},
            {'keys': 'Espacio', 'description': 'Activar botón o checkbox'},
            {'keys': 'Esc', 'description': 'Cerrar modal o menú desplegable'}
        ],
        'accessibility': [
            {'keys': 'Alt + 1', 'description': 'Activar/desactivar alto contraste'},
            {'keys': 'Alt + 2', 'description': 'Aumentar/disminuir tamaño de texto'},
            {'keys': 'Alt + 3', 'description': 'Activar/desactivar síntesis de voz'},
            {'keys': 'Alt + 4', 'description': 'Mostrar información de accesibilidad'},
            {'keys': 'Alt + 0', 'description': 'Restablecer configuraciones de accesibilidad'}
        ]
    }
    
    return render_template('accessibility/keyboard_shortcuts.html', shortcuts=shortcuts)

@accessibility_bp.route('/feedback', methods=['GET', 'POST'])
def accessibility_feedback():
    """Formulario de feedback de accesibilidad"""
    
    if request.method == 'POST':
        # Determinar si es solicitud AJAX
        if request.is_json:
            data = request.get_json()
        else:
            data = request.form.to_dict()
        
        # Obtener datos del formulario
        feedback_type = data.get('feedback_type', '').strip()
        description = data.get('description', '').strip()
        page_url = data.get('page_url', '').strip()
        name = data.get('name', '').strip()
        email = data.get('email', '').strip()
        assistive_technology = data.get('assistive_technology', '').strip()
        browser_info = data.get('browser_info', '').strip()
        severity = data.get('severity', 'medium')
        
        # Validaciones
        errors = []
        
        if not feedback_type or feedback_type not in ['bug', 'suggestion', 'complaint', 'compliment']:
            errors.append('Tipo de feedback inválido.')
        
        if not description or len(description) < 10:
            errors.append('La descripción debe tener al menos 10 caracteres.')
        
        if not current_user.is_authenticated:
            if not name:
                errors.append('El nombre es obligatorio.')
            if not email:
                errors.append('El email es obligatorio.')
        
        if errors:
            if request.is_json:
                return jsonify({
                    'success': False,
                    'errors': errors
                }), 400
            else:
                for error in errors:
                    flash(error, 'error')
                return render_template('accessibility/feedback.html')
        
        try:
            # Crear feedback
            feedback = AccessibilityFeedback(
                feedback_type=feedback_type,
                description=description,
                page_url=page_url,
                name=name if not current_user.is_authenticated else None,
                email=email if not current_user.is_authenticated else None,
                assistive_technology=assistive_technology,
                browser_info=browser_info,
                severity=severity,
                user_id=current_user.id if current_user.is_authenticated else None
            )
            
            db.session.add(feedback)
            db.session.commit()
            
            if request.is_json:
                return jsonify({
                    'success': True,
                    'message': 'Feedback enviado exitosamente'
                }), 201
            else:
                flash('¡Gracias por tu feedback! Nos ayuda a mejorar la accesibilidad del sitio.', 'success')
                return redirect(url_for('accessibility.accessibility_feedback'))
                
        except Exception as e:
            db.session.rollback()
            current_app.logger.error(f'Error enviando feedback: {str(e)}')
            
            if request.is_json:
                return jsonify({
                    'success': False,
                    'message': 'Error interno del servidor'
                }), 500
            else:
                flash('Ocurrió un error al enviar el feedback. Inténtalo de nuevo.', 'error')
    
    # Tipos de feedback
    feedback_types = [
        {'value': 'bug', 'name': 'Problema/Error', 'description': 'Reportar un problema de accesibilidad'},
        {'value': 'suggestion', 'name': 'Sugerencia', 'description': 'Sugerir una mejora de accesibilidad'},
        {'value': 'complaint', 'name': 'Queja', 'description': 'Expresar una preocupación sobre accesibilidad'},
        {'value': 'compliment', 'name': 'Felicitación', 'description': 'Elogiar algún aspecto de accesibilidad'}
    ]
    
    # Tecnologías asistivas comunes
    assistive_technologies = [
        'JAWS (Job Access With Speech)',
        'NVDA (NonVisual Desktop Access)',
        'VoiceOver (macOS/iOS)',
        'TalkBack (Android)',
        'Dragon NaturallySpeaking',
        'ZoomText',
        'Teclado en pantalla',
        'Switch Control',
        'Eye-tracking software',
        'Otro'
    ]
    
    return render_template('accessibility/feedback.html',
                         feedback_types=feedback_types,
                         assistive_technologies=assistive_technologies)

@accessibility_bp.route('/voice-synthesis', methods=['POST'])
def voice_synthesis():
    """Endpoint para síntesis de voz"""
    
    try:
        data = request.get_json()
        text = data.get('text', '').strip()
        
        if not text:
            return jsonify({
                'success': False,
                'message': 'Texto requerido'
            }), 400
        
        if len(text) > 5000:
            return jsonify({
                'success': False,
                'message': 'El texto es demasiado largo (máximo 5000 caracteres)'
            }), 400
        
        # En una implementación real, aquí procesarías el texto
        # Por ahora, devolvemos el texto para procesamiento en el frontend
        return jsonify({
            'success': True,
            'text': text,
            'message': 'Texto listo para síntesis de voz'
        })
        
    except Exception as e:
        current_app.logger.error(f'Error en síntesis de voz: {str(e)}')
        return jsonify({
            'success': False,
            'message': 'Error interno del servidor'
        }), 500

@accessibility_bp.route('/contrast-settings', methods=['POST'])
def update_contrast_settings():
    """Actualizar configuraciones de contraste"""
    
    try:
        data = request.get_json()
        high_contrast = data.get('high_contrast', False)
        
        # Si el usuario está autenticado, guardar en la base de datos
        if current_user.is_authenticated:
            current_user.high_contrast = high_contrast
            db.session.commit()
        
        return jsonify({
            'success': True,
            'high_contrast': high_contrast,
            'message': 'Configuración de contraste actualizada'
        })
        
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f'Error actualizando contraste: {str(e)}')
        return jsonify({
            'success': False,
            'message': 'Error interno del servidor'
        }), 500

@accessibility_bp.route('/text-size-settings', methods=['POST'])
def update_text_size_settings():
    """Actualizar configuraciones de tamaño de texto"""
    
    try:
        data = request.get_json()
        large_text = data.get('large_text', False)
        
        # Si el usuario está autenticado, guardar en la base de datos
        if current_user.is_authenticated:
            current_user.large_text = large_text
            db.session.commit()
        
        return jsonify({
            'success': True,
            'large_text': large_text,
            'message': 'Configuración de tamaño de texto actualizada'
        })
        
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f'Error actualizando tamaño de texto: {str(e)}')
        return jsonify({
            'success': False,
            'message': 'Error interno del servidor'
        }), 500

@accessibility_bp.route('/user-settings', methods=['GET', 'POST'])
def user_accessibility_settings():
    """Configuraciones de accesibilidad del usuario"""
    
    if not current_user.is_authenticated:
        return redirect(url_for('auth.login'))
    
    if request.method == 'POST':
        try:
            # Obtener configuraciones del formulario
            settings = {
                'high_contrast': request.form.get('high_contrast') == 'on',
                'large_text': request.form.get('large_text') == 'on',
                'screen_reader': request.form.get('screen_reader') == 'on',
                'keyboard_navigation': request.form.get('keyboard_navigation') == 'on',
                'voice_enabled': request.form.get('voice_enabled') == 'on',
                'preferred_language': request.form.get('preferred_language', 'es')
            }
            
            # Actualizar configuraciones
            current_user.update_accessibility_settings(settings)
            
            flash('Configuraciones de accesibilidad actualizadas exitosamente.', 'success')
            return redirect(url_for('accessibility.user_accessibility_settings'))
            
        except Exception as e:
            db.session.rollback()
            current_app.logger.error(f'Error actualizando configuraciones: {str(e)}')
            flash('Ocurrió un error al actualizar las configuraciones.', 'error')
    
    # Idiomas disponibles
    languages = [
        {'code': 'es', 'name': 'Español'},
        {'code': 'en', 'name': 'English'},
        {'code': 'qu', 'name': 'Kichwa'}
    ]
    
    return render_template('accessibility/user_settings.html', languages=languages)

@accessibility_bp.route('/guidelines')
def accessibility_guidelines():
    """Guías de accesibilidad seguidas por el sitio"""
    
    guidelines = {
        'wcag': {
            'name': 'WCAG 2.1 Nivel AA',
            'description': 'Web Content Accessibility Guidelines',
            'principles': [
                {
                    'name': 'Perceptible',
                    'description': 'La información debe presentarse de manera que los usuarios puedan percibirla',
                    'criteria': [
                        'Textos alternativos para imágenes',
                        'Subtítulos para contenido multimedia',
                        'Suficiente contraste de color',
                        'Texto redimensionable'
                    ]
                },
                {
                    'name': 'Operable',
                    'description': 'Los componentes de la interfaz deben ser operables',
                    'criteria': [
                        'Navegación completa por teclado',
                        'No contenido que cause convulsiones',
                        'Tiempo suficiente para usar el contenido',
                        'Ayudas para la navegación'
                    ]
                },
                {
                    'name': 'Comprensible',
                    'description': 'La información y el funcionamiento deben ser comprensibles',
                    'criteria': [
                        'Texto legible y comprensible',
                        'Contenido predecible',
                        'Ayuda para evitar errores',
                        'Identificación de errores'
                    ]
                },
                {
                    'name': 'Robusto',
                    'description': 'El contenido debe ser suficientemente robusto',
                    'criteria': [
                        'Compatible con tecnologías asistivas',
                        'Código válido y semántico',
                        'Funciona en diferentes navegadores',
                        'Adaptable a futuras tecnologías'
                    ]
                }
            ]
        }
    }
    
    return render_template('accessibility/guidelines.html', guidelines=guidelines)
