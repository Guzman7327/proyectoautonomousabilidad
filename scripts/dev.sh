#!/bin/bash

# Script de desarrollo para Portal de Turismo Inclusivo
# Inicia todos los servicios necesarios para desarrollo

set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Función para limpiar procesos al salir
cleanup() {
    print_status "Deteniendo servicios de desarrollo..."
    pkill -f "npm run dev" || true
    pkill -f "nodemon" || true
    print_success "Servicios detenidos"
    exit 0
}

# Capturar señales de terminación
trap cleanup SIGINT SIGTERM

# Verificar si los archivos .env existen
check_env_files() {
    if [ ! -f backend/.env ]; then
        print_error "Archivo backend/.env no encontrado. Ejecuta ./scripts/setup.sh primero."
        exit 1
    fi
    
    if [ ! -f frontend/.env ]; then
        print_error "Archivo frontend/.env no encontrado. Ejecuta ./scripts/setup.sh primero."
        exit 1
    fi
    
    print_success "Archivos de configuración encontrados"
}

# Iniciar base de datos
start_database() {
    print_status "Iniciando base de datos..."
    
    if ! docker-compose ps postgres | grep -q "Up"; then
        docker-compose up -d postgres
        print_status "Esperando que la base de datos esté lista..."
        sleep 10
    else
        print_warning "Base de datos ya está ejecutándose"
    fi
    
    print_success "Base de datos lista"
}

# Iniciar backend en modo desarrollo
start_backend() {
    print_status "Iniciando backend en modo desarrollo..."
    
    cd backend
    
    # Verificar si node_modules existe
    if [ ! -d node_modules ]; then
        print_status "Instalando dependencias del backend..."
        npm install
    fi
    
    # Iniciar en background
    npm run dev &
    BACKEND_PID=$!
    
    cd ..
    
    print_success "Backend iniciado (PID: $BACKEND_PID)"
}

# Iniciar frontend en modo desarrollo
start_frontend() {
    print_status "Iniciando frontend en modo desarrollo..."
    
    cd frontend
    
    # Verificar si node_modules existe
    if [ ! -d node_modules ]; then
        print_status "Instalando dependencias del frontend..."
        npm install
    fi
    
    # Iniciar en background
    npm run dev &
    FRONTEND_PID=$!
    
    cd ..
    
    print_success "Frontend iniciado (PID: $FRONTEND_PID)"
}

# Mostrar información de servicios
show_services_info() {
    echo ""
    echo "🎉 ¡Servicios de desarrollo iniciados!"
    echo ""
    echo "📋 Servicios disponibles:"
    echo "   • Frontend: http://localhost:5173"
    echo "   • Backend API: http://localhost:3001"
    echo "   • Documentación API: http://localhost:3001/api-docs"
    echo "   • Base de datos: localhost:5432"
    echo ""
    echo "🔧 Comandos útiles:"
    echo "   • Ver logs del backend: tail -f backend/logs/app.log"
    echo "   • Ver logs de Docker: docker-compose logs -f"
    echo "   • Detener todo: Ctrl+C"
    echo ""
    echo "📝 Notas:"
    echo "   • Los cambios en el código se recargan automáticamente"
    echo "   • La base de datos se reinicia automáticamente si se detiene"
    echo "   • Usa Ctrl+C para detener todos los servicios"
    echo ""
}

# Función principal
main() {
    print_status "Iniciando entorno de desarrollo..."
    
    check_env_files
    start_database
    start_backend
    start_frontend
    show_services_info
    
    # Mantener el script ejecutándose
    print_status "Presiona Ctrl+C para detener todos los servicios..."
    wait
}

# Ejecutar función principal
main "$@" 