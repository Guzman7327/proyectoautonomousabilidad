from app import db
from datetime import datetime
from sqlalchemy.dialects.postgresql import JSON
import uuid

class Destination(db.Model):
    """Modelo de destinos turísticos con información de accesibilidad"""
    
    __tablename__ = 'destinations'
    
    id = db.Column(db.Integer, primary_key=True)
    public_id = db.Column(db.String(50), unique=True, default=lambda: str(uuid.uuid4()))
    
    # Información básica
    name = db.Column(db.String(200), nullable=False, index=True)
    slug = db.Column(db.String(250), unique=True, nullable=False, index=True)
    description = db.Column(db.Text, nullable=False)
    short_description = db.Column(db.String(500))
    
    # Ubicación
    province = db.Column(db.String(100), nullable=False, index=True)
    city = db.Column(db.String(100), nullable=False)
    address = db.Column(db.String(500))
    latitude = db.Column(db.Float)
    longitude = db.Column(db.Float)
    
    # Información de accesibilidad
    wheelchair_accessible = db.Column(db.Boolean, default=False)
    audio_guide_available = db.Column(db.Boolean, default=False)
    braille_info_available = db.Column(db.Boolean, default=False)
    sign_language_guide = db.Column(db.Boolean, default=False)
    accessible_parking = db.Column(db.Boolean, default=False)
    accessible_bathrooms = db.Column(db.Boolean, default=False)
    tactile_paths = db.Column(db.Boolean, default=False)
    accessibility_rating = db.Column(db.Integer, default=0)  # 0-5 estrellas
    accessibility_notes = db.Column(db.Text)
    
    # Categorías y actividades
    category = db.Column(db.String(100), nullable=False, index=True)  # natural, cultural, aventura, etc.
    activities = db.Column(JSON)  # Lista de actividades disponibles
    facilities = db.Column(JSON)  # Instalaciones disponibles
    
    # Información práctica
    opening_hours = db.Column(JSON)  # Horarios por día de la semana
    admission_fee = db.Column(db.Float, default=0.0)
    contact_phone = db.Column(db.String(20))
    contact_email = db.Column(db.String(120))
    website = db.Column(db.String(200))
    
    # Multimedia
    main_image = db.Column(db.String(300))
    image_alt_text = db.Column(db.String(500))  # Texto alternativo para accesibilidad
    images = db.Column(JSON)  # Lista de imágenes adicionales con alt text
    virtual_tour_url = db.Column(db.String(300))
    
    # Metadatos
    is_active = db.Column(db.Boolean, default=True)
    is_featured = db.Column(db.Boolean, default=False)
    difficulty_level = db.Column(db.String(20), default='easy')  # easy, moderate, hard
    best_time_to_visit = db.Column(db.String(100))
    average_duration = db.Column(db.Integer)  # duración en minutos
    
    # Fechas
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relaciones
    reviews = db.relationship('Review', backref='destination', lazy='dynamic', cascade='all, delete-orphan')
    favorites = db.relationship('Favorite', backref='destination', lazy='dynamic', cascade='all, delete-orphan')
    
    def __init__(self, name, description, province, city, category, **kwargs):
        self.name = name
        self.description = description
        self.province = province
        self.city = city
        self.category = category
        self.slug = self.generate_slug(name)
        
        for key, value in kwargs.items():
            if hasattr(self, key):
                setattr(self, key, value)
    
    def generate_slug(self, name):
        """Generar slug único para el destino"""
        import re
        from unidecode import unidecode
        
        # Convertir a ASCII y limpiar
        slug = unidecode(name).lower()
        slug = re.sub(r'[^a-z0-9]+', '-', slug)
        slug = slug.strip('-')
        
        # Verificar unicidad
        base_slug = slug
        counter = 1
        while Destination.query.filter_by(slug=slug).first():
            slug = f"{base_slug}-{counter}"
            counter += 1
        
        return slug
    
    def get_accessibility_score(self):
        """Calcular puntuación de accesibilidad"""
        accessibility_features = [
            self.wheelchair_accessible,
            self.audio_guide_available,
            self.braille_info_available,
            self.sign_language_guide,
            self.accessible_parking,
            self.accessible_bathrooms,
            self.tactile_paths
        ]
        
        return sum(1 for feature in accessibility_features if feature)
    
    def get_reviews_summary(self):
        """Obtener resumen de reseñas"""
        reviews = self.reviews.filter_by(is_approved=True)
        total_reviews = reviews.count()
        
        if total_reviews == 0:
            return {'total': 0, 'average_rating': 0, 'accessibility_average': 0}
        
        ratings = [review.rating for review in reviews]
        accessibility_ratings = [review.accessibility_rating for review in reviews if review.accessibility_rating]
        
        return {
            'total': total_reviews,
            'average_rating': sum(ratings) / len(ratings),
            'accessibility_average': sum(accessibility_ratings) / len(accessibility_ratings) if accessibility_ratings else 0
        }
    
    def to_dict(self, include_reviews=False):
        """Convertir destino a diccionario"""
        reviews_summary = self.get_reviews_summary()
        
        data = {
            'public_id': self.public_id,
            'name': self.name,
            'slug': self.slug,
            'description': self.description,
            'short_description': self.short_description,
            'location': {
                'province': self.province,
                'city': self.city,
                'address': self.address,
                'coordinates': {
                    'latitude': self.latitude,
                    'longitude': self.longitude
                } if self.latitude and self.longitude else None
            },
            'accessibility': {
                'wheelchair_accessible': self.wheelchair_accessible,
                'audio_guide_available': self.audio_guide_available,
                'braille_info_available': self.braille_info_available,
                'sign_language_guide': self.sign_language_guide,
                'accessible_parking': self.accessible_parking,
                'accessible_bathrooms': self.accessible_bathrooms,
                'tactile_paths': self.tactile_paths,
                'rating': self.accessibility_rating,
                'notes': self.accessibility_notes,
                'score': self.get_accessibility_score()
            },
            'category': self.category,
            'activities': self.activities or [],
            'facilities': self.facilities or [],
            'practical_info': {
                'opening_hours': self.opening_hours,
                'admission_fee': self.admission_fee,
                'contact_phone': self.contact_phone,
                'contact_email': self.contact_email,
                'website': self.website,
                'difficulty_level': self.difficulty_level,
                'best_time_to_visit': self.best_time_to_visit,
                'average_duration': self.average_duration
            },
            'media': {
                'main_image': self.main_image,
                'image_alt_text': self.image_alt_text,
                'images': self.images or [],
                'virtual_tour_url': self.virtual_tour_url
            },
            'reviews': reviews_summary,
            'is_featured': self.is_featured,
            'created_at': self.created_at.isoformat() if self.created_at else None
        }
        
        if include_reviews:
            data['detailed_reviews'] = [
                review.to_dict() for review in self.reviews.filter_by(is_approved=True).limit(10)
            ]
        
        return data
    
    @staticmethod
    def get_provinces():
        """Obtener lista de provincias"""
        return db.session.query(Destination.province).distinct().all()
    
    @staticmethod
    def get_categories():
        """Obtener lista de categorías"""
        return db.session.query(Destination.category).distinct().all()
    
    def __repr__(self):
        return f'<Destination {self.name}>'
