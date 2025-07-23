# 🔧 SOLUCIÓN AL ERROR DE BASE DE DATOS

## ❌ **Problema identificado:**
```
ERROR: no existe la columna «token_recuperacion»
SQL state: 42703
```

Este error ocurre porque:
1. **La tabla `usuarios` ya existía** en la base de datos
2. **La estructura anterior** no incluía las nuevas columnas que agregamos
3. **Los índices** intentan referenciar columnas que no existen

## ✅ **Solución implementada:**

He creado **DOS archivos** para resolver esto:

### 📄 **1. `init.sql` (ACTUALIZADO)**
- ✅ **Corregido** para manejar tablas existentes
- ✅ **Agregados comandos `ALTER TABLE`** para añadir columnas faltantes
- ✅ **Verificación `IF NOT EXISTS`** para evitar errores
- ✅ **Compatible** con bases de datos nuevas y existentes

### 📄 **2. `update_database.sql` (NUEVO)**
- ✅ **Script especializado** para actualizar bases de datos existentes
- ✅ **Migración segura** sin pérdida de datos
- ✅ **Verificaciones inteligentes** de estructura existente
- ✅ **Manejo de errores** para compatibilidad

---

## 🚀 **INSTRUCCIONES DE USO:**

### **OPCIÓN A: Si quieres actualizar la base de datos existente (RECOMENDADO)**

```bash
# Ejecutar el script de actualización
psql -U postgres -d turismo -f update_database.sql
```

**Ventajas:**
- ✅ **No pierdes datos existentes**
- ✅ **Actualización segura**
- ✅ **Agrega solo lo que falta**
- ✅ **Manejo inteligente de errores**

### **OPCIÓN B: Si quieres empezar desde cero**

```bash
# 1. Eliminar base de datos existente
dropdb -U postgres turismo

# 2. Crear nueva base de datos
createdb -U postgres turismo

# 3. Ejecutar script completo
psql -U postgres -d turismo -f init.sql
```

**Ventajas:**
- ✅ **Estructura completamente limpia**
- ✅ **Datos de ejemplo incluidos**
- ✅ **Sin problemas de compatibilidad**

---

## 🛠️ **¿QUÉ HACE EL SCRIPT DE ACTUALIZACIÓN?**

### **1. Actualiza tabla `usuarios`:**
```sql
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS token_recuperacion VARCHAR(255);
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS aceptar_terminos BOOLEAN DEFAULT false;
-- + 6 columnas más
```

### **2. Actualiza tabla `destinos`:**
```sql
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS descripcion TEXT;
ALTER TABLE destinos ADD COLUMN IF NOT EXISTS caracteristicas JSONB;
-- + 7 columnas más
```

### **3. Actualiza tabla `mensajes_contacto`:**
```sql
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS categoria VARCHAR(50);
ALTER TABLE mensajes_contacto ADD COLUMN IF NOT EXISTS respuesta TEXT;
-- + 3 columnas más
```

### **4. Crea tablas nuevas:**
- ✅ `rutas_turisticas`
- ✅ `busquedas_guardadas`
- ✅ `favoritos_usuarios`
- ✅ `configuraciones_sistema`
- ✅ `estadisticas_uso`

### **5. Agrega funcionalidades:**
- ✅ **Índices optimizados**
- ✅ **Triggers automáticos**
- ✅ **Vistas útiles**
- ✅ **Funciones de mantenimiento**

---

## 🎯 **RECOMENDACIÓN:**

**USA EL SCRIPT DE ACTUALIZACIÓN** (`update_database.sql`) porque:

1. ✅ **Preserva tus datos existentes**
2. ✅ **Actualización inteligente y segura**
3. ✅ **Manejo automático de errores**
4. ✅ **Verificaciones de compatibilidad**
5. ✅ **Mensajes informativos durante el proceso**

---

## 📋 **DESPUÉS DE LA ACTUALIZACIÓN:**

### **Credenciales de prueba:**
- **Usuario:** `admin`
- **Contraseña:** `admin123` (si ya existe) o crear nuevo admin

### **Verificar que funciona:**
```sql
-- Verificar nuevas columnas
\d usuarios
\d destinos
\d mensajes_contacto

-- Ver todas las tablas
\dt

-- Ver configuraciones
SELECT * FROM configuraciones_sistema;
```

---

## ✅ **RESULTADO FINAL:**

Después de ejecutar cualquiera de los scripts tendrás:

| **Funcionalidad** | **Estado** |
|------------------|------------|
| 🔐 **Login/Registro** | ✅ Completo |
| 📝 **Formulario contacto** | ✅ Completo |
| 🗺️ **Nueva ruta** | ✅ Completo |
| 🔍 **Búsqueda avanzada** | ✅ Completo |
| 💾 **Guardar registros** | ✅ Completo |
| ⭐ **Sistema favoritos** | ✅ Completo |
| ♿ **Accesibilidad** | ✅ Completo |
| 📊 **Estadísticas** | ✅ Completo |
| 🔒 **Seguridad** | ✅ Completo |

**¡Tu base de datos estará 100% funcional y optimizada!** 🎉
