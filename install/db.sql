-- 1. Tabla de usuarios
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL,
  user_password VARCHAR(255) NOT NULL,
  user_role INT NOT NULL DEFAULT 3,
  user_status INT NOT NULL DEFAULT 2,
  user_image VARCHAR(255) NOT NULL,
  user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_role_id) REFERENCES user_roles(user_role_id)
);

-- 2. Tabla de log de usuarios
CREATE TABLE user_logs (
  user_log_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  user_log_action VARCHAR(100) NOT NULL,
  user_log_description VARCHAR(100) NOT NULL,
  user_log_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- 3. Tabla de opciones
CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100),
  option_value TEXT NOT NULL
);

TRUNCATE TABLE options;
INSERT INTO options (option_key, option_value) VALUES
('site_name', 'Php Start'),
('site_url', 'http://php-start.test'),
('site_description', 'A simple PHP starter project'),
('site_keywords', 'php, start, project, template'),
('favicon', '{"favicon.ico":"favicon.ico"}'),
('white_logo', 'whitelogo.png'),
('dark_logo', 'darklogo.png'),
('og_image', 'og_image.png'),
('smtp_host', 'smtp.test.com'),
('smtp_email', 'no-reply@test.com'),
('smtp_password', '********'),
('smtp_port', '587'),
('smtp_encryption', 'tls'),
('facebook', 'https://facebook.com'),
('twitter', 'https://twitter.com'),
('instagram', 'https://instagram.com'),
('youtube', 'https://youtube.com');

-- 4. Tabla de visitas
CREATE TABLE visits (
  visit_id INT AUTO_INCREMENT PRIMARY KEY,
  visit_page VARCHAR(255) NOT NULL,
  visit_ip VARCHAR(45) NOT NULL,
  visit_country VARCHAR(100) NOT NULL,
  visit_browser VARCHAR(255) NOT NULL,
  visit_os VARCHAR(255) NOT NULL,
  visit_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 5. Tabla de ADS
CREATE TABLE ads (
  ad_id INT AUTO_INCREMENT PRIMARY KEY,
  ad_title VARCHAR(255) NOT NULL DEFAULT '',
  ad_subtitle VARCHAR(255) NOT NULL DEFAULT '',
  ad_content MEDIUMTEXT NOT NULL,
  ad_status TINYINT(4) NOT NULL DEFAULT 1,
  ad_position VARCHAR(255) NOT NULL DEFAULT ''
);

INSERT INTO ads (ad_id, ad_title, ad_subtitle, ad_content, ad_status, ad_position) VALUES
(1, 'Header', '(Appears on all pages right under the nav bar)', '<div><a href="#"><img src="https://dummyimage.com/200x400/bababa/ebecf5.jpg"/></a></div>', 1, 'header'),
(2, 'Footer', '(Appears on all pages right before the footer)', '<div><a href="#"><img src="https://wicombit.com/demo/banner.jpg"/></a></div>', 1, 'footer'),
(3, 'Sidebar', '(Appears on all pages right on left bar)', '<div><a href="#"><img src="https://wicombit.com/demo/sidebar.jpg"/></a></div>', 1, 'sidebar');

-- -----------------------------------------------------------------------------

INSERT INTO users (user_name, user_email, user_password, user_role_id, user_status, user_image) VALUES
('Admin', 'admin@admin.com', 'VWpZK25XUGxDS0k1MVd2bGdxbFhXZz09', 1, 1, 'admin.png');

TRUNCATE TABLE user_logs;