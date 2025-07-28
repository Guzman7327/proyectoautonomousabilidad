@echo off
echo ============================================
echo Portal de Turismo Inclusivo - Ecuador
echo Script de Instalacion para PostgreSQL
echo ============================================
echo.

echo [1/6] Verificando dependencias...
where python >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Python no encontrado. Instale Python 3.8+ primero.
    pause
    exit /b 1
)

where psql >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PostgreSQL no encontrado. Instale PostgreSQL primero.
    echo Descargue desde: https://www.postgresql.org/download/windows/
    pause
    exit /b 1
)

echo [2/6] Creando entorno virtual...
if not exist "venv\" (
    python -m venv venv
)

echo [3/6] Activando entorno virtual e instalando dependencias...
call venv\Scripts\activate.bat
pip install --upgrade pip
pip install -r requirements.txt

echo [4/6] Configurando variables de entorno...
if not exist ".env" (
    copy .env.example .env
    echo Archivo .env creado. Edite las configuraciones si es necesario.
)

echo [5/6] Inicializando base de datos PostgreSQL...
echo Ingrese la contraseña del usuario postgres cuando se solicite:
psql -U postgres -f init-db.sql

if %errorlevel% neq 0 (
    echo ERROR: No se pudo inicializar la base de datos.
    echo Verifique que PostgreSQL este ejecutandose y que tenga permisos.
    pause
    exit /b 1
)

echo [6/6] Inicializando aplicacion Flask...
set FLASK_APP=app.py
flask init-db
flask populate-sample-data

echo.
echo ============================================
echo INSTALACION COMPLETADA EXITOSAMENTE!
echo ============================================
echo.
echo Para ejecutar la aplicacion:
echo 1. Activar entorno virtual: venv\Scripts\activate.bat
echo 2. Ejecutar aplicacion: python app.py
echo 3. Abrir navegador en: http://localhost:5000
echo.
echo Base de datos: turismo_inclusivo_ecuador
echo Usuario DB: turismo_user
echo Puerto: 5432
echo.
echo Para crear un usuario administrador:
echo flask create-admin
echo.
pause
