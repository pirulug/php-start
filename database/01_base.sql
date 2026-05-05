-- =========================================================
-- TABLA: OPTIONS
-- =========================================================
CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100) NOT NULL UNIQUE,
  option_value TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DATOS
INSERT INTO options 
  (option_key, option_value) 
VALUES 
('site_name', 'PHP Start'),
('site_url', 'http://php-start.test'),
('site_description', 'A simple PHP starter project'),
('site_keywords', 'php,start,project,template'),
('site_language', 'es'),
('site_timezone', 'America/Lima'),
('date_format', 'l d F, Y'),
('time_format', 'h:i a'),
('datetime_format', 'l d F, yy - h:i a'),
('favicon', '{\"android-chrome-192x192\":\"android-chrome-192x192.png\",\"android-chrome-512x512\":\"android-chrome-512x512.png\",\"apple-touch-icon\":\"apple-touch-icon.png\",\"favicon-16x16\":\"favicon-16x16.png\",\"favicon-32x32\":\"favicon-32x32.png\",\"favicon.ico\":\"favicon.ico\",\"webmanifest\":\"site.webmanifest\"}'),
('white_logo', 'st_logo_light.webp'),
('dark_logo', 'st_logo_dark.webp'),
('og_image', 'og_image.webp'),
('loader_admin', 'false'),
('loader_home', 'false'),
('smtp_host', 'mail.pirulug.pw'),
('smtp_email', 'no-reply@pirulug.pw'),
('smtp_password', '=Ktv3la+R50}bS-F'),
('smtp_port', '587'),
('smtp_encryption', 'tls'),
('google_recaptcha_site_key', '-'),
('google_recaptcha_secret_key', '-'),
('cloudflare_turnstile_site_key', '-'),
('cloudflare_turnstile_secret_key', '-'),
('captcha_enabled', '1'),
('captcha_type', 'vanilla'),
('google_analytics_id', '-'),
('meta_pixel_id', '-'),
('google_search_console', '-'),
('site_maintenance_msg', 'Estamos trabajando en mejoras. Volvemos pronto.'),
('site_social', '[{"name":"Facebook","url":"https://facebook.com"}]'),
('tracking_type', 'internal'),
('version', '1.0'),
('site_permissions', '{\"admin\":{\"dashboard.dashboard\":{\"name\":\"Dashboard Panel\",\"group\":\"dashboard\",\"desc\":\"Acceso al dashboard administrativo\"},\"account.settings\":{\"name\":\"Configuraci\\u00f3n Admin\",\"group\":\"account\",\"desc\":\"Configuraci\\u00f3n de cuenta en administraci\\u00f3n\"},\"analytics.summary\":{\"name\":\"Analytics summary\",\"group\":\"analytics\"},\"analytics.visitors\":{\"name\":\"Analytics visitors\",\"group\":\"analytics\"},\"analytics.views\":{\"name\":\"Analytics views\",\"group\":\"analytics\"},\"analytics.online\":{\"name\":\"Analytics online\",\"group\":\"analytics\"},\"analytics.top\":{\"name\":\"Analytics top\",\"group\":\"analytics\"},\"analytics.mapa\":{\"name\":\"Analytics mapa\",\"group\":\"analytics\"},\"roles.new\":{\"name\":\"Roles new\",\"group\":\"roles\"},\"roles.list\":{\"name\":\"Roles list\",\"group\":\"roles\"},\"roles.edit\":{\"name\":\"Roles edit\",\"group\":\"roles\"},\"roles.delete\":{\"name\":\"Roles delete\",\"group\":\"roles\"},\"permissions.list\":{\"name\":\"Permissions list\",\"group\":\"permissions\"},\"permissions.new\":{\"name\":\"Permissions new\",\"group\":\"permissions\"},\"permissions.edit\":{\"name\":\"Permissions edit\",\"group\":\"permissions\"},\"permissions.delete\":{\"name\":\"Permissions delete\",\"group\":\"permissions\"},\"settings.general\":{\"name\":\"Settings general\",\"group\":\"settings\"},\"settings.options\":{\"name\":\"Settings options\",\"group\":\"settings\"},\"settings.backups\":{\"name\":\"Settings backups\",\"group\":\"settings\"},\"settings.brand\":{\"name\":\"Settings brand\",\"group\":\"settings\"},\"settings.captcha\":{\"name\":\"Settings captcha\",\"group\":\"settings\"},\"settings.date_time\":{\"name\":\"Settings date_time\",\"group\":\"settings\"},\"settings.info\":{\"name\":\"Settings info\",\"group\":\"settings\"},\"settings.robots\":{\"name\":\"Settings robots\",\"group\":\"settings\"},\"settings.sitemap\":{\"name\":\"Settings sitemap\",\"group\":\"settings\"},\"settings.smtp\":{\"name\":\"Settings smtp\",\"group\":\"settings\"},\"settings.social\":{\"name\":\"Settings social\",\"group\":\"settings\"},\"users.list\":{\"name\":\"Users list\",\"group\":\"users\"},\"users.new\":{\"name\":\"Users new\",\"group\":\"users\"},\"users.edit\":{\"name\":\"Users edit\",\"group\":\"users\"},\"users.deactivate\":{\"name\":\"Users deactivate\",\"group\":\"users\"},\"users.delete\":{\"name\":\"Users delete\",\"group\":\"users\"},\"account.profile\":{\"name\":\"Account Profile\",\"group\":\"account\",\"desc\":\"\"},\"policies.list\":{\"name\":\"Policies list\",\"group\":\"policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.new\":{\"name\":\"Policies new\",\"group\":\"policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.edit\":{\"name\":\"Policies edit\",\"group\":\"policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.delete\":{\"name\":\"Policies delete\",\"group\":\"policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}},\"front\":{\"account.profile\":{\"name\":\"Ver Perfil\",\"group\":\"account\",\"desc\":\"\"},\"account.edit\":{\"name\":\"Editar Perfil\",\"group\":\"account\",\"desc\":\"\"}}}');