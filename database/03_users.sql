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
  user_status TINYINT NOT NULL DEFAULT 1,
  user_image VARCHAR(255) NOT NULL DEFAULT 'default.webp',
  user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_deleted DATETIME DEFAULT NULL,
  user_last_login DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLA: USERMETA
-- =========================================================
CREATE TABLE usermeta (
  usermeta_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  usermeta_key VARCHAR(150) NOT NULL,
  usermeta_value TEXT NULL,
  UNIQUE KEY uniq_user_meta (user_id, usermeta_key),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- DATOS INICIALES
-- =========================================================

INSERT INTO users (user_id, user_login, user_password, user_nickname, user_display_name, user_email, user_status, user_image, user_created, user_updated, user_last_login) VALUES 
(1, 'admin', '$2y$12$jaw4Tfj9sl89d3CxeyKsmOobTZooker2W/0BX.6yD2A57klOpVlwe', 'Admin', 'Admin', 'admin@gmail.com', 1, 'default.webp', '2026-04-11 22:34:01', '2026-04-20 15:26:34', '2026-04-20 15:26:34'),
(2, 'user', '$2y$12$h9WAlbYEIDg2mqqRgwyFnub3OoI1eSvCcp.7mN8BkKWp8BIoWdAki', 'User', 'User', 'pirulug@gmail.com', 1, 'default.webp', '2026-04-11 22:34:01', '2026-04-20 22:48:30', '2026-04-20 22:48:30');

INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES 
-- Metadata Usuario 1 (Admin)
(1, 'role_id', '1'),
(1, 'first_name', 'Administrador'),
(1, 'last_name', ''),
(1, 'second_last_name', ''),
(1, 'remember_token', ''),
(1, 'api_key', '{"key":"b2748dac5d04d00437ef19f5b6c7f055","created_at":"2026-04-22 00:28:06","updated_at":"2026-04-22 00:28:06"}'),
-- Permisos individuales
(1, 'permissions', '[]'),
-- Loggin access
(1, 'login_access', ''),

-- Metadata Usuario 2 (User)
(2, 'role_id', '2'),
(2, 'first_name', 'Jhon'),
(2, 'last_name', 'Doe'),
(2, 'second_last_name', 'Plus'),
(2, 'remember_token', 'f1bd10c2c9a1f2c22b8f862892233dd775284955cb2802a6bdec0012f142835c'),
(2, 'api_key', '{"key":"e38fd424356ac799959ac835b25888e0","created_at":"2026-04-22 00:28:06","updated_at":"2026-04-22 00:28:06"}'),
-- Permisos individuales
(2, 'permissions', '[]'),
-- Loggin access
(2, 'login_access', '');