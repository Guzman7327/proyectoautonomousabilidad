from flask import Blueprint, render_template, request, redirect, url_for, flash, abort
from flask_login import login_required, current_user
from app.models import Destination, Review, Favorite
from app import db

destinations_bp = Blueprint('destinations', __name__)

@destinations_bp.route('/')
def destinations_list():
    """Lista de todos los destinos"""
    
    # Parámetros de filtrado y paginación
    page = request.args.get('page', 1, type=int)
    per_page = 12
    search = request.args.get('search', '').strip()
    province = request.args.get('province', '').strip()
    category = request.args.get('category', '').strip()
    accessibility = request.args.get('accessibility', '').strip()
    sort_by = request.args.get('sort_by', 'featured')
    
    # Query base
    query = Destination.query.filter_by(is_active=True)
    
    # Aplicar filtros
    if search:
        query = query.filter(
            Destination.name.ilike(f'%{search}%') |
            Destination.description.ilike(f'%{search}%') |
            Destination.city.ilike(f'%{search}%')
        )
    
    if province:
        query = query.filter_by(province=province)
    
    if category:
        query = query.filter_by(category=category)
    
    if accessibility:
        if accessibility == 'wheelchair':
            query = query.filter_by(wheelchair_accessible=True)
        elif accessibility == 'audio':
            query = query.filter_by(audio_guide_available=True)
        elif accessibility == 'visual':
            query = query.filter_by(braille_info_available=True)
        elif accessibility == 'high_score':
            query = query.filter(Destination.accessibility_rating >= 4)
    
    # Ordenamiento
    if sort_by == 'name':
        query = query.order_by(Destination.name.asc())
    elif sort_by == 'rating':
        query = query.order_by(Destination.accessibility_rating.desc())
    elif sort_by == 'newest':
        query = query.order_by(Destination.created_at.desc())
    else:  # featured
        query = query.order_by(
            Destination.is_featured.desc(),
            Destination.created_at.desc()
        )
    
    # Paginar resultados
    destinations = query.paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    # Obtener opciones para filtros
    provinces = [p[0] for p in Destination.get_provinces()]
    categories = [c[0] for c in Destination.get_categories()]
    
    # Configurar información de categorías
    category_info = {
        'natural': 'Naturaleza y Paisajes',
        'cultural': 'Patrimonio Cultural',
        'adventure': 'Aventura y Deportes',
        'urban': 'Turismo Urbano',
        'wellness': 'Bienestar y Relax'
    }
    
    return render_template('destinations/list.html',
                         destinations=destinations,
                         provinces=provinces,
                         categories=categories,
                         category_info=category_info,
                         current_filters={
                             'search': search,
                             'province': province,
                             'category': category,
                             'accessibility': accessibility,
                             'sort_by': sort_by
                         })

@destinations_bp.route('/<slug>')
def destination_detail(slug):
    """Detalle de un destino específico"""
    
    destination = Destination.query.filter_by(
        slug=slug,
        is_active=True
    ).first_or_404()
    
    # Obtener reseñas aprobadas
    reviews = destination.reviews.filter_by(is_approved=True).order_by(
        Review.created_at.desc()
    ).limit(10).all()
    
    # Verificar si está en favoritos del usuario actual
    is_favorite = False
    if current_user.is_authenticated:
        is_favorite = Favorite.query.filter_by(
            user_id=current_user.id,
            destination_id=destination.id
        ).first() is not None
    
    # Obtener destinos similares
    similar_destinations = Destination.query.filter(
        Destination.category == destination.category,
        Destination.province == destination.province,
        Destination.id != destination.id,
        Destination.is_active == True
    ).limit(4).all()
    
    # Si no hay suficientes en la misma provincia, buscar por categoría
    if len(similar_destinations) < 4:
        additional = Destination.query.filter(
            Destination.category == destination.category,
            Destination.id != destination.id,
            Destination.is_active == True
        ).filter(
            ~Destination.id.in_([d.id for d in similar_destinations])
        ).limit(4 - len(similar_destinations)).all()
        
        similar_destinations.extend(additional)
    
    # Obtener resumen de reseñas
    reviews_summary = destination.get_reviews_summary()
    
    return render_template('destinations/detail.html',
                         destination=destination,
                         reviews=reviews,
                         reviews_summary=reviews_summary,
                         is_favorite=is_favorite,
                         similar_destinations=similar_destinations)

@destinations_bp.route('/<slug>/review', methods=['GET', 'POST'])
@login_required
def create_review(slug):
    """Crear reseña para un destino"""
    
    destination = Destination.query.filter_by(
        slug=slug,
        is_active=True
    ).first_or_404()
    
    # Verificar si el usuario ya tiene una reseña
    existing_review = Review.query.filter_by(
        user_id=current_user.id,
        destination_id=destination.id
    ).first()
    
    if existing_review:
        flash('Ya has creado una reseña para este destino.', 'warning')
        return redirect(url_for('destinations.destination_detail', slug=slug))
    
    if request.method == 'POST':
        # Obtener datos del formulario
        title = request.form.get('title', '').strip()
        content = request.form.get('content', '').strip()
        rating = request.form.get('rating', type=int)
        accessibility_rating = request.form.get('accessibility_rating', type=int)
        accessibility_notes = request.form.get('accessibility_notes', '').strip()
        
        # Evaluaciones específicas de accesibilidad
        wheelchair_experience = request.form.get('wheelchair_experience', '').strip()
        visual_accessibility = request.form.get('visual_accessibility', '').strip()
        hearing_accessibility = request.form.get('hearing_accessibility', '').strip()
        cognitive_accessibility = request.form.get('cognitive_accessibility', '').strip()
        
        # Validaciones
        errors = []
        
        if not title or len(title) < 5:
            errors.append('El título debe tener al menos 5 caracteres.')
        
        if not content or len(content) < 20:
            errors.append('El contenido debe tener al menos 20 caracteres.')
        
        if not rating or rating < 1 or rating > 5:
            errors.append('Debes seleccionar una calificación entre 1 y 5 estrellas.')
        
        if accessibility_rating and (accessibility_rating < 1 or accessibility_rating > 5):
            errors.append('La calificación de accesibilidad debe estar entre 1 y 5 estrellas.')
        
        if errors:
            for error in errors:
                flash(error, 'error')
            return render_template('destinations/create_review.html', destination=destination)
        
        try:
            # Crear nueva reseña
            review = Review(
                user_id=current_user.id,
                destination_id=destination.id,
                title=title,
                content=content,
                rating=rating,
                accessibility_rating=accessibility_rating,
                accessibility_notes=accessibility_notes,
                wheelchair_experience=wheelchair_experience,
                visual_accessibility=visual_accessibility,
                hearing_accessibility=hearing_accessibility,
                cognitive_accessibility=cognitive_accessibility
            )
            
            db.session.add(review)
            db.session.commit()
            
            flash('¡Reseña creada exitosamente! Será revisada antes de publicarse.', 'success')
            return redirect(url_for('destinations.destination_detail', slug=slug))
            
        except Exception as e:
            db.session.rollback()
            flash('Ocurrió un error al crear la reseña. Inténtalo de nuevo.', 'error')
    
    return render_template('destinations/create_review.html', destination=destination)

@destinations_bp.route('/category/<category>')
def destinations_by_category(category):
    """Destinos filtrados por categoría"""
    
    page = request.args.get('page', 1, type=int)
    per_page = 12
    
    # Validar categoría
    valid_categories = ['natural', 'cultural', 'adventure', 'urban', 'wellness']
    if category not in valid_categories:
        abort(404)
    
    # Información de la categoría
    category_info = {
        'natural': {
            'name': 'Naturaleza y Paisajes',
            'description': 'Descubre los parques nacionales, reservas naturales, montañas, playas y maravillas naturales de Ecuador.',
            'icon': 'leaf'
        },
        'cultural': {
            'name': 'Patrimonio Cultural',
            'description': 'Explora sitios históricos, museos, arquitectura colonial y tradiciones culturales ecuatorianas.',
            'icon': 'landmark'
        },
        'adventure': {
            'name': 'Aventura y Deportes',
            'description': 'Vive experiencias emocionantes con actividades al aire libre y deportes extremos.',
            'icon': 'mountain'
        },
        'urban': {
            'name': 'Turismo Urbano',
            'description': 'Experimenta la vida urbana, gastronomía, arte y cultura en las ciudades ecuatorianas.',
            'icon': 'building'
        },
        'wellness': {
            'name': 'Bienestar y Relax',
            'description': 'Relájate en termas, spas y destinos de turismo de salud y bienestar.',
            'icon': 'spa'
        }
    }
    
    # Obtener destinos de la categoría
    destinations = Destination.query.filter_by(
        category=category,
        is_active=True
    ).order_by(
        Destination.is_featured.desc(),
        Destination.created_at.desc()
    ).paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    # Obtener provincias disponibles para esta categoría
    provinces = db.session.query(Destination.province).filter_by(
        category=category,
        is_active=True
    ).distinct().all()
    provinces = [p[0] for p in provinces]
    
    return render_template('destinations/category.html',
                         destinations=destinations,
                         category=category,
                         category_info=category_info[category],
                         provinces=provinces)

@destinations_bp.route('/province/<province>')
def destinations_by_province(province):
    """Destinos filtrados por provincia"""
    
    page = request.args.get('page', 1, type=int)
    per_page = 12
    
    # Verificar que la provincia existe
    province_exists = Destination.query.filter_by(
        province=province,
        is_active=True
    ).first()
    
    if not province_exists:
        abort(404)
    
    # Obtener destinos de la provincia
    destinations = Destination.query.filter_by(
        province=province,
        is_active=True
    ).order_by(
        Destination.is_featured.desc(),
        Destination.created_at.desc()
    ).paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    # Obtener estadísticas de la provincia
    total_destinations = Destination.query.filter_by(
        province=province,
        is_active=True
    ).count()
    
    accessible_destinations = Destination.query.filter_by(
        province=province,
        is_active=True,
        wheelchair_accessible=True
    ).count()
    
    # Obtener categorías disponibles en esta provincia
    categories = db.session.query(Destination.category).filter_by(
        province=province,
        is_active=True
    ).distinct().all()
    categories = [c[0] for c in categories]
    
    province_stats = {
        'total': total_destinations,
        'accessible': accessible_destinations,
        'categories': categories
    }
    
    return render_template('destinations/province.html',
                         destinations=destinations,
                         province=province,
                         province_stats=province_stats)

@destinations_bp.route('/accessible')
def accessible_destinations():
    """Destinos con características de accesibilidad"""
    
    page = request.args.get('page', 1, type=int)
    per_page = 12
    accessibility_type = request.args.get('type', 'all')
    
    # Query base para destinos accesibles
    query = Destination.query.filter_by(is_active=True)
    
    # Filtrar por tipo de accesibilidad
    if accessibility_type == 'wheelchair':
        query = query.filter_by(wheelchair_accessible=True)
        title = "Destinos Accesibles en Silla de Ruedas"
        description = "Destinos turísticos completamente accesibles para usuarios de sillas de ruedas"
    elif accessibility_type == 'audio':
        query = query.filter_by(audio_guide_available=True)
        title = "Destinos con Audioguías"
        description = "Destinos que ofrecen audioguías para una experiencia accesible"
    elif accessibility_type == 'visual':
        query = query.filter_by(braille_info_available=True)
        title = "Destinos con Información en Braille"
        description = "Destinos que proporcionan información en braille para personas con discapacidad visual"
    elif accessibility_type == 'high_rating':
        query = query.filter(Destination.accessibility_rating >= 4)
        title = "Destinos con Alta Puntuación de Accesibilidad"
        description = "Destinos con las mejores calificaciones en accesibilidad"
    else:
        # Mostrar destinos con al menos una característica de accesibilidad
        query = query.filter(
            (Destination.wheelchair_accessible == True) |
            (Destination.audio_guide_available == True) |
            (Destination.braille_info_available == True) |
            (Destination.sign_language_guide == True) |
            (Destination.accessible_parking == True) |
            (Destination.accessible_bathrooms == True) |
            (Destination.tactile_paths == True)
        )
        title = "Todos los Destinos Accesibles"
        description = "Destinos turísticos con características de accesibilidad"
    
    # Ordenar por puntuación de accesibilidad
    destinations = query.order_by(
        Destination.accessibility_rating.desc(),
        Destination.is_featured.desc()
    ).paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    # Tipos de accesibilidad disponibles
    accessibility_types = [
        {'key': 'all', 'name': 'Todos', 'icon': 'universal-access'},
        {'key': 'wheelchair', 'name': 'Silla de Ruedas', 'icon': 'wheelchair'},
        {'key': 'audio', 'name': 'Audioguías', 'icon': 'headphones'},
        {'key': 'visual', 'name': 'Información Braille', 'icon': 'braille'},
        {'key': 'high_rating', 'name': 'Alta Puntuación', 'icon': 'star'}
    ]
    
    return render_template('destinations/accessible.html',
                         destinations=destinations,
                         title=title,
                         description=description,
                         accessibility_types=accessibility_types,
                         current_type=accessibility_type)
