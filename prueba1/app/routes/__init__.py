# Importar todos los blueprints
from .main import main_bp
from .auth import auth_bp
from .destinations import destinations_bp
from .api import api_bp
from .accessibility import accessibility_bp

__all__ = ['main_bp', 'auth_bp', 'destinations_bp', 'api_bp', 'accessibility_bp']
