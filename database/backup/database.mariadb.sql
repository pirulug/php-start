-- PHP-Start Database Backup
-- Fecha: 2026-05-31 17:44:18
-- Base de datos: `php-start`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `option_id` int(11) NOT NULL ,
  `option_key` varchar(100) NOT NULL ,
  `option_value` text DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `options`
--

INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES 
('1', 'site_name', 'PHP Start'),
('2', 'site_url', 'http://php-start.test'),
('3', 'site_description', 'A simple PHP starter project'),
('4', 'site_keywords', 'php,start,project,template'),
('5', 'site_language', 'es'),
('6', 'site_timezone', 'America/Lima'),
('7', 'date_format', 'l d F, Y'),
('8', 'time_format', 'h:i a'),
('9', 'datetime_format', 'l d F, yy - h:i a'),
('10', 'favicon', '{\"android-chrome-192x192\":\"android-chrome-192x192.png\",\"android-chrome-512x512\":\"android-chrome-512x512.png\",\"apple-touch-icon\":\"apple-touch-icon.png\",\"favicon-16x16\":\"favicon-16x16.png\",\"favicon-32x32\":\"favicon-32x32.png\",\"favicon.ico\":\"favicon.ico\",\"webmanifest\":\"site.webmanifest\"}'),
('11', 'white_logo', 'st_logo_light.webp'),
('12', 'dark_logo', 'st_logo_dark.webp'),
('13', 'og_image', 'og_image.webp'),
('14', 'loader_admin', 'false'),
('15', 'loader_home', 'false'),
('16', 'smtp_host', 'mail.pirulug.pw'),
('17', 'smtp_email', 'no-reply@pirulug.pw'),
('18', 'smtp_password', '=Ktv3la+R50}bS-F'),
('19', 'smtp_port', '587'),
('20', 'smtp_encryption', 'tls'),
('21', 'google_recaptcha_site_key', '-'),
('22', 'google_recaptcha_secret_key', '-'),
('23', 'cloudflare_turnstile_site_key', '-'),
('24', 'cloudflare_turnstile_secret_key', '-'),
('25', 'captcha_enabled', '1'),
('26', 'captcha_type', 'vanilla'),
('27', 'google_analytics_id', '-'),
('28', 'meta_pixel_id', '-'),
('29', 'google_search_console', '-'),
('30', 'site_maintenance_msg', 'Estamos trabajando en mejoras. Volvemos pronto.'),
('31', 'site_social', '[{\"name\":\"Facebook\",\"url\":\"https:\\/\\/facebook.com\",\"icon\":\"\"}]'),
('32', 'tracking_type', 'internal'),
('33', 'version', '1.0'),
('34', 'site_permissions', '{\"admin\":{\"access.admin\":{\"name\":\"Acceso al Panel Administrativo\",\"group\":\"Sistema\",\"desc\":\"Permiso ra\\u00edz (gatekeeper) para habilitar el acceso al panel administrativo.\"},\"account.profile\":{\"name\":\"Profile\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"account.settings\":{\"name\":\"Settings\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.captcha\":{\"name\":\"Captcha\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.sweetalert\":{\"name\":\"Sweetalert\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.gravatar\":{\"name\":\"Gravatar\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.barcode\":{\"name\":\"Barcode\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.qrcode\":{\"name\":\"Qrcode\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.fpdf\":{\"name\":\"Fpdf\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"dashboard.dashboard\":{\"name\":\"Dashboard\",\"group\":\"Dashboard\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.list\":{\"name\":\"List\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.new\":{\"name\":\"New\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.edit\":{\"name\":\"Edit\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.delete\":{\"name\":\"Delete\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.status\":{\"name\":\"Status\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.list\":{\"name\":\"List\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.new\":{\"name\":\"New\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.edit\":{\"name\":\"Edit\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.delete\":{\"name\":\"Delete\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.list\":{\"name\":\"List\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.new\":{\"name\":\"New\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.edit\":{\"name\":\"Edit\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.delete\":{\"name\":\"Delete\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.formats\":{\"name\":\"Formats\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.general\":{\"name\":\"General\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.social\":{\"name\":\"Social\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.robots\":{\"name\":\"Robots\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.sitemap\":{\"name\":\"Sitemap\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.brand\":{\"name\":\"Brand\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.info\":{\"name\":\"Info\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.backup\":{\"name\":\"Backup\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.captcha\":{\"name\":\"Captcha\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.smtp\":{\"name\":\"Smtp\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.list\":{\"name\":\"List\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.new\":{\"name\":\"New\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.edit\":{\"name\":\"Edit\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.deactivate\":{\"name\":\"Deactivate\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.delete\":{\"name\":\"Delete\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.api\":{\"name\":\"Api\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.permissions\":{\"name\":\"Permissions\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.kiki\":{\"name\":\"Kiki\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"logs.list\":{\"name\":\"List\",\"group\":\"Logs\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"modules.admin\":{\"name\":\"Admin\",\"group\":\"Modules\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"modules.front\":{\"name\":\"Front\",\"group\":\"Modules\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"modules.api\":{\"name\":\"Api\",\"group\":\"Modules\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}},\"front\":{\"account.profile\":{\"name\":\"Profile\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"account.edit\":{\"name\":\"Edit\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}}}'),
('35', 'logo_type', 'text'),
('36', 'logo_icon_source', 'none'),
('37', 'logo_icon_color', '#ff0055'),
('38', 'logo_icon_class', 'bi bi-lightning-charge-fill'),
('39', 'logo_icon_svg', ''),
('44', 'number_decimal_sep', '.'),
('45', 'number_thousand_sep', ' '),
('46', 'number_decimals', '2'),
('47', 'currency_symbol', 'S/'),
('48', 'currency_position', 'before'),
('49', 'currency_decimal_sep', '.'),
('50', 'currency_thousand_sep', ','),
('51', 'currency_decimals', '2'),
('55', 'modules', '{\"admin\":{\"dashboard\":{\"active\":true,\"sidebar\":true,\"order\":1},\"components\":{\"active\":true,\"sidebar\":true,\"order\":2},\"security\":{\"active\":true,\"sidebar\":true,\"order\":3},\"users\":{\"active\":true,\"sidebar\":true,\"order\":4},\"policies\":{\"active\":true,\"sidebar\":true,\"order\":5},\"settings\":{\"active\":true,\"sidebar\":true,\"order\":6},\"modules\":{\"active\":true,\"sidebar\":true,\"order\":7},\"logs\":{\"active\":true,\"sidebar\":true,\"order\":8},\"account\":{\"active\":true,\"sidebar\":false,\"order\":9},\"auth\":{\"active\":true,\"sidebar\":false,\"order\":10},\"errors\":{\"active\":true,\"sidebar\":false,\"order\":11}},\"front\":{\"account\":{\"active\":true,\"order\":2},\"auth\":{\"active\":true,\"order\":3},\"docs\":{\"active\":true,\"order\":6},\"errors\":{\"active\":true,\"order\":4},\"index\":{\"active\":true,\"order\":1},\"policies\":{\"active\":true,\"order\":7},\"test\":{\"active\":true,\"order\":5}},\"api\":{\"test\":{\"active\":true,\"order\":2},\"users\":{\"active\":true,\"order\":1}}}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postmeta`
--

DROP TABLE IF EXISTS `postmeta`;
CREATE TABLE `postmeta` (
  `postmeta_id` bigint(20) unsigned NOT NULL ,
  `post_id` bigint(20) unsigned NOT NULL ,
  `postmeta_key` varchar(150) NOT NULL ,
  `postmeta_value` longtext DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `postmeta`
--

INSERT INTO `postmeta` (`postmeta_id`, `post_id`, `postmeta_key`, `postmeta_value`) VALUES 
('1', '1', 'type', 'faq'),
('2', '2', 'type', 'markdown'),
('3', '3', 'type', 'markdown');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `post_id` bigint(20) unsigned NOT NULL ,
  `post_author` int(11) DEFAULT NULL ,
  `post_title` varchar(255) NOT NULL ,
  `post_slug` varchar(255) NOT NULL ,
  `post_content` longtext DEFAULT NULL ,
  `post_excerpt` text DEFAULT NULL ,
  `post_type` varchar(50) NOT NULL DEFAULT 'post',
  `post_status` tinyint(4) NOT NULL DEFAULT '1',
  `post_created_at` datetime NOT NULL DEFAULT 'current_timestamp()',
  `post_updated_at` datetime NOT NULL DEFAULT 'current_timestamp()'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`post_id`, `post_author`, `post_title`, `post_slug`, `post_content`, `post_excerpt`, `post_type`, `post_status`, `post_created_at`, `post_updated_at`) VALUES 
('1', '1', 'FAQs', 'faqs', '[{\"q\":\"¿Cómo funciona?\",\"a\":\"Funciona mediante el framework PHP-Start.\"},{\"q\":\"¿Es seguro?\",\"a\":\"Sí, utiliza PDO y estándares de seguridad.\"}]', NULL, 'policy', '1', '2026-04-13 10:51:11', '2026-04-13 11:13:32'),
('2', '1', 'Política de Privacidad', 'privacy-policy', 'Contenido inicial de privacidad.', NULL, 'policy', '1', '2026-04-13 10:51:11', '2026-04-13 11:05:16'),
('3', '1', 'Términos y Condiciones', 'terms-and-conditions', 'Contenido inicial de términos.', NULL, 'policy', '1', '2026-04-13 10:51:11', '2026-04-13 11:05:03'),
('4', '1', 'Sobre Nosotros', 'about-us', 'Bienvenido a nuestra empresa...', NULL, 'page', '1', '2026-05-31 20:47:42', '2026-05-31 20:47:42'),
('5', '1', 'Primer Blog Post', 'mi-primer-post', 'Este es el contenido completo del post...', 'Este es un resumen corto.', 'post', '1', '2026-05-31 20:47:42', '2026-05-31 20:47:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolemeta`
--

DROP TABLE IF EXISTS `rolemeta`;
CREATE TABLE `rolemeta` (
  `rolemeta_id` int(11) NOT NULL ,
  `role_id` int(11) DEFAULT NULL ,
  `rolemeta_key` varchar(150) NOT NULL ,
  `rolemeta_value` text DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rolemeta`
--

INSERT INTO `rolemeta` (`rolemeta_id`, `role_id`, `rolemeta_key`, `rolemeta_value`) VALUES 
('1', '1', 'permissions', '[\"access.admin\", \"dashboard.dashboard\", \"account.profile\", \"account.settings\", \"analytics.summary\", \"analytics.visitors\", \"analytics.views\", \"analytics.online\", \"analytics.top\", \"analytics.mapa\", \"roles.new\", \"roles.list\", \"roles.edit\", \"roles.delete\", \"permissions.list\", \"permissions.new\", \"permissions.edit\", \"permissions.delete\", \"settings.general\", \"settings.options\", \"settings.backups\", \"settings.brand\", \"settings.captcha\", \"settings.date_time\", \"settings.info\", \"settings.robots\", \"settings.sitemap\", \"settings.smtp\", \"settings.social\", \"users.list\", \"users.new\", \"users.edit\", \"users.deactivate\", \"users.delete\", \"account.profile_front\", \"account.edit_front\"]'),
('2', '2', 'permissions', '[\"account.profile_front\", \"account.edit_front\"]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL ,
  `role_name` varchar(50) NOT NULL ,
  `role_description` varchar(150) DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_description`) VALUES 
('1', 'Administrador', 'Usuario con acceso administrativo'),
('2', 'Usuario', 'Usuario con acceso básico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usermeta`
--

DROP TABLE IF EXISTS `usermeta`;
CREATE TABLE `usermeta` (
  `usermeta_id` int(11) NOT NULL ,
  `user_id` int(11) DEFAULT NULL ,
  `usermeta_key` varchar(150) NOT NULL ,
  `usermeta_value` text DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usermeta`
--

INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES 
('1', '1', 'role_id', '1'),
('2', '1', 'first_name', 'Administrador'),
('3', '1', 'last_name', ''),
('4', '1', 'second_last_name', ''),
('5', '1', 'remember_token', ''),
('6', '1', 'api_key', '{\"key\":\"b2748dac5d04d00437ef19f5b6c7f055\",\"created_at\":\"2026-04-22 00:28:06\",\"updated_at\":\"2026-04-22 00:28:06\"}'),
('7', '1', 'permissions', '[]'),
('8', '1', 'login_access', ''),
('9', '2', 'role_id', '2'),
('10', '2', 'first_name', 'Jhon'),
('11', '2', 'last_name', 'Doe'),
('12', '2', 'second_last_name', 'Plus'),
('13', '2', 'remember_token', 'f1bd10c2c9a1f2c22b8f862892233dd775284955cb2802a6bdec0012f142835c'),
('14', '2', 'api_key', '{\"key\":\"e38fd424356ac799959ac835b25888e0\",\"created_at\":\"2026-04-22 00:28:06\",\"updated_at\":\"2026-04-22 00:28:06\"}'),
('15', '2', 'permissions', '[]'),
('16', '2', 'login_access', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL ,
  `user_login` varchar(255) DEFAULT NULL ,
  `user_password` varchar(255) DEFAULT NULL ,
  `user_nickname` varchar(100) DEFAULT NULL ,
  `user_display_name` varchar(150) DEFAULT NULL ,
  `user_email` varchar(255) DEFAULT NULL ,
  `user_status` tinyint(4) NOT NULL DEFAULT '1',
  `user_image` varchar(255) NOT NULL DEFAULT 'default.webp',
  `user_created` datetime NOT NULL DEFAULT 'current_timestamp()',
  `user_updated` datetime NOT NULL DEFAULT 'current_timestamp()',
  `user_deleted` datetime DEFAULT NULL ,
  `user_last_login` datetime DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_nickname`, `user_display_name`, `user_email`, `user_status`, `user_image`, `user_created`, `user_updated`, `user_deleted`, `user_last_login`) VALUES 
('1', 'admin', '$2y$12$jaw4Tfj9sl89d3CxeyKsmOobTZooker2W/0BX.6yD2A57klOpVlwe', 'Admin', 'Admin', 'admin@gmail.com', '1', 'default.webp', '2026-04-11 22:34:01', '2026-04-20 15:26:34', NULL, '2026-04-20 15:26:34'),
('2', 'user', '$2y$12$h9WAlbYEIDg2mqqRgwyFnub3OoI1eSvCcp.7mN8BkKWp8BIoWdAki', 'User', 'User', 'pirulug@gmail.com', '1', 'default.webp', '2026-04-11 22:34:01', '2026-04-20 22:48:30', NULL, '2026-04-20 22:48:30');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `options`
--
ALTER TABLE `options` 
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_key` (`option_key`);

--
-- Indices de la tabla `postmeta`
--
ALTER TABLE `postmeta` 
  ADD PRIMARY KEY (`postmeta_id`),
  ADD UNIQUE KEY `uniq_post_meta` (`post_id`, `postmeta_key`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts` 
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `post_slug` (`post_slug`),
  ADD KEY `post_author` (`post_author`);

--
-- Indices de la tabla `rolemeta`
--
ALTER TABLE `rolemeta` 
  ADD PRIMARY KEY (`rolemeta_id`),
  ADD UNIQUE KEY `uniq_role_meta` (`role_id`, `rolemeta_key`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles` 
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indices de la tabla `usermeta`
--
ALTER TABLE `usermeta` 
  ADD PRIMARY KEY (`usermeta_id`),
  ADD UNIQUE KEY `uniq_user_meta` (`user_id`, `usermeta_key`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users` 
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_login` (`user_login`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `options`
--
ALTER TABLE `options` 
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `postmeta`
--
ALTER TABLE `postmeta` 
  MODIFY `postmeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts` 
  MODIFY `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `rolemeta`
--
ALTER TABLE `rolemeta` 
  MODIFY `rolemeta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles` 
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usermeta`
--
ALTER TABLE `usermeta` 
  MODIFY `usermeta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users` 
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
