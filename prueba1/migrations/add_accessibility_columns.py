# -*- coding: utf-8 -*-
"""
Script de migración para agregar todas las columnas de accesibilidad según ISO 9241-11 y ISO 25010:2011
Este script actualiza la tabla users con todas las configuraciones de accesibilidad requeridas
"""

import psycopg2
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT
import os
import sys
from datetime import datetime

# Configurar codificación
if sys.platform == "win32":
    import codecs
    sys.stdout = codecs.getwriter('utf-8')(sys.stdout.detach())
    sys.stderr = codecs.getwriter('utf-8')(sys.stderr.detach())

# Configuración de la base de datos
DATABASE_CONFIG = {
    'host': 'localhost',
    'port': '5432',
    'database': 'ecuador_tourism',
    'user': 'postgres',
    'password': 'postgres123'
}

def create_connection():
    """Crear conexión a la base de datos"""
    try:
        conn = psycopg2.connect(**DATABASE_CONFIG)
        conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)
        return conn
    except psycopg2.Error as e:
        print(f"Error conectando a la base de datos: {e}")
        return None

def add_accessibility_columns():
    """Agregar todas las columnas de accesibilidad a la tabla users"""
    
    # SQL para agregar todas las columnas de accesibilidad
    sql_commands = [
        # Visual Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones visuales
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'monochromatic_mode') THEN
                ALTER TABLE users ADD COLUMN monochromatic_mode BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'increased_spacing') THEN
                ALTER TABLE users ADD COLUMN increased_spacing BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'adjusted_line_height') THEN
                ALTER TABLE users ADD COLUMN adjusted_line_height BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'page_zoom_level') THEN
                ALTER TABLE users ADD COLUMN page_zoom_level INTEGER DEFAULT 100;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'focus_highlight') THEN
                ALTER TABLE users ADD COLUMN focus_highlight BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'pause_animations') THEN
                ALTER TABLE users ADD COLUMN pause_animations BOOLEAN DEFAULT FALSE;
            END IF;
        END
        $$;
        """,
        
        # Auditory Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones auditivas
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'video_subtitles') THEN
                ALTER TABLE users ADD COLUMN video_subtitles BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'live_subtitles') THEN
                ALTER TABLE users ADD COLUMN live_subtitles BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'audio_description') THEN
                ALTER TABLE users ADD COLUMN audio_description BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'volume_control') THEN
                ALTER TABLE users ADD COLUMN volume_control INTEGER DEFAULT 50;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'visual_alerts') THEN
                ALTER TABLE users ADD COLUMN visual_alerts BOOLEAN DEFAULT FALSE;
            END IF;
        END
        $$;
        """,
        
        # Navigation Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones de navegación
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'skip_links') THEN
                ALTER TABLE users ADD COLUMN skip_links BOOLEAN DEFAULT TRUE;
            END IF;
        END
        $$;
        """,
        
        # Form Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones de formularios
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'real_time_validation') THEN
                ALTER TABLE users ADD COLUMN real_time_validation BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'visual_error_feedback') THEN
                ALTER TABLE users ADD COLUMN visual_error_feedback BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'auto_focus_fields') THEN
                ALTER TABLE users ADD COLUMN auto_focus_fields BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'password_toggle') THEN
                ALTER TABLE users ADD COLUMN password_toggle BOOLEAN DEFAULT TRUE;
            END IF;
        END
        $$;
        """,
        
        # Interface Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones de interfaz
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'consistent_menu_location') THEN
                ALTER TABLE users ADD COLUMN consistent_menu_location BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'hierarchical_menus') THEN
                ALTER TABLE users ADD COLUMN hierarchical_menus BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'clear_menu_labels') THEN
                ALTER TABLE users ADD COLUMN clear_menu_labels BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'active_item_highlight') THEN
                ALTER TABLE users ADD COLUMN active_item_highlight BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'text_icon_combination') THEN
                ALTER TABLE users ADD COLUMN text_icon_combination BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'responsive_design') THEN
                ALTER TABLE users ADD COLUMN responsive_design BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'hover_feedback') THEN
                ALTER TABLE users ADD COLUMN hover_feedback BOOLEAN DEFAULT TRUE;
            END IF;
        END
        $$;
        """,
        
        # Security Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones de seguridad
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'session_timeout') THEN
                ALTER TABLE users ADD COLUMN session_timeout INTEGER DEFAULT 1800;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'antispam_verification') THEN
                ALTER TABLE users ADD COLUMN antispam_verification BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'secure_remembering') THEN
                ALTER TABLE users ADD COLUMN secure_remembering BOOLEAN DEFAULT FALSE;
            END IF;
        END
        $$;
        """,
        
        # Language Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones de idioma
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'multilingual_support') THEN
                ALTER TABLE users ADD COLUMN multilingual_support BOOLEAN DEFAULT TRUE;
            END IF;
        END
        $$;
        """,
        
        # Cognitive Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones cognitivas
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'cognitive_load_reduction') THEN
                ALTER TABLE users ADD COLUMN cognitive_load_reduction BOOLEAN DEFAULT FALSE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'logical_order') THEN
                ALTER TABLE users ADD COLUMN logical_order BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'explanatory_text') THEN
                ALTER TABLE users ADD COLUMN explanatory_text BOOLEAN DEFAULT TRUE;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'minimum_response_time') THEN
                ALTER TABLE users ADD COLUMN minimum_response_time BOOLEAN DEFAULT FALSE;
            END IF;
        END
        $$;
        """,
        
        # Voice and Speech Accessibility
        """
        DO $$
        BEGIN
            -- Configuraciones de voz
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'speech_rate') THEN
                ALTER TABLE users ADD COLUMN speech_rate DECIMAL(3,1) DEFAULT 1.0;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'speech_volume') THEN
                ALTER TABLE users ADD COLUMN speech_volume DECIMAL(3,1) DEFAULT 0.8;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'speech_pitch') THEN
                ALTER TABLE users ADD COLUMN speech_pitch DECIMAL(3,1) DEFAULT 1.0;
            END IF;
            
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'preferred_voice') THEN
                ALTER TABLE users ADD COLUMN preferred_voice VARCHAR(100) DEFAULT '';
            END IF;
        END
        $$;
        """,
        
        # Accessibility Profile
        """
        DO $$
        BEGIN
            -- Perfil de accesibilidad
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                          WHERE table_name = 'users' AND column_name = 'accessibility_profile') THEN
                ALTER TABLE users ADD COLUMN accessibility_profile VARCHAR(50) DEFAULT 'standard';
            END IF;
        END
        $$;
        """
    ]
    
    conn = create_connection()
    if not conn:
        return False
    
    try:
        cursor = conn.cursor()
        
        print(f"[{datetime.now()}] Iniciando migración de columnas de accesibilidad...")
        
        for i, command in enumerate(sql_commands, 1):
            print(f"[{datetime.now()}] Ejecutando comando {i} de {len(sql_commands)}...")
            cursor.execute(command)
            print(f"[{datetime.now()}] Comando {i} ejecutado exitosamente")
        
        # Verificar que las columnas se agregaron correctamente
        cursor.execute("""
            SELECT column_name, data_type, column_default 
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
        """)
        
        columns = cursor.fetchall()
        print(f"\n[{datetime.now()}] Columnas de accesibilidad agregadas:")
        for column in columns:
            print(f"  - {column[0]} ({column[1]}) DEFAULT {column[2]}")
        
        print(f"\n[{datetime.now()}] ¡Migración completada exitosamente!")
        print(f"Se agregaron {len(columns)} columnas de accesibilidad a la tabla users")
        
        cursor.close()
        conn.close()
        return True
        
    except psycopg2.Error as e:
        print(f"Error ejecutando migración: {e}")
        if conn:
            conn.close()
        return False

def verify_migration():
    """Verificar que la migración se ejecutó correctamente"""
    conn = create_connection()
    if not conn:
        return False
    
    try:
        cursor = conn.cursor()
        
        # Contar total de columnas en la tabla users
        cursor.execute("""
            SELECT COUNT(*) 
            FROM information_schema.columns 
            WHERE table_name = 'users';
        """)
        total_columns = cursor.fetchone()[0]
        
        # Contar columnas de accesibilidad
        cursor.execute("""
            SELECT COUNT(*) 
            FROM information_schema.columns 
            WHERE table_name = 'users' 
            AND column_name LIKE '%accessibility%' 
            OR column_name IN (
                'high_contrast', 'large_text', 'screen_reader', 'keyboard_navigation',
                'voice_enabled', 'preferred_language', 'monochromatic_mode',
                'increased_spacing', 'adjusted_line_height', 'page_zoom_level',
                'focus_highlight', 'pause_animations', 'video_subtitles',
                'live_subtitles', 'audio_description', 'volume_control',
                'visual_alerts', 'skip_links', 'real_time_validation',
                'visual_error_feedback', 'auto_focus_fields', 'password_toggle',
                'consistent_menu_location', 'hierarchical_menus', 'clear_menu_labels',
                'active_item_highlight', 'text_icon_combination', 'responsive_design',
                'hover_feedback', 'session_timeout', 'antispam_verification',
                'secure_remembering', 'multilingual_support', 'cognitive_load_reduction',
                'logical_order', 'explanatory_text', 'minimum_response_time',
                'speech_rate', 'speech_volume', 'speech_pitch', 'preferred_voice'
            );
        """)
        accessibility_columns = cursor.fetchone()[0]
        
        print(f"\n[{datetime.now()}] Verificación de migración:")
        print(f"  - Total de columnas en tabla users: {total_columns}")
        print(f"  - Columnas de accesibilidad: {accessibility_columns}")
        print(f"  - Porcentaje de accesibilidad: {(accessibility_columns/total_columns)*100:.1f}%")
        
        cursor.close()
        conn.close()
        return True
        
    except psycopg2.Error as e:
        print(f"Error verificando migración: {e}")
        if conn:
            conn.close()
        return False

if __name__ == "__main__":
    print("="*80)
    print("MIGRACIÓN DE ACCESIBILIDAD - ISO 9241-11 & ISO 25010:2011")
    print("Portal Turístico de Ecuador - Sistema de Accesibilidad Completo")
    print("="*80)
    
    # Ejecutar migración
    if add_accessibility_columns():
        print("\n✅ Migración ejecutada exitosamente")
        
        # Verificar migración
        if verify_migration():
            print("✅ Verificación completada")
        else:
            print("❌ Error en verificación")
    else:
        print("\n❌ Error en migración")
    
    print("\n" + "="*80)
    print("Migración finalizada")
    print("="*80)
