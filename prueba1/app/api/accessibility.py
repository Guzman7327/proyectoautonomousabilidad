"""
API para configuraciones de accesibilidad del usuario
Implementa endpoints para gestionar preferencias ISO 9241-11 y ISO 25010:2011
"""

from flask import Blueprint, request, jsonify, current_app
from flask_login import login_required, current_user
from app.models.user import User
from app import db
import logging

# Configurar logging
logger = logging.getLogger(__name__)

# Crear blueprint para API de accesibilidad
accessibility_api = Blueprint('accessibility_api', __name__, url_prefix='/api/user')

@accessibility_api.route('/accessibility', methods=['GET'])
@login_required
def get_accessibility_settings():
    """
    Obtener configuraciones de accesibilidad del usuario actual
    """
    try:
        settings = current_user.get_accessibility_preferences()
        return jsonify({
            'success': True,
            'settings': settings
        })
    except Exception as e:
        logger.error(f"Error getting accessibility settings: {e}")
        return jsonify({
            'success': False,
            'error': 'Error al obtener configuraciones de accesibilidad'
        }), 500

@accessibility_api.route('/accessibility', methods=['POST'])
@login_required
def update_accessibility_settings():
    """
    Actualizar configuraciones de accesibilidad del usuario
    """
    try:
        data = request.get_json()
        
        if not data:
            return jsonify({
                'success': False,
                'error': 'No se proporcionaron datos'
            }), 400
        
        # Validar y filtrar configuraciones válidas
        valid_settings = {}
        accessibility_fields = [
            # Visual
            'high_contrast', 'monochromatic_mode', 'large_text', 'increased_spacing',
            'adjusted_line_height', 'page_zoom_level', 'focus_highlight', 'pause_animations',
            
            # Auditivo
            'video_subtitles', 'live_subtitles', 'audio_description', 'volume_control', 'visual_alerts',
            
            # Navegación
            'screen_reader', 'keyboard_navigation', 'voice_enabled', 'skip_links',
            
            # Formularios
            'real_time_validation', 'visual_error_feedback', 'auto_focus_fields', 'password_toggle',
            
            # Interfaz
            'consistent_menu_location', 'hierarchical_menus', 'clear_menu_labels',
            'active_item_highlight', 'text_icon_combination', 'responsive_design', 'hover_feedback',
            
            # Seguridad
            'session_timeout', 'antispam_verification', 'secure_remembering',
            
            # Idioma
            'preferred_language', 'multilingual_support',
            
            # Cognitivo
            'cognitive_load_reduction', 'logical_order', 'explanatory_text', 'minimum_response_time',
            
            # Voz
            'speech_rate', 'speech_volume', 'speech_pitch', 'preferred_voice',
            
            # Perfil
            'accessibility_profile'
        ]
        
        for field, value in data.items():
            if field in accessibility_fields:
                # Validar tipos de datos
                if field in ['page_zoom_level', 'volume_control', 'session_timeout']:
                    try:
                        valid_settings[field] = int(value)
                    except (ValueError, TypeError):
                        logger.warning(f"Invalid integer value for {field}: {value}")
                        continue
                        
                elif field in ['speech_rate', 'speech_volume', 'speech_pitch']:
                    try:
                        valid_settings[field] = float(value)
                    except (ValueError, TypeError):
                        logger.warning(f"Invalid float value for {field}: {value}")
                        continue
                        
                elif field in ['preferred_language', 'preferred_voice', 'accessibility_profile']:
                    valid_settings[field] = str(value)
                    
                else:  # Boolean fields
                    valid_settings[field] = bool(value)
        
        if not valid_settings:
            return jsonify({
                'success': False,
                'error': 'No se proporcionaron configuraciones válidas'
            }), 400
        
        # Actualizar configuraciones del usuario
        current_user.update_accessibility_settings(valid_settings)
        
        logger.info(f"Updated accessibility settings for user {current_user.id}: {list(valid_settings.keys())}")
        
        return jsonify({
            'success': True,
            'message': 'Configuraciones de accesibilidad actualizadas exitosamente',
            'updated_settings': valid_settings
        })
        
    except Exception as e:
        logger.error(f"Error updating accessibility settings: {e}")
        db.session.rollback()
        return jsonify({
            'success': False,
            'error': 'Error al actualizar configuraciones de accesibilidad'
        }), 500

@accessibility_api.route('/accessibility/profile', methods=['POST'])
@login_required
def set_accessibility_profile():
    """
    Establecer un perfil de accesibilidad predefinido
    """
    try:
        data = request.get_json()
        profile_type = data.get('profile')
        
        if not profile_type:
            return jsonify({
                'success': False,
                'error': 'Tipo de perfil requerido'
            }), 400
        
        valid_profiles = ['visual', 'auditory', 'motor', 'cognitive', 'standard']
        
        if profile_type not in valid_profiles:
            return jsonify({
                'success': False,
                'error': f'Perfil inválido. Opciones válidas: {", ".join(valid_profiles)}'
            }), 400
        
        # Establecer perfil predefinido
        current_user.set_accessibility_profile(profile_type)
        
        logger.info(f"Set accessibility profile for user {current_user.id}: {profile_type}")
        
        return jsonify({
            'success': True,
            'message': f'Perfil de accesibilidad "{profile_type}" establecido exitosamente',
            'profile': profile_type,
            'settings': current_user.get_accessibility_preferences()
        })
        
    except Exception as e:
        logger.error(f"Error setting accessibility profile: {e}")
        db.session.rollback()
        return jsonify({
            'success': False,
            'error': 'Error al establecer perfil de accesibilidad'
        }), 500

@accessibility_api.route('/accessibility/reset', methods=['POST'])
@login_required
def reset_accessibility_settings():
    """
    Restablecer configuraciones de accesibilidad a valores por defecto
    """
    try:
        # Restablecer a perfil estándar
        current_user.set_accessibility_profile('standard')
        
        # Configuraciones por defecto
        default_settings = {
            'high_contrast': False,
            'monochromatic_mode': False,
            'large_text': False,
            'increased_spacing': False,
            'adjusted_line_height': False,
            'page_zoom_level': 100,
            'focus_highlight': False,
            'pause_animations': False,
            'video_subtitles': False,
            'live_subtitles': False,
            'audio_description': False,
            'volume_control': 50,
            'visual_alerts': False,
            'screen_reader': False,
            'keyboard_navigation': False,
            'voice_enabled': False,
            'skip_links': True,
            'real_time_validation': True,
            'visual_error_feedback': True,
            'auto_focus_fields': False,
            'password_toggle': True,
            'preferred_language': 'es',
            'speech_rate': 1.0,
            'speech_volume': 0.8,
            'speech_pitch': 1.0,
            'preferred_voice': '',
            'accessibility_profile': 'standard'
        }
        
        current_user.update_accessibility_settings(default_settings)
        
        logger.info(f"Reset accessibility settings for user {current_user.id}")
        
        return jsonify({
            'success': True,
            'message': 'Configuraciones de accesibilidad restablecidas exitosamente',
            'settings': current_user.get_accessibility_preferences()
        })
        
    except Exception as e:
        logger.error(f"Error resetting accessibility settings: {e}")
        db.session.rollback()
        return jsonify({
            'success': False,
            'error': 'Error al restablecer configuraciones de accesibilidad'
        }), 500

@accessibility_api.route('/accessibility/export', methods=['GET'])
@login_required
def export_accessibility_settings():
    """
    Exportar configuraciones de accesibilidad del usuario
    """
    try:
        settings = current_user.get_accessibility_preferences()
        
        export_data = {
            'user_id': current_user.public_id,
            'exported_at': current_user.updated_at.isoformat() if current_user.updated_at else None,
            'accessibility_settings': settings,
            'version': '1.0',
            'standards': ['ISO 9241-11', 'ISO 25010:2011', 'WCAG 2.1 AA']
        }
        
        return jsonify({
            'success': True,
            'data': export_data
        })
        
    except Exception as e:
        logger.error(f"Error exporting accessibility settings: {e}")
        return jsonify({
            'success': False,
            'error': 'Error al exportar configuraciones de accesibilidad'
        }), 500

@accessibility_api.route('/accessibility/import', methods=['POST'])
@login_required
def import_accessibility_settings():
    """
    Importar configuraciones de accesibilidad
    """
    try:
        data = request.get_json()
        
        if not data or 'accessibility_settings' not in data:
            return jsonify({
                'success': False,
                'error': 'Datos de importación inválidos'
            }), 400
        
        settings_to_import = data['accessibility_settings']
        
        # Validar que las configuraciones sean válidas
        if not isinstance(settings_to_import, dict):
            return jsonify({
                'success': False,
                'error': 'Formato de configuraciones inválido'
            }), 400
        
        # Actualizar configuraciones
        current_user.update_accessibility_settings(settings_to_import)
        
        logger.info(f"Imported accessibility settings for user {current_user.id}")
        
        return jsonify({
            'success': True,
            'message': 'Configuraciones de accesibilidad importadas exitosamente',
            'settings': current_user.get_accessibility_preferences()
        })
        
    except Exception as e:
        logger.error(f"Error importing accessibility settings: {e}")
        db.session.rollback()
        return jsonify({
            'success': False,
            'error': 'Error al importar configuraciones de accesibilidad'
        }), 500

# Manejador de errores para el blueprint
@accessibility_api.errorhandler(404)
def not_found(error):
    return jsonify({
        'success': False,
        'error': 'Endpoint no encontrado'
    }), 404

@accessibility_api.errorhandler(405)
def method_not_allowed(error):
    return jsonify({
        'success': False,
        'error': 'Método no permitido'
    }), 405

@accessibility_api.errorhandler(500)
def internal_error(error):
    db.session.rollback()
    return jsonify({
        'success': False,
        'error': 'Error interno del servidor'
    }), 500
