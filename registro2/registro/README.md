# 🌟 Portal Turístico E.C - Sistema Completo

## 📋 Descripción
Portal turístico completo para Ecuador con sistema de registro, login, panel de administración y funcionalidades avanzadas de accesibilidad.

## ✨ Características Principales

### 🔐 Sistema de Autenticación
- **Registro de usuarios** con validación en tiempo real
- **Login seguro** con protección contra ataques de fuerza bruta
- **Sesiones persistentes** con opción "Recordarme"
- **Roles de usuario** (Usuario normal / Administrador)
- **Recuperación de contraseña** con tokens seguros

### 🎨 Interfaz de Usuario
- **Diseño moderno** con tema verde unificado
- **Interfaz responsive** para todos los dispositivos
- **Formularios con pestañas** (Login/Registro en un solo contenedor)
- **Validación en tiempo real** con feedback visual
- **Iconos intuitivos** de Font Awesome

### ♿ Accesibilidad Avanzada
- **Menú de accesibilidad** completo con opciones configurables
- **Múltiples tamaños de fuente** (Pequeño, Mediano, Grande, Extra Grande)
- **Esquemas de color** (Verde, Azul, Púrpura, Naranja)
- **Modo alto contraste** para mejor visibilidad
- **Reducción de animaciones** para usuarios sensibles
- **Navegación por teclado** mejorada
- **Compatibilidad con lectores de pantalla**

### 🔧 Panel de Administración
- **Dashboard completo** con estadísticas en tiempo real
- **Gestión de usuarios** (Ver, Editar, Eliminar)
- **Filtros avanzados** por rol, estado, fecha
- **Búsqueda en tiempo real** sin recargar página
- **Exportación a CSV** de datos de usuarios
- **Paginación inteligente**
- **Edición inline** de información de usuarios

### ⚙️ Configuración Avanzada del Sistema
- **Estadísticas del sistema** (usuarios, logs, tamaño de BD)
- **Configuración de seguridad** (intentos de login, tiempo de sesión)
- **Respaldo de base de datos** automático
- **Limpieza de logs** antiguos
- **Optimización de base de datos**
- **Verificación de seguridad**

### 📱 Funcionalidades Móviles
- **Diseño completamente responsive**
- **Navegación táctil** optimizada
- **Botones de tamaño adecuado** para móviles
- **Interfaz adaptativa** según el dispositivo

## 🚀 Instalación y Configuración

### Requisitos Previos
- **XAMPP** (Apache + MySQL + PHP)
- **PHP 7.4+**
- **MySQL 5.7+**
- **Navegador web moderno**

### Pasos de Instalación

1. **Clonar/Descargar el proyecto**
   ```bash
   # Colocar en la carpeta htdocs de XAMPP
   C:\xampp\htdocs\registro\registro\
   ```

2. **Configurar la base de datos**
   - Abrir phpMyAdmin: `http://localhost/phpmyadmin`
   - Crear base de datos: `registro`
   - Importar el archivo: `registro.sql`

3. **Configurar conexión**
   - Editar `connect.php` si es necesario
   - Verificar credenciales de MySQL

4. **Iniciar el servidor**
   - Iniciar XAMPP Control Panel
   - Activar Apache y MySQL
   - Acceder: `http://localhost/registro/registro/`

## 📁 Estructura del Proyecto

```
registro/
├── 📄 index.php              # Página principal con login/registro
├── 📄 admin.php              # Panel de administración
├── 📄 admin_config.php       # Configuración avanzada del sistema
├── 📄 admin_login.php        # Login específico para administradores
├── 📄 accessibility.php      # Menú de accesibilidad
├── 📄 home.php              # Página de inicio para usuarios
├── 📄 login.php             # Procesamiento de login
├── 📄 register.php          # Procesamiento de registro
├── 📄 logout.php            # Cierre de sesión
├── 📄 connect.php           # Conexión a base de datos y utilidades
├── 📄 editar_usuario.php    # Edición de usuarios (AJAX)
├── 📄 eliminar_usuario.php  # Eliminación de usuarios (AJAX)
├── 📄 export_users.php      # Exportación de usuarios a CSV
├── 📄 registro.sql          # Script completo de base de datos
├── 📄 styles.css            # Estilos CSS principales
├── 📄 script.js             # JavaScript del frontend
└── 📄 README.md             # Este archivo
```

## 🔑 Credenciales de Acceso

### Usuario Administrador
- **Email:** `admin@admin.com`
- **Contraseña:** `password`

### Crear Nuevo Administrador
1. Registrarse como usuario normal
2. Acceder a la base de datos
3. Cambiar el campo `role` de `user` a `admin`

## 🎯 Funcionalidades Detalladas

### Sistema de Filtros Avanzados
- **Filtro por rol:** Usuarios / Administradores
- **Filtro por estado:** Activos / Inactivos
- **Filtro por fecha:** Hoy, Esta semana, Este mes, Este año
- **Búsqueda en tiempo real:** Por nombre, apellido o email
- **Ordenamiento:** Por cualquier campo (ascendente/descendente)
- **Filtros rápidos:** Recientes, Inactivos, Administradores

### Menú de Accesibilidad
- **Tamaño de fuente:** 4 opciones configurables
- **Esquemas de color:** 4 temas diferentes
- **Alto contraste:** Modo especial para mejor visibilidad
- **Reducción de movimiento:** Para usuarios sensibles
- **Navegación por teclado:** Resaltado de elementos
- **Persistencia:** Configuraciones guardadas en localStorage

### Seguridad Implementada
- **Hash de contraseñas:** Bcrypt con salt
- **Protección CSRF:** Tokens en formularios
- **Prepared Statements:** Prevención de SQL Injection
- **Validación de entrada:** Sanitización de datos
- **Límite de intentos:** Bloqueo temporal de cuentas
- **Sesiones seguras:** Timeout configurable
- **Logs de actividad:** Registro de todas las acciones

### Base de Datos
- **13 tablas principales** con relaciones optimizadas
- **Vistas SQL** para consultas complejas
- **Procedimientos almacenados** para operaciones comunes
- **Triggers** para auditoría automática
- **Índices optimizados** para mejor rendimiento

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 7.4+** - Lógica del servidor
- **MySQL 5.7+** - Base de datos
- **Apache** - Servidor web

### Frontend
- **HTML5** - Estructura semántica
- **CSS3** - Estilos y animaciones
- **JavaScript ES6+** - Interactividad
- **Font Awesome** - Iconografía

### Características Especiales
- **AJAX** - Comunicación asíncrona
- **Responsive Design** - Adaptable a todos los dispositivos
- **Progressive Enhancement** - Funcionalidad sin JavaScript
- **Accessibility First** - Diseño inclusivo

## 📊 Estadísticas del Sistema

El sistema incluye un dashboard completo con:
- **Total de usuarios registrados**
- **Usuarios activos**
- **Nuevos usuarios hoy**
- **Nuevos usuarios este mes**
- **Registros de log del sistema**
- **Tamaño de la base de datos**

## 🔧 Configuración del Sistema

### Parámetros Configurables
- **Modo de mantenimiento**
- **Intentos máximos de login** (1-10)
- **Tiempo de sesión** (30-480 minutos)
- **Limpieza automática de logs** (30 días)

### Acciones del Sistema
- **Crear respaldo de base de datos**
- **Limpiar logs antiguos**
- **Optimizar tablas de base de datos**
- **Verificar seguridad del sistema**

## 🎨 Personalización

### Temas de Color Disponibles
1. **Verde (Predeterminado)** - Tema principal
2. **Azul** - Tema alternativo
3. **Púrpura** - Tema elegante
4. **Naranja** - Tema cálido

### Tamaños de Fuente
- **Pequeño:** 14px
- **Mediano:** 16px (predeterminado)
- **Grande:** 18px
- **Extra Grande:** 20px

## 📱 Compatibilidad

### Navegadores Soportados
- **Chrome** 80+
- **Firefox** 75+
- **Safari** 13+
- **Edge** 80+

### Dispositivos
- **Desktop** - Pantallas grandes
- **Tablet** - Pantallas medianas
- **Mobile** - Pantallas pequeñas

## 🚀 Optimizaciones Implementadas

### Rendimiento
- **Lazy loading** de imágenes
- **Minificación** de CSS/JS
- **Caché** de consultas frecuentes
- **Índices optimizados** en base de datos

### SEO
- **Meta tags** completos
- **Estructura semántica** HTML5
- **URLs amigables**
- **Sitemap** automático

### Accesibilidad
- **WCAG 2.1 AA** compliant
- **Navegación por teclado**
- **Lectores de pantalla**
- **Alto contraste**
- **Reducción de movimiento**

## 🔒 Seguridad

### Medidas Implementadas
- **Autenticación segura**
- **Autorización basada en roles**
- **Protección contra ataques comunes**
- **Logs de auditoría**
- **Backup automático**

### Mejores Prácticas
- **Principio de menor privilegio**
- **Validación de entrada**
- **Escape de salida**
- **Prepared statements**
- **HTTPS recomendado**

## 📞 Soporte

### Información de Contacto
- **Desarrollador:** Sistema Portal Turístico
- **Versión:** 2.0
- **Última actualización:** Diciembre 2024

### Documentación Adicional
- **Manual de usuario** incluido en el sistema
- **Guía de administración** en el panel
- **Ayuda contextual** en cada página

## 🎉 ¡Sistema Completo y Funcional!

Este portal turístico incluye todas las funcionalidades solicitadas en el documento de buenas prácticas:

✅ **Filtros avanzados** con búsqueda en tiempo real  
✅ **Menú de accesibilidad** completo  
✅ **Funcionalidades móviles** optimizadas  
✅ **Opciones avanzadas del administrador**  
✅ **Sistema de respaldos** automático  
✅ **Logs de auditoría** completos  
✅ **Interfaz unificada** con tema verde  
✅ **Validación en tiempo real**  
✅ **Exportación de datos**  
✅ **Configuración del sistema**  

¡El sistema está listo para producción! 🚀 