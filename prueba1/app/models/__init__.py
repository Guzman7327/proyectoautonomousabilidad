# Importar todos los modelos para facilitar las importaciones
from .user import User
from .destination import Destination
from .review import Review, Favorite, AccessibilityFeedback

__all__ = ['User', 'Destination', 'Review', 'Favorite', 'AccessibilityFeedback']
