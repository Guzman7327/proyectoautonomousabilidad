-- =====================================================
-- SISTEMA TURÍSTICO - BASE DE DATOS COMPLETA
-- Nombre de la base de datos: registro
-- Versión: 1.0
-- Fecha: 2024
-- =====================================================

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `registro` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;   -- Cambiar a utf8mb4_unicode_ci para soporte de emojis y caracteres especiales    

USE `registro`;

-- =====================================================
-- TABLA DE USUARIOS
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1,
  `email_verified` tinyint(1) DEFAULT 0,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'Ecuador',
  `birth_date` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_active` (`is_active`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE ADMINISTRADORES
-- =====================================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `admin_level` enum('super_admin','admin','moderator') DEFAULT 'admin',
  `permissions` json DEFAULT NULL,
  `last_admin_action` datetime DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE SESIONES DE USUARIO
-- =====================================================
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_token` (`session_token`),
  KEY `idx_expires` (`expires_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE INTENTOS DE LOGIN
-- =====================================================
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `success` tinyint(1) DEFAULT 0,
  `attempted_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_ip` (`ip_address`),
  KEY `idx_attempted` (`attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE RESETEO DE CONTRASEÑAS
-- =====================================================
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `used` tinyint(1) DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_token` (`token`),
  KEY `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE DESTINOS TURÍSTICOS
-- =====================================================
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(100) NOT NULL,
  `province` varchar(50) NOT NULL,
  `category` enum('playa','montaña','ciudad','selva','isla','cultural') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `price_range` enum('económico','medio','alto','lujo') DEFAULT 'medio',
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_province` (`province`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE ALOJAMIENTOS
-- =====================================================
CREATE TABLE IF NOT EXISTS `accommodations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `destination_id` int(11) NOT NULL,
  `type` enum('hotel','hostal','cabaña','resort','apartamento','casa') NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `amenities` json DEFAULT NULL,
  `images` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_destination` (`destination_id`),
  KEY `idx_type` (`type`),
  KEY `idx_active` (`is_active`),
  FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE RESERVAS
-- =====================================================
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_accommodation` (`accommodation_id`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`check_in`, `check_out`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE RESEÑAS
-- =====================================================
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (rating >= 1 AND rating <= 5),
  `title` varchar(200) DEFAULT NULL,
  `comment` text NOT NULL,
  `images` json DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_destination` (`destination_id`),
  KEY `idx_accommodation` (`accommodation_id`),
  KEY `idx_rating` (`rating`),
  KEY `idx_approved` (`is_approved`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE PREFERENCIAS DE ACCESIBILIDAD
-- =====================================================
CREATE TABLE IF NOT EXISTS `accessibility_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `font_size` enum('small','medium','large','x-large') DEFAULT 'medium',
  `contrast` enum('normal','high') DEFAULT 'normal',
  `animations` tinyint(1) DEFAULT 1,
  `screen_reader` tinyint(1) DEFAULT 0,
  `reduced_motion` tinyint(1) DEFAULT 0,
  `high_contrast` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE ACTIVIDADES TURÍSTICAS
-- =====================================================
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `destination_id` int(11) NOT NULL,
  `category` enum('aventura','cultural','gastronomía','naturaleza','deportes','relax') NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `difficulty` enum('fácil','moderado','difícil','experto') DEFAULT 'moderado',
  `min_age` int(11) DEFAULT 0,
  `max_participants` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_destination` (`destination_id`),
  KEY `idx_category` (`category`),
  KEY `idx_active` (`is_active`),
  FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE NOTIFICACIONES
-- =====================================================
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `action_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_read` (`is_read`),
  KEY `idx_created` (`created_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA DE LOGS DEL SISTEMA
-- =====================================================
CREATE TABLE IF NOT EXISTS `system_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `level` enum('info','warning','error','critical') DEFAULT 'info',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_level` (`level`),
  KEY `idx_created` (`created_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTAR DATOS DE EJEMPLO
-- =====================================================

-- Usuario administrador por defecto
INSERT INTO `users` (`firstName`, `lastName`, `email`, `password`, `role`, `is_active`, `email_verified`) VALUES
('Administrador', 'Sistema', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1);

-- Crear registro de administrador
INSERT INTO `admins` (`user_id`, `admin_level`, `permissions`) VALUES
(1, 'super_admin', '["all"]');

-- Destinos turísticos de Ecuador
INSERT INTO `destinations` (`name`, `description`, `location`, `province`, `category`, `rating`, `price_range`, `is_featured`) VALUES
('Galápagos', 'Archipiélago único con especies endémicas y paisajes volcánicos impresionantes', 'Islas Galápagos', 'Galápagos', 'isla', 4.8, 'alto', 1),
('Quito', 'Capital histórica con centro colonial declarado Patrimonio de la Humanidad', 'Quito', 'Pichincha', 'ciudad', 4.5, 'medio', 1),
('Baños', 'Ciudad de aventura y aguas termales en las faldas del Tungurahua', 'Baños de Agua Santa', 'Tungurahua', 'montaña', 4.6, 'medio', 1),
('Manta', 'Hermosa ciudad costera con playas y gastronomía marina', 'Manta', 'Manabí', 'playa', 4.3, 'medio', 0),
('Cuenca', 'Ciudad colonial con arquitectura histórica y artesanías', 'Cuenca', 'Azuay', 'cultural', 4.4, 'medio', 0),
('Salinas', 'Destino de playa con hoteles de lujo y actividades acuáticas', 'Salinas', 'Santa Elena', 'playa', 4.2, 'lujo', 0);

-- Alojamientos de ejemplo
INSERT INTO `accommodations` (`name`, `description`, `destination_id`, `type`, `address`, `phone`, `email`, `price_per_night`, `rating`, `amenities`) VALUES
('Hotel Galápagos Paradise', 'Hotel de lujo con vista al mar en las Islas Galápagos', 1, 'hotel', 'Av. Charles Darwin, Puerto Ayora', '+593 5 252 1234', 'info@galapagosparadise.com', 150.00, 4.7, '["wifi", "piscina", "restaurante", "spa"]'),
('Hostal Colonial Quito', 'Hostal en el centro histórico de Quito', 2, 'hostal', 'Calle García Moreno 123, Quito', '+593 2 228 5678', 'info@colonialquito.com', 45.00, 4.2, '["wifi", "desayuno", "terraza"]'),
('Cabañas Baños Adventure', 'Cabañas de madera con vista a las montañas', 3, 'cabaña', 'Vía a Puyo Km 5, Baños', '+593 3 274 9012', 'info@banosadventure.com', 65.00, 4.4, '["wifi", "chimenea", "estacionamiento"]'),
('Resort Manta Beach', 'Resort frente al mar en Manta', 4, 'resort', 'Malecón Escénico 200, Manta', '+593 5 262 3456', 'info@mantabeach.com', 120.00, 4.5, '["wifi", "piscina", "restaurante", "gimnasio", "spa"]');

-- Actividades turísticas
INSERT INTO `activities` (`name`, `description`, `destination_id`, `category`, `duration`, `price`, `difficulty`) VALUES
('Buceo en Galápagos', 'Explora la vida marina única de las Galápagos', 1, 'aventura', '4 horas', 120.00, 'moderado'),
('City Tour Quito', 'Recorrido por el centro histórico de Quito', 2, 'cultural', '3 horas', 25.00, 'fácil'),
('Rafting en Baños', 'Descenso por ríos de montaña', 3, 'aventura', '2 horas', 45.00, 'moderado'),
('Cooking Class Manta', 'Aprende a cocinar ceviche y otros platos marinos', 4, 'gastronomía', '2 horas', 35.00, 'fácil');

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista de estadísticas de usuarios
CREATE OR REPLACE VIEW `user_stats` AS
SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as users_today,
    COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) as users_this_month,
    COUNT(CASE WHEN role = 'admin' THEN 1 END) as total_admins,
    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_users
FROM users;

-- Vista de destinos con información completa
CREATE OR REPLACE VIEW `destination_details` AS
SELECT 
    d.*,
    COUNT(DISTINCT a.id) as accommodation_count,
    COUNT(DISTINCT act.id) as activity_count,
    AVG(r.rating) as average_rating,
    COUNT(r.id) as review_count
FROM destinations d
LEFT JOIN accommodations a ON d.id = a.destination_id AND a.is_active = 1
LEFT JOIN activities act ON d.id = act.destination_id AND act.is_active = 1
LEFT JOIN reviews r ON d.id = r.destination_id AND r.is_approved = 1
GROUP BY d.id;

-- Vista de reservas con información completa
CREATE OR REPLACE VIEW `booking_details` AS
SELECT 
    b.*,
    u.firstName,
    u.lastName,
    u.email as user_email,
    acc.name as accommodation_name,
    acc.type as accommodation_type,
    d.name as destination_name,
    DATEDIFF(b.check_out, b.check_in) as nights
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN accommodations acc ON b.accommodation_id = acc.id
JOIN destinations d ON acc.destination_id = d.id;

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS
-- =====================================================

-- Procedimiento para limpiar sesiones expiradas
DELIMITER //
CREATE PROCEDURE `clean_expired_sessions`()
BEGIN
    DELETE FROM user_sessions 
    WHERE expires_at < NOW() OR is_active = 0;
END //
DELIMITER ;

-- Procedimiento para limpiar intentos de login antiguos
DELIMITER //
CREATE PROCEDURE `clean_old_login_attempts`()
BEGIN
    DELETE FROM login_attempts 
    WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
END //
DELIMITER ;

-- Procedimiento para limpiar tokens de reset expirados
DELIMITER //
CREATE PROCEDURE `clean_expired_reset_tokens`()
BEGIN
    DELETE FROM password_resets 
    WHERE expires_at < NOW() OR used = 1;
END //
DELIMITER ;

-- Procedimiento para obtener estadísticas de usuario
DELIMITER //
CREATE PROCEDURE `get_user_statistics`(IN user_id INT)
BEGIN
    SELECT 
        u.firstName,
        u.lastName,
        u.email,
        u.created_at,
        COUNT(b.id) as total_bookings,
        COUNT(r.id) as total_reviews,
        AVG(r.rating) as average_rating
    FROM users u
    LEFT JOIN bookings b ON u.id = b.user_id
    LEFT JOIN reviews r ON u.id = r.user_id
    WHERE u.id = user_id
    GROUP BY u.id;
END //
DELIMITER ;

-- =====================================================
-- TRIGGERS
-- =====================================================

-- Trigger para actualizar rating de destino cuando se agrega una reseña
DELIMITER //
CREATE TRIGGER `update_destination_rating` 
AFTER INSERT ON `reviews`
FOR EACH ROW
BEGIN
    IF NEW.destination_id IS NOT NULL THEN
        UPDATE destinations 
        SET rating = (
            SELECT AVG(rating) 
            FROM reviews 
            WHERE destination_id = NEW.destination_id 
            AND is_approved = 1
        )
        WHERE id = NEW.destination_id;
    END IF;
END //
DELIMITER ;

-- Trigger para actualizar rating de alojamiento cuando se agrega una reseña
DELIMITER //
CREATE TRIGGER `update_accommodation_rating` 
AFTER INSERT ON `reviews`
FOR EACH ROW
BEGIN
    IF NEW.accommodation_id IS NOT NULL THEN
        UPDATE accommodations 
        SET rating = (
            SELECT AVG(rating) 
            FROM reviews 
            WHERE accommodation_id = NEW.accommodation_id 
            AND is_approved = 1
        )
        WHERE id = NEW.accommodation_id;
    END IF;
END //
DELIMITER ;

-- Trigger para registrar actividad del usuario
DELIMITER //
CREATE TRIGGER `log_user_activity` 
AFTER INSERT ON `users`
FOR EACH ROW
BEGIN
    INSERT INTO system_logs (user_id, action, description, level)
    VALUES (NEW.id, 'user_registered', CONCAT('Nuevo usuario registrado: ', NEW.email), 'info');
END //
DELIMITER ;

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices para búsquedas rápidas
CREATE INDEX `idx_users_search` ON `users` (`firstName`, `lastName`, `email`);
CREATE INDEX `idx_destinations_search` ON `destinations` (`name`, `location`, `province`);
CREATE INDEX `idx_accommodations_search` ON `accommodations` (`name`, `type`, `price_per_night`);
CREATE INDEX `idx_bookings_dates` ON `bookings` (`check_in`, `check_out`, `status`);
CREATE INDEX `idx_reviews_rating` ON `reviews` (`rating`, `is_approved`);

-- =====================================================
-- CONFIGURACIÓN DE PERMISOS
-- =====================================================

-- Crear usuario de solo lectura para reportes (opcional)
-- CREATE USER 'report_user'@'localhost' IDENTIFIED BY 'report_password';
-- GRANT SELECT ON registro.* TO 'report_user'@'localhost';

-- =====================================================
-- MENSAJE DE ÉXITO
-- =====================================================

SELECT 'Base de datos registro creada exitosamente!' as mensaje;
SELECT 'Usuario administrador: admin@admin.com' as admin_info;
SELECT 'Contraseña: password' as password_info;
SELECT 'Recuerda cambiar la contraseña del administrador después del primer login' as recomendacion;
