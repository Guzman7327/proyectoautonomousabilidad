from app import db
from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash
from datetime import datetime
import uuid
import json

class User(UserMixin, db.Model):
    """Modelo de usuario con características de accesibilidad completas según ISO 9241-11"""
    
    __tablename__ = 'users'
    
    id = db.Column(db.Integer, primary_key=True)
    public_id = db.Column(db.String(50), unique=True, default=lambda: str(uuid.uuid4()))
    email = db.Column(db.String(120), unique=True, nullable=False, index=True)
    username = db.Column(db.String(80), unique=True, nullable=False, index=True)
    password_hash = db.Column(db.String(255), nullable=False)
    first_name = db.Column(db.String(100), nullable=False)
    last_name = db.Column(db.String(100), nullable=False)
    
    # ===== CONFIGURACIONES DE ACCESIBILIDAD VISUAL =====
    high_contrast = db.Column(db.Boolean, default=False)
    monochromatic_mode = db.Column(db.Boolean, default=False)
    large_text = db.Column(db.Boolean, default=False)
    increased_spacing = db.Column(db.Boolean, default=False)
    adjusted_line_height = db.Column(db.Boolean, default=False)
    page_zoom_level = db.Column(db.Integer, default=100)  # 50-200%
    focus_highlight = db.Column(db.Boolean, default=False)
    pause_animations = db.Column(db.Boolean, default=False)
    
    # ===== CONFIGURACIONES AUDITIVAS =====
    video_subtitles = db.Column(db.Boolean, default=True)
    live_subtitles = db.Column(db.Boolean, default=False)
    audio_description = db.Column(db.Boolean, default=False)
    volume_control = db.Column(db.Boolean, default=True)
    visual_alerts = db.Column(db.Boolean, default=False)
    
    # ===== NAVEGACIÓN Y CONTROL =====
    screen_reader = db.Column(db.Boolean, default=False)
    keyboard_navigation = db.Column(db.Boolean, default=False)
    voice_enabled = db.Column(db.Boolean, default=False)
    skip_links = db.Column(db.Boolean, default=True)
    
    # ===== FORMULARIOS Y VALIDACIÓN =====
    real_time_validation = db.Column(db.Boolean, default=True)
    visual_error_feedback = db.Column(db.Boolean, default=True)
    auto_focus_fields = db.Column(db.Boolean, default=True)
    password_toggle = db.Column(db.Boolean, default=True)
    
    # ===== PREFERENCIAS DE INTERFAZ =====
    consistent_menu_location = db.Column(db.Boolean, default=True)
    hierarchical_menus = db.Column(db.Boolean, default=True)
    clear_menu_labels = db.Column(db.Boolean, default=True)
    active_item_highlight = db.Column(db.Boolean, default=True)
    text_icon_combination = db.Column(db.Boolean, default=True)
    responsive_design = db.Column(db.Boolean, default=True)
    hover_feedback = db.Column(db.Boolean, default=True)
    
    # ===== CONFIGURACIONES DE SEGURIDAD =====
    session_timeout = db.Column(db.Integer, default=900)  # 15 minutos en segundos
    antispam_verification = db.Column(db.Boolean, default=True)
    secure_remembering = db.Column(db.Boolean, default=True)
    
    # ===== PREFERENCIAS DE IDIOMA Y LOCALIZACIÓN =====
    preferred_language = db.Column(db.String(10), default='es')
    multilingual_support = db.Column(db.Boolean, default=True)
    
    # ===== CONFIGURACIONES COGNITIVAS =====
    cognitive_load_reduction = db.Column(db.Boolean, default=True)
    logical_order = db.Column(db.Boolean, default=True)
    explanatory_text = db.Column(db.Boolean, default=True)
    minimum_response_time = db.Column(db.Boolean, default=True)
    
    # ===== CONFIGURACIÓN DE VOZ =====
    speech_rate = db.Column(db.Float, default=1.0)  # 0.5 - 2.0
    speech_volume = db.Column(db.Float, default=1.0)  # 0.0 - 1.0
    speech_pitch = db.Column(db.Float, default=1.0)  # 0.0 - 2.0
    preferred_voice = db.Column(db.String(100), default='')
    
    # Metadatos
    is_active = db.Column(db.Boolean, default=True)
    is_admin = db.Column(db.Boolean, default=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    last_login = db.Column(db.DateTime)
    accessibility_profile = db.Column(db.String(50), default='standard')  # standard, visual, auditory, motor, cognitive
    
    # Relaciones
    reviews = db.relationship('Review', backref='user', lazy='dynamic', cascade='all, delete-orphan')
    favorites = db.relationship('Favorite', backref='user', lazy='dynamic', cascade='all, delete-orphan')
    
    def __init__(self, email, username, password, first_name, last_name, **kwargs):
        self.email = email
        self.username = username
        self.first_name = first_name
        self.last_name = last_name
        self.set_password(password)
        for key, value in kwargs.items():
            if hasattr(self, key):
                setattr(self, key, value)
    
    def set_password(self, password):
        """Hashear la contraseña"""
        self.password_hash = generate_password_hash(password)
    
    def check_password(self, password):
        """Verificar la contraseña"""
        return check_password_hash(self.password_hash, password)
    
    def get_full_name(self):
        """Obtener nombre completo"""
        return f"{self.first_name} {self.last_name}"
    
    def get_accessibility_preferences(self):
        """Obtener todas las preferencias de accesibilidad como diccionario"""
        return {
            # Visual
            'high_contrast': self.high_contrast,
            'monochromatic_mode': self.monochromatic_mode,
            'large_text': self.large_text,
            'increased_spacing': self.increased_spacing,
            'adjusted_line_height': self.adjusted_line_height,
            'page_zoom_level': self.page_zoom_level,
            'focus_highlight': self.focus_highlight,
            'pause_animations': self.pause_animations,
            
            # Auditivo
            'video_subtitles': self.video_subtitles,
            'live_subtitles': self.live_subtitles,
            'audio_description': self.audio_description,
            'volume_control': self.volume_control,
            'visual_alerts': self.visual_alerts,
            
            # Navegación
            'screen_reader': self.screen_reader,
            'keyboard_navigation': self.keyboard_navigation,
            'voice_enabled': self.voice_enabled,
            'skip_links': self.skip_links,
            
            # Formularios
            'real_time_validation': self.real_time_validation,
            'visual_error_feedback': self.visual_error_feedback,
            'auto_focus_fields': self.auto_focus_fields,
            'password_toggle': self.password_toggle,
            
            # Interfaz
            'consistent_menu_location': self.consistent_menu_location,
            'hierarchical_menus': self.hierarchical_menus,
            'clear_menu_labels': self.clear_menu_labels,
            'active_item_highlight': self.active_item_highlight,
            'text_icon_combination': self.text_icon_combination,
            'responsive_design': self.responsive_design,
            'hover_feedback': self.hover_feedback,
            
            # Seguridad
            'session_timeout': self.session_timeout,
            'antispam_verification': self.antispam_verification,
            'secure_remembering': self.secure_remembering,
            
            # Idioma
            'preferred_language': self.preferred_language,
            'multilingual_support': self.multilingual_support,
            
            # Cognitivo
            'cognitive_load_reduction': self.cognitive_load_reduction,
            'logical_order': self.logical_order,
            'explanatory_text': self.explanatory_text,
            'minimum_response_time': self.minimum_response_time,
            
            # Voz
            'speech_rate': self.speech_rate,
            'speech_volume': self.speech_volume,
            'speech_pitch': self.speech_pitch,
            'preferred_voice': self.preferred_voice,
            
            # Perfil
            'accessibility_profile': self.accessibility_profile
        }
    
    def update_accessibility_settings(self, settings):
        """Actualizar configuraciones de accesibilidad completas"""
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
        
        for field in accessibility_fields:
            if field in settings:
                setattr(self, field, settings[field])
        
        self.updated_at = datetime.utcnow()
        db.session.commit()
    
    def set_accessibility_profile(self, profile_type):
        """Establecer perfil de accesibilidad predefinido"""
        profiles = {
            'visual': {
                'high_contrast': True,
                'large_text': True,
                'focus_highlight': True,
                'screen_reader': True,
                'keyboard_navigation': True
            },
            'auditory': {
                'video_subtitles': True,
                'live_subtitles': True,
                'visual_alerts': True,
                'audio_description': True
            },
            'motor': {
                'keyboard_navigation': True,
                'focus_highlight': True,
                'increased_spacing': True,
                'pause_animations': True,
                'hover_feedback': False
            },
            'cognitive': {
                'cognitive_load_reduction': True,
                'logical_order': True,
                'explanatory_text': True,
                'minimum_response_time': True,
                'pause_animations': True,
                'real_time_validation': True
            }
        }
        
        if profile_type in profiles:
            self.accessibility_profile = profile_type
            self.update_accessibility_settings(profiles[profile_type])
    
    def to_dict(self, include_sensitive=False):
        """Convertir usuario a diccionario"""
        data = {
            'public_id': self.public_id,
            'email': self.email,
            'username': self.username,
            'first_name': self.first_name,
            'last_name': self.last_name,
            'full_name': self.get_full_name(),
            'accessibility_settings': self.get_accessibility_preferences(),
            'is_active': self.is_active,
            'is_admin': self.is_admin,
            'created_at': self.created_at.isoformat() if self.created_at else None,
            'last_login': self.last_login.isoformat() if self.last_login else None
        }
        
        if include_sensitive:
            data['id'] = self.id
            data['updated_at'] = self.updated_at.isoformat() if self.updated_at else None
        
        return data
    
    def __repr__(self):
        return f'<User {self.username}>'
