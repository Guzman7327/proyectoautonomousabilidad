# -*- coding: utf-8 -*-
"""
Script de migración simplificado para agregar columnas de accesibilidad
"""

from app import create_app, db
from app.models.user import User
from sqlalchemy import text
import sys

def run_migration():
    """Ejecutar migración de accesibilidad usando Flask-SQLAlchemy"""
    app = create_app()
    
    with app.app_context():
        try:
            # Comandos SQL para agregar columnas
            commands = [
                # Visual
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS monochromatic_mode BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS increased_spacing BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS adjusted_line_height BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS page_zoom_level INTEGER DEFAULT 100;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS focus_highlight BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS pause_animations BOOLEAN DEFAULT FALSE;",
                
                # Auditivo
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS video_subtitles BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS live_subtitles BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS audio_description BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS volume_control INTEGER DEFAULT 50;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS visual_alerts BOOLEAN DEFAULT FALSE;",
                
                # Navegación
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS skip_links BOOLEAN DEFAULT TRUE;",
                
                # Formularios
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS real_time_validation BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS visual_error_feedback BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS auto_focus_fields BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS password_toggle BOOLEAN DEFAULT TRUE;",
                
                # Interfaz
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS consistent_menu_location BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS hierarchical_menus BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS clear_menu_labels BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS active_item_highlight BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS text_icon_combination BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS responsive_design BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS hover_feedback BOOLEAN DEFAULT TRUE;",
                
                # Seguridad
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS session_timeout INTEGER DEFAULT 1800;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS antispam_verification BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS secure_remembering BOOLEAN DEFAULT FALSE;",
                
                # Idioma
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS multilingual_support BOOLEAN DEFAULT TRUE;",
                
                # Cognitivo
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS cognitive_load_reduction BOOLEAN DEFAULT FALSE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS logical_order BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS explanatory_text BOOLEAN DEFAULT TRUE;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS minimum_response_time BOOLEAN DEFAULT FALSE;",
                
                # Voz
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS speech_rate DECIMAL(3,1) DEFAULT 1.0;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS speech_volume DECIMAL(3,1) DEFAULT 0.8;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS speech_pitch DECIMAL(3,1) DEFAULT 1.0;",
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS preferred_voice VARCHAR(100) DEFAULT '';",
                
                # Perfil
                "ALTER TABLE users ADD COLUMN IF NOT EXISTS accessibility_profile VARCHAR(50) DEFAULT 'standard';"
            ]
            
            print("Iniciando migración de accesibilidad...")
            
            for i, command in enumerate(commands, 1):
                print(f"Ejecutando comando {i}/{len(commands)}...")
                db.session.execute(text(command))
            
            db.session.commit()
            print(f"✅ Migración completada! Se agregaron {len(commands)} columnas de accesibilidad")
            
            # Verificar columnas agregadas
            result = db.session.execute(text("""
                SELECT column_name, data_type 
                FROM information_schema.columns 
                WHERE table_name = 'users' 
                AND column_name IN (
                    'monochromatic_mode', 'increased_spacing', 'adjusted_line_height',
                    'page_zoom_level', 'focus_highlight', 'pause_animations',
                    'video_subtitles', 'live_subtitles', 'audio_description',
                    'volume_control', 'visual_alerts', 'skip_links',
                    'real_time_validation', 'visual_error_feedback', 'auto_focus_fields',
                    'password_toggle', 'consistent_menu_location', 'hierarchical_menus',
                    'clear_menu_labels', 'active_item_highlight', 'text_icon_combination',
                    'responsive_design', 'hover_feedback', 'session_timeout',
                    'antispam_verification', 'secure_remembering', 'multilingual_support',
                    'cognitive_load_reduction', 'logical_order', 'explanatory_text',
                    'minimum_response_time', 'speech_rate', 'speech_volume',
                    'speech_pitch', 'preferred_voice', 'accessibility_profile'
                )
                ORDER BY column_name;
            """))
            
            columns = result.fetchall()
            print(f"\nColumnas de accesibilidad verificadas ({len(columns)}):")
            for column in columns:
                print(f"  ✓ {column[0]} ({column[1]})")
            
            return True
            
        except Exception as e:
            print(f"❌ Error en migración: {e}")
            db.session.rollback()
            return False

if __name__ == "__main__":
    print("="*70)
    print("MIGRACIÓN DE ACCESIBILIDAD ISO 9241-11 & ISO 25010:2011")
    print("="*70)
    
    if run_migration():
        print("\n✅ Migración exitosa")
    else:
        print("\n❌ Error en migración")
        sys.exit(1)
