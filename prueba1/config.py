import os
from dotenv import load_dotenv

load_dotenv()

class Config:
    """Configuración base de la aplicación"""
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'dev-secret-key-change-in-production'
    
    # Configuración de CSRF
    WTF_CSRF_ENABLED = True
    WTF_CSRF_TIME_LIMIT = 3600  # 1 hora
    
    # Configuración de base de datos - SQLite por defecto, PostgreSQL si está disponible
    DATABASE_URL = os.environ.get('DATABASE_URL')
    
    if not DATABASE_URL:
        # Si no hay DATABASE_URL, intentar PostgreSQL local o usar SQLite
        try:
            DATABASE_HOST = os.environ.get('DB_HOST') or 'localhost'
            DATABASE_PORT = os.environ.get('DB_PORT') or '5432'
            DATABASE_NAME = os.environ.get('DB_NAME') or 'turismo_inclusivo_ecuador'
            DATABASE_USER = os.environ.get('DB_USER') or 'turismo_user'
            DATABASE_PASSWORD = os.environ.get('DB_PASSWORD') or 'turismo_password_2024'
            
            DATABASE_URL = f'postgresql://{DATABASE_USER}:{DATABASE_PASSWORD}@{DATABASE_HOST}:{DATABASE_PORT}/{DATABASE_NAME}'
        except:
            # Fallback a SQLite para desarrollo
            DATABASE_URL = 'sqlite:///turismo_inclusivo_ecuador.db'
    
    SQLALCHEMY_DATABASE_URI = DATABASE_URL
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    
    # Configuración JWT
    JWT_SECRET_KEY = os.environ.get('JWT_SECRET_KEY') or 'jwt-secret-change-in-production'
    JWT_ACCESS_TOKEN_EXPIRES = 3600  # 1 hora
    
    # Configuración de correo
    MAIL_SERVER = os.environ.get('MAIL_SERVER') or 'localhost'
    MAIL_PORT = int(os.environ.get('MAIL_PORT') or 587)
    MAIL_USE_TLS = os.environ.get('MAIL_USE_TLS', 'true').lower() in ['true', 'on', '1']
    MAIL_USERNAME = os.environ.get('MAIL_USERNAME')
    MAIL_PASSWORD = os.environ.get('MAIL_PASSWORD')
    
    # Configuración de archivos
    UPLOAD_FOLDER = os.environ.get('UPLOAD_FOLDER') or 'app/static/uploads'
    MAX_CONTENT_LENGTH = 16 * 1024 * 1024  # 16MB máximo
    
    # Configuración de Redis
    REDIS_URL = os.environ.get('REDIS_URL') or 'redis://localhost:6379/0'
    
    # Configuración de Celery
    CELERY_BROKER_URL = os.environ.get('CELERY_BROKER_URL') or 'redis://localhost:6379/0'
    CELERY_RESULT_BACKEND = os.environ.get('CELERY_RESULT_BACKEND') or 'redis://localhost:6379/0'

class DevelopmentConfig(Config):
    """Configuración para desarrollo"""
    DEBUG = True
    SQLALCHEMY_ECHO = True

class ProductionConfig(Config):
    """Configuración para producción"""
    DEBUG = False
    TESTING = False

class TestingConfig(Config):
    """Configuración para testing"""
    TESTING = True
    SQLALCHEMY_DATABASE_URI = 'sqlite:///:memory:'
    WTF_CSRF_ENABLED = False

config = {
    'development': DevelopmentConfig,
    'production': ProductionConfig,
    'testing': TestingConfig,
    'default': DevelopmentConfig
}
