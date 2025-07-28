#!/bin/bash

echo "============================================"
echo "Portal de Turismo Inclusivo - Ecuador"
echo "Script de Instalación para PostgreSQL"
echo "============================================"
echo

# Verificar dependencias
echo "[1/6] Verificando dependencias..."
if ! command -v python3 &> /dev/null; then
    echo "ERROR: Python 3 no encontrado. Instale Python 3.8+ primero."
    exit 1
fi

if ! command -v psql &> /dev/null; then
    echo "ERROR: PostgreSQL no encontrado. Instale PostgreSQL primero."
    echo "Ubuntu/Debian: sudo apt-get install postgresql postgresql-contrib"
    echo "macOS: brew install postgresql"
    exit 1
fi

# Crear entorno virtual
echo "[2/6] Creando entorno virtual..."
if [ ! -d "venv" ]; then
    python3 -m venv venv
fi

# Activar entorno virtual e instalar dependencias
echo "[3/6] Activando entorno virtual e instalando dependencias..."
source venv/bin/activate
pip install --upgrade pip
pip install -r requirements.txt

# Configurar variables de entorno
echo "[4/6] Configurando variables de entorno..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "Archivo .env creado. Edite las configuraciones si es necesario."
fi

# Inicializar base de datos
echo "[5/6] Inicializando base de datos PostgreSQL..."
echo "Ingrese la contraseña del usuario postgres cuando se solicite:"

# Intentar con diferentes usuarios comunes de PostgreSQL
if psql -U postgres -f init-db.sql 2>/dev/null; then
    echo "Base de datos inicializada con usuario 'postgres'"
elif psql -U $USER -f init-db.sql 2>/dev/null; then
    echo "Base de datos inicializada con usuario '$USER'"
else
    echo "ERROR: No se pudo conectar a PostgreSQL."
    echo "Verifique que PostgreSQL esté ejecutándose y que tenga permisos."
    echo "Puede intentar manualmente: psql -U postgres -f init-db.sql"
    exit 1
fi

# Inicializar aplicación Flask
echo "[6/6] Inicializando aplicación Flask..."
export FLASK_APP=app.py
flask init-db
flask populate-sample-data

echo
echo "============================================"
echo "INSTALACIÓN COMPLETADA EXITOSAMENTE!"
echo "============================================"
echo
echo "Para ejecutar la aplicación:"
echo "1. Activar entorno virtual: source venv/bin/activate"
echo "2. Ejecutar aplicación: python app.py"
echo "3. Abrir navegador en: http://localhost:5000"
echo
echo "Base de datos: turismo_inclusivo_ecuador"
echo "Usuario DB: turismo_user"
echo "Puerto: 5432"
echo
echo "Para crear un usuario administrador:"
echo "flask create-admin"
echo
