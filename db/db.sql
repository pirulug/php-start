-- =========================================================
-- PHP START - BASE DE DATOS
-- =========================================================

-- =========================================================
-- TABLA: ROLES
-- =========================================================
CREATE TABLE roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL UNIQUE,
  role_description VARCHAR(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (role_name, role_description) VALUES
('Administrador', 'Usuario con acceso administrativo'),
('Usuario', 'Usuario con acceso básico');

-- =========================================================
-- TABLA: PERMISSION CONTEXTS
-- =========================================================
CREATE TABLE permission_contexts (
  permission_context_id INT AUTO_INCREMENT PRIMARY KEY,
  permission_context_key VARCHAR(50) NOT NULL UNIQUE,
  permission_context_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permission_contexts (permission_context_key, permission_context_name) VALUES
('admin', 'Panel administrativo'),
('front', 'Frontend'),
('api', 'API'),
('ajax', 'Peticiones AJAX');

-- =========================================================
-- TABLA: PERMISSION GROUPS
-- =========================================================
CREATE TABLE permission_groups (
  permission_group_id INT AUTO_INCREMENT PRIMARY KEY,
  permission_group_name VARCHAR(100) NOT NULL,
  permission_group_key_name VARCHAR(100) NOT NULL UNIQUE,
  permission_group_description VARCHAR(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permission_groups (permission_group_name, permission_group_key_name, permission_group_description) VALUES
('Sistema', 'system', 'Permisos del sistema');

-- =========================================================
-- TABLA: PERMISSIONS
-- =========================================================
CREATE TABLE permissions (
  permission_id INT AUTO_INCREMENT PRIMARY KEY,
  permission_name VARCHAR(100) NOT NULL,
  permission_key_name VARCHAR(100) NOT NULL,
  permission_description VARCHAR(150) DEFAULT NULL,
  permission_group_id INT NOT NULL,
  permission_context_id INT NOT NULL,
  UNIQUE(permission_key_name, permission_context_id),
  FOREIGN KEY (permission_group_id) REFERENCES permission_groups(permission_group_id),
  FOREIGN KEY (permission_context_id) REFERENCES permission_contexts(permission_context_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permissions (
  permission_name,
  permission_key_name,
  permission_description,
  permission_group_id,
  permission_context_id
) VALUES
('Acceso Dashboard', 'dashboard.view', 'Acceso al dashboard', 1, 1);

-- =========================================================
-- TABLA: ROLE PERMISSIONS
-- =========================================================
CREATE TABLE role_permissions (
  role_id INT NOT NULL,
  permission_id INT NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES roles(role_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(permission_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO role_permissions (role_id, permission_id) VALUES
(1, 1);

-- =========================================================
-- TABLA: USERS
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (
  user_login,
  user_password,
  user_nickname,
  user_display_name,
  user_email,
  role_id
) VALUES
('admin', 'Y2FtQ09UWGxjcmRGbm9hOHNpWDVjZz09', 'Admin', 'Admin', 'admin@gmail.com', 1),
('user', 'Y2FtQ09UWGxjcmRGbm9hOHNpWDVjZz09', 'User', 'User', 'user@gmail.com', 2);

-- =========================================================
-- TABLA: USERMETA
-- =========================================================
CREATE TABLE usermeta (
  usermeta_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  usermeta_key VARCHAR(150) NOT NULL,
  usermeta_value TEXT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES
(1, 'first_name', 'Administrador'),
(1, 'last_name', ''),
(1, 'second_last_name', '');

-- =========================================================
-- TABLA: USER ACCESS
-- =========================================================
CREATE TABLE user_access (
  access_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  access_ip VARCHAR(45) NOT NULL,

  access_attempts INT NOT NULL DEFAULT 0,
  access_last_attempt DATETIME NOT NULL,
  access_blocked_until DATETIME DEFAULT NULL,

  UNIQUE KEY uniq_user_ip (user_id, access_ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLA: OPTIONS
-- =========================================================
CREATE TABLE options (
  option_id INT AUTO_INCREMENT PRIMARY KEY,
  option_key VARCHAR(100) NULL,
  option_value TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO options (option_key, option_value) VALUES
('site_name', 'PHP Start'),
('site_language', 'es'),
('site_timezone', 'America/Lima'),
('version', '1.0');
