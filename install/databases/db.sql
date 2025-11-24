-- =========================================================
-- PHP Start - BASE DE DATOS
-- =========================================================

-- =========================================================
-- TABLA: ROLES
-- Contiene los diferentes roles del sistema (Admin, Usuario, etc.)
-- =========================================================
CREATE TABLE roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL UNIQUE, -- Ejemplo: 'Administrador'
  role_description VARCHAR(150) DEFAULT NULL -- Descripción del rol
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (role_name, role_description) VALUES
('Administrador', 'Usuario con todos los permisos del sistema'),
('Usuario', 'Usuario con permisos básicos de acceso');

-- =========================================================
-- TABLA: permission_groups
-- Grupos o módulos de permisos (ej: Usuarios, Configuración, Ventas)
-- =========================================================
CREATE TABLE permission_groups (
  permission_group_id INT AUTO_INCREMENT PRIMARY KEY,
  permission_group_name VARCHAR(100) NOT NULL,                -- Nombre del grupo (ej: Usuarios)
  permission_group_key_name VARCHAR(100) NOT NULL UNIQUE,     -- Clave única (ej: users)
  permission_group_description VARCHAR(150) DEFAULT NULL      -- Descripción del grupo
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permission_groups (permission_group_name, permission_group_key_name, permission_group_description) VALUES
("Sin grupo", "no_group", "Permisos sin grupo asignado");

-- =========================================================
-- TABLA: PERMISSIONS
-- Lista de permisos individuales del sistema (crear, editar, eliminar, etc.)
-- =========================================================
CREATE TABLE permissions (
  permission_id INT AUTO_INCREMENT PRIMARY KEY,
  permission_name VARCHAR(100) NOT NULL,              -- Nombre legible del permiso
  permission_key_name VARCHAR(100) NOT NULL UNIQUE,   -- Clave única, ej: 'users-edit'
  permission_description VARCHAR(150) DEFAULT NULL,    -- Descripción del permiso
  permission_group_id INT NOT NULL,
  FOREIGN KEY (permission_group_id) REFERENCES permission_groups(permission_group_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permissions (permission_name, permission_key_name, permission_description, permission_group_id) VALUES
("Dashboard", "dashboard", "", 1);

-- =========================================================
-- TABLA INTERMEDIA: ROLE_PERMISSIONS
-- Relación muchos a muchos entre roles y permisos
-- =========================================================
CREATE TABLE role_permissions (
  role_id INT NOT NULL,
  permission_id INT NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO role_permissions (role_id, permission_id) VALUES
(1, 1);

-- =========================================================
-- TABLA: USERS
-- Información general y de acceso de los usuarios del sistema
-- =========================================================
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  user_login VARCHAR(255) NULL UNIQUE,
  user_password VARCHAR(255) NULL,
  user_nickname VARCHAR(100) DEFAULT NULL,
  user_display_name VARCHAR(150) DEFAULT NULL,
  user_email VARCHAR(255) NULL UNIQUE,
  role_id INT NULL,
  user_status TINYINT NOT NULL DEFAULT 1,
  user_image VARCHAR(255) NOT NULL DEFAULT 'default.webp',
  user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_deleted DATETIME DEFAULT NULL,
  user_last_login DATETIME DEFAULT NULL,

  FOREIGN KEY (role_id) REFERENCES roles(role_id)
    ON DELETE SET NULL ON UPDATE CASCADE
);

INSERT INTO users (user_login, user_password, user_nickname, user_display_name, user_email, role_id) VALUES
('admin', 'Y2FtQ09UWGxjcmRGbm9hOHNpWDVjZz09', 'Admin', 'Admin', 'admin@gmail.com', 1),
('user', 'Y2FtQ09UWGxjcmRGbm9hOHNpWDVjZz09', 'User', "User", 'user@gmail.com', 2);

CREATE TABLE usermeta (
  usermeta_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  usermeta_key VARCHAR(150) NOT NULL,
  usermeta_value TEXT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES
(1, 'first_name', 'Administrador'),
(1, 'last_name', ''),
(1, 'second_last_name', '');

-- 2. Tabla de opciones
CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100) NULL,
  option_value TEXT NULL
);

TRUNCATE TABLE options;
INSERT INTO options (option_key, option_value) VALUES
('site_name', 'PHP Start'),
('site_url', 'http://php-start.test'),
('site_description', 'A simple PHP starter project'),
('site_keywords', 'php, start, project, template'),
('site_language', 'es'),
('site_timezone', 'America/Lima'),
('date_format', 'Y-m-d'),
('time_format', 'H:i:s'),
('datetime_format', 'Y-m-d H:i:s'),
('favicon', '{"android-chrome-192x192":"android-chrome-192x192.png","android-chrome-512x512":"android-chrome-512x512.png","apple-touch-icon":"apple-touch-icon.png","favicon-16x16":"favicon-16x16.png","favicon-32x32":"favicon-32x32.png","favicon.ico":"favicon.ico","webmanifest":"site.webmanifest"}'),
('white_logo', 'st_logo_light.webp'),
('dark_logo', 'st_logo_dark.webp'),
('og_image', 'og_image.png'),
('smtp_host', 'smtp.test.com'),
('smtp_email', 'no-reply@test.com'),
('smtp_password', '********'),
('smtp_port', '587'),
('smtp_encryption', 'tls'),
('google_recaptcha_enabled', '0'),
('google_recaptcha_site_key', '-'),
('google_recaptcha_secret_key', '-'),
('facebook', 'https://facebook.com'),
('twitter', 'https://twitter.com'),
('instagram', 'https://instagram.com'),
('youtube', 'https://youtube.com'),
('version', '1.0');
