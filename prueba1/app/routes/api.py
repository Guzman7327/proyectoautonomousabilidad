from flask import Blueprint, request, jsonify, current_app
from flask_login import login_required, current_user
from flask_jwt_extended import create_access_token, jwt_required, get_jwt_identity
from app import db
from app.models import Destination, Review, User, Favorite, AccessibilityFeedback
from sqlalchemy import or_, func
import json

api_bp = Blueprint('api', __name__)

# Utilidades
def paginate_query(query, page=1, per_page=20):
    """Paginar una consulta y devolver formato estándar"""
    pagination = query.paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    return {
        'items': [item.to_dict() for item in pagination.items],
        'pagination': {
            'page': pagination.page,
            'pages': pagination.pages,
            'per_page': pagination.per_page,
            'total': pagination.total,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
            'next_num': pagination.next_num,
            'prev_num': pagination.prev_num
        }
    }

def success_response(data=None, message="Success", status_code=200):
    """Respuesta exitosa estándar"""
    response = {
        'success': True,
        'message': message,
        'data': data
    }
    return jsonify(response), status_code

def error_response(message="Error", status_code=400, errors=None):
    """Respuesta de error estándar"""
    response = {
        'success': False,
        'message': message,
        'errors': errors
    }
    return jsonify(response), status_code

# Endpoints de destinos
@api_bp.route('/destinations', methods=['GET'])
def get_destinations():
    """Obtener lista de destinos con filtros y paginación"""
    
    try:
        # Parámetros de consulta
        page = request.args.get('page', 1, type=int)
        per_page = min(request.args.get('per_page', 20, type=int), 100)
        search = request.args.get('search', '').strip()
        province = request.args.get('province', '').strip()
        category = request.args.get('category', '').strip()
        accessibility = request.args.get('accessibility', '').strip()
        sort_by = request.args.get('sort_by', 'created_at')
        order = request.args.get('order', 'desc')
        
        # Query base
        query = Destination.query.filter_by(is_active=True)
        
        # Aplicar filtros
        if search:
            query = query.filter(
                or_(
                    Destination.name.ilike(f'%{search}%'),
                    Destination.description.ilike(f'%{search}%'),
                    Destination.city.ilike(f'%{search}%')
                )
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
                # Destinos con puntuación de accesibilidad alta (>=4)
                query = query.filter(Destination.accessibility_rating >= 4)
        
        # Ordenamiento
        if sort_by == 'name':
            query = query.order_by(Destination.name.asc() if order == 'asc' else Destination.name.desc())
        elif sort_by == 'rating':
            query = query.order_by(Destination.accessibility_rating.desc() if order == 'desc' else Destination.accessibility_rating.asc())
        elif sort_by == 'featured':
            query = query.order_by(Destination.is_featured.desc(), Destination.created_at.desc())
        else:
            query = query.order_by(Destination.created_at.desc() if order == 'desc' else Destination.created_at.asc())
        
        # Paginar resultados
        result = paginate_query(query, page, per_page)
        
        return success_response(result)
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo destinos: {str(e)}')
        return error_response('Error interno del servidor', 500)

@api_bp.route('/destinations/<public_id>', methods=['GET'])
def get_destination(public_id):
    """Obtener un destino específico"""
    
    try:
        destination = Destination.query.filter_by(
            public_id=public_id,
            is_active=True
        ).first()
        
        if not destination:
            return error_response('Destino no encontrado', 404)
        
        # Incluir reseñas en la respuesta
        destination_data = destination.to_dict(include_reviews=True)
        
        return success_response(destination_data)
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo destino {public_id}: {str(e)}')
        return error_response('Error interno del servidor', 500)

@api_bp.route('/destinations/<public_id>/reviews', methods=['GET'])
def get_destination_reviews(public_id):
    """Obtener reseñas de un destino"""
    
    try:
        destination = Destination.query.filter_by(
            public_id=public_id,
            is_active=True
        ).first()
        
        if not destination:
            return error_response('Destino no encontrado', 404)
        
        page = request.args.get('page', 1, type=int)
        per_page = min(request.args.get('per_page', 10, type=int), 50)
        
        reviews_query = destination.reviews.filter_by(is_approved=True).order_by(Review.created_at.desc())
        result = paginate_query(reviews_query, page, per_page)
        
        return success_response(result)
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo reseñas del destino {public_id}: {str(e)}')
        return error_response('Error interno del servidor', 500)

@api_bp.route('/destinations/<public_id>/reviews', methods=['POST'])
@login_required
def create_review(public_id):
    """Crear una reseña para un destino"""
    
    try:
        destination = Destination.query.filter_by(
            public_id=public_id,
            is_active=True
        ).first()
        
        if not destination:
            return error_response('Destino no encontrado', 404)
        
        # Verificar si el usuario ya tiene una reseña para este destino
        existing_review = Review.query.filter_by(
            user_id=current_user.id,
            destination_id=destination.id
        ).first()
        
        if existing_review:
            return error_response('Ya has creado una reseña para este destino', 400)
        
        data = request.get_json()
        
        # Validaciones
        if not data:
            return error_response('Datos requeridos', 400)
        
        title = data.get('title', '').strip()
        content = data.get('content', '').strip()
        rating = data.get('rating')
        
        if not title or len(title) < 5:
            return error_response('El título debe tener al menos 5 caracteres', 400)
        
        if not content or len(content) < 20:
            return error_response('El contenido debe tener al menos 20 caracteres', 400)
        
        if not rating or rating < 1 or rating > 5:
            return error_response('La calificación debe estar entre 1 y 5', 400)
        
        # Crear reseña
        review = Review(
            user_id=current_user.id,
            destination_id=destination.id,
            title=title,
            content=content,
            rating=rating,
            accessibility_rating=data.get('accessibility_rating'),
            accessibility_notes=data.get('accessibility_notes', '').strip(),
            wheelchair_experience=data.get('wheelchair_experience'),
            visual_accessibility=data.get('visual_accessibility'),
            hearing_accessibility=data.get('hearing_accessibility'),
            cognitive_accessibility=data.get('cognitive_accessibility')
        )
        
        db.session.add(review)
        db.session.commit()
        
        return success_response(
            review.to_dict(),
            'Reseña creada exitosamente. Será revisada antes de publicarse.',
            201
        )
        
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f'Error creando reseña: {str(e)}')
        return error_response('Error interno del servidor', 500)

@api_bp.route('/destinations/<public_id>/favorite', methods=['POST'])
@login_required
def toggle_favorite(public_id):
    """Agregar o quitar destino de favoritos"""
    
    try:
        destination = Destination.query.filter_by(
            public_id=public_id,
            is_active=True
        ).first()
        
        if not destination:
            return error_response('Destino no encontrado', 404)
        
        # Verificar si ya está en favoritos
        favorite = Favorite.query.filter_by(
            user_id=current_user.id,
            destination_id=destination.id
        ).first()
        
        if favorite:
            # Quitar de favoritos
            db.session.delete(favorite)
            db.session.commit()
            return success_response(
                {'is_favorite': False},
                'Destino removido de favoritos'
            )
        else:
            # Agregar a favoritos
            favorite = Favorite(
                user_id=current_user.id,
                destination_id=destination.id
            )
            db.session.add(favorite)
            db.session.commit()
            return success_response(
                {'is_favorite': True},
                'Destino agregado a favoritos'
            )
        
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f'Error manejando favorito: {str(e)}')
        return error_response('Error interno del servidor', 500)

# Endpoints de usuario
@api_bp.route('/user/favorites', methods=['GET'])
@login_required
def get_user_favorites():
    """Obtener destinos favoritos del usuario"""
    
    try:
        page = request.args.get('page', 1, type=int)
        per_page = min(request.args.get('per_page', 20, type=int), 100)
        
        favorites_query = current_user.favorites.order_by(Favorite.created_at.desc())
        result = paginate_query(favorites_query, page, per_page)
        
        return success_response(result)
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo favoritos: {str(e)}')
        return error_response('Error interno del servidor', 500)

@api_bp.route('/user/reviews', methods=['GET'])
@login_required
def get_user_reviews():
    """Obtener reseñas del usuario"""
    
    try:
        page = request.args.get('page', 1, type=int)
        per_page = min(request.args.get('per_page', 20, type=int), 100)
        
        reviews_query = current_user.reviews.order_by(Review.created_at.desc())
        result = paginate_query(reviews_query, page, per_page)
        
        return success_response(result)
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo reseñas del usuario: {str(e)}')
        return error_response('Error interno del servidor', 500)

# Endpoints de búsqueda
@api_bp.route('/search/suggestions', methods=['GET'])
def search_suggestions():
    """Obtener sugerencias de búsqueda"""
    
    try:
        query = request.args.get('q', '').strip()
        
        if not query or len(query) < 2:
            return success_response([])
        
        # Buscar en nombres de destinos y ciudades
        destinations = Destination.query.filter(
            Destination.is_active == True,
            or_(
                Destination.name.ilike(f'{query}%'),
                Destination.city.ilike(f'{query}%')
            )
        ).limit(10).all()
        
        suggestions = []
        
        # Agregar nombres de destinos
        for dest in destinations:
            suggestions.append({
                'type': 'destination',
                'text': dest.name,
                'province': dest.province,
                'city': dest.city,
                'public_id': dest.public_id
            })
        
        # Agregar ciudades únicas
        cities = db.session.query(Destination.city, Destination.province).filter(
            Destination.is_active == True,
            Destination.city.ilike(f'{query}%')
        ).distinct().limit(5).all()
        
        for city, province in cities:
            if not any(s['text'] == city for s in suggestions):
                suggestions.append({
                    'type': 'city',
                    'text': city,
                    'province': province
                })
        
        return success_response(suggestions[:10])
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo sugerencias: {str(e)}')
        return error_response('Error interno del servidor', 500)

# Endpoints de estadísticas
@api_bp.route('/stats', methods=['GET'])
def get_stats():
    """Obtener estadísticas del sitio"""
    
    try:
        stats = {
            'destinations': {
                'total': Destination.query.filter_by(is_active=True).count(),
                'by_category': dict(
                    db.session.query(
                        Destination.category,
                        func.count(Destination.id)
                    ).filter_by(is_active=True).group_by(Destination.category).all()
                ),
                'accessible': Destination.query.filter_by(
                    is_active=True,
                    wheelchair_accessible=True
                ).count(),
                'with_audio_guide': Destination.query.filter_by(
                    is_active=True,
                    audio_guide_available=True
                ).count()
            },
            'reviews': {
                'total': Review.query.filter_by(is_approved=True).count(),
                'average_rating': db.session.query(func.avg(Review.rating)).filter_by(is_approved=True).scalar() or 0
            },
            'users': {
                'total': User.query.filter_by(is_active=True).count(),
                'with_accessibility_settings': User.query.filter(
                    User.is_active == True,
                    or_(
                        User.high_contrast == True,
                        User.large_text == True,
                        User.screen_reader == True,
                        User.keyboard_navigation == True,
                        User.voice_enabled == True
                    )
                ).count()
            }
        }
        
        return success_response(stats)
        
    except Exception as e:
        current_app.logger.error(f'Error obteniendo estadísticas: {str(e)}')
        return error_response('Error interno del servidor', 500)

# Endpoints de accesibilidad
@api_bp.route('/accessibility/feedback', methods=['POST'])
def submit_accessibility_feedback():
    """Enviar feedback de accesibilidad"""
    
    try:
        data = request.get_json()
        
        if not data:
            return error_response('Datos requeridos', 400)
        
        feedback_type = data.get('feedback_type', '').strip()
        description = data.get('description', '').strip()
        
        if not feedback_type or feedback_type not in ['bug', 'suggestion', 'complaint', 'compliment']:
            return error_response('Tipo de feedback inválido', 400)
        
        if not description or len(description) < 10:
            return error_response('La descripción debe tener al menos 10 caracteres', 400)
        
        feedback = AccessibilityFeedback(
            feedback_type=feedback_type,
            description=description,
            page_url=data.get('page_url', '').strip(),
            email=data.get('email', '').strip(),
            name=data.get('name', '').strip(),
            assistive_technology=data.get('assistive_technology', '').strip(),
            browser_info=data.get('browser_info', '').strip(),
            severity=data.get('severity', 'medium'),
            user_id=current_user.id if current_user.is_authenticated else None
        )
        
        db.session.add(feedback)
        db.session.commit()
        
        return success_response(
            feedback.to_dict(),
            'Feedback de accesibilidad enviado exitosamente',
            201
        )
        
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f'Error enviando feedback: {str(e)}')
        return error_response('Error interno del servidor', 500)
