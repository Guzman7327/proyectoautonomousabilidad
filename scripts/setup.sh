#!/bin/bash

# Script de configuración inicial para Portal de Turismo Inclusivo
# Este script configura el entorno de desarrollo completo

set -e

echo "🚀 Configurando Portal de Turismo Inclusivo..."

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
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

# Verificar si Docker está instalado
check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker no está instalado. Por favor instala Docker primero."
        exit 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose no está instalado. Por favor instala Docker Compose primero."
        exit 1
    fi
    
    print_success "Docker y Docker Compose están instalados"
}

# Verificar si Node.js está instalado
check_node() {
    if ! command -v node &> /dev/null; then
        print_error "Node.js no está instalado. Por favor instala Node.js 18+ primero."
        exit 1
    fi
    
    NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
    if [ "$NODE_VERSION" -lt 18 ]; then
        print_error "Node.js versión 18 o superior es requerida. Versión actual: $(node -v)"
        exit 1
    fi
    
    print_success "Node.js $(node -v) está instalado"
}

# Configurar variables de entorno
setup_env() {
    print_status "Configurando variables de entorno..."
    
    # Backend .env
    if [ ! -f backend/.env ]; then
        cat > backend/.env << EOF
# Configuración de la base de datos
DATABASE_URL=postgresql://postgres:postgres123@localhost:5432/turismo_inclusivo

# Configuración de JWT
JWT_SECRET=your-super-secret-jwt-key-change-in-production
JWT_EXPIRES_IN=7d

# Configuración del servidor
PORT=3001
NODE_ENV=development

# Configuración de CORS
CORS_ORIGIN=http://localhost:5173
CORS_CREDENTIALS=true

# Configuración de rate limiting
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100

# Configuración de logs
LOG_LEVEL=info
EOF
        print_success "Archivo backend/.env creado"
    else
        print_warning "Archivo backend/.env ya existe"
    fi
    
    # Frontend .env
    if [ ! -f frontend/.env ]; then
        cat > frontend/.env << EOF
# Configuración de la API
VITE_API_URL=http://localhost:3001/api

# Configuración de Mapbox
VITE_MAPBOX_TOKEN=your-mapbox-token-here

# Configuración de la aplicación
VITE_APP_NAME=Portal de Turismo Inclusivo
VITE_APP_VERSION=1.0.0
EOF
        print_success "Archivo frontend/.env creado"
    else
        print_warning "Archivo frontend/.env ya existe"
    fi
}

# Instalar dependencias del backend
setup_backend() {
    print_status "Configurando backend..."
    
    cd backend
    
    if [ ! -d node_modules ]; then
        print_status "Instalando dependencias del backend..."
        npm install
        print_success "Dependencias del backend instaladas"
    else
        print_warning "Dependencias del backend ya están instaladas"
    fi
    
    cd ..
}

# Instalar dependencias del frontend
setup_frontend() {
    print_status "Configurando frontend..."
    
    cd frontend
    
    if [ ! -d node_modules ]; then
        print_status "Instalando dependencias del frontend..."
        npm install
        print_success "Dependencias del frontend instaladas"
    else
        print_warning "Dependencias del frontend ya están instaladas"
    fi
    
    cd ..
}

# Iniciar servicios con Docker Compose
start_services() {
    print_status "Iniciando servicios con Docker Compose..."
    
    # Crear red de Docker si no existe
    docker network create turismo_network 2>/dev/null || true
    
    # Iniciar solo la base de datos
    docker-compose up -d postgres
    
    print_status "Esperando que la base de datos esté lista..."
    sleep 10
    
    # Verificar que la base de datos esté funcionando
    if docker-compose exec -T postgres pg_isready -U postgres; then
        print_success "Base de datos iniciada correctamente"
    else
        print_error "Error al iniciar la base de datos"
        exit 1
    fi
}

# Ejecutar migraciones
run_migrations() {
    print_status "Ejecutando migraciones de la base de datos..."
    
    cd backend
    
    # Esperar un poco más para asegurar que la base de datos esté completamente lista
    sleep 5
    
    # Ejecutar script de inicialización de la base de datos
    if [ -f database/init.sql ]; then
        docker-compose exec -T postgres psql -U postgres -d turismo_inclusivo -f /docker-entrypoint-initdb.d/init.sql || true
        print_success "Migraciones ejecutadas"
    else
        print_warning "Archivo de migraciones no encontrado"
    fi
    
    cd ..
}

# Crear directorios necesarios
create_directories() {
    print_status "Creando directorios necesarios..."
    
    mkdir -p backend/uploads
    mkdir -p logs
    mkdir -p nginx/ssl
    
    print_success "Directorios creados"
}

# Mostrar información final
show_info() {
    echo ""
    echo "🎉 ¡Configuración completada exitosamente!"
    echo ""
    echo "📋 Información del proyecto:"
    echo "   • Backend API: http://localhost:3001"
    echo "   • Frontend: http://localhost:5173"
    echo "   • Base de datos: localhost:5432"
    echo "   • Documentación API: http://localhost:3001/api-docs"
    echo ""
    echo "🚀 Comandos útiles:"
    echo "   • Iniciar todo: docker-compose up -d"
    echo "   • Ver logs: docker-compose logs -f"
    echo "   • Detener todo: docker-compose down"
    echo "   • Desarrollo backend: cd backend && npm run dev"
    echo "   • Desarrollo frontend: cd frontend && npm run dev"
    echo ""
    echo "⚠️  IMPORTANTE:"
    echo "   • Revisa y actualiza las variables de entorno en backend/.env y frontend/.env"
    echo "   • Configura tu token de Mapbox en frontend/.env"
    echo "   • Cambia las claves secretas en producción"
    echo ""
}

# Función principal
main() {
    print_status "Iniciando configuración del Portal de Turismo Inclusivo..."
    
    check_docker
    check_node
    setup_env
    setup_backend
    setup_frontend
    create_directories
    start_services
    run_migrations
    show_info
    
    print_success "¡Configuración completada!"
}

# Ejecutar función principal
main "$@" 