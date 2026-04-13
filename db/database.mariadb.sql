-- PHP-Start Database Backup
-- Fecha: 2026-04-13 09:46:07
-- Base de datos: php-start

SET FOREIGN_KEY_CHECKS = 0;

-- Estructura de la tabla options --
DROP TABLE IF EXISTS options;
CREATE TABLE options (
  option_id int(11) NOT NULL AUTO_INCREMENT,
  option_key varchar(100) NOT NULL,
  option_value text DEFAULT NULL,
  PRIMARY KEY (option_id),
  UNIQUE KEY option_key (option_key)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla options --
INSERT INTO options (option_id, option_key, option_value) VALUES 
('1', 'site_name', 'PHP Start'),
('2', 'site_url', 'http://php-start.test'),
('3', 'site_description', 'A simple PHP starter project'),
('4', 'site_keywords', 'php,start,project,template'),
('5', 'site_language', 'es'),
('6', 'site_timezone', 'America/Lima'),
('7', 'date_format', 'd/m/Y'),
('8', 'time_format', 'H:i a'),
('9', 'datetime_format', 'd/m/Y - H:i a'),
('10', 'favicon', '{\"android-chrome-192x192\":\"android-chrome-192x192.png\",\"android-chrome-512x512\":\"android-chrome-512x512.png\",\"apple-touch-icon\":\"apple-touch-icon.png\",\"favicon-16x16\":\"favicon-16x16.png\",\"favicon-32x32\":\"favicon-32x32.png\",\"favicon.ico\":\"favicon.ico\",\"webmanifest\":\"site.webmanifest\"}'),
('11', 'white_logo', 'st_logo_light.webp'),
('12', 'dark_logo', 'st_logo_dark.webp'),
('13', 'og_image', 'og_image.webp'),
('14', 'loader_admin', 'false'),
('15', 'loader_home', 'false'),
('16', 'smtp_host', 'mail.pirulug.pw'),
('17', 'smtp_email', 'no-reply@pirulug.pw'),
('18', 'smtp_password', 'IB0]}]oynY=Qkgk*'),
('19', 'smtp_port', '587'),
('20', 'smtp_encryption', 'tls'),
('21', 'google_recaptcha_site_key', '-'),
('22', 'google_recaptcha_secret_key', '-'),
('23', 'cloudflare_turnstile_site_key', '-'),
('24', 'cloudflare_turnstile_secret_key', '-'),
('25', 'captcha_enabled', '0'),
('26', 'captcha_type', 'vanilla'),
('27', 'google_analytics_id', '-'),
('28', 'meta_pixel_id', '-'),
('29', 'google_search_console', '-'),
('30', 'site_maintenance_msg', 'Estamos trabajando en mejoras. Volvemos pronto.'),
('31', 'facebook', 'https://facebook.com'),
('32', 'twitter', 'https://twitter.com'),
('33', 'instagram', 'https://instagram.com'),
('34', 'youtube', 'https://youtube.com'),
('35', 'linkedin', 'https://linkedin.com'),
('36', 'tiktok', 'https://tiktok.com'),
('37', 'version', '1.0');

-- Estructura de la tabla permission_contexts --
DROP TABLE IF EXISTS permission_contexts;
CREATE TABLE permission_contexts (
  permission_context_id int(11) NOT NULL AUTO_INCREMENT,
  permission_context_key varchar(50) NOT NULL,
  permission_context_name varchar(100) NOT NULL,
  PRIMARY KEY (permission_context_id),
  UNIQUE KEY permission_context_key (permission_context_key)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla permission_contexts --
INSERT INTO permission_contexts (permission_context_id, permission_context_key, permission_context_name) VALUES 
('1', 'admin', 'Panel administrativo'),
('2', 'front', 'Frontend'),
('3', 'api', 'API'),
('4', 'ajax', 'Peticiones AJAX');

-- Estructura de la tabla permission_groups --
DROP TABLE IF EXISTS permission_groups;
CREATE TABLE permission_groups (
  permission_group_id int(11) NOT NULL AUTO_INCREMENT,
  permission_group_name varchar(100) NOT NULL,
  permission_group_key_name varchar(100) NOT NULL,
  permission_group_description varchar(150) DEFAULT NULL,
  PRIMARY KEY (permission_group_id),
  UNIQUE KEY permission_group_key_name (permission_group_key_name)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla permission_groups --
INSERT INTO permission_groups (permission_group_id, permission_group_name, permission_group_key_name, permission_group_description) VALUES 
('1', 'Sistema', 'system', 'Permisos del sistema'),
('2', 'Analytics', 'analytics', NULL),
('3', 'Roles', 'roles', NULL),
('4', 'Permissions', 'permissions', NULL),
('5', 'Settings', 'settings', NULL),
('6', 'Users', 'users', NULL),
('7', 'Dashboard', 'dashboard', NULL),
('8', 'Account', 'account', NULL);

-- Estructura de la tabla permissions --
DROP TABLE IF EXISTS permissions;
CREATE TABLE permissions (
  permission_id int(11) NOT NULL AUTO_INCREMENT,
  permission_name varchar(100) NOT NULL,
  permission_key_name varchar(100) NOT NULL,
  permission_description varchar(150) DEFAULT NULL,
  permission_group_id int(11) NOT NULL,
  permission_context_id int(11) NOT NULL,
  PRIMARY KEY (permission_id),
  UNIQUE KEY permission_key_name (permission_key_name,permission_context_id),
  KEY permission_group_id (permission_group_id),
  KEY permission_context_id (permission_context_id),
  CONSTRAINT permissions_ibfk_1 FOREIGN KEY (permission_group_id) REFERENCES permission_groups (permission_group_id),
  CONSTRAINT permissions_ibfk_2 FOREIGN KEY (permission_context_id) REFERENCES permission_contexts (permission_context_id)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla permissions --
INSERT INTO permissions (permission_id, permission_name, permission_key_name, permission_description, permission_group_id, permission_context_id) VALUES 
('1', 'Dashboard Panel', 'dashboard.dashboard', 'Acceso al dashboard administrativo', '7', '1'),
('3', 'Perfil Admin', 'account.profile', 'Ver perfil en administración', '8', '1'),
('4', 'Configuración Admin', 'account.settings', 'Configuración de cuenta en administración', '8', '1'),
('5', 'Ver Perfil', 'account.profile', 'Ver perfil en el frontend', '8', '2'),
('6', 'Editar Perfil', 'account.edit', 'Editar perfil en el frontend', '8', '2'),
('7', 'Analytics summary', 'analytics.summary', NULL, '2', '1'),
('8', 'Analytics visitors', 'analytics.visitors', NULL, '2', '1'),
('9', 'Analytics views', 'analytics.views', NULL, '2', '1'),
('10', 'Analytics online', 'analytics.online', NULL, '2', '1'),
('11', 'Analytics top', 'analytics.top', NULL, '2', '1'),
('12', 'Analytics mapa', 'analytics.mapa', NULL, '2', '1'),
('13', 'Roles new', 'roles.new', NULL, '3', '1'),
('14', 'Permissions list', 'permissions.list', NULL, '4', '1'),
('15', 'Permissions new', 'permissions.new', NULL, '4', '1'),
('16', 'Permissions edit', 'permissions.edit', NULL, '4', '1'),
('17', 'Permissions delete', 'permissions.delete', NULL, '4', '1'),
('18', 'Roles list', 'roles.list', NULL, '3', '1'),
('19', 'Roles edit', 'roles.edit', NULL, '3', '1'),
('20', 'Roles delete', 'roles.delete', NULL, '3', '1'),
('21', 'Settings general', 'settings.general', NULL, '5', '1'),
('22', 'Settings options', 'settings.options', NULL, '5', '1'),
('23', 'Settings backups', 'settings.backups', NULL, '5', '1'),
('24', 'Settings brand', 'settings.brand', NULL, '5', '1'),
('25', 'Settings captcha', 'settings.captcha', NULL, '5', '1'),
('26', 'Settings date_time', 'settings.date_time', NULL, '5', '1'),
('27', 'Settings info', 'settings.info', NULL, '5', '1'),
('28', 'Settings robots', 'settings.robots', NULL, '5', '1'),
('29', 'Settings sitemap', 'settings.sitemap', NULL, '5', '1'),
('30', 'Settings smtp', 'settings.smtp', NULL, '5', '1'),
('31', 'Settings social', 'settings.social', NULL, '5', '1'),
('32', 'Users list', 'users.list', NULL, '6', '1'),
('33', 'Users new', 'users.new', NULL, '6', '1'),
('34', 'Users edit', 'users.edit', NULL, '6', '1'),
('35', 'Users deactivate', 'users.deactivate', NULL, '6', '1'),
('36', 'Users delete', 'users.delete', NULL, '6', '1');

-- Estructura de la tabla role_permissions --
DROP TABLE IF EXISTS role_permissions;
CREATE TABLE role_permissions (
  role_id int(11) NOT NULL,
  permission_id int(11) NOT NULL,
  PRIMARY KEY (role_id,permission_id),
  KEY permission_id (permission_id),
  CONSTRAINT role_permissions_ibfk_1 FOREIGN KEY (role_id) REFERENCES roles (role_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT role_permissions_ibfk_2 FOREIGN KEY (permission_id) REFERENCES permissions (permission_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla role_permissions --
INSERT INTO role_permissions (role_id, permission_id) VALUES 
('1', '1'),
('1', '3'),
('1', '4'),
('1', '5'),
('2', '5'),
('1', '6'),
('2', '6');

-- Estructura de la tabla roles --
DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
  role_id int(11) NOT NULL AUTO_INCREMENT,
  role_name varchar(50) NOT NULL,
  role_description varchar(150) DEFAULT NULL,
  PRIMARY KEY (role_id),
  UNIQUE KEY role_name (role_name)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla roles --
INSERT INTO roles (role_id, role_name, role_description) VALUES 
('1', 'Administrador', 'Usuario con acceso administrativo'),
('2', 'Usuario', 'Usuario con acceso básico');

-- Estructura de la tabla user_access --
DROP TABLE IF EXISTS user_access;
CREATE TABLE user_access (
  access_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) DEFAULT NULL,
  access_ip varchar(45) NOT NULL,
  access_attempts int(11) NOT NULL DEFAULT 0,
  access_last_attempt datetime NOT NULL,
  access_blocked_until datetime DEFAULT NULL,
  PRIMARY KEY (access_id),
  UNIQUE KEY uniq_user_ip (user_id,access_ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estructura de la tabla user_api_keys --
DROP TABLE IF EXISTS user_api_keys;
CREATE TABLE user_api_keys (
  api_key_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  api_key varchar(64) NOT NULL,
  api_key_status tinyint(4) NOT NULL DEFAULT 1,
  api_key_created datetime NOT NULL DEFAULT current_timestamp(),
  api_key_last_used datetime DEFAULT NULL,
  PRIMARY KEY (api_key_id),
  UNIQUE KEY api_key (api_key),
  KEY user_id (user_id),
  CONSTRAINT user_api_keys_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla user_api_keys --
INSERT INTO user_api_keys (api_key_id, user_id, api_key, api_key_status, api_key_created, api_key_last_used) VALUES 
('13', '1', 'b2748dac5d04d00437ef19f5b6c7f055', '1', '2026-04-12 00:29:14', NULL),
('14', '2', 'e38fd424356ac799959ac835b25888e0', '1', '2026-04-12 00:32:23', NULL);

-- Estructura de la tabla usermeta --
DROP TABLE IF EXISTS usermeta;
CREATE TABLE usermeta (
  usermeta_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  usermeta_key varchar(150) NOT NULL,
  usermeta_value text DEFAULT NULL,
  PRIMARY KEY (usermeta_id),
  KEY uniq_user_meta (user_id,usermeta_key),
  CONSTRAINT usermeta_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla usermeta --
INSERT INTO usermeta (usermeta_id, user_id, usermeta_key, usermeta_value) VALUES 
('1', '1', 'first_name', 'Administrador'),
('2', '1', 'last_name', ''),
('3', '1', 'second_last_name', ''),
('4', '1', 'remember_token', ''),
('5', '2', 'first_name', 'Jhon'),
('6', '2', 'last_name', 'Doe'),
('7', '2', 'second_last_name', 'Plus'),
('8', '2', 'remember_token', ''),
('9', '1', 'remember_token', '9da8dd68e976489ed4386302f5502b534d575ff5ef823d06a0c1085a3bb8b2a8');

-- Estructura de la tabla users --
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  user_login varchar(255) DEFAULT NULL,
  user_password varchar(255) DEFAULT NULL,
  user_nickname varchar(100) DEFAULT NULL,
  user_display_name varchar(150) DEFAULT NULL,
  user_email varchar(255) DEFAULT NULL,
  role_id int(11) DEFAULT NULL,
  user_status tinyint(4) NOT NULL DEFAULT 1,
  user_image varchar(255) NOT NULL DEFAULT 'default.webp',
  user_created datetime NOT NULL DEFAULT current_timestamp(),
  user_updated datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  user_deleted datetime DEFAULT NULL,
  user_last_login datetime DEFAULT NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY user_login (user_login),
  UNIQUE KEY user_email (user_email),
  KEY role_id (role_id),
  CONSTRAINT users_ibfk_1 FOREIGN KEY (role_id) REFERENCES roles (role_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Datos de la tabla users --
INSERT INTO users (user_id, user_login, user_password, user_nickname, user_display_name, user_email, role_id, user_status, user_image, user_created, user_updated, user_deleted, user_last_login) VALUES 
('1', 'admin', '$2y$12$jaw4Tfj9sl89d3CxeyKsmOobTZooker2W/0BX.6yD2A57klOpVlwe', 'Admin', 'Admin', 'admin@gmail.com', '1', '1', 'default.webp', '2026-04-11 22:34:01', '2026-04-13 09:36:13', NULL, '2026-04-13 09:36:13'),
('2', 'user', '$2y$12$h9WAlbYEIDg2mqqRgwyFnub3OoI1eSvCcp.7mN8BkKWp8BIoWdAki', 'User', 'User', 'user@gmail.com', '2', '1', 'default.webp', '2026-04-11 22:34:01', '2026-04-11 22:34:01', NULL, NULL);

-- Estructura de la tabla visitor_pages --
DROP TABLE IF EXISTS visitor_pages;
CREATE TABLE visitor_pages (
  visitor_page_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  visitor_page_uri varchar(255) NOT NULL,
  visitor_page_title varchar(255) DEFAULT NULL,
  visitor_page_type varchar(100) DEFAULT 'page',
  visitor_page_total_views int(11) DEFAULT 0,
  visitor_page_unique_visitors int(11) DEFAULT 0,
  visitor_page_last_viewed datetime DEFAULT NULL,
  visitor_page_created_at datetime DEFAULT current_timestamp(),
  PRIMARY KEY (visitor_page_id),
  UNIQUE KEY uniq_visitor_pages_uri (visitor_page_uri),
  KEY idx_visitor_pages_type (visitor_page_type)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de la tabla visitor_pages --
INSERT INTO visitor_pages (visitor_page_id, visitor_page_uri, visitor_page_title, visitor_page_type, visitor_page_total_views, visitor_page_unique_visitors, visitor_page_last_viewed, visitor_page_created_at) VALUES 
('1', '/', 'Home', 'page', '11', '5', '2026-04-13 09:36:13', '2026-04-11 22:49:10'),
('7', '/account/profile', 'Mi Perfil', 'page', '26', '26', '2026-04-13 09:36:28', '2026-04-11 23:37:26'),
('8', '/account/settings/profile', 'Configuración de Perfil', 'page', '30', '30', '2026-04-13 09:36:28', '2026-04-11 23:37:28'),
('9', '/account/settings/api', 'API Keys', 'page', '23', '23', '2026-04-13 09:36:27', '2026-04-11 23:37:29'),
('10', '/account/settings/password', 'Seguridad de la Cuenta', 'page', '24', '24', '2026-04-13 09:36:28', '2026-04-11 23:37:30'),
('93', '/account/settings/api?delete_key=10', 'API Keys', 'page', '1', '1', NULL, '2026-04-12 00:07:31');

-- Estructura de la tabla visitor_sessions --
DROP TABLE IF EXISTS visitor_sessions;
CREATE TABLE visitor_sessions (
  visitor_session_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  visitor_session_visitor_id bigint(20) unsigned NOT NULL,
  visitor_session_cookie varchar(64) NOT NULL,
  visitor_session_start_page varchar(255) DEFAULT NULL,
  visitor_session_end_page varchar(255) DEFAULT NULL,
  visitor_session_path text DEFAULT NULL,
  visitor_session_start_time datetime DEFAULT current_timestamp(),
  visitor_session_end_time datetime DEFAULT NULL,
  PRIMARY KEY (visitor_session_id),
  UNIQUE KEY uniq_cookie (visitor_session_cookie),
  KEY visitor_session_visitor_id (visitor_session_visitor_id),
  KEY idx_sessions_start_time (visitor_session_start_time),
  CONSTRAINT visitor_sessions_ibfk_1 FOREIGN KEY (visitor_session_visitor_id) REFERENCES visitors (visitor_id) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de la tabla visitor_sessions --
INSERT INTO visitor_sessions (visitor_session_id, visitor_session_visitor_id, visitor_session_cookie, visitor_session_start_page, visitor_session_end_page, visitor_session_path, visitor_session_start_time, visitor_session_end_time) VALUES 
('3', '1', 'c3edfc77ee9a624a9ecc4189e3534f05', '/', '/', '[{\"uri\":\"\\/\",\"time\":\"2026-04-11 23:37:19\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-11 23:42:58\"},{\"uri\":\"\\/account\\/settings\\/api\",\"time\":\"2026-04-11 23:44:04\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-11 23:46:43\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-11 23:48:16\"},{\"uri\":\"\\/account\\/settings\\/profile\",\"time\":\"2026-04-11 23:49:45\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-11 23:51:52\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-11 23:53:09\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-11 23:57:20\"},{\"uri\":\"\\/account\\/settings\\/password\",\"time\":\"2026-04-11 23:59:25\"},{\"uri\":\"\\/account\\/settings\\/api\",\"time\":\"2026-04-12 00:07:22\"},{\"uri\":\"\\/\",\"time\":\"2026-04-12 00:19:15\"},{\"uri\":\"\\/\",\"time\":\"2026-04-12 00:26:28\"},{\"uri\":\"\\/account\\/profile\",\"time\":\"2026-04-12 00:28:53\"},{\"uri\":\"\\/account\\/settings\\/api\",\"time\":\"2026-04-12 00:32:28\"},{\"uri\":\"\\/\",\"time\":\"2026-04-13 09:35:29\"}]', '2026-04-11 23:37:19', '2026-04-13 09:35:29');

-- Estructura de la tabla visitor_useronlines --
DROP TABLE IF EXISTS visitor_useronlines;
CREATE TABLE visitor_useronlines (
  visitor_useronline_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  visitor_useronline_visitor_id bigint(20) unsigned NOT NULL,
  visitor_useronline_page_id bigint(20) unsigned DEFAULT NULL,
  visitor_useronline_ip varchar(60) NOT NULL,
  visitor_useronline_last_activity datetime NOT NULL DEFAULT current_timestamp(),
  visitor_useronline_referer varchar(512) DEFAULT NULL,
  visitor_useronline_agent varchar(255) DEFAULT NULL,
  visitor_useronline_platform varchar(100) DEFAULT NULL,
  visitor_useronline_country varchar(100) DEFAULT NULL,
  PRIMARY KEY (visitor_useronline_id),
  UNIQUE KEY visitor_useronline_visitor_id (visitor_useronline_visitor_id),
  UNIQUE KEY visitor_useronline_ip (visitor_useronline_ip),
  KEY visitor_useronline_page_id (visitor_useronline_page_id),
  KEY idx_useronline_last_activity (visitor_useronline_last_activity),
  CONSTRAINT visitor_useronlines_ibfk_1 FOREIGN KEY (visitor_useronline_visitor_id) REFERENCES visitors (visitor_id) ON DELETE CASCADE,
  CONSTRAINT visitor_useronlines_ibfk_2 FOREIGN KEY (visitor_useronline_page_id) REFERENCES visitor_pages (visitor_page_id) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de la tabla visitor_useronlines --
INSERT INTO visitor_useronlines (visitor_useronline_id, visitor_useronline_visitor_id, visitor_useronline_page_id, visitor_useronline_ip, visitor_useronline_last_activity, visitor_useronline_referer, visitor_useronline_agent, visitor_useronline_platform, visitor_useronline_country) VALUES 
('1', '1', '1', '127.0.0.1', '2026-04-13 09:35:29', '', NULL, NULL, NULL);

-- Estructura de la tabla visitors --
DROP TABLE IF EXISTS visitors;
CREATE TABLE visitors (
  visitor_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  visitor_ip varchar(60) NOT NULL,
  visitor_user_agent varchar(512) NOT NULL,
  visitor_browser varchar(100) DEFAULT NULL,
  visitor_platform varchar(100) DEFAULT NULL,
  visitor_device varchar(50) DEFAULT NULL,
  visitor_is_bot tinyint(1) DEFAULT 0,
  visitor_country varchar(100) DEFAULT NULL,
  visitor_region varchar(100) DEFAULT NULL,
  visitor_city varchar(100) DEFAULT NULL,
  visitor_referer varchar(512) DEFAULT NULL,
  visitor_first_visit datetime DEFAULT current_timestamp(),
  visitor_last_visit datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  visitor_total_hits int(11) DEFAULT 0,
  PRIMARY KEY (visitor_id),
  UNIQUE KEY uniq_visitor_ip_ua (visitor_ip,visitor_user_agent(255)),
  KEY idx_visitor_ip (visitor_ip),
  KEY idx_visitor_country (visitor_country)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de la tabla visitors --
INSERT INTO visitors (visitor_id, visitor_ip, visitor_user_agent, visitor_browser, visitor_platform, visitor_device, visitor_is_bot, visitor_country, visitor_region, visitor_city, visitor_referer, visitor_first_visit, visitor_last_visit, visitor_total_hits) VALUES 
('1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Chrome', 'Windows', 'Desktop', '0', 'Desconocido', NULL, NULL, 'http://php-start.test/account/settings/profile', '2026-04-11 23:36:48', '2026-04-13 09:36:28', '113');

SET FOREIGN_KEY_CHECKS = 1;
