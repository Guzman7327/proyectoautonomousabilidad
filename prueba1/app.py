#!/usr/bin/env python3
"""
Portal de Turismo Inclusivo - Ecuador
Aplicación principal Flask
"""

import os
import click
from flask.cli import with_appcontext
from flask import Flask, render_template
from app import create_app, db
from app.models import User, Destination, Review, Favorite, AccessibilityFeedback

# Crear instancia de la aplicación
app = create_app(os.getenv('FLASK_ENV', 'default'))

# Corregir import faltante en app/__init__.py
@app.errorhandler(404)
def not_found_error(error):
    return render_template('errors/404.html'), 404

@app.errorhandler(500)
def internal_error(error):
    db.session.rollback()
    return render_template('errors/500.html'), 500

# Contexto de shell para desarrollo
@app.shell_context_processor
def make_shell_context():
    return {
        'db': db,
        'User': User,
        'Destination': Destination,
        'Review': Review,
        'Favorite': Favorite,
        'AccessibilityFeedback': AccessibilityFeedback
    }

# Comandos CLI personalizados
@click.command()
@with_appcontext
def init_db():
    """Inicializar la base de datos."""
    db.create_all()
    click.echo('Base de datos inicializada.')

@click.command()
@with_appcontext
def create_admin():
    """Crear usuario administrador."""
    email = click.prompt('Email del administrador')
    username = click.prompt('Nombre de usuario')
    password = click.prompt('Contraseña', hide_input=True)
    first_name = click.prompt('Nombre')
    last_name = click.prompt('Apellido')
    
    # Verificar si ya existe
    if User.query.filter_by(email=email).first():
        click.echo('Error: Ya existe un usuario con ese email.')
        return
    
    if User.query.filter_by(username=username).first():
        click.echo('Error: Ya existe un usuario con ese nombre de usuario.')
        return
    
    # Crear administrador
    admin = User(
        email=email,
        username=username,
        password=password,
        first_name=first_name,
        last_name=last_name,
        is_admin=True
    )
    
    db.session.add(admin)
    db.session.commit()
    
    click.echo(f'Administrador {username} creado exitosamente.')

@click.command()
@with_appcontext
def populate_sample_data():
    """Poblar la base de datos con datos de ejemplo."""
    
    # Crear algunos destinos de ejemplo
    sample_destinations = [
        {
            'name': 'Parque Nacional Galápagos',
            'description': 'Las Islas Galápagos, declaradas Patrimonio Natural de la Humanidad por la UNESCO, ofrecen una experiencia única de biodiversidad. Este archipiélago volcánico es hogar de especies endémicas como las tortugas gigantes, iguanas marinas y pinzones de Darwin.',
            'short_description': 'Archipelago único con biodiversidad excepcional y especies endémicas.',
            'province': 'Galápagos',
            'city': 'Puerto Ayora',
            'category': 'natural',
            'latitude': -0.7435,
            'longitude': -90.3084,
            'wheelchair_accessible': True,
            'audio_guide_available': True,
            'braille_info_available': True,
            'accessibility_rating': 5,
            'admission_fee': 100.0,
            'is_featured': True,
            'activities': ['observación de fauna', 'snorkel', 'caminatas', 'fotografía'],
            'facilities': ['centro de visitantes', 'senderos', 'muelles', 'área de descanso']
        },
        {
            'name': 'Centro Histórico de Quito',
            'description': 'El Centro Histórico de Quito, primer Patrimonio Cultural de la Humanidad declarado por la UNESCO, conserva la arquitectura colonial mejor preservada de América Latina. Sus iglesias, plazas y calles empedradas narran 500 años de historia.',
            'short_description': 'Centro histórico colonial mejor conservado de América Latina.',
            'province': 'Pichincha',
            'city': 'Quito',
            'category': 'cultural',
            'latitude': -0.2201,
            'longitude': -78.5123,
            'wheelchair_accessible': False,
            'audio_guide_available': True,
            'braille_info_available': True,
            'sign_language_guide': True,
            'accessibility_rating': 3,
            'admission_fee': 0.0,
            'is_featured': True,
            'activities': ['tour histórico', 'visita a iglesias', 'fotografía', 'gastronomía'],
            'facilities': ['museos', 'iglesias', 'plazas', 'restaurantes']
        },
        {
            'name': 'Parque Nacional Cotopaxi',
            'description': 'El Parque Nacional Cotopaxi protege uno de los volcanes activos más altos del mundo. Sus páramos andinos, lagunas glaciales y fauna única ofrecen experiencias de montañismo y ecoturismo de alta calidad.',
            'short_description': 'Volcán activo más alto del mundo con páramos andinos únicos.',
            'province': 'Cotopaxi',
            'city': 'Latacunga',
            'category': 'adventure',
            'latitude': -0.6847,
            'longitude': -78.4376,
            'wheelchair_accessible': False,
            'audio_guide_available': False,
            'accessibility_rating': 2,
            'admission_fee': 10.0,
            'is_featured': True,
            'activities': ['montañismo', 'trekking', 'ciclismo', 'observación de aves'],
            'facilities': ['refugio', 'senderos', 'área de camping', 'centro de interpretación']
        },
        {
            'name': 'Malecón 2000 - Guayaquil',
            'description': 'El Malecón 2000 es un moderno paseo urbano a orillas del río Guayas. Este espacio completamente accesible combina historia, cultura, entretenimiento y gastronomía en un ambiente seguro y familiar.',
            'short_description': 'Moderno malecón urbano completamente accesible con múltiples atracciones.',
            'province': 'Guayas',
            'city': 'Guayaquil',
            'category': 'urban',
            'latitude': -2.1962,
            'longitude': -79.8862,
            'wheelchair_accessible': True,
            'audio_guide_available': True,
            'accessible_parking': True,
            'accessible_bathrooms': True,
            'tactile_paths': True,
            'accessibility_rating': 5,
            'admission_fee': 0.0,
            'is_featured': True,
            'activities': ['paseo', 'museos', 'cine IMAX', 'gastronomía'],
            'facilities': ['jardines', 'fuentes', 'plazas', 'restaurantes', 'museos']
        },
        {
            'name': 'Termas de Papallacta',
            'description': 'Las Termas de Papallacta ofrecen aguas termales naturales con propiedades relajantes y terapéuticas. Ubicadas en un entorno natural privilegiado, brindan una experiencia de bienestar integral.',
            'short_description': 'Aguas termales naturales con propiedades terapéuticas y relajantes.',
            'province': 'Napo',
            'city': 'Papallacta',
            'category': 'wellness',
            'latitude': -0.3693,
            'longitude': -78.1489,
            'wheelchair_accessible': True,
            'accessible_parking': True,
            'accessible_bathrooms': True,
            'accessibility_rating': 4,
            'admission_fee': 15.0,
            'activities': ['termas', 'spa', 'relajación', 'masajes'],
            'facilities': ['piscinas termales', 'spa', 'restaurante', 'alojamiento']
        }
    ]
    
    for dest_data in sample_destinations:
        # Verificar si ya existe
        if not Destination.query.filter_by(name=dest_data['name']).first():
            destination = Destination(**dest_data)
            db.session.add(destination)
    
    db.session.commit()
    click.echo('Datos de ejemplo agregados exitosamente.')

app.cli.add_command(init_db)
app.cli.add_command(create_admin)
app.cli.add_command(populate_sample_data)

@app.route('/health')
def health_check():
    """Endpoint de verificación de salud para Docker"""
    return {
        'status': 'healthy',
        'service': 'Portal de Turismo Inclusivo Ecuador',
        'version': '1.0.0'
    }, 200

@app.errorhandler(404)
def page_not_found(e):
    """Manejador de error 404 personalizado"""
    return render_template('errors/404.html'), 404

@app.errorhandler(500)
def internal_server_error(e):
    """Manejador de error 500 personalizado"""
    db.session.rollback()
    return render_template('errors/500.html'), 500

@app.errorhandler(403)
def forbidden(e):
    """Manejador de error 403 personalizado"""
    return render_template('errors/403.html'), 403

@app.shell_context_processor
def make_shell_context():
    """Contexto de shell para facilitar el desarrollo"""
    return {
        'db': db,
        'User': User,
        'Destination': Destination, 
        'Review': Review,
        'AccessibilityFeedback': AccessibilityFeedback
    }

if __name__ == '__main__':
    # Configuración para desarrollo y producción
    port = int(os.environ.get('PORT', 5000))
    debug = os.environ.get('FLASK_ENV') == 'development'
    
    app.run(
        host='0.0.0.0',
        port=port,
        debug=debug,
        threaded=True
    )
