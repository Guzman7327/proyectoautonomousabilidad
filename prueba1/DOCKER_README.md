# Portal de Turismo Inclusivo Ecuador - Despliegue con Docker

Este documento explica cómo desplegar el Portal de Turismo Inclusivo Ecuador usando Docker.

## 🐳 Requisitos Previos

- Docker Desktop instalado
- Docker Compose instalado
- Al menos 4GB de RAM disponible
- 10GB de espacio en disco

## 🚀 Despliegue Rápido

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd prueba1
```

### 2. Ejecutar el script de despliegue
```bash
# Dar permisos de ejecución al script
chmod +x docker-deploy.sh

# Iniciar la aplicación
./docker-deploy.sh start
```

### 3. Acceder a la aplicación
- **Aplicación principal**: http://localhost
- **pgAdmin**: http://localhost:5050
- **Base de datos**: localhost:5432

## 📋 Comandos Disponibles

```bash
# Iniciar servicios
./docker-deploy.sh start

# Detener servicios
./docker-deploy.sh stop

# Reiniciar servicios
./docker-deploy.sh restart

# Construir imágenes
./docker-deploy.sh build

# Ver logs
./docker-deploy.sh logs

# Ver estado de servicios
./docker-deploy.sh status

# Limpiar todo
./docker-deploy.sh clean

# Mostrar ayuda
./docker-deploy.sh help
```

## 🏗️ Arquitectura de Servicios

### Servicios Incluidos

1. **web** - Aplicación Flask (Puerto 5000)
2. **db** - Base de datos PostgreSQL (Puerto 5432)
3. **pgadmin** - Administrador de base de datos (Puerto 5050)
4. **redis** - Cache y sesiones (Puerto 6379)
5. **nginx** - Proxy reverso (Puerto 80/443)

### Variables de Entorno

```yaml
# Base de datos
DATABASE_URL=postgresql://turismo_user:turismo_password_2024@db:5432/turismo_inclusivo_ecuador

# Redis
REDIS_URL=redis://redis:6379/0

# Seguridad
SECRET_KEY=your-secret-key-change-in-production
JWT_SECRET_KEY=your-jwt-secret-change-in-production
```

## 🔧 Configuración Manual

### Usando Docker Compose directamente

```bash
# Construir e iniciar
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Detener servicios
docker-compose down

# Reconstruir sin cache
docker-compose build --no-cache
```

### Configuración de Base de Datos

```bash
# Acceder a la base de datos
docker-compose exec db psql -U turismo_user -d turismo_inclusivo_ecuador

# Ejecutar migraciones
docker-compose exec web flask db upgrade

# Crear usuario administrador
docker-compose exec web flask create-admin
```

## 🛠️ Solución de Problemas

### Problema: Puerto ya en uso
```bash
# Verificar puertos en uso
netstat -tulpn | grep :5000

# Cambiar puerto en docker-compose.yml
ports:
  - "5001:5000"  # Cambiar 5000 por 5001
```

### Problema: Error de permisos
```bash
# Dar permisos a directorios
sudo chown -R $USER:$USER app/static/uploads
sudo chown -R $USER:$USER logs
```

### Problema: Base de datos no conecta
```bash
# Verificar estado de servicios
docker-compose ps

# Reiniciar base de datos
docker-compose restart db

# Ver logs de base de datos
docker-compose logs db
```

### Problema: Imagen no se construye
```bash
# Limpiar cache de Docker
docker system prune -a

# Reconstruir sin cache
docker-compose build --no-cache
```

## 📊 Monitoreo

### Ver recursos utilizados
```bash
# Estadísticas de contenedores
docker stats

# Uso de volúmenes
docker volume ls
docker volume inspect turismo_inclusivo_postgres_data
```

### Logs específicos
```bash
# Logs de la aplicación
docker-compose logs web

# Logs de base de datos
docker-compose logs db

# Logs de nginx
docker-compose logs nginx
```

## 🔒 Seguridad

### Cambiar contraseñas por defecto
1. Editar `docker-compose.yml`
2. Cambiar `POSTGRES_PASSWORD`
3. Cambiar `PGADMIN_DEFAULT_PASSWORD`
4. Cambiar `SECRET_KEY` y `JWT_SECRET_KEY`
5. Reconstruir: `./docker-deploy.sh restart`

### Configurar SSL
1. Colocar certificados en directorio `ssl/`
2. Configurar nginx.conf para HTTPS
3. Reiniciar servicios

## 🧹 Mantenimiento

### Backup de base de datos
```bash
# Crear backup
docker-compose exec db pg_dump -U turismo_user turismo_inclusivo_ecuador > backup.sql

# Restaurar backup
docker-compose exec -T db psql -U turismo_user turismo_inclusivo_ecuador < backup.sql
```

### Actualizar aplicación
```bash
# Parar servicios
./docker-deploy.sh stop

# Obtener cambios
git pull

# Reconstruir e iniciar
./docker-deploy.sh start
```

### Limpiar recursos no utilizados
```bash
# Limpiar contenedores parados
docker container prune

# Limpiar imágenes no utilizadas
docker image prune

# Limpiar volúmenes no utilizados
docker volume prune

# Limpiar todo
docker system prune -a
```

## 📝 Notas Importantes

- Los datos de la base de datos se almacenan en volúmenes Docker
- Los archivos subidos se guardan en `app/static/uploads/`
- Los logs se guardan en el directorio `logs/`
- pgAdmin está disponible en http://localhost:5050
- Credenciales por defecto de pgAdmin: admin@turismoinclusivo.ec / admin123

## 🆘 Soporte

Si encuentras problemas:

1. Revisar logs: `./docker-deploy.sh logs`
2. Verificar estado: `./docker-deploy.sh status`
3. Reiniciar servicios: `./docker-deploy.sh restart`
4. Limpiar y reconstruir: `./docker-deploy.sh clean && ./docker-deploy.sh start` 