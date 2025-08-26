-- 1. Tabla de usuarios
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL,
  user_password VARCHAR(255) NOT NULL,
  user_role INT NOT NULL DEFAULT 3,
  user_status INT NOT NULL DEFAULT 2,
  user_image VARCHAR(255) NOT NULL DEFAULT 'default.webp',
  user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabla de opciones
CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100),
  option_value TEXT NOT NULL
);

TRUNCATE TABLE options;
INSERT INTO options (option_key, option_value) VALUES
('site_name', '-'),
('site_url', '-'),
('site_description', 'A simple PHP starter project'),
('site_keywords', 'php, start, project, template'),
('favicon', '{}'),
('white_logo', '-'),
('dark_logo', '-'),
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
