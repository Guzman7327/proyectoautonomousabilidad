from flask import Blueprint, render_template, request, jsonify, current_app
from app.models import Destination, Review, User

main_bp = Blueprint('main', __name__)

@main_bp.route('/')
def index():
    """Página principal del portal de turismo"""
    
    # Obtener destinos destacados
    featured_destinations = Destination.query.filter_by(
        is_featured=True, 
        is_active=True
    ).limit(6).all()
    
    # Obtener destinos por categorías principales
    nature_destinations = Destination.query.filter_by(
        category='natural',
        is_active=True
    ).limit(4).all()
    
    cultural_destinations = Destination.query.filter_by(
        category='cultural',
        is_active=True
    ).limit(4).all()
    
    # Obtener estadísticas
    stats = {
        'total_destinations': Destination.query.filter_by(is_active=True).count(),
        'accessible_destinations': Destination.query.filter_by(
            is_active=True,
            wheelchair_accessible=True
        ).count(),
        'total_reviews': Review.query.filter_by(is_approved=True).count(),
        'total_users': User.query.filter_by(is_active=True).count()
    }
    
    return render_template('index.html',
                         featured_destinations=featured_destinations,
                         nature_destinations=nature_destinations,
                         cultural_destinations=cultural_destinations,
                         stats=stats)

@main_bp.route('/about')
def about():
    """Página acerca de nosotros"""
    return render_template('about.html')

@main_bp.route('/accessibility')
def accessibility():
    """Página de información de accesibilidad"""
    return render_template('accessibility.html')

@main_bp.route('/contact')
def contact():
    """Página de contacto"""
    return render_template('contact.html')

@main_bp.route('/search')
def search():
    """Página de búsqueda de destinos"""
    
    # Obtener parámetros de búsqueda
    query = request.args.get('q', '')
    province = request.args.get('province', '')
    category = request.args.get('category', '')
    accessibility = request.args.get('accessibility', '')
    page = request.args.get('page', 1, type=int)
    per_page = 12
    
    # Construir query base
    destinations_query = Destination.query.filter_by(is_active=True)
    
    # Aplicar filtros
    if query:
        destinations_query = destinations_query.filter(
            Destination.name.ilike(f'%{query}%') |
            Destination.description.ilike(f'%{query}%')
        )
    
    if province:
        destinations_query = destinations_query.filter_by(province=province)
    
    if category:
        destinations_query = destinations_query.filter_by(category=category)
    
    if accessibility == 'wheelchair':
        destinations_query = destinations_query.filter_by(wheelchair_accessible=True)
    elif accessibility == 'audio':
        destinations_query = destinations_query.filter_by(audio_guide_available=True)
    elif accessibility == 'visual':
        destinations_query = destinations_query.filter_by(braille_info_available=True)
    
    # Ordenar y paginar
    destinations = destinations_query.order_by(
        Destination.is_featured.desc(),
        Destination.created_at.desc()
    ).paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    # Obtener opciones para filtros
    provinces = [p[0] for p in Destination.get_provinces()]
    categories = [c[0] for c in Destination.get_categories()]
    
    return render_template('search.html',
                         destinations=destinations,
                         provinces=provinces,
                         categories=categories,
                         current_filters={
                             'query': query,
                             'province': province,
                             'category': category,
                             'accessibility': accessibility
                         })

@main_bp.route('/map')
def map_view():
    """Vista de mapa interactivo"""
    
    # Obtener todos los destinos con coordenadas
    destinations = Destination.query.filter(
        Destination.is_active == True,
        Destination.latitude.isnot(None),
        Destination.longitude.isnot(None)
    ).all()
    
    # Convertir a formato para el mapa
    map_data = []
    for dest in destinations:
        map_data.append({
            'id': dest.public_id,
            'name': dest.name,
            'latitude': dest.latitude,
            'longitude': dest.longitude,
            'category': dest.category,
            'province': dest.province,
            'city': dest.city,
            'image': dest.main_image,
            'accessibility_score': dest.get_accessibility_score(),
            'wheelchair_accessible': dest.wheelchair_accessible,
            'audio_guide': dest.audio_guide_available,
            'rating': dest.get_reviews_summary()['average_rating']
        })
    
    return render_template('map.html', destinations_data=map_data)

@main_bp.route('/provinces')
def provinces():
    """Página de destinos por provincias"""
    
    # Obtener destinos agrupados por provincia
    provinces_data = {}
    provinces = [p[0] for p in Destination.get_provinces()]
    
    for province in provinces:
        destinations = Destination.query.filter_by(
            province=province,
            is_active=True
        ).limit(6).all()
        
        if destinations:
            provinces_data[province] = {
                'destinations': destinations,
                'total_count': Destination.query.filter_by(
                    province=province,
                    is_active=True
                ).count(),
                'accessible_count': Destination.query.filter_by(
                    province=province,
                    is_active=True,
                    wheelchair_accessible=True
                ).count()
            }
    
    return render_template('provinces.html', provinces_data=provinces_data)

@main_bp.route('/categories')
def categories():
    """Página de destinos por categorías"""
    
    category_info = {
        'natural': {
            'name': 'Naturaleza y Paisajes',
            'description': 'Parques nacionales, reservas, montañas, playas y maravillas naturales',
            'icon': 'tree'
        },
        'cultural': {
            'name': 'Patrimonio Cultural',
            'description': 'Sitios históricos, museos, arquitectura colonial y tradiciones',
            'icon': 'museum'
        },
        'adventure': {
            'name': 'Aventura y Deportes',
            'description': 'Actividades al aire libre, deportes extremos y turismo activo',
            'icon': 'mountain'
        },
        'urban': {
            'name': 'Turismo Urbano',
            'description': 'Ciudades, gastronomía, vida nocturna y experiencias urbanas',
            'icon': 'building'
        },
        'wellness': {
            'name': 'Bienestar y Relax',
            'description': 'Termas, spas, turismo de salud y experiencias de relajación',
            'icon': 'spa'
        }
    }
    
    categories_data = {}
    
    for category_key, category_info_item in category_info.items():
        destinations = Destination.query.filter_by(
            category=category_key,
            is_active=True
        ).limit(8).all()
        
        if destinations:
            categories_data[category_key] = {
                'info': category_info_item,
                'destinations': destinations,
                'total_count': Destination.query.filter_by(
                    category=category_key,
                    is_active=True
                ).count()
            }
    
    return render_template('categories.html', categories_data=categories_data)

@main_bp.app_errorhandler(404)
def not_found_error(error):
    """Manejar errores 404"""
    return render_template('errors/404.html'), 404

@main_bp.app_errorhandler(500)
def internal_error(error):
    """Manejar errores 500"""
    return render_template('errors/500.html'), 500
