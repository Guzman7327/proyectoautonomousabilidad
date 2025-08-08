# 5. IMPLEMENTACIÓN PROTOTIPO

## SISTEMA ALOJAMIENTO
**Por ejemplo: Hostinger u otro**

**Recomendación principal:** Hostinger
- Plan Premium: $2.99/mes
- Incluye: Dominio gratuito, SSL, 100GB SSD
- Soporte PHP 8.0+, MySQL 5.7+

**Alternativas:**
- SiteGround StartUp: $14.99/mes
- DigitalOcean Droplet: $5/mes
- Local XAMPP: Gratis (desarrollo/pruebas)

---

## DOMINIO WEB (ACCESO PRINCIPAL AL MENU SISTEMA)
**https://portal-turistico-ecuador.com**

**URLs de acceso principales:**
- https://portal-turistico-ecuador.com/pagina_principal.php (Entrada principal)
- https://portal-turistico-ecuador.com/sistema_integrado.php (Sistema completo)  
- https://portal-turistico-ecuador.com/dashboard.php (Panel de control)

**URLs locales (desarrollo):**
- http://localhost/registro/registro/pagina_principal.php (Entrada principal)
- http://localhost/registro/registro/sistema_integrado.php (Sistema completo)
- http://localhost/registro/registro/dashboard.php (Panel de control)
- http://localhost/registro/registro/index.html (Página pública)

---

## USUARIOS REGISTRADOS EN EL SISTEMA

| Nombre usuario | Contraseña | Rol |
|----------------|------------|-----|
| admin@correo.com | [Encriptada] | admin |
| admin@ec.com | [Encriptada] | admin |
| MK@gmail.com | [Encriptada] | usuario |
| gt3@gmail.com | [Encriptada] | usuario |
| sd3@gmail.com | [Encriptada] | usuario |
| px2@gmail.com | [Encriptada] | usuario |
| RM@gmail.com | [Encriptada] | usuario |
| as1@gmail.com | [Encriptada] | usuario |
| ZM@gmail.com | [Encriptada] | usuario |

**Total:** 9 usuarios registrados (2 administradores + 7 usuarios estándar)
**Estado:** Todos los usuarios están activos y funcionales
**Seguridad:** Contraseñas almacenadas con hash bcrypt

---

*Sistema listo para producción*
*Base de datos: 28 tablas configuradas*
*Módulos: 4 sistemas principales implementados*
