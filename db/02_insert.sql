-- ROLES
INSERT INTO roles (role_name, role_description) VALUES
('Administrador', 'Usuario con acceso administrativo'),
('Usuario', 'Usuario con acceso básico');

-- PERMISSION CONTEXTS
INSERT INTO permission_contexts (permission_context_key, permission_context_name) VALUES
('admin', 'Panel administrativo'),
('front', 'Frontend'),
('api', 'API'),
('ajax', 'Peticiones AJAX');

-- PERMISSION GROUPS
INSERT INTO permission_groups (permission_group_name, permission_group_key_name, permission_group_description) VALUES
('Sistema', 'system', 'Permisos del sistema');

-- PERMISSIONS
INSERT INTO permissions (
  permission_name,
  permission_key_name,
  permission_description,
  permission_group_id,
  permission_context_id
) VALUES
('Dashboard Panel', 'dashboard.dashboard', 'Acceso al dashboard administrativo', 1, 1),
('Acceso al panel', 'auth.login', 'Permite loguearse en el panel', 1, 1),
('Perfil Admin', 'account.profile', 'Ver perfil en administración', 1, 1),
('Configuración Admin', 'account.settings', 'Configuración de cuenta en administración', 1, 1),
('Ver Perfil', 'account.profile', 'Ver perfil en el frontend', 1, 2),
('Editar Perfil', 'account.edit', 'Editar perfil en el frontend', 1, 2);

-- ROLE PERMISSIONS
-- 1: Administrador, 2: Usuario
-- Permisos: 1-4 (admin), 5-6 (front)
INSERT INTO role_permissions (role_id, permission_id) 
VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), -- Admin tiene todo
(2, 5), (2, 6); -- Usuario solo tiene perfil en el frontend

-- USERS
INSERT INTO users (
  user_login,
  user_password,
  user_nickname,
  user_display_name,
  user_email,
  role_id
) VALUES
('admin', '$2y$12$jaw4Tfj9sl89d3CxeyKsmOobTZooker2W/0BX.6yD2A57klOpVlwe', 'Admin', 'Admin', 'admin@gmail.com', 1),
('user', '$2y$12$h9WAlbYEIDg2mqqRgwyFnub3OoI1eSvCcp.7mN8BkKWp8BIoWdAki', 'User', 'User', 'user@gmail.com', 2);

-- USERMETA
INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES
(1, 'first_name', 'Administrador'),
(1, 'last_name', ''),
(1, 'second_last_name', ''),
(1, 'remember_token', ''),
(2, 'first_name', 'Jhon'),
(2, 'last_name', 'Doe'),
(2, 'second_last_name', 'Plus'),
(2, 'remember_token', '');

-- OPTIONS
INSERT INTO options (option_key, option_value) VALUES
('site_name', 'PHP Start'),
('site_url', 'http://php-start.test'),
('site_description', 'A simple PHP starter project'),
('site_keywords', 'php,start,project,template'),
('site_language', 'es'),
('site_timezone', 'America/Lima'),
('date_format', 'd/m/Y'),
('time_format', 'H:i a'),
('datetime_format', 'd/m/Y - H:i a'),
('favicon', '{"android-chrome-192x192":"android-chrome-192x192.png","android-chrome-512x512":"android-chrome-512x512.png","apple-touch-icon":"apple-touch-icon.png","favicon-16x16":"favicon-16x16.png","favicon-32x32":"favicon-32x32.png","favicon.ico":"favicon.ico","webmanifest":"site.webmanifest"}'),
('white_logo', 'st_logo_light.webp'),
('dark_logo', 'st_logo_dark.webp'),
('og_image', 'og_image.webp'),
('loader_admin', 'false'),
('loader_home', 'false'),
('smtp_host', 'mail.pirulug.pw'),
('smtp_email', 'no-reply@pirulug.pw'),
('smtp_password', 'IB0]}]oynY=Qkgk*'),
('smtp_port', '587'),
('smtp_encryption', 'tls'),
('google_recaptcha_site_key', '-'),
('google_recaptcha_secret_key', '-'),
('cloudflare_turnstile_site_key', '-'),
('cloudflare_turnstile_secret_key', '-'),
('captcha_enabled', '0'),
('captcha_type', 'vanilla'), -- // vanilla | recaptcha
('google_analytics_id', '-'),
('meta_pixel_id', '-'),
('google_search_console', '-'),
('site_maintenance_msg', 'Estamos trabajando en mejoras. Volvemos pronto.'),
('facebook', 'https://facebook.com'),
('twitter', 'https://twitter.com'),
('instagram', 'https://instagram.com'),
('youtube', 'https://youtube.com'),
('linkedin', 'https://linkedin.com'),
('tiktok', 'https://tiktok.com'),
('version', '1.0');