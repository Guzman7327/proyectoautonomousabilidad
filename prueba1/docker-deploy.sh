#!/bin/bash

# Script de despliegue para Portal de Turismo Inclusivo Ecuador
# Uso: ./docker-deploy.sh [start|stop|restart|build|logs|clean]

set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${BLUE}================================${NC}"
    echo -e "${BLUE}  Portal de Turismo Inclusivo${NC}"
    echo -e "${BLUE}  Script de Despliegue Docker${NC}"
    echo -e "${BLUE}================================${NC}"
}

# Función para verificar si Docker está instalado
check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker no está instalado. Por favor, instala Docker primero."
        exit 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose no está instalado. Por favor, instala Docker Compose primero."
        exit 1
    fi
}

# Función para crear directorios necesarios
create_directories() {
    print_message "Creando directorios necesarios..."
    mkdir -p app/static/uploads
    mkdir -p logs
    mkdir -p ssl
}

# Función para construir la imagen
build() {
    print_message "Construyendo imagen Docker..."
    docker-compose build --no-cache
    print_message "Imagen construida exitosamente."
}

# Función para iniciar los servicios
start() {
    print_message "Iniciando servicios..."
    docker-compose up -d
    print_message "Servicios iniciados exitosamente."
    print_message "Aplicación disponible en: http://localhost"
    print_message "pgAdmin disponible en: http://localhost:5050"
    print_message "Base de datos PostgreSQL en: localhost:5432"
}

# Función para detener los servicios
stop() {
    print_message "Deteniendo servicios..."
    docker-compose down
    print_message "Servicios detenidos exitosamente."
}

# Función para reiniciar los servicios
restart() {
    print_message "Reiniciando servicios..."
    docker-compose down
    docker-compose up -d
    print_message "Servicios reiniciados exitosamente."
}

# Función para mostrar logs
logs() {
    print_message "Mostrando logs..."
    docker-compose logs -f
}

# Función para limpiar todo
clean() {
    print_warning "Esta acción eliminará todos los contenedores, volúmenes e imágenes."
    read -p "¿Estás seguro? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_message "Limpiando todo..."
        docker-compose down -v --rmi all
        docker system prune -f
        print_message "Limpieza completada."
    else
        print_message "Limpieza cancelada."
    fi
}

# Función para mostrar estado
status() {
    print_message "Estado de los servicios:"
    docker-compose ps
}

# Función para mostrar ayuda
show_help() {
    echo "Uso: $0 [COMANDO]"
    echo ""
    echo "Comandos disponibles:"
    echo "  start     - Iniciar todos los servicios"
    echo "  stop      - Detener todos los servicios"
    echo "  restart   - Reiniciar todos los servicios"
    echo "  build     - Construir las imágenes Docker"
    echo "  logs      - Mostrar logs de los servicios"
    echo "  status    - Mostrar estado de los servicios"
    echo "  clean     - Limpiar todo (contenedores, volúmenes, imágenes)"
    echo "  help      - Mostrar esta ayuda"
    echo ""
    echo "Ejemplos:"
    echo "  $0 start    # Iniciar la aplicación"
    echo "  $0 logs     # Ver logs en tiempo real"
    echo "  $0 stop     # Detener la aplicación"
}

# Función principal
main() {
    print_header
    
    # Verificar Docker
    check_docker
    
    # Crear directorios
    create_directories
    
    # Procesar comando
    case "${1:-help}" in
        start)
            start
            ;;
        stop)
            stop
            ;;
        restart)
            restart
            ;;
        build)
            build
            ;;
        logs)
            logs
            ;;
        status)
            status
            ;;
        clean)
            clean
            ;;
        help|--help|-h)
            show_help
            ;;
        *)
            print_error "Comando desconocido: $1"
            show_help
            exit 1
            ;;
    esac
}

# Ejecutar función principal
main "$@" 