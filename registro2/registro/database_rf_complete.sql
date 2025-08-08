-- =====================================================
-- SISTEMA TURÍSTICO - BASE DE DATOS COMPLETA CON FORMULARIOS RF
-- Nombre de la base de datos: registro
-- Versión: 2.0 - Incluye todos los formularios RF
-- Fecha: 2024
-- =====================================================

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `registro` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `registro`;

-- =====================================================
-- TABLAS EXISTENTES (mantener estructura actual)
-- =====================================================

-- Tabla de usuarios (existente)
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
-- RF7: SISTEMA DE RESERVAS Y PAGOS
-- =====================================================

-- Tabla de reservas
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `adults` int(2) DEFAULT 1,
  `children` int(2) DEFAULT 0,
  `rooms` int(2) DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','bank_transfer','cash') DEFAULT 'credit_card',
  `payment_status` enum('pending','paid','cancelled','refunded') DEFAULT 'pending',
  `reservation_status` enum('confirmed','pending','cancelled','completed') DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_dates` (`check_in_date`, `check_out_date`),
  KEY `idx_status` (`reservation_status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de pagos
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','bank_transfer','cash') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `card_last_four` varchar(4) DEFAULT NULL,
  `card_type` varchar(20) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reservation_id` (`reservation_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`payment_status`),
  FOREIGN KEY (`reservation_id`) REFERENCES `reservations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- RF8: SISTEMA DE ANÁLISIS Y REPORTES
-- =====================================================

-- Tabla de reportes
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `report_type` enum('reservations','revenue','user_activity','destination_popularity','payment_analysis') NOT NULL,
  `report_title` varchar(255) NOT NULL,
  `report_data` json NOT NULL,
  `date_range_start` date DEFAULT NULL,
  `date_range_end` date DEFAULT NULL,
  `filters_applied` json DEFAULT NULL,
  `generated_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `is_scheduled` tinyint(1) DEFAULT 0,
  `schedule_frequency` enum('daily','weekly','monthly','quarterly') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_type` (`report_type`),
  KEY `idx_generated` (`generated_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de métricas
CREATE TABLE IF NOT EXISTS `metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metric_name` varchar(100) NOT NULL,
  `metric_value` decimal(15,2) NOT NULL,
  `metric_unit` varchar(20) DEFAULT NULL,
  `date_recorded` date NOT NULL,
  `category` enum('reservations','revenue','users','destinations','payments') NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`metric_name`),
  KEY `idx_date` (`date_recorded`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de análisis de tendencias
CREATE TABLE IF NOT EXISTS `trend_analysis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trend_type` enum('reservation_trend','revenue_trend','user_growth','destination_popularity') NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `growth_rate` decimal(5,2) DEFAULT NULL,
  `trend_data` json NOT NULL,
  `insights` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`trend_type`),
  KEY `idx_period` (`period_start`, `period_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- RF9: SISTEMA DE NOTIFICACIONES Y COMUNICACIONES
-- =====================================================

-- Tabla de notificaciones
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `notification_type` enum('reservation_confirmation','payment_received','reminder','promotion','system_alert','custom') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `sent_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `delivery_method` enum('email','sms','push','in_app') DEFAULT 'in_app',
  `delivery_status` enum('pending','sent','delivered','failed') DEFAULT 'pending',
  `metadata` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_type` (`notification_type`),
  KEY `idx_read` (`is_read`),
  KEY `idx_sent` (`sent_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de plantillas de notificaciones
CREATE TABLE IF NOT EXISTS `notification_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(100) NOT NULL,
  `template_type` enum('email','sms','push','in_app') NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `variables` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`template_type`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de comunicaciones masivas
CREATE TABLE IF NOT EXISTS `mass_communications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(255) NOT NULL,
  `campaign_type` enum('promotion','newsletter','announcement','reminder') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `target_audience` json DEFAULT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `total_recipients` int(11) DEFAULT 0,
  `delivered_count` int(11) DEFAULT 0,
  `opened_count` int(11) DEFAULT 0,
  `clicked_count` int(11) DEFAULT 0,
  `status` enum('draft','scheduled','sending','sent','cancelled') DEFAULT 'draft',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`campaign_type`),
  KEY `idx_status` (`status`),
  KEY `idx_scheduled` (`scheduled_at`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- RF10: SISTEMA DE GESTIÓN DE CONTENIDO Y SEO
-- =====================================================

-- Tabla de contenido
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` enum('destination','accommodation','activity','news','blog','page') NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `content` longtext NOT NULL,
  `excerpt` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `seo_score` int(3) DEFAULT 0,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `author_id` int(11) NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`content_type`),
  KEY `idx_status` (`status`),
  KEY `idx_author` (`author_id`),
  KEY `idx_slug` (`slug`),
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de SEO
CREATE TABLE IF NOT EXISTS `seo_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) DEFAULT NULL,
  `page_url` varchar(500) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `twitter_card` varchar(50) DEFAULT NULL,
  `structured_data` json DEFAULT NULL,
  `page_speed_score` int(3) DEFAULT NULL,
  `mobile_friendly_score` int(3) DEFAULT NULL,
  `seo_score` int(3) DEFAULT NULL,
  `last_analyzed` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_url` (`page_url`),
  KEY `idx_seo_score` (`seo_score`),
  FOREIGN KEY (`content_id`) REFERENCES `content`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de palabras clave
CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `search_volume` int(11) DEFAULT 0,
  `difficulty_score` int(3) DEFAULT 0,
  `current_ranking` int(11) DEFAULT NULL,
  `target_ranking` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_tracked` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`),
  KEY `idx_volume` (`search_volume`),
  KEY `idx_difficulty` (`difficulty_score`),
  KEY `idx_tracked` (`is_tracked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de análisis de rendimiento
CREATE TABLE IF NOT EXISTS `performance_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(500) NOT NULL,
  `page_views` int(11) DEFAULT 0,
  `unique_visitors` int(11) DEFAULT 0,
  `bounce_rate` decimal(5,2) DEFAULT NULL,
  `avg_session_duration` int(11) DEFAULT NULL,
  `conversion_rate` decimal(5,2) DEFAULT NULL,
  `organic_traffic` int(11) DEFAULT 0,
  `paid_traffic` int(11) DEFAULT 0,
  `social_traffic` int(11) DEFAULT 0,
  `date_recorded` date NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_url` (`page_url`),
  KEY `idx_date` (`date_recorded`),
  KEY `idx_views` (`page_views`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS ADICIONALES DEL SISTEMA
-- =====================================================

-- Tabla de destinos (existente mejorada)
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `province` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `best_time_to_visit` varchar(255) DEFAULT NULL,
  `average_temperature` varchar(50) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_province` (`province`),
  KEY `idx_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de alojamientos (existente mejorada)
CREATE TABLE IF NOT EXISTS `accommodations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('hotel','hostel','cabin','resort','apartment','guesthouse') NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `description` text DEFAULT NULL,
  `amenities` json DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'USD',
  `rating` decimal(3,2) DEFAULT NULL,
  `total_reviews` int(11) DEFAULT 0,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_destination` (`destination_id`),
  KEY `idx_price` (`price_per_night`),
  KEY `idx_rating` (`rating`),
  FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de actividades
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `category` enum('adventure','cultural','nature','relaxation','food','shopping') NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `difficulty_level` enum('easy','moderate','difficult','expert') DEFAULT 'moderate',
  `price` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'USD',
  `image_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_destination` (`destination_id`),
  KEY `idx_difficulty` (`difficulty_level`),
  FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de reseñas
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content_type` enum('destination','accommodation','activity') NOT NULL,
  `content_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (rating >= 1 AND rating <= 5),
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `helpful_votes` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_content` (`content_type`, `content_id`),
  KEY `idx_rating` (`rating`),
  KEY `idx_approved` (`is_approved`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS DE PRUEBA
-- =====================================================

-- Insertar destinos de ejemplo
INSERT INTO `destinations` (`name`, `location`, `province`, `description`, `highlights`, `best_time_to_visit`, `average_temperature`, `image_url`, `is_featured`) VALUES
('Galápagos', 'Islas Galápagos', 'Galápagos', 'Archipiélago único con especies endémicas', 'Tortugas gigantes, iguanas marinas, buceo', 'Junio-Diciembre', '22-28°C', '/img/galapagos.jpg', 1),
('Quito', 'Quito', 'Pichincha', 'Capital histórica del Ecuador', 'Centro histórico, Mitad del Mundo, teleférico', 'Todo el año', '15-25°C', '/img/quito.jpg', 1),
('Cuenca', 'Cuenca', 'Azuay', 'Ciudad patrimonio de la humanidad', 'Centro histórico, artesanías, gastronomía', 'Todo el año', '12-22°C', '/img/cuenca.jpg', 1),
('Baños', 'Baños de Agua Santa', 'Tungurahua', 'Puerta de entrada a la Amazonía', 'Cascadas, aguas termales, deportes extremos', 'Todo el año', '18-25°C', '/img/banos.jpg', 1),
('Manta', 'Manta', 'Manabí', 'Ciudad costera del Pacífico', 'Playas, pesca, gastronomía marina', 'Diciembre-Abril', '24-30°C', '/img/manta.jpg', 0),
('Salinas', 'Salinas', 'Santa Elena', 'Destino playero por excelencia', 'Playas, deportes acuáticos, vida nocturna', 'Diciembre-Abril', '24-30°C', '/img/salinas.jpg', 0);

-- Insertar alojamientos de ejemplo
INSERT INTO `accommodations` (`name`, `type`, `destination_id`, `address`, `description`, `price_per_night`, `rating`, `total_reviews`) VALUES
('Hotel Plaza Grande', 'hotel', 2, 'Plaza de la Independencia, Quito', 'Hotel de lujo en el centro histórico', 150.00, 4.5, 120),
('Hostal Quito Colonial', 'hostel', 2, 'Calle Ronda, Quito', 'Hostal económico en el centro', 25.00, 4.2, 85),
('Resort Galápagos Paradise', 'resort', 1, 'Puerto Ayora, Galápagos', 'Resort exclusivo en las islas', 300.00, 4.8, 95),
('Cabaña Baños Adventure', 'cabin', 4, 'Baños de Agua Santa', 'Cabaña con vista a las cascadas', 80.00, 4.3, 65);

-- Insertar actividades de ejemplo
INSERT INTO `activities` (`name`, `destination_id`, `category`, `description`, `duration`, `difficulty_level`, `price`) VALUES
('Tour Centro Histórico Quito', 2, 'cultural', 'Recorrido por los principales monumentos', '4 horas', 'easy', 25.00),
('Buceo en Galápagos', 1, 'adventure', 'Inmersión con tortugas y leones marinos', '6 horas', 'moderate', 150.00),
('Rafting Río Pastaza', 4, 'adventure', 'Deportes extremos en aguas bravas', '3 horas', 'difficult', 45.00),
('Tour Gastronómico Cuenca', 3, 'food', 'Degustación de platos típicos', '5 horas', 'easy', 35.00);

-- Insertar plantillas de notificaciones
INSERT INTO `notification_templates` (`template_name`, `template_type`, `subject`, `content`, `variables`) VALUES
('reservation_confirmation', 'email', 'Confirmación de Reserva - Portal Turístico Ecuador', 'Hola {{user_name}},\n\nTu reserva ha sido confirmada exitosamente.\n\nDetalles de la reserva:\n- Destino: {{destination}}\n- Fechas: {{check_in}} - {{check_out}}\n- Total: ${{amount}}\n\nGracias por elegirnos.\n\nSaludos,\nPortal Turístico Ecuador', '["user_name", "destination", "check_in", "check_out", "amount"]'),
('payment_received', 'email', 'Pago Recibido - Portal Turístico Ecuador', 'Hola {{user_name}},\n\nHemos recibido tu pago de ${{amount}}.\n\nTu reserva está confirmada y lista.\n\nGracias,\nPortal Turístico Ecuador', '["user_name", "amount"]'),
('reminder_24h', 'email', 'Recordatorio: Tu viaje mañana - Portal Turístico Ecuador', 'Hola {{user_name}},\n\nTe recordamos que mañana tienes tu viaje a {{destination}}.\n\n¡Que tengas un excelente viaje!\n\nPortal Turístico Ecuador', '["user_name", "destination"]');

-- Insertar palabras clave SEO
INSERT INTO `keywords` (`keyword`, `search_volume`, `difficulty_score`, `category`) VALUES
('turismo ecuador', 1200, 45, 'general'),
('galapagos tours', 800, 60, 'destinos'),
('quito turismo', 600, 40, 'destinos'),
('hoteles quito', 400, 35, 'alojamiento'),
('baños ecuador', 300, 30, 'destinos'),
('cuenca ecuador', 250, 25, 'destinos'),
('playas ecuador', 500, 40, 'destinos'),
('viajes ecuador', 900, 50, 'general');

-- =====================================================
-- ÍNDICES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices para búsquedas rápidas
CREATE INDEX `idx_reservations_dates` ON `reservations` (`check_in_date`, `check_out_date`);
CREATE INDEX `idx_payments_status` ON `payments` (`payment_status`, `payment_date`);
CREATE INDEX `idx_notifications_user_read` ON `notifications` (`user_id`, `is_read`);
CREATE INDEX `idx_content_status_published` ON `content` (`status`, `published_at`);
CREATE INDEX `idx_seo_url` ON `seo_data` (`page_url`);
CREATE INDEX `idx_keywords_volume` ON `keywords` (`search_volume` DESC);
CREATE INDEX `idx_analytics_date_url` ON `performance_analytics` (`date_recorded`, `page_url`);

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista de estadísticas de reservas
CREATE OR REPLACE VIEW `reservation_stats` AS
SELECT 
    COUNT(*) as total_reservations,
    SUM(CASE WHEN reservation_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_reservations,
    SUM(CASE WHEN reservation_status = 'pending' THEN 1 ELSE 0 END) as pending_reservations,
    SUM(CASE WHEN reservation_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_reservations,
    AVG(total_amount) as average_amount,
    SUM(total_amount) as total_revenue
FROM reservations;

-- Vista de notificaciones no leídas
CREATE OR REPLACE VIEW `unread_notifications` AS
SELECT 
    n.*,
    u.firstName,
    u.lastName,
    u.email
FROM notifications n
JOIN users u ON n.user_id = u.id
WHERE n.is_read = 0
ORDER BY n.sent_at DESC;

-- Vista de contenido SEO optimizado
CREATE OR REPLACE VIEW `seo_optimized_content` AS
SELECT 
    c.*,
    s.seo_score,
    s.page_speed_score,
    s.mobile_friendly_score
FROM content c
LEFT JOIN seo_data s ON c.id = s.content_id
WHERE c.status = 'published'
ORDER BY s.seo_score DESC;

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS
-- =====================================================

-- Procedimiento para limpiar notificaciones antiguas
DELIMITER //
CREATE PROCEDURE `CleanOldNotifications`(IN days_old INT)
BEGIN
    DELETE FROM notifications 
    WHERE sent_at < DATE_SUB(NOW(), INTERVAL days_old DAY)
    AND is_read = 1;
END //
DELIMITER ;

-- Procedimiento para generar reporte de reservas
DELIMITER //
CREATE PROCEDURE `GenerateReservationReport`(
    IN start_date DATE,
    IN end_date DATE,
    IN user_id INT
)
BEGIN
    INSERT INTO reports (user_id, report_type, report_title, report_data, date_range_start, date_range_end)
    SELECT 
        user_id,
        'reservations',
        CONCAT('Reporte de Reservas ', start_date, ' - ', end_date),
        JSON_OBJECT(
            'total_reservations', COUNT(*),
            'total_revenue', SUM(total_amount),
            'average_amount', AVG(total_amount),
            'reservations_by_status', JSON_OBJECT(
                'confirmed', SUM(CASE WHEN reservation_status = 'confirmed' THEN 1 ELSE 0 END),
                'pending', SUM(CASE WHEN reservation_status = 'pending' THEN 1 ELSE 0 END),
                'cancelled', SUM(CASE WHEN reservation_status = 'cancelled' THEN 1 ELSE 0 END)
            )
        ),
        start_date,
        end_date
    FROM reservations
    WHERE created_at BETWEEN start_date AND end_date;
END //
DELIMITER ;

-- =====================================================
-- TRIGGERS
-- =====================================================

-- Trigger para actualizar estadísticas cuando se crea una reserva
DELIMITER //
CREATE TRIGGER `after_reservation_insert`
AFTER INSERT ON `reservations`
FOR EACH ROW
BEGIN
    INSERT INTO metrics (metric_name, metric_value, metric_unit, date_recorded, category)
    VALUES ('new_reservations', 1, 'count', CURDATE(), 'reservations');
    
    INSERT INTO metrics (metric_name, metric_value, metric_unit, date_recorded, category)
    VALUES ('revenue', NEW.total_amount, 'USD', CURDATE(), 'revenue');
END //
DELIMITER ;

-- Trigger para actualizar estadísticas cuando se paga una reserva
DELIMITER //
CREATE TRIGGER `after_payment_insert`
AFTER INSERT ON `payments`
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'completed' THEN
        INSERT INTO metrics (metric_name, metric_value, metric_unit, date_recorded, category)
        VALUES ('completed_payments', 1, 'count', CURDATE(), 'payments');
    END IF;
END //
DELIMITER ;

-- =====================================================
-- PERMISOS Y SEGURIDAD
-- =====================================================

-- Crear usuario para la aplicación (opcional)
-- CREATE USER 'turismo_user'@'localhost' IDENTIFIED BY 'turismo_password_2024';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON registro.* TO 'turismo_user'@'localhost';
-- FLUSH PRIVILEGES;

-- =====================================================
-- FINALIZACIÓN
-- =====================================================

-- Verificar que todas las tablas se crearon correctamente
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'registro'
ORDER BY TABLE_NAME;

-- Mostrar estadísticas de la base de datos
SELECT 
    COUNT(*) as total_tables,
    SUM(TABLE_ROWS) as total_rows,
    ROUND(SUM(DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) as total_size_mb
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'registro';
