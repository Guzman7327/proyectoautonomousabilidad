#!/usr/bin/env python3
"""
Script de configuración de base de datos para Portal Turístico Ecuador
Automatiza la creación y configuración de la base de datos PostgreSQL
"""

import psycopg2
import bcrypt
import sys
import os
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT

def crear_base_datos():
    """Crear la base de datos si no existe"""
    try:
        # Conectar como superusuario para crear la base de datos
        conn = psycopg2.connect(
            host="localhost",
            user="postgres",
            password="123456789"  # Cambiar por tu contraseña de postgres
        )
        conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)
        cur = conn.cursor()
        
        # Verificar si la base de datos existe
        cur.execute("SELECT 1 FROM pg_database WHERE datname='turismo'")
        if not cur.fetchone():
            print("🗄️  Creando base de datos 'turismo'...")
            cur.execute("CREATE DATABASE turismo WITH ENCODING 'UTF8' LC_COLLATE='es_ES.UTF-8' LC_CTYPE='es_ES.UTF-8'")
            print("✅ Base de datos creada exitosamente")
        else:
            print("ℹ️  La base de datos 'turismo' ya existe")
        
        cur.close()
        conn.close()
        return True
        
    except Exception as e:
        print(f"❌ Error creando base de datos: {e}")
        return False

def ejecutar_script_sql():
    """Ejecutar el script SQL para crear las tablas"""
    try:
        # Conectar a la base de datos turismo
        conn = psycopg2.connect(
            host="localhost",
            database="turismo",
            user="postgres",
            password="123456789"  # Cambiar por tu contraseña de postgres
        )
        cur = conn.cursor()
        
        print("📋 Ejecutando script SQL...")
        
        # Leer y ejecutar el archivo init.sql
        with open('init.sql', 'r', encoding='utf-8') as file:
            sql_script = file.read()
        
        # Dividir el script en comandos individuales
        commands = sql_script.split(';')
        
        for command in commands:
            command = command.strip()
            if command and not command.startswith('--') and not command.startswith('/*'):
                try:
                    cur.execute(command)
                except Exception as e:
                    if "already exists" not in str(e).lower():
                        print(f"⚠️  Advertencia en comando SQL: {e}")
        
        conn.commit()
        print("✅ Script SQL ejecutado exitosamente")
        
        cur.close()
        conn.close()
        return True
        
    except Exception as e:
        print(f"❌ Error ejecutando script SQL: {e}")
        return False

def verificar_tablas():
    """Verificar que las tablas se crearon correctamente"""
    try:
        conn = psycopg2.connect(
            host="localhost",
            database="turismo",
            user="postgres",
            password="123456789"  # Cambiar por tu contraseña de postgres
        )
        cur = conn.cursor()
        
        # Verificar tablas principales
        tablas_esperadas = ['usuarios', 'destinos', 'mensajes_contacto', 'accesibilidad_log']
        tablas_creadas = []
        
        for tabla in tablas_esperadas:
            cur.execute(f"SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = '{tabla}')")
            if cur.fetchone()[0]:
                tablas_creadas.append(tabla)
                print(f"✅ Tabla '{tabla}' creada correctamente")
            else:
                print(f"❌ Tabla '{tabla}' NO encontrada")
        
        # Verificar datos de ejemplo
        cur.execute("SELECT COUNT(*) FROM usuarios")
        num_usuarios = cur.fetchone()[0]
        print(f"👥 Usuarios en la base de datos: {num_usuarios}")
        
        cur.execute("SELECT COUNT(*) FROM destinos")
        num_destinos = cur.fetchone()[0]
        print(f"🗺️  Destinos en la base de datos: {num_destinos}")
        
        cur.close()
        conn.close()
        
        return len(tablas_creadas) == len(tablas_esperadas)
        
    except Exception as e:
        print(f"❌ Error verificando tablas: {e}")
        return False

def crear_usuario_admin():
    """Crear usuario administrador si no existe"""
    try:
        conn = psycopg2.connect(
            host="localhost",
            database="turismo",
            user="postgres",
            password="123456789"  # Cambiar por tu contraseña de postgres
        )
        cur = conn.cursor()
        
        # Verificar si el admin ya existe
        cur.execute("SELECT id FROM usuarios WHERE usuario = 'admin'")
        if not cur.fetchone():
            print("👤 Creando usuario administrador...")
            
            # Hash de la contraseña
            password = "admin123"
            hashed = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt())
            
            cur.execute("""
                INSERT INTO usuarios (usuario, clave, rol, nombre, cedula, email, telefono, notificaciones)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                'admin', hashed, 'admin', 'Administrador del Sistema',
                '1234567890', 'admin@turismoecuador.com', '+593 2 234 5678', True
            ))
            
            conn.commit()
            print("✅ Usuario administrador creado")
            print("   Usuario: admin")
            print("   Contraseña: admin123")
        else:
            print("ℹ️  Usuario administrador ya existe")
        
        cur.close()
        conn.close()
        return True
        
    except Exception as e:
        print(f"❌ Error creando usuario admin: {e}")
        return False

def mostrar_instrucciones():
    """Mostrar instrucciones de configuración"""
    print("\n" + "="*60)
    print("🎯 CONFIGURACIÓN DE BASE DE DATOS COMPLETADA")
    print("="*60)
    print("\n📋 Próximos pasos:")
    print("1. Verifica que PostgreSQL esté instalado y ejecutándose")
    print("2. Ajusta las credenciales en backend/app.py si es necesario")
    print("3. Ejecuta la aplicación: python backend/app.py")
    print("4. Accede a http://localhost:5000")
    print("\n🔑 Credenciales de acceso:")
    print("   Usuario: admin")
    print("   Contraseña: admin123")
    print("\n📊 Estructura de la base de datos:")
    print("   ✅ Tabla usuarios - Para login y registro")
    print("   ✅ Tabla destinos - Para información turística")
    print("   ✅ Tabla mensajes_contacto - Para formulario de contacto")
    print("   ✅ Tabla accesibilidad_log - Para seguimiento de accesibilidad")
    print("   ✅ Tabla sesiones - Para gestión de sesiones")
    print("   ✅ Tabla reservas - Para futuras funcionalidades")
    print("\n🚀 ¡Tu portal turístico está listo para usar!")

def main():
    """Función principal"""
    print("🚀 CONFIGURADOR DE BASE DE DATOS - PORTAL TURÍSTICO ECUADOR")
    print("="*60)
    
    # Verificar que PostgreSQL esté disponible
    try:
        psycopg2.connect(
            host="localhost",
            user="postgres",
            password="123456789"
        )
    except Exception as e:
        print("❌ Error conectando a PostgreSQL:")
        print("   Asegúrate de que PostgreSQL esté instalado y ejecutándose")
        print("   Verifica las credenciales en este script")
        print(f"   Error: {e}")
        return False
    
    # Paso 1: Crear base de datos
    if not crear_base_datos():
        return False
    
    # Paso 2: Ejecutar script SQL
    if not ejecutar_script_sql():
        return False
    
    # Paso 3: Verificar tablas
    if not verificar_tablas():
        return False
    
    # Paso 4: Crear usuario admin
    if not crear_usuario_admin():
        return False
    
    # Mostrar instrucciones finales
    mostrar_instrucciones()
    
    return True

if __name__ == "__main__":
    try:
        success = main()
        if success:
            print("\n✅ Configuración completada exitosamente")
            sys.exit(0)
        else:
            print("\n❌ Configuración falló")
            sys.exit(1)
    except KeyboardInterrupt:
        print("\n\n⏹️  Configuración cancelada por el usuario")
        sys.exit(1)
    except Exception as e:
        print(f"\n❌ Error inesperado: {e}")
        sys.exit(1) 