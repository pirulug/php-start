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
('site_permissions', '{\"admin\":{\"access.admin\":{\"name\":\"Acceso al Panel Administrativo\",\"group\":\"Sistema\",\"desc\":\"Permiso ra\\u00edz (gatekeeper) para habilitar el acceso al panel administrativo.\"},\"account.profile\":{\"name\":\"Profile\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"account.settings\":{\"name\":\"Settings\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.captcha\":{\"name\":\"Captcha\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.sweetalert\":{\"name\":\"Sweetalert\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.gravatar\":{\"name\":\"Gravatar\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.barcode\":{\"name\":\"Barcode\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.qrcode\":{\"name\":\"Qrcode\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.fpdf\":{\"name\":\"Fpdf\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"dashboard.dashboard\":{\"name\":\"Dashboard\",\"group\":\"Dashboard\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.list\":{\"name\":\"List\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.new\":{\"name\":\"New\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.edit\":{\"name\":\"Edit\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.delete\":{\"name\":\"Delete\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.status\":{\"name\":\"Status\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.list\":{\"name\":\"List\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.new\":{\"name\":\"New\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.edit\":{\"name\":\"Edit\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.delete\":{\"name\":\"Delete\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.list\":{\"name\":\"List\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.new\":{\"name\":\"New\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.edit\":{\"name\":\"Edit\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.delete\":{\"name\":\"Delete\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.formats\":{\"name\":\"Formats\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.general\":{\"name\":\"General\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.social\":{\"name\":\"Social\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.robots\":{\"name\":\"Robots\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.sitemap\":{\"name\":\"Sitemap\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.brand\":{\"name\":\"Brand\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.info\":{\"name\":\"Info\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.backup\":{\"name\":\"Backup\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.captcha\":{\"name\":\"Captcha\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.smtp\":{\"name\":\"Smtp\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.list\":{\"name\":\"List\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.new\":{\"name\":\"New\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.edit\":{\"name\":\"Edit\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.deactivate\":{\"name\":\"Deactivate\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.delete\":{\"name\":\"Delete\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.api\":{\"name\":\"Api\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.permissions\":{\"name\":\"Permissions\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}},\"front\":{\"account.profile\":{\"name\":\"Profile\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"account.edit\":{\"name\":\"Edit\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}}}');