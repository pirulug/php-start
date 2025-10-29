-- =========================================================
-- PHP Start - BASE DE DATOS
-- =========================================================

-- =========================================================
-- TABLA: ROLES
-- Contiene los diferentes roles del sistema (Admin, Usuario, etc.)
-- =========================================================
CREATE TABLE roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL UNIQUE,       -- Ejemplo: 'Administrador'
  role_description VARCHAR(150) DEFAULT NULL   -- Descripción del rol
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (role_name, role_description) VALUES
('Administrador', 'Usuario con todos los permisos del sistema'),
('Editor', 'Usuario con permisos para editar contenido'),
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
  permission_key_name VARCHAR(100) NOT NULL UNIQUE,   -- Clave única, ej: 'users.edit'
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
(1, 1); -- Asignar permiso de dashboard al rol Administrador

-- =========================================================
-- TABLA: USERS
-- Información general y de acceso de los usuarios del sistema
-- =========================================================
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  
  -- Credenciales principales
  user_name VARCHAR(255) NOT NULL UNIQUE,     -- Nombre de usuario (login)
  user_password VARCHAR(255) NOT NULL,        -- Contraseña cifrada (hash)
  user_email VARCHAR(255) NOT NULL UNIQUE,    -- Correo electrónico único
  
  -- Datos personales
  user_nickname VARCHAR(100) DEFAULT NULL,    -- Apodo
  user_first_name VARCHAR(100) DEFAULT NULL,  -- Nombre
  user_last_name VARCHAR(100) DEFAULT NULL,   -- Apellido
  user_display_name VARCHAR(150) DEFAULT NULL,-- Nombre público mostrado
  
  -- Información estado
  user_status TINYINT NOT NULL DEFAULT 2,     -- 1 = Activo, 2 = Inactivo
  role_id INT NOT NULL DEFAULT 3,             -- Relación con roles
  
  -- Imagen de perfil
  user_image VARCHAR(255) NOT NULL DEFAULT 'default.webp',
  
  -- Metadatos adicionales
  user_activation_key VARCHAR(255) DEFAULT NULL,
  user_url VARCHAR(255) DEFAULT NULL,
  
  -- Fechas de registro y actualización
  user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_last_login DATETIME DEFAULT NULL, -- No debe actualizarse automáticamente cada vez
  
  -- Relación con roles
  FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE SET DEFAULT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


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
('site_language', 'es'),
('site_timezone', 'America/New_York'),
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
('google_recaptcha_site_key', ''),
('google_recaptcha_secret_key', ''),
('facebook', 'https://facebook.com'),
('twitter', 'https://twitter.com'),
('instagram', 'https://instagram.com'),
('youtube', 'https://youtube.com'),
('version', '1.0');
