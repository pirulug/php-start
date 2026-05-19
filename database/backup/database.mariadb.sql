-- PHP-Start Database Backup
-- Fecha: 2026-05-15 10:41:40
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
('34', 'site_permissions', '{\"admin\":{\"access.admin\":{\"name\":\"Acceso al Panel Administrativo\",\"group\":\"Sistema\",\"desc\":\"Permiso ra\\u00edz (gatekeeper) para habilitar el acceso al panel administrativo.\"},\"account.profile\":{\"name\":\"Profile\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"account.settings\":{\"name\":\"Settings\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.captcha\":{\"name\":\"Captcha\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.sweetalert\":{\"name\":\"Sweetalert\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.gravatar\":{\"name\":\"Gravatar\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.barcode\":{\"name\":\"Barcode\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.qrcode\":{\"name\":\"Qrcode\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"components.fpdf\":{\"name\":\"Fpdf\",\"group\":\"Components\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"dashboard.dashboard\":{\"name\":\"Dashboard\",\"group\":\"Dashboard\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.list\":{\"name\":\"List\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.new\":{\"name\":\"New\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.edit\":{\"name\":\"Edit\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.delete\":{\"name\":\"Delete\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"policies.status\":{\"name\":\"Status\",\"group\":\"Policies\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.list\":{\"name\":\"List\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.new\":{\"name\":\"New\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.edit\":{\"name\":\"Edit\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"permissions.delete\":{\"name\":\"Delete\",\"group\":\"Permissions\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.list\":{\"name\":\"List\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.new\":{\"name\":\"New\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.edit\":{\"name\":\"Edit\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"roles.delete\":{\"name\":\"Delete\",\"group\":\"Roles\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.formats\":{\"name\":\"Formats\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.general\":{\"name\":\"General\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.social\":{\"name\":\"Social\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.robots\":{\"name\":\"Robots\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.sitemap\":{\"name\":\"Sitemap\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.brand\":{\"name\":\"Brand\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.info\":{\"name\":\"Info\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.backup\":{\"name\":\"Backup\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.captcha\":{\"name\":\"Captcha\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"settings.smtp\":{\"name\":\"Smtp\",\"group\":\"Settings\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.list\":{\"name\":\"List\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.new\":{\"name\":\"New\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.edit\":{\"name\":\"Edit\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.deactivate\":{\"name\":\"Deactivate\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.delete\":{\"name\":\"Delete\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.api\":{\"name\":\"Api\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"users.permissions\":{\"name\":\"Permissions\",\"group\":\"Users\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}},\"front\":{\"account.profile\":{\"name\":\"Profile\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"},\"account.edit\":{\"name\":\"Edit\",\"group\":\"Account\",\"desc\":\"Sincronizado autom\\u00e1ticamente desde el c\\u00f3digo\"}}}');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`post_id`, `post_author`, `post_title`, `post_slug`, `post_content`, `post_excerpt`, `post_type`, `post_status`, `post_created_at`, `post_updated_at`) VALUES 
('1', '1', 'FAQs', 'faqs', '[{\"q\":\"¿Cómo funciona?\",\"a\":\"Funciona mediante el framework PHP-Start.\"},{\"q\":\"¿Es seguro?\",\"a\":\"Sí, utiliza PDO y estándares de seguridad.\"}]', NULL, 'policy', '1', '2026-04-13 10:51:11', '2026-04-13 11:13:32'),
('2', '1', 'Política de Privacidad', 'privacy-policy', 'Contenido inicial de privacidad.', NULL, 'policy', '1', '2026-04-13 10:51:11', '2026-04-13 11:05:16'),
('3', '1', 'Términos y Condiciones', 'terms-and-conditions', 'Contenido inicial de términos.', NULL, 'policy', '1', '2026-04-13 10:51:11', '2026-04-13 11:05:03'),
('4', '1', 'Sobre Nosotros', 'about-us', 'Bienvenido a nuestra empresa...', NULL, 'page', '1', '2026-05-05 10:11:02', '2026-05-05 10:11:02'),
('5', '1', 'Primer Blog Post', 'mi-primer-post', 'Este es el contenido completo del post...', 'Este es un resumen corto.', 'post', '1', '2026-05-05 10:11:02', '2026-05-05 10:11:02');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `rolemeta`
--

INSERT INTO `rolemeta` (`rolemeta_id`, `role_id`, `rolemeta_key`, `rolemeta_value`) VALUES 
('1', '1', 'permissions', '[\"access.admin\", \"dashboard.dashboard\", \"account.profile\", \"account.settings\", \"analytics.summary\", \"analytics.visitors\", \"analytics.views\", \"analytics.online\", \"analytics.top\", \"analytics.mapa\", \"roles.new\", \"roles.list\", \"roles.edit\", \"roles.delete\", \"permissions.list\", \"permissions.new\", \"permissions.edit\", \"permissions.delete\", \"settings.general\", \"settings.options\", \"settings.backups\", \"settings.brand\", \"settings.captcha\", \"settings.date_time\", \"settings.info\", \"settings.robots\", \"settings.sitemap\", \"settings.smtp\", \"settings.social\", \"users.list\", \"users.new\", \"users.edit\", \"users.deactivate\", \"users.delete\", \"account.profile_front\", \"account.edit_front\"]'),
('2', '2', 'permissions', '[\"front:account.profile\",\"front:account.edit\"]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL ,
  `role_name` varchar(50) NOT NULL ,
  `role_description` varchar(150) DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `usermeta`
--

INSERT INTO `usermeta` (`usermeta_id`, `user_id`, `usermeta_key`, `usermeta_value`) VALUES 
('1', '1', 'role_id', '1'),
('2', '1', 'first_name', 'Administrador'),
('3', '1', 'last_name', ''),
('4', '1', 'second_last_name', ''),
('5', '1', 'remember_token', 'da433e767178637c7723ac3eed153c6ff23607e84c8bf4f56b1e373d225c7316'),
('6', '1', 'api_key', '{\"key\":\"b2748dac5d04d00437ef19f5b6c7f055\",\"created_at\":\"2026-04-22 00:28:06\",\"updated_at\":\"2026-04-22 00:28:06\"}'),
('7', '1', 'permissions', '[]'),
('9', '2', 'role_id', '2'),
('10', '2', 'first_name', 'Jhon'),
('11', '2', 'last_name', 'Doe'),
('12', '2', 'second_last_name', 'Plus'),
('13', '2', 'remember_token', 'b3a04ae003280d7eac6d6a7dcbb531f2137301dcf9e3c9e234fa93754f837105'),
('14', '2', 'api_key', '{\"key\":\"e38fd424356ac799959ac835b25888e0\",\"created_at\":\"2026-04-22 00:28:06\",\"updated_at\":\"2026-04-22 00:28:06\"}'),
('15', '2', 'permissions', '[\"admin:dashboard.dashboard\",\"admin:users.list\",\"admin:users.new\",\"admin:users.edit\"]'),
('36', '3', 'role_id', '1'),
('39', '4', 'role_id', '2'),
('40', '5', 'role_id', '2'),
('41', '6', 'role_id', '1'),
('42', '7', 'role_id', '1'),
('43', '8', 'role_id', '1'),
('44', '9', 'role_id', '2'),
('45', '10', 'role_id', '2'),
('46', '11', 'role_id', '1'),
('47', '12', 'role_id', '1'),
('48', '13', 'role_id', '1'),
('49', '13', 'first_name', 'Carlos'),
('50', '13', 'last_name', 'Diaz'),
('51', '13', 'second_last_name', 'Garcia'),
('52', '14', 'role_id', '2'),
('53', '14', 'first_name', 'Andres'),
('54', '14', 'last_name', 'Moreno'),
('55', '14', 'second_last_name', 'Moreno'),
('56', '15', 'role_id', '1'),
('57', '15', 'first_name', 'Maria'),
('58', '15', 'last_name', 'Perez'),
('59', '15', 'second_last_name', 'Moreno'),
('60', '16', 'role_id', '2'),
('61', '16', 'first_name', 'Lucia'),
('62', '16', 'last_name', 'Gutierrez'),
('63', '16', 'second_last_name', 'Moreno'),
('64', '17', 'role_id', '2'),
('65', '17', 'first_name', 'Elena'),
('66', '17', 'last_name', 'Perez'),
('67', '17', 'second_last_name', 'Jimenez'),
('68', '18', 'role_id', '2'),
('69', '18', 'first_name', 'Ricardo'),
('70', '18', 'last_name', 'Alvarez'),
('71', '18', 'second_last_name', 'Ruiz'),
('72', '19', 'role_id', '2'),
('73', '19', 'first_name', 'Andres'),
('74', '19', 'last_name', 'Ruiz'),
('75', '19', 'second_last_name', 'Jimenez'),
('76', '20', 'role_id', '2'),
('77', '20', 'first_name', 'Ricardo'),
('78', '20', 'last_name', 'Rodriguez'),
('79', '20', 'second_last_name', 'Gomez'),
('80', '21', 'role_id', '1'),
('81', '21', 'first_name', 'Isabel'),
('82', '21', 'last_name', 'Lopez'),
('83', '21', 'second_last_name', 'Alvarez'),
('84', '22', 'role_id', '2'),
('85', '22', 'first_name', 'Pedro'),
('86', '22', 'last_name', 'Fernandez'),
('87', '22', 'second_last_name', 'Garcia'),
('88', '23', 'role_id', '1'),
('89', '23', 'first_name', 'Jose'),
('90', '23', 'last_name', 'Sanchez'),
('91', '23', 'second_last_name', 'Alonso'),
('92', '24', 'role_id', '1'),
('93', '24', 'first_name', 'Juan'),
('94', '24', 'last_name', 'Sanchez'),
('95', '24', 'second_last_name', 'Muñoz'),
('96', '25', 'role_id', '2'),
('97', '25', 'first_name', 'Paula'),
('98', '25', 'last_name', 'Rodriguez'),
('99', '25', 'second_last_name', 'Ruiz'),
('248', '63', 'role_id', '2'),
('249', '63', 'first_name', 'Jose'),
('250', '63', 'last_name', 'Alvarez'),
('251', '63', 'second_last_name', 'Sanchez'),
('252', '64', 'role_id', '2'),
('253', '64', 'first_name', 'Isabel'),
('254', '64', 'last_name', 'Garcia'),
('255', '64', 'second_last_name', 'Gomez'),
('256', '65', 'role_id', '2'),
('257', '65', 'first_name', 'Carlos'),
('258', '65', 'last_name', 'Rodriguez'),
('259', '65', 'second_last_name', 'Jimenez'),
('260', '66', 'role_id', '2'),
('261', '66', 'first_name', 'Carmen'),
('262', '66', 'last_name', 'Hernandez'),
('263', '66', 'second_last_name', 'Ruiz'),
('264', '67', 'role_id', '1'),
('265', '67', 'first_name', 'Ricardo'),
('266', '67', 'last_name', 'Alvarez'),
('267', '67', 'second_last_name', 'Lopez'),
('268', '68', 'role_id', '2'),
('269', '68', 'first_name', 'Andres'),
('270', '68', 'last_name', 'Muñoz'),
('271', '68', 'second_last_name', 'Alonso'),
('272', '69', 'role_id', '2'),
('273', '69', 'first_name', 'Javier'),
('274', '69', 'last_name', 'Ruiz'),
('275', '69', 'second_last_name', 'Diaz'),
('276', '70', 'role_id', '1'),
('277', '70', 'first_name', 'Luis'),
('278', '70', 'last_name', 'Martinez'),
('279', '70', 'second_last_name', 'Fernandez'),
('280', '71', 'role_id', '2'),
('281', '71', 'first_name', 'Ana'),
('282', '71', 'last_name', 'Jimenez'),
('283', '71', 'second_last_name', 'Muñoz'),
('284', '72', 'role_id', '2'),
('285', '72', 'first_name', 'Isabel'),
('286', '72', 'last_name', 'Gonzalez'),
('287', '72', 'second_last_name', 'Gutierrez'),
('288', '73', 'role_id', '2'),
('289', '73', 'first_name', 'Ana'),
('290', '73', 'last_name', 'Perez'),
('291', '73', 'second_last_name', 'Lopez'),
('292', '74', 'role_id', '1'),
('293', '74', 'first_name', 'Jose'),
('294', '74', 'last_name', 'Fernandez'),
('295', '74', 'second_last_name', 'Lopez'),
('296', '75', 'role_id', '1'),
('297', '75', 'first_name', 'Pedro'),
('298', '75', 'last_name', 'Diaz'),
('299', '75', 'second_last_name', 'Alonso'),
('300', '76', 'role_id', '2'),
('301', '76', 'first_name', 'Maria'),
('302', '76', 'last_name', 'Muñoz'),
('303', '76', 'second_last_name', 'Lopez'),
('304', '77', 'role_id', '2'),
('305', '77', 'first_name', 'Marta'),
('306', '77', 'last_name', 'Alvarez'),
('307', '77', 'second_last_name', 'Gonzalez'),
('308', '78', 'role_id', '2'),
('309', '78', 'first_name', 'Lucia'),
('310', '78', 'last_name', 'Fernandez'),
('311', '78', 'second_last_name', 'Hernandez'),
('312', '79', 'role_id', '2'),
('313', '79', 'first_name', 'Isabel'),
('314', '79', 'last_name', 'Sanchez'),
('315', '79', 'second_last_name', 'Martin'),
('316', '80', 'role_id', '2'),
('317', '80', 'first_name', 'Elena'),
('318', '80', 'last_name', 'Fernandez'),
('319', '80', 'second_last_name', 'Muñoz'),
('320', '81', 'role_id', '1'),
('321', '81', 'first_name', 'Laura'),
('322', '81', 'last_name', 'Perez'),
('323', '81', 'second_last_name', 'Muñoz'),
('324', '82', 'role_id', '2'),
('325', '82', 'first_name', 'Pedro'),
('326', '82', 'last_name', 'Rodriguez'),
('327', '82', 'second_last_name', 'Muñoz'),
('328', '83', 'role_id', '2'),
('329', '83', 'first_name', 'Andres'),
('330', '83', 'last_name', 'Alonso'),
('331', '83', 'second_last_name', 'Diaz'),
('332', '84', 'role_id', '2'),
('333', '84', 'first_name', 'Isabel'),
('334', '84', 'last_name', 'Ruiz'),
('335', '84', 'second_last_name', 'Perez'),
('336', '85', 'role_id', '2'),
('337', '85', 'first_name', 'Carmen'),
('338', '85', 'last_name', 'Lopez'),
('339', '85', 'second_last_name', 'Rodriguez'),
('340', '86', 'role_id', '1'),
('341', '86', 'first_name', 'Lucia'),
('342', '86', 'last_name', 'Alvarez'),
('343', '86', 'second_last_name', 'Perez'),
('344', '87', 'role_id', '2'),
('345', '87', 'first_name', 'Javier'),
('346', '87', 'last_name', 'Rodriguez'),
('347', '87', 'second_last_name', 'Hernandez'),
('348', '88', 'role_id', '1'),
('349', '88', 'first_name', 'Sofia'),
('350', '88', 'last_name', 'Martinez'),
('351', '88', 'second_last_name', 'Alvarez'),
('352', '89', 'role_id', '1'),
('353', '89', 'first_name', 'Ana'),
('354', '89', 'last_name', 'Ruiz'),
('355', '89', 'second_last_name', 'Alonso'),
('356', '90', 'role_id', '2'),
('357', '90', 'first_name', 'Carmen'),
('358', '90', 'last_name', 'Martin'),
('359', '90', 'second_last_name', 'Moreno'),
('360', '91', 'role_id', '1'),
('361', '91', 'first_name', 'Juan'),
('362', '91', 'last_name', 'Gonzalez'),
('363', '91', 'second_last_name', 'Alvarez'),
('364', '92', 'role_id', '1'),
('365', '92', 'first_name', 'Luis'),
('366', '92', 'last_name', 'Garcia'),
('367', '92', 'second_last_name', 'Rodriguez'),
('368', '93', 'role_id', '2'),
('369', '93', 'first_name', 'Sofia'),
('370', '93', 'last_name', 'Rodriguez'),
('371', '93', 'second_last_name', 'Garcia'),
('372', '94', 'role_id', '1'),
('373', '94', 'first_name', 'Andres'),
('374', '94', 'last_name', 'Romero'),
('375', '94', 'second_last_name', 'Moreno'),
('376', '95', 'role_id', '1'),
('377', '95', 'first_name', 'Paula'),
('378', '95', 'last_name', 'Ruiz'),
('379', '95', 'second_last_name', 'Alonso'),
('380', '96', 'role_id', '1'),
('381', '96', 'first_name', 'Diego'),
('382', '96', 'last_name', 'Romero'),
('383', '96', 'second_last_name', 'Martinez'),
('384', '97', 'role_id', '2'),
('385', '97', 'first_name', 'Juan'),
('386', '97', 'last_name', 'Perez'),
('387', '97', 'second_last_name', 'Garcia'),
('388', '98', 'role_id', '1'),
('389', '98', 'first_name', 'Sofia'),
('390', '98', 'last_name', 'Sanchez'),
('391', '98', 'second_last_name', 'Ruiz'),
('392', '99', 'role_id', '1'),
('393', '99', 'first_name', 'Andres'),
('394', '99', 'last_name', 'Martinez'),
('395', '99', 'second_last_name', 'Diaz'),
('396', '100', 'role_id', '2'),
('397', '100', 'first_name', 'Andres'),
('398', '100', 'last_name', 'Sanchez'),
('399', '100', 'second_last_name', 'Gonzalez'),
('400', '101', 'role_id', '1'),
('401', '101', 'first_name', 'Jose'),
('402', '101', 'last_name', 'Alvarez'),
('403', '101', 'second_last_name', 'Gonzalez'),
('404', '102', 'role_id', '2'),
('405', '102', 'first_name', 'Juan'),
('406', '102', 'last_name', 'Jimenez'),
('407', '102', 'second_last_name', 'Perez'),
('408', '103', 'role_id', '1'),
('409', '103', 'first_name', 'Miguel'),
('410', '103', 'last_name', 'Hernandez'),
('411', '103', 'second_last_name', 'Jimenez'),
('412', '104', 'role_id', '1'),
('413', '104', 'first_name', 'Juan'),
('414', '104', 'last_name', 'Hernandez'),
('415', '104', 'second_last_name', 'Alvarez'),
('416', '105', 'role_id', '1'),
('417', '105', 'first_name', 'Maria'),
('418', '105', 'last_name', 'Ruiz'),
('419', '105', 'second_last_name', 'Romero'),
('420', '106', 'role_id', '1'),
('421', '106', 'first_name', 'Carlos'),
('422', '106', 'last_name', 'Martinez'),
('423', '106', 'second_last_name', 'Sanchez'),
('424', '107', 'role_id', '1'),
('425', '107', 'first_name', 'Laura'),
('426', '107', 'last_name', 'Gonzalez'),
('427', '107', 'second_last_name', 'Fernandez'),
('428', '108', 'role_id', '2'),
('429', '108', 'first_name', 'Diego'),
('430', '108', 'last_name', 'Muñoz'),
('431', '108', 'second_last_name', 'Romero'),
('432', '109', 'role_id', '2'),
('433', '109', 'first_name', 'Miguel'),
('434', '109', 'last_name', 'Jimenez'),
('435', '109', 'second_last_name', 'Perez'),
('436', '110', 'role_id', '2'),
('437', '110', 'first_name', 'Ana'),
('438', '110', 'last_name', 'Hernandez'),
('439', '110', 'second_last_name', 'Martin'),
('440', '111', 'role_id', '2'),
('441', '111', 'first_name', 'Jose'),
('442', '111', 'last_name', 'Diaz'),
('443', '111', 'second_last_name', 'Garcia'),
('444', '112', 'role_id', '1'),
('445', '112', 'first_name', 'Paula'),
('446', '112', 'last_name', 'Gomez'),
('447', '112', 'second_last_name', 'Ruiz'),
('448', '113', 'role_id', '2'),
('449', '113', 'first_name', 'Carmen'),
('450', '113', 'last_name', 'Lopez'),
('451', '113', 'second_last_name', 'Martinez'),
('452', '114', 'role_id', '1'),
('453', '114', 'first_name', 'Ana'),
('454', '114', 'last_name', 'Romero'),
('455', '114', 'second_last_name', 'Martin'),
('456', '115', 'role_id', '2'),
('457', '115', 'first_name', 'Lucia'),
('458', '115', 'last_name', 'Muñoz'),
('459', '115', 'second_last_name', 'Sanchez'),
('460', '116', 'role_id', '2'),
('461', '116', 'first_name', 'Javier'),
('462', '116', 'last_name', 'Moreno'),
('463', '116', 'second_last_name', 'Jimenez'),
('464', '117', 'role_id', '2'),
('465', '117', 'first_name', 'Sofia'),
('466', '117', 'last_name', 'Jimenez'),
('467', '117', 'second_last_name', 'Hernandez'),
('468', '118', 'role_id', '2'),
('469', '118', 'first_name', 'Juan'),
('470', '118', 'last_name', 'Gonzalez'),
('471', '118', 'second_last_name', 'Diaz'),
('472', '119', 'role_id', '2'),
('473', '119', 'first_name', 'Ricardo'),
('474', '119', 'last_name', 'Lopez'),
('475', '119', 'second_last_name', 'Martin'),
('476', '120', 'role_id', '2'),
('477', '120', 'first_name', 'Elena'),
('478', '120', 'last_name', 'Romero'),
('479', '120', 'second_last_name', 'Alvarez'),
('480', '121', 'role_id', '1'),
('481', '121', 'first_name', 'Maria'),
('482', '121', 'last_name', 'Lopez'),
('483', '121', 'second_last_name', 'Gonzalez'),
('484', '122', 'role_id', '1'),
('485', '122', 'first_name', 'Laura'),
('486', '122', 'last_name', 'Fernandez'),
('487', '122', 'second_last_name', 'Gomez'),
('488', '123', 'role_id', '2'),
('489', '123', 'first_name', 'Miguel'),
('490', '123', 'last_name', 'Gutierrez'),
('491', '123', 'second_last_name', 'Gutierrez'),
('492', '124', 'role_id', '2'),
('493', '124', 'first_name', 'Marta'),
('494', '124', 'last_name', 'Alonso'),
('495', '124', 'second_last_name', 'Alonso'),
('496', '125', 'role_id', '1'),
('497', '125', 'first_name', 'Andres'),
('498', '125', 'last_name', 'Gomez'),
('499', '125', 'second_last_name', 'Martin'),
('500', '126', 'role_id', '1'),
('501', '126', 'first_name', 'Juan'),
('502', '126', 'last_name', 'Gomez'),
('503', '126', 'second_last_name', 'Fernandez'),
('504', '127', 'role_id', '1'),
('505', '127', 'first_name', 'Elena'),
('506', '127', 'last_name', 'Fernandez'),
('507', '127', 'second_last_name', 'Muñoz'),
('508', '128', 'role_id', '1'),
('509', '128', 'first_name', 'Ricardo'),
('510', '128', 'last_name', 'Hernandez'),
('511', '128', 'second_last_name', 'Diaz'),
('512', '129', 'role_id', '2'),
('513', '129', 'first_name', 'Ana'),
('514', '129', 'last_name', 'Hernandez'),
('515', '129', 'second_last_name', 'Garcia'),
('516', '130', 'role_id', '2'),
('517', '130', 'first_name', 'Pedro'),
('518', '130', 'last_name', 'Hernandez'),
('519', '130', 'second_last_name', 'Sanchez'),
('520', '131', 'role_id', '1'),
('521', '131', 'first_name', 'Lucia'),
('522', '131', 'last_name', 'Gomez'),
('523', '131', 'second_last_name', 'Martinez'),
('524', '132', 'role_id', '1'),
('525', '132', 'first_name', 'Ricardo'),
('526', '132', 'last_name', 'Romero'),
('527', '132', 'second_last_name', 'Perez'),
('528', '133', 'role_id', '2'),
('529', '133', 'first_name', 'Andres'),
('530', '133', 'last_name', 'Gonzalez'),
('531', '133', 'second_last_name', 'Hernandez'),
('532', '134', 'role_id', '1'),
('533', '134', 'first_name', 'Jose'),
('534', '134', 'last_name', 'Gomez'),
('535', '134', 'second_last_name', 'Gutierrez'),
('536', '135', 'role_id', '1'),
('537', '135', 'first_name', 'Marta'),
('538', '135', 'last_name', 'Gomez'),
('539', '135', 'second_last_name', 'Lopez'),
('540', '136', 'role_id', '2'),
('541', '136', 'first_name', 'Andres'),
('542', '136', 'last_name', 'Lopez'),
('543', '136', 'second_last_name', 'Romero'),
('544', '137', 'role_id', '1'),
('545', '137', 'first_name', 'Elena'),
('546', '137', 'last_name', 'Ruiz'),
('547', '137', 'second_last_name', 'Moreno'),
('548', '138', 'role_id', '2'),
('549', '138', 'first_name', 'Pedro'),
('550', '138', 'last_name', 'Lopez'),
('551', '138', 'second_last_name', 'Rodriguez'),
('552', '139', 'role_id', '2'),
('553', '139', 'first_name', 'Paula'),
('554', '139', 'last_name', 'Perez'),
('555', '139', 'second_last_name', 'Gonzalez'),
('556', '140', 'role_id', '1'),
('557', '140', 'first_name', 'Carmen'),
('558', '140', 'last_name', 'Ruiz'),
('559', '140', 'second_last_name', 'Diaz'),
('560', '141', 'role_id', '1'),
('561', '141', 'first_name', 'Paula'),
('562', '141', 'last_name', 'Alvarez'),
('563', '141', 'second_last_name', 'Sanchez'),
('564', '142', 'role_id', '1'),
('565', '142', 'first_name', 'Juan'),
('566', '142', 'last_name', 'Diaz'),
('567', '142', 'second_last_name', 'Muñoz'),
('568', '143', 'role_id', '2'),
('569', '143', 'first_name', 'Miguel'),
('570', '143', 'last_name', 'Diaz'),
('571', '143', 'second_last_name', 'Rodriguez'),
('572', '144', 'role_id', '2'),
('573', '144', 'first_name', 'Javier'),
('574', '144', 'last_name', 'Martinez'),
('575', '144', 'second_last_name', 'Alonso'),
('576', '145', 'role_id', '2'),
('577', '145', 'first_name', 'Pedro'),
('578', '145', 'last_name', 'Hernandez'),
('579', '145', 'second_last_name', 'Garcia'),
('580', '146', 'role_id', '1'),
('581', '146', 'first_name', 'Isabel'),
('582', '146', 'last_name', 'Alvarez'),
('583', '146', 'second_last_name', 'Muñoz'),
('584', '147', 'role_id', '2'),
('585', '147', 'first_name', 'Lucia'),
('586', '147', 'last_name', 'Martinez'),
('587', '147', 'second_last_name', 'Gomez'),
('588', '148', 'role_id', '1'),
('589', '148', 'first_name', 'Lucia'),
('590', '148', 'last_name', 'Hernandez'),
('591', '148', 'second_last_name', 'Garcia'),
('592', '149', 'role_id', '2'),
('593', '149', 'first_name', 'Javier'),
('594', '149', 'last_name', 'Hernandez'),
('595', '149', 'second_last_name', 'Muñoz'),
('596', '150', 'role_id', '2'),
('597', '150', 'first_name', 'Andres'),
('598', '150', 'last_name', 'Hernandez'),
('599', '150', 'second_last_name', 'Martin'),
('600', '151', 'role_id', '2'),
('601', '151', 'first_name', 'Luis'),
('602', '151', 'last_name', 'Gomez'),
('603', '151', 'second_last_name', 'Fernandez'),
('604', '152', 'role_id', '2'),
('605', '152', 'first_name', 'Paula'),
('606', '152', 'last_name', 'Muñoz'),
('607', '152', 'second_last_name', 'Perez'),
('608', '153', 'role_id', '1'),
('609', '153', 'first_name', 'Andres'),
('610', '153', 'last_name', 'Fernandez'),
('611', '153', 'second_last_name', 'Romero'),
('612', '154', 'role_id', '2'),
('613', '154', 'first_name', 'Ricardo'),
('614', '154', 'last_name', 'Garcia'),
('615', '154', 'second_last_name', 'Ruiz'),
('616', '155', 'role_id', '1'),
('617', '155', 'first_name', 'Andres'),
('618', '155', 'last_name', 'Alonso'),
('619', '155', 'second_last_name', 'Martin'),
('620', '156', 'role_id', '1'),
('621', '156', 'first_name', 'Maria'),
('622', '156', 'last_name', 'Moreno'),
('623', '156', 'second_last_name', 'Fernandez'),
('624', '157', 'role_id', '2'),
('625', '157', 'first_name', 'Ricardo'),
('626', '157', 'last_name', 'Hernandez'),
('627', '157', 'second_last_name', 'Sanchez'),
('628', '158', 'role_id', '2'),
('629', '158', 'first_name', 'Juan'),
('630', '158', 'last_name', 'Ruiz'),
('631', '158', 'second_last_name', 'Gonzalez'),
('632', '159', 'role_id', '2'),
('633', '159', 'first_name', 'Carmen'),
('634', '159', 'last_name', 'Martin'),
('635', '159', 'second_last_name', 'Martinez'),
('636', '160', 'role_id', '2'),
('637', '160', 'first_name', 'Pedro'),
('638', '160', 'last_name', 'Hernandez'),
('639', '160', 'second_last_name', 'Sanchez'),
('640', '161', 'role_id', '2'),
('641', '161', 'first_name', 'Ricardo'),
('642', '161', 'last_name', 'Sanchez'),
('643', '161', 'second_last_name', 'Rodriguez'),
('644', '162', 'role_id', '2'),
('645', '162', 'first_name', 'Elena'),
('646', '162', 'last_name', 'Moreno'),
('647', '162', 'second_last_name', 'Sanchez'),
('650', '162', 'api_key', '{\"key\":\"66543766bf7bd708598ac4b80b5e03e5\",\"created_at\":\"2026-05-08 12:55:00\",\"updated_at\":\"2026-05-08 12:55:00\"}');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_nickname`, `user_display_name`, `user_email`, `user_status`, `user_image`, `user_created`, `user_updated`, `user_deleted`, `user_last_login`) VALUES 
('1', 'admin', '$2y$12$jaw4Tfj9sl89d3CxeyKsmOobTZooker2W/0BX.6yD2A57klOpVlwe', 'Admin', 'Admin', 'admin@gmail.com', '1', 'default.webp', '2026-04-11 22:34:01', '2026-05-15 10:38:51', NULL, '2026-05-15 10:38:51'),
('2', 'user', '$2y$12$Fa0jnV.IvF5xKval728EIeD8FPG5CNR/4KoRUeHQ41QWGyvYYOWAm', 'User', 'User', 'pirulug@gmail.com', '1', 'default.webp', '2026-04-11 22:34:01', '2026-05-08 12:13:55', NULL, '2026-05-05 10:57:52'),
('63', 'jose101', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jose101', 'Jose Alvarez', 'jose101@example.com', '1', 'default.webp', '2026-05-08 10:47:09', '2026-05-08 10:47:09', NULL, NULL),
('64', 'isabel102', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'isabel102', 'Isabel Garcia', 'isabel102@example.com', '0', 'default.webp', '2026-05-08 10:47:09', '2026-05-08 10:47:09', NULL, NULL),
('65', 'carlos103', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carlos103', 'Carlos Rodriguez', 'carlos103@example.com', '1', 'default.webp', '2026-05-08 10:47:09', '2026-05-08 10:47:09', NULL, NULL),
('66', 'carmen104', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carmen104', 'Carmen Hernandez', 'carmen104@example.com', '1', 'default.webp', '2026-05-08 10:47:09', '2026-05-08 10:47:09', NULL, NULL),
('67', 'ricardo105', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo105', 'Ricardo Alvarez', 'ricardo105@example.com', '1', 'default.webp', '2026-05-08 10:47:09', '2026-05-08 10:47:09', NULL, NULL),
('68', 'andres106', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres106', 'Andres Muñoz', 'andres106@example.com', '0', 'default.webp', '2026-05-08 10:47:09', '2026-05-08 10:47:09', NULL, NULL),
('69', 'javier107', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'javier107', 'Javier Ruiz', 'javier107@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('70', 'luis108', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'luis108', 'Luis Martinez', 'luis108@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('71', 'ana109', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana109', 'Ana Jimenez', 'ana109@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('72', 'isabel110', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'isabel110', 'Isabel Gonzalez', 'isabel110@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('73', 'ana111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana111', 'Ana Perez', 'ana111@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('74', 'jose112', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jose112', 'Jose Fernandez', 'jose112@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('75', 'pedro113', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro113', 'Pedro Diaz', 'pedro113@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('76', 'maria114', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maria114', 'Maria Muñoz', 'maria114@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('77', 'marta115', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'marta115', 'Marta Alvarez', 'marta115@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('78', 'lucia116', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lucia116', 'Lucia Fernandez', 'lucia116@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('79', 'isabel117', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'isabel117', 'Isabel Sanchez', 'isabel117@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('80', 'elena118', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'elena118', 'Elena Fernandez', 'elena118@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('81', 'laura119', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'laura119', 'Laura Perez', 'laura119@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('82', 'pedro120', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro120', 'Pedro Rodriguez', 'pedro120@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('83', 'andres121', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres121', 'Andres Alonso', 'andres121@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('84', 'isabel122', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'isabel122', 'Isabel Ruiz', 'isabel122@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('85', 'carmen123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carmen123', 'Carmen Lopez', 'carmen123@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('86', 'lucia124', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lucia124', 'Lucia Alvarez', 'lucia124@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('87', 'javier125', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'javier125', 'Javier Rodriguez', 'javier125@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('88', 'sofia126', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sofia126', 'Sofia Martinez', 'sofia126@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('89', 'ana127', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana127', 'Ana Ruiz', 'ana127@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('90', 'carmen128', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carmen128', 'Carmen Martin', 'carmen128@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('91', 'juan129', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan129', 'Juan Gonzalez', 'juan129@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('92', 'luis130', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'luis130', 'Luis Garcia', 'luis130@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('93', 'sofia131', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sofia131', 'Sofia Rodriguez', 'sofia131@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('94', 'andres132', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres132', 'Andres Romero', 'andres132@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('95', 'paula133', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'paula133', 'Paula Ruiz', 'paula133@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('96', 'diego134', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'diego134', 'Diego Romero', 'diego134@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('97', 'juan135', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan135', 'Juan Perez', 'juan135@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('98', 'sofia136', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sofia136', 'Sofia Sanchez', 'sofia136@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('99', 'andres137', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres137', 'Andres Martinez', 'andres137@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('100', 'andres138', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres138', 'Andres Sanchez', 'andres138@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('101', 'jose139', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jose139', 'Jose Alvarez', 'jose139@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('102', 'juan140', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan140', 'Juan Jimenez', 'juan140@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('103', 'miguel141', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miguel141', 'Miguel Hernandez', 'miguel141@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('104', 'juan142', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan142', 'Juan Hernandez', 'juan142@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('105', 'maria143', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maria143', 'Maria Ruiz', 'maria143@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('106', 'carlos144', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carlos144', 'Carlos Martinez', 'carlos144@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('107', 'laura145', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'laura145', 'Laura Gonzalez', 'laura145@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('108', 'diego146', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'diego146', 'Diego Muñoz', 'diego146@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('109', 'miguel147', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miguel147', 'Miguel Jimenez', 'miguel147@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('110', 'ana148', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana148', 'Ana Hernandez', 'ana148@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:51:46', NULL, NULL),
('111', 'jose149', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jose149', 'Jose Diaz', 'jose149@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('112', 'paula150', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'paula150', 'Paula Gomez', 'paula150@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('113', 'carmen151', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carmen151', 'Carmen Lopez', 'carmen151@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('114', 'ana152', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana152', 'Ana Romero', 'ana152@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('115', 'lucia153', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lucia153', 'Lucia Muñoz', 'lucia153@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('116', 'javier154', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'javier154', 'Javier Moreno', 'javier154@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('117', 'sofia155', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sofia155', 'Sofia Jimenez', 'sofia155@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('118', 'juan156', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan156', 'Juan Gonzalez', 'juan156@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('119', 'ricardo157', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo157', 'Ricardo Lopez', 'ricardo157@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('120', 'elena158', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'elena158', 'Elena Romero', 'elena158@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('121', 'maria159', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maria159', 'Maria Lopez', 'maria159@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('122', 'laura160', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'laura160', 'Laura Fernandez', 'laura160@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('123', 'miguel161', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miguel161', 'Miguel Gutierrez', 'miguel161@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('124', 'marta162', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'marta162', 'Marta Alonso', 'marta162@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('125', 'andres163', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres163', 'Andres Gomez', 'andres163@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('126', 'juan164', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan164', 'Juan Gomez', 'juan164@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('127', 'elena165', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'elena165', 'Elena Fernandez', 'elena165@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('128', 'ricardo166', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo166', 'Ricardo Hernandez', 'ricardo166@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('129', 'ana167', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana167', 'Ana Hernandez', 'ana167@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('130', 'pedro168', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro168', 'Pedro Hernandez', 'pedro168@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 12:00:50', NULL, NULL),
('131', 'lucia169', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lucia169', 'Lucia Gomez', 'lucia169@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('132', 'ricardo170', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo170', 'Ricardo Romero', 'ricardo170@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('133', 'andres171', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres171', 'Andres Gonzalez', 'andres171@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('134', 'jose172', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jose172', 'Jose Gomez', 'jose172@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('135', 'marta173', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'marta173', 'Marta Gomez', 'marta173@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('136', 'andres174', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres174', 'Andres Lopez', 'andres174@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('137', 'elena175', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'elena175', 'Elena Ruiz', 'elena175@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('138', 'pedro176', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro176', 'Pedro Lopez', 'pedro176@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('139', 'paula177', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'paula177', 'Paula Perez', 'paula177@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('140', 'carmen178', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carmen178', 'Carmen Ruiz', 'carmen178@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('141', 'paula179', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'paula179', 'Paula Alvarez', 'paula179@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('142', 'juan180', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan180', 'Juan Diaz', 'juan180@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('143', 'miguel181', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miguel181', 'Miguel Diaz', 'miguel181@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('144', 'javier182', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'javier182', 'Javier Martinez', 'javier182@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('145', 'pedro183', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro183', 'Pedro Hernandez', 'pedro183@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('146', 'isabel184', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'isabel184', 'Isabel Alvarez', 'isabel184@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('147', 'lucia185', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lucia185', 'Lucia Martinez', 'lucia185@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('148', 'lucia186', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lucia186', 'Lucia Hernandez', 'lucia186@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('149', 'javier187', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'javier187', 'Javier Hernandez', 'javier187@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('150', 'andres188', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres188', 'Andres Hernandez', 'andres188@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('151', 'luis189', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'luis189', 'Luis Gomez', 'luis189@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:52:20', NULL, NULL),
('152', 'paula190', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'paula190', 'Paula Muñoz', 'paula190@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('153', 'andres191', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres191', 'Andres Fernandez', 'andres191@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('154', 'ricardo192', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo192', 'Ricardo Garcia', 'ricardo192@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('155', 'andres193', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andres193', 'Andres Alonso', 'andres193@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:51:53', NULL, NULL),
('156', 'maria194', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maria194', 'Maria Moreno', 'maria194@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 11:45:48', NULL, NULL),
('157', 'ricardo195', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo195', 'Ricardo Hernandez', 'ricardo195@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('158', 'juan196', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan196', 'Juan Ruiz', 'juan196@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:51:51', NULL, NULL),
('159', 'carmen197', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carmen197', 'Carmen Martin', 'carmen197@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:47:10', NULL, NULL),
('160', 'pedro198', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro198', 'Pedro Hernandez', 'pedro198@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 10:51:50', NULL, NULL),
('161', 'ricardo199', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ricardo199', 'Ricardo Sanchez', 'ricardo199@example.com', '0', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 12:06:13', NULL, NULL),
('162', 'elena200', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'elena200', 'Elena Moreno', 'elena200@example.com', '1', 'default.webp', '2026-05-08 10:47:10', '2026-05-08 12:06:17', NULL, NULL);

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
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

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
  MODIFY `rolemeta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles` 
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usermeta`
--
ALTER TABLE `usermeta` 
  MODIFY `usermeta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=651;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users` 
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
