from flask import Flask, g
from flask_sqlalchemy import SQLAlchemy
from flask_migrate import Migrate
from flask_login import LoginManager
from flask_cors import CORS
from flask_jwt_extended import JWTManager
from config import config
import os
import secrets

# Inicialización de extensiones
db = SQLAlchemy()
migrate = Migrate()
login_manager = LoginManager()
jwt = JWTManager()

def create_app(config_name=None):
    """Factory function para crear la aplicación Flask"""
    
    if config_name is None:
        config_name = os.environ.get('FLASK_ENV', 'default')
    
    app = Flask(__name__)
    app.config.from_object(config[config_name])
    
    # Inicializar extensiones
    db.init_app(app)
    migrate.init_app(app, db)
    login_manager.init_app(app)
    jwt.init_app(app)
    CORS(app)
    
    # Configuración de Flask-Login
    login_manager.login_view = 'auth.login'
    login_manager.login_message = 'Por favor, inicia sesión para acceder a esta página.'
    login_manager.login_message_category = 'info'
    
    @login_manager.user_loader
    def load_user(user_id):
        from app.models.user import User
        return User.query.get(int(user_id))
    
    # Función simple para generar CSRF token
    def generate_csrf_token():
        if not hasattr(g, 'csrf_token'):
            g.csrf_token = secrets.token_hex(32)
        return g.csrf_token
    
    app.jinja_env.globals['csrf_token'] = generate_csrf_token
    
    # Registrar blueprints
    from app.routes.main import main_bp
    from app.routes.auth import auth_bp
    from app.routes.destinations import destinations_bp
    from app.routes.api import api_bp
    from app.routes.accessibility import accessibility_bp
    
    app.register_blueprint(main_bp)
    app.register_blueprint(auth_bp, url_prefix='/auth')
    app.register_blueprint(destinations_bp, url_prefix='/destinations')
    app.register_blueprint(api_bp, url_prefix='/api')
    app.register_blueprint(accessibility_bp, url_prefix='/accessibility')
    
    # Registrar API de accesibilidad
    from app.api.accessibility import accessibility_api
    app.register_blueprint(accessibility_api)
    
    # Configurar handlers de errores
    @app.errorhandler(404)
    def not_found_error(error):
        return render_template('errors/404.html'), 404
    
    @app.errorhandler(500)
    def internal_error(error):
        db.session.rollback()
        return render_template('errors/500.html'), 500
    
    # Contexto de aplicación para templates
    @app.context_processor
    def inject_config():
        return dict(config=app.config)
    
    return app
