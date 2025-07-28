from app import db
from datetime import datetime

class Review(db.Model):
    """Modelo de reseñas de destinos con enfoque en accesibilidad"""
    
    __tablename__ = 'reviews'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    destination_id = db.Column(db.Integer, db.ForeignKey('destinations.id'), nullable=False)
    
    # Contenido de la reseña
    title = db.Column(db.String(200), nullable=False)
    content = db.Column(db.Text, nullable=False)
    rating = db.Column(db.Integer, nullable=False)  # 1-5 estrellas
    
    # Evaluación específica de accesibilidad
    accessibility_rating = db.Column(db.Integer)  # 1-5 estrellas para accesibilidad
    accessibility_notes = db.Column(db.Text)
    
    # Información específica de accesibilidad evaluada
    wheelchair_experience = db.Column(db.String(20))  # excellent, good, fair, poor
    visual_accessibility = db.Column(db.String(20))
    hearing_accessibility = db.Column(db.String(20))
    cognitive_accessibility = db.Column(db.String(20))
    
    # Metadatos
    is_approved = db.Column(db.Boolean, default=False)
    is_featured = db.Column(db.Boolean, default=False)
    helpful_votes = db.Column(db.Integer, default=0)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    def __init__(self, user_id, destination_id, title, content, rating, **kwargs):
        self.user_id = user_id
        self.destination_id = destination_id
        self.title = title
        self.content = content
        self.rating = rating
        
        for key, value in kwargs.items():
            if hasattr(self, key):
                setattr(self, key, value)
    
    def to_dict(self, include_user=True):
        """Convertir reseña a diccionario"""
        data = {
            'id': self.id,
            'title': self.title,
            'content': self.content,
            'rating': self.rating,
            'accessibility': {
                'rating': self.accessibility_rating,
                'notes': self.accessibility_notes,
                'wheelchair_experience': self.wheelchair_experience,
                'visual_accessibility': self.visual_accessibility,
                'hearing_accessibility': self.hearing_accessibility,
                'cognitive_accessibility': self.cognitive_accessibility
            },
            'helpful_votes': self.helpful_votes,
            'is_featured': self.is_featured,
            'created_at': self.created_at.isoformat() if self.created_at else None,
            'updated_at': self.updated_at.isoformat() if self.updated_at else None
        }
        
        if include_user and self.user:
            data['user'] = {
                'username': self.user.username,
                'full_name': self.user.get_full_name()
            }
        
        return data
    
    def __repr__(self):
        return f'<Review {self.title} by {self.user.username}>'


class Favorite(db.Model):
    """Modelo de destinos favoritos de usuarios"""
    
    __tablename__ = 'favorites'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    destination_id = db.Column(db.Integer, db.ForeignKey('destinations.id'), nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    # Índice único para evitar duplicados
    __table_args__ = (db.UniqueConstraint('user_id', 'destination_id', name='unique_user_destination'),)
    
    def __init__(self, user_id, destination_id):
        self.user_id = user_id
        self.destination_id = destination_id
    
    def to_dict(self):
        """Convertir favorito a diccionario"""
        return {
            'id': self.id,
            'destination': self.destination.to_dict() if self.destination else None,
            'created_at': self.created_at.isoformat() if self.created_at else None
        }
    
    def __repr__(self):
        return f'<Favorite {self.user.username} - {self.destination.name}>'


class AccessibilityFeedback(db.Model):
    """Modelo para feedback de accesibilidad del sitio web"""
    
    __tablename__ = 'accessibility_feedback'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=True)
    
    # Información del feedback
    email = db.Column(db.String(120))  # Para usuarios no registrados
    name = db.Column(db.String(100))
    feedback_type = db.Column(db.String(50), nullable=False)  # bug, suggestion, complaint, compliment
    page_url = db.Column(db.String(500))
    description = db.Column(db.Text, nullable=False)
    
    # Información de accesibilidad
    assistive_technology = db.Column(db.String(100))  # screen reader, voice recognition, etc.
    browser_info = db.Column(db.String(200))
    severity = db.Column(db.String(20), default='medium')  # low, medium, high, critical
    
    # Metadatos
    is_resolved = db.Column(db.Boolean, default=False)
    admin_response = db.Column(db.Text)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    resolved_at = db.Column(db.DateTime)
    
    def __init__(self, feedback_type, description, **kwargs):
        self.feedback_type = feedback_type
        self.description = description
        
        for key, value in kwargs.items():
            if hasattr(self, key):
                setattr(self, key, value)
    
    def to_dict(self):
        """Convertir feedback a diccionario"""
        data = {
            'id': self.id,
            'feedback_type': self.feedback_type,
            'page_url': self.page_url,
            'description': self.description,
            'assistive_technology': self.assistive_technology,
            'browser_info': self.browser_info,
            'severity': self.severity,
            'is_resolved': self.is_resolved,
            'admin_response': self.admin_response,
            'created_at': self.created_at.isoformat() if self.created_at else None,
            'resolved_at': self.resolved_at.isoformat() if self.resolved_at else None
        }
        
        if self.user:
            data['user'] = {
                'username': self.user.username,
                'email': self.user.email
            }
        else:
            data['user'] = {
                'name': self.name,
                'email': self.email
            }
        
        return data
    
    def __repr__(self):
        return f'<AccessibilityFeedback {self.feedback_type} - {self.id}>'
