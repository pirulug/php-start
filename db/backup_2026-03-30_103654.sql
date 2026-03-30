-- PHP-Start Database Backup
-- Fecha: 2026-03-30 10:36:54
-- Base de datos: php-start

SET FOREIGN_KEY_CHECKS = 0;

-- Estructura de la tabla `options` --
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_key` varchar(100) DEFAULT NULL,
  `option_value` text DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `options` --
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('1', 'site_name', 'PHP Start');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('2', 'site_url', 'http://php-start.test');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('3', 'site_description', 'A simple PHP starter project');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('4', 'site_keywords', 'php,start,project,template');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('5', 'site_language', 'es');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('6', 'site_timezone', 'America/Lima');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('7', 'date_format', 'd/m/Y');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('8', 'time_format', 'H:i a');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('9', 'datetime_format', 'd/m/Y - H:i a');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('10', 'favicon', '{\"android-chrome-192x192\":\"android-chrome-192x192-2283b9d2.png\",\"android-chrome-512x512\":\"android-chrome-512x512,2283b9d2.png\",\"apple-touch-icon\":\"apple-touch-icon-2283b9d2.png\",\"favicon-16x16\":\"favicon-16x16-2283b9d2.png\",\"favicon-32x32\":\"favicon-32x32-2283b9d2.png\",\"favicon.ico\":\"favicon-2283b9d2.ico\",\"webmanifest\":\"site-2283b9d2.webmanifest\"}');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('11', 'white_logo', 'st_logo_light_6962747f29c46870091717.webp');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('12', 'dark_logo', 'st_logo_dark_69627476f0736557823395.webp');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('13', 'og_image', 'og_image_6962748bb0c10821618393.webp');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('14', 'loader', '0');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('15', 'smtp_host', 'mail.pirulug.pw');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('16', 'smtp_email', 'no-reply@pirulug.pw');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('17', 'smtp_password', 'IB0]}]oynY=Qkgk*');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('18', 'smtp_port', '587');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('19', 'smtp_encryption', 'tls');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('20', 'google_recaptcha_site_key', '-');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('21', 'google_recaptcha_secret_key', '-');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('22', 'cloudflare_turnstile_site_key', '-');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('23', 'cloudflare_turnstile_secret_key', '-');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('24', 'captcha_enabled', '0');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('25', 'captcha_type', 'vanilla');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('26', 'facebook', 'https://facebook.com');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('27', 'twitter', 'https://twitter.com');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('28', 'instagram', 'https://instagram.com');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('29', 'youtube', 'https://youtube.com');
INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES ('30', 'version', '1.0');

-- Estructura de la tabla `permission_contexts` --
DROP TABLE IF EXISTS `permission_contexts`;
CREATE TABLE `permission_contexts` (
  `permission_context_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_context_key` varchar(50) NOT NULL,
  `permission_context_name` varchar(100) NOT NULL,
  PRIMARY KEY (`permission_context_id`),
  UNIQUE KEY `permission_context_key` (`permission_context_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `permission_contexts` --
INSERT INTO `permission_contexts` (`permission_context_id`, `permission_context_key`, `permission_context_name`) VALUES ('1', 'admin', 'Panel administrativo');
INSERT INTO `permission_contexts` (`permission_context_id`, `permission_context_key`, `permission_context_name`) VALUES ('2', 'front', 'Frontend');
INSERT INTO `permission_contexts` (`permission_context_id`, `permission_context_key`, `permission_context_name`) VALUES ('3', 'api', 'API');
INSERT INTO `permission_contexts` (`permission_context_id`, `permission_context_key`, `permission_context_name`) VALUES ('4', 'ajax', 'Peticiones AJAX');

-- Estructura de la tabla `permission_groups` --
DROP TABLE IF EXISTS `permission_groups`;
CREATE TABLE `permission_groups` (
  `permission_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_group_name` varchar(100) NOT NULL,
  `permission_group_key_name` varchar(100) NOT NULL,
  `permission_group_description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`permission_group_id`),
  UNIQUE KEY `permission_group_key_name` (`permission_group_key_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `permission_groups` --
INSERT INTO `permission_groups` (`permission_group_id`, `permission_group_name`, `permission_group_key_name`, `permission_group_description`) VALUES ('1', 'Sistema', 'system', 'Permisos del sistema');

-- Estructura de la tabla `permissions` --
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(100) NOT NULL,
  `permission_key_name` varchar(100) NOT NULL,
  `permission_description` varchar(150) DEFAULT NULL,
  `permission_group_id` int(11) NOT NULL,
  `permission_context_id` int(11) NOT NULL,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_key_name` (`permission_key_name`,`permission_context_id`),
  KEY `permission_group_id` (`permission_group_id`),
  KEY `permission_context_id` (`permission_context_id`),
  CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`permission_group_id`) REFERENCES `permission_groups` (`permission_group_id`),
  CONSTRAINT `permissions_ibfk_2` FOREIGN KEY (`permission_context_id`) REFERENCES `permission_contexts` (`permission_context_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `permissions` --
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_key_name`, `permission_description`, `permission_group_id`, `permission_context_id`) VALUES ('1', 'Dashboard Panel', 'dashboard.dashboard', 'Acceso al dashboard administrativo', '1', '1');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_key_name`, `permission_description`, `permission_group_id`, `permission_context_id`) VALUES ('2', 'Acceso al panel', 'auth.login', 'Permite loguearse en el panel', '1', '1');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_key_name`, `permission_description`, `permission_group_id`, `permission_context_id`) VALUES ('3', 'Perfil Admin', 'account.profile', 'Ver perfil en administración', '1', '1');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_key_name`, `permission_description`, `permission_group_id`, `permission_context_id`) VALUES ('4', 'Configuración Admin', 'account.settings', 'Configuración de cuenta en administración', '1', '1');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_key_name`, `permission_description`, `permission_group_id`, `permission_context_id`) VALUES ('5', 'Ver Perfil', 'account.profile', 'Ver perfil en el frontend', '1', '2');
INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_key_name`, `permission_description`, `permission_group_id`, `permission_context_id`) VALUES ('6', 'Editar Perfil', 'account.edit', 'Editar perfil en el frontend', '1', '2');

-- Estructura de la tabla `role_permissions` --
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `role_permissions` --
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('1', '1');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('1', '2');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('1', '3');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('1', '4');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('1', '5');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('2', '5');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('1', '6');
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES ('2', '6');

-- Estructura de la tabla `roles` --
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `role_description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `roles` --
INSERT INTO `roles` (`role_id`, `role_name`, `role_description`) VALUES ('1', 'Administrador', 'Usuario con acceso administrativo');
INSERT INTO `roles` (`role_id`, `role_name`, `role_description`) VALUES ('2', 'Usuario', 'Usuario con acceso básico');

-- Estructura de la tabla `user_access` --
DROP TABLE IF EXISTS `user_access`;
CREATE TABLE `user_access` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `access_ip` varchar(45) NOT NULL,
  `access_attempts` int(11) NOT NULL DEFAULT 0,
  `access_last_attempt` datetime NOT NULL,
  `access_blocked_until` datetime DEFAULT NULL,
  PRIMARY KEY (`access_id`),
  UNIQUE KEY `uniq_user_ip` (`user_id`,`access_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estructura de la tabla `usermeta` --
DROP TABLE IF EXISTS `usermeta`;
CREATE TABLE `usermeta` (
  `usermeta_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `usermeta_key` varchar(150) NOT NULL,
  `usermeta_value` text DEFAULT NULL,
  PRIMARY KEY (`usermeta_id`),
  KEY `uniq_user_meta` (`user_id`,`usermeta_key`),
  CONSTRAINT `usermeta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `usermeta` --
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('1', '1', 'first_name', 'Administrador');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('2', '1', 'last_name', '');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('3', '1', 'second_last_name', '');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('4', '1', 'remember_token', '');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('5', '2', 'first_name', 'Jhon');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('6', '2', 'last_name', 'Doe');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('7', '2', 'second_last_name', 'Plus');
INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES ('8', '2', 'remember_token', '');

-- Estructura de la tabla `users` --
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_nickname` varchar(100) DEFAULT NULL,
  `user_display_name` varchar(150) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `user_status` tinyint(4) NOT NULL DEFAULT 1,
  `user_image` varchar(255) NOT NULL DEFAULT 'default.webp',
  `user_created` datetime NOT NULL DEFAULT current_timestamp(),
  `user_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_deleted` datetime DEFAULT NULL,
  `user_last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla `users` --
INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_nickname`, `user_display_name`, `user_email`, `role_id`, `user_status`, `user_image`, `user_created`, `user_updated`, `user_deleted`, `user_last_login`) VALUES ('1', 'admin', '$2y$12$jaw4Tfj9sl89d3CxeyKsmOobTZooker2W/0BX.6yD2A57klOpVlwe', 'Admin', 'Admin', 'admin@gmail.com', '1', '1', 'default.webp', '2026-03-30 10:33:54', '2026-03-30 10:33:54', NULL, NULL);
INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_nickname`, `user_display_name`, `user_email`, `role_id`, `user_status`, `user_image`, `user_created`, `user_updated`, `user_deleted`, `user_last_login`) VALUES ('2', 'user', '$2y$12$h9WAlbYEIDg2mqqRgwyFnub3OoI1eSvCcp.7mN8BkKWp8BIoWdAki', 'User', 'User', 'user@gmail.com', '2', '1', 'default.webp', '2026-03-30 10:33:54', '2026-03-30 10:33:54', NULL, NULL);

-- Estructura de la tabla `visitor_pages` --
DROP TABLE IF EXISTS `visitor_pages`;
CREATE TABLE `visitor_pages` (
  `visitor_page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visitor_page_uri` varchar(255) NOT NULL,
  `visitor_page_title` varchar(255) DEFAULT NULL,
  `visitor_page_type` varchar(100) DEFAULT 'page',
  `visitor_page_total_views` int(11) DEFAULT 0,
  `visitor_page_unique_visitors` int(11) DEFAULT 0,
  `visitor_page_last_viewed` datetime DEFAULT NULL,
  `visitor_page_created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`visitor_page_id`),
  UNIQUE KEY `uniq_visitor_pages_uri` (`visitor_page_uri`),
  KEY `idx_visitor_pages_type` (`visitor_page_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de la tabla `visitor_sessions` --
DROP TABLE IF EXISTS `visitor_sessions`;
CREATE TABLE `visitor_sessions` (
  `visitor_session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visitor_session_visitor_id` bigint(20) unsigned NOT NULL,
  `visitor_session_cookie` varchar(64) NOT NULL,
  `visitor_session_start_page` varchar(255) DEFAULT NULL,
  `visitor_session_end_page` varchar(255) DEFAULT NULL,
  `visitor_session_path` text DEFAULT NULL,
  `visitor_session_start_time` datetime DEFAULT current_timestamp(),
  `visitor_session_end_time` datetime DEFAULT NULL,
  PRIMARY KEY (`visitor_session_id`),
  UNIQUE KEY `uniq_cookie` (`visitor_session_cookie`),
  KEY `visitor_session_visitor_id` (`visitor_session_visitor_id`),
  KEY `idx_sessions_start_time` (`visitor_session_start_time`),
  CONSTRAINT `visitor_sessions_ibfk_1` FOREIGN KEY (`visitor_session_visitor_id`) REFERENCES `visitors` (`visitor_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de la tabla `visitor_useronlines` --
DROP TABLE IF EXISTS `visitor_useronlines`;
CREATE TABLE `visitor_useronlines` (
  `visitor_useronline_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visitor_useronline_visitor_id` bigint(20) unsigned NOT NULL,
  `visitor_useronline_page_id` bigint(20) unsigned DEFAULT NULL,
  `visitor_useronline_ip` varchar(60) NOT NULL,
  `visitor_useronline_last_activity` datetime NOT NULL DEFAULT current_timestamp(),
  `visitor_useronline_referer` varchar(512) DEFAULT NULL,
  `visitor_useronline_agent` varchar(255) DEFAULT NULL,
  `visitor_useronline_platform` varchar(100) DEFAULT NULL,
  `visitor_useronline_country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`visitor_useronline_id`),
  UNIQUE KEY `visitor_useronline_visitor_id` (`visitor_useronline_visitor_id`),
  UNIQUE KEY `visitor_useronline_ip` (`visitor_useronline_ip`),
  KEY `visitor_useronline_page_id` (`visitor_useronline_page_id`),
  KEY `idx_useronline_last_activity` (`visitor_useronline_last_activity`),
  CONSTRAINT `visitor_useronlines_ibfk_1` FOREIGN KEY (`visitor_useronline_visitor_id`) REFERENCES `visitors` (`visitor_id`) ON DELETE CASCADE,
  CONSTRAINT `visitor_useronlines_ibfk_2` FOREIGN KEY (`visitor_useronline_page_id`) REFERENCES `visitor_pages` (`visitor_page_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de la tabla `visitors` --
DROP TABLE IF EXISTS `visitors`;
CREATE TABLE `visitors` (
  `visitor_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visitor_ip` varchar(60) NOT NULL,
  `visitor_user_agent` varchar(512) NOT NULL,
  `visitor_browser` varchar(100) DEFAULT NULL,
  `visitor_platform` varchar(100) DEFAULT NULL,
  `visitor_device` varchar(50) DEFAULT NULL,
  `visitor_country` varchar(100) DEFAULT NULL,
  `visitor_region` varchar(100) DEFAULT NULL,
  `visitor_city` varchar(100) DEFAULT NULL,
  `visitor_referer` varchar(512) DEFAULT NULL,
  `visitor_first_visit` datetime DEFAULT current_timestamp(),
  `visitor_last_visit` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visitor_total_hits` int(11) DEFAULT 0,
  PRIMARY KEY (`visitor_id`),
  UNIQUE KEY `uniq_visitor_ip_ua` (`visitor_ip`,`visitor_user_agent`(255)),
  KEY `idx_visitor_country` (`visitor_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
