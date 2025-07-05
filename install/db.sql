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


CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100),
  option_value TEXT NOT NULL
);

INSERT INTO options (option_key, option_value) VALUES
('site_name', 'Php Start'),
('site_url', 'http://php-start.test'),
('site_url_admin', 'http://php-start.test/admin'),
('base_dir', '/ruta/absoluta/a/tu/proyecto'),
('base_dir_admin', '/ruta/absoluta/a/tu/proyecto/admin'),
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
('twitter', 'https://twitter.com');

-- Roles de usuario
CREATE TABLE user_roles (
  user_role_id INT PRIMARY KEY,
  user_role_name VARCHAR(50) NOT NULL
);

CREATE TABLE users (
  user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL,
  user_password VARCHAR(255) NOT NULL,
  user_role_id INT NOT NULL,
  user_status INT NOT NULL DEFAULT 1,
  user_image VARCHAR(255) NOT NULL,
  user_updated datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  user_created datetime NOT NULL DEFAULT current_timestamp(),
);

CREATE TABLE user_logs (
  user_log_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(100) NOT NULL,
  user_log_action VARCHAR(100) NOT NULL,
  user_log_description VARCHAR(100) NOT NULL,
  user_log_timestamp timestamp NOT NULL DEFAULT current_timestamp(),
);

CREATE TABLE visits (
  visit_id INT AUTO_INCREMENT PRIMARY KEY,
  visit_page VARCHAR(255) NOT NULL,
  visit_date DATE NOT NULL,
);

CREATE TABLE visit_ips (
  visit_ip_id INT AUTO_INCREMENT PRIMARY KEY,
  visit_id INT NOT NULL,
  visit_ip_address VARCHAR(45) NOT NULL,
  visit_ip_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (visit_id) REFERENCES visits (id) ON DELETE CASCADE
);

-- -----------------------------------------------------------------------------