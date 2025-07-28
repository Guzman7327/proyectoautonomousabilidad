# Guía de Instalación PostgreSQL Nativo - Portal de Turismo Inclusivo Ecuador

## 📋 Resumen de Configuración

- **Base de datos**: `turismo_inclusivo_ecuador`
- **Usuario**: `turismo_user` 
- **Contraseña**: `turismo_password_2024`
- **Puerto**: `5432`
- **Host**: `localhost`

## 🚀 Instalación Rápida

### Windows
```batch
# 1. Descargar el proyecto
git clone https://github.com/usuario/portal-turismo-inclusivo-ecuador.git
cd portal-turismo-inclusivo-ecuador

# 2. Ejecutar instalación automática
install-windows.bat
```

### Linux/macOS
```bash
# 1. Descargar el proyecto
git clone https://github.com/usuario/portal-turismo-inclusivo-ecuador.git
cd portal-turismo-inclusivo-ecuador

# 2. Ejecutar instalación automática
chmod +x install-linux.sh
./install-linux.sh
```

## 🔧 Instalación Manual Paso a Paso

### 1. Instalar PostgreSQL

#### Windows
1. Descargar desde [postgresql.org](https://www.postgresql.org/download/windows/)
2. Ejecutar el instalador
3. Configurar usuario `postgres` con contraseña
4. Asegurar que el servicio esté ejecutándose

#### Ubuntu/Debian
```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Configurar usuario postgres
sudo -u postgres psql
ALTER USER postgres PASSWORD 'nueva_contraseña';
\q
```

#### macOS
```bash
# Con Homebrew
brew install postgresql
brew services start postgresql

# Crear usuario postgres si no existe
createuser -s postgres
```

### 2. Configurar el Proyecto

```bash
# Clonar repositorio
git clone https://github.com/usuario/portal-turismo-inclusivo-ecuador.git
cd portal-turismo-inclusivo-ecuador

# Crear entorno virtual Python
python -m venv venv

# Activar entorno virtual
source venv/bin/activate  # Linux/macOS
venv\Scripts\activate.bat # Windows

# Instalar dependencias
pip install --upgrade pip
pip install -r requirements.txt
```

### 3. Inicializar Base de Datos

```bash
# Ejecutar script de inicialización (requiere contraseña de postgres)
psql -U postgres -f init-db.sql

# El script creará automáticamente:
# - Base de datos: turismo_inclusivo_ecuador
# - Usuario: turismo_user
# - Tablas y datos de ejemplo
```

### 4. Configurar Variables de Entorno

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Editar .env con sus configuraciones
# DATABASE_URL=postgresql://turismo_user:turismo_password_2024@localhost:5432/turismo_inclusivo_ecuador
```

### 5. Inicializar Aplicación Flask

```bash
# Configurar Flask
export FLASK_APP=app.py    # Linux/macOS
set FLASK_APP=app.py       # Windows

# Inicializar esquemas Flask (si necesario)
flask init-db

# Cargar datos de ejemplo
flask populate-sample-data

# Crear usuario administrador
flask create-admin
```

### 6. Ejecutar la Aplicación

```bash
# Ejecutar servidor de desarrollo
python app.py

# La aplicación estará disponible en:
# http://localhost:5000
```

## 🗃️ Estructura de Base de Datos

### Tablas Creadas

1. **users**: Usuarios con preferencias de accesibilidad
2. **destinations**: Destinos turísticos de Ecuador
3. **reviews**: Reseñas de usuarios
4. **accessibility_feedback**: Retroalimentación de accesibilidad

### Datos de Ejemplo Incluidos

- **11 destinos turísticos** reales de Ecuador
- **4 regiones**: Costa, Sierra, Amazonía, Galápagos
- **Características de accesibilidad** para cada destino
- **Coordenadas GPS** precisas
- **Información de contacto** real

## 🔍 Verificación de Instalación

### Verificar PostgreSQL
```bash
# Verificar que PostgreSQL esté ejecutándose
psql -U postgres -c "SELECT version();"

# Verificar que la base de datos existe
psql -U postgres -c "\l" | grep turismo_inclusivo_ecuador

# Verificar que el usuario fue creado
psql -U postgres -c "\du" | grep turismo_user
```

### Verificar Aplicación Flask
```bash
# Verificar conexión a base de datos
python -c "from app import create_app; app = create_app(); print('Conexión exitosa')"

# Verificar que las tablas existen
flask shell
>>> from app import db
>>> db.engine.table_names()
```

## 🐛 Solución de Problemas

### Error: "psql: FATAL: password authentication failed"
```bash
# Editar pg_hba.conf para permitir autenticación por contraseña
sudo nano /etc/postgresql/*/main/pg_hba.conf
# Cambiar 'peer' a 'md5' para local connections
sudo systemctl restart postgresql
```

### Error: "database does not exist"
```bash
# Crear base de datos manualmente
createdb -U postgres turismo_inclusivo_ecuador
psql -U postgres -d turismo_inclusivo_ecuador -f init-db.sql
```

### Error: "No module named 'psycopg2'"
```bash
# Instalar dependencias de PostgreSQL
sudo apt-get install libpq-dev python3-dev  # Ubuntu/Debian
brew install postgresql                       # macOS
pip install psycopg2-binary
```

### Puerto 5000 en uso
```bash
# Cambiar puerto en app.py o usar variable de entorno
export PORT=8000
python app.py
```

## 🔐 Configuración de Seguridad (Producción)

### Cambiar Contraseñas por Defecto
```sql
-- Conectar como postgres
psql -U postgres

-- Cambiar contraseña del usuario de la aplicación
ALTER USER turismo_user PASSWORD 'nueva_contraseña_segura_2024';
```

### Configurar .env para Producción
```bash
# .env para producción
SECRET_KEY=clave_secreta_muy_larga_y_segura_cambiar_siempre
JWT_SECRET_KEY=jwt_secret_key_muy_segura_cambiar_siempre
DATABASE_URL=postgresql://turismo_user:nueva_contraseña_segura@localhost:5432/turismo_inclusivo_ecuador
FLASK_ENV=production
```

### Configurar Firewall PostgreSQL
```bash
# Solo permitir conexiones locales
sudo ufw allow from 127.0.0.1 to any port 5432
```

## 📊 Comandos Útiles

### Respaldo de Base de Datos
```bash
# Crear respaldo
pg_dump -U turismo_user -h localhost turismo_inclusivo_ecuador > backup.sql

# Restaurar respaldo
psql -U turismo_user -h localhost turismo_inclusivo_ecuador < backup.sql
```

### Gestión de Usuarios Flask
```bash
# Crear usuario administrador
flask create-admin

# Listar usuarios
flask shell
>>> from app.models.user import User
>>> User.query.all()
```

### Logs y Debugging
```bash
# Ver logs de PostgreSQL
sudo tail -f /var/log/postgresql/postgresql-*-main.log

# Activar modo debug en Flask
export FLASK_ENV=development
python app.py
```

## 🎯 Próximos Pasos

1. **Acceder a la aplicación**: http://localhost:5000
2. **Crear cuenta de administrador**: `flask create-admin`
3. **Explorar funcionalidades de accesibilidad**
4. **Configurar email** (opcional)
5. **Personalizar destinos** según sus necesidades

---

**¡La aplicación está lista para usar!** 🇪🇨 ♿ 🌟
