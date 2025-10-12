-- 1. Tabla de usuarios
-- CREATE TABLE users (
--   user_id INT AUTO_INCREMENT PRIMARY KEY,
--   user_name VARCHAR(255) NOT NULL,
--   user_email VARCHAR(255) NOT NULL,
--   user_password VARCHAR(255) NOT NULL,
--   user_role INT NOT NULL DEFAULT 3,
--   user_status INT NOT NULL DEFAULT 2,
--   user_image VARCHAR(255) NOT NULL DEFAULT 'default.webp',
--   user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--   user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
-- );

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  
  -- Credenciales principales
  user_name VARCHAR(255) NOT NULL UNIQUE,
  user_password VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL UNIQUE,
  
  -- Datos personales
  user_nickname VARCHAR(100) DEFAULT NULL,
  user_first_name VARCHAR(100) DEFAULT NULL,
  user_last_name VARCHAR(100) DEFAULT NULL,
  user_display_name VARCHAR(150) DEFAULT NULL,
  
  -- Información estado
  user_status INT NOT NULL DEFAULT 2, -- 1 Activo - 2 Inactivo
  user_role INT NOT NULL DEFAULT 3, -- 1 Super Admin - 2 Admin - 3 Usuario
  
  -- Imagen
  user_image VARCHAR(255) NOT NULL DEFAULT 'default.webp',

  -- Metadatos
  user_activation_key VARCHAR(255) DEFAULT NULL,
  user_url VARCHAR(255) DEFAULT NULL,

  -- Registro
  user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- 2. Tabla de opciones
CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100),
  option_value TEXT NOT NULL
);

TRUNCATE TABLE options;
INSERT INTO options (option_key, option_value) VALUES
('site_name', 'PHP Start'),
('site_url', 'http://php-start.test'),
('site_description', 'A simple PHP starter project'),
('site_keywords', 'php, start, project, template'),
('favicon', '{"android-chrome-192x192":"android-chrome-192x192.png","android-chrome-512x512":"android-chrome-512x512.png","apple-touch-icon":"apple-touch-icon.png","favicon-16x16":"favicon-16x16.png","favicon-32x32":"favicon-32x32.png","favicon.ico":"favicon.ico","webmanifest":"site.webmanifest"}'),
('white_logo', 'st_logo_light.webp'),
('dark_logo', 'st_logo_dark.webp'),
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
