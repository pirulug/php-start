-- =========================================================
-- PHP START - UNIFIED SECURITY STRUCTURE
-- Consolidates: roles, permissions, groups, contexts
-- =========================================================

-- =========================================================
-- TABLA: ROLES
-- =========================================================
CREATE TABLE roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL UNIQUE,
  role_description VARCHAR(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLA: ROLEMETA
-- =========================================================
-- Unifica definiciones de permisos, grupos, contextos y asignaciones
CREATE TABLE rolemeta (
  rolemeta_id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NULL, -- NULL para definiciones globales de permisos
  rolemeta_key VARCHAR(150) NOT NULL,
  rolemeta_value TEXT NULL,
  UNIQUE KEY uniq_role_meta (role_id, rolemeta_key),
  FOREIGN KEY (role_id) REFERENCES roles(role_id)
  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- DATOS INICIALES
-- =========================================================

-- 1. ROLES
INSERT INTO roles (role_id, role_name, role_description) VALUES 
(1, 'Administrador', 'Usuario con acceso administrativo'),
(2, 'Usuario', 'Usuario con acceso básico');

-- 2. ASIGNACIÓN DE PERMISOS A ROLES
-- Role 1: Administrador (Todos los permisos admin + front)
INSERT INTO rolemeta (role_id, rolemeta_key, rolemeta_value) VALUES 
(1, 'permissions', '["access.admin", "dashboard.dashboard", "account.profile", "account.settings", "analytics.summary", "analytics.visitors", "analytics.views", "analytics.online", "analytics.top", "analytics.mapa", "roles.new", "roles.list", "roles.edit", "roles.delete", "permissions.list", "permissions.new", "permissions.edit", "permissions.delete", "settings.general", "settings.options", "settings.backups", "settings.brand", "settings.captcha", "settings.date_time", "settings.info", "settings.robots", "settings.sitemap", "settings.smtp", "settings.social", "users.list", "users.new", "users.edit", "users.deactivate", "users.delete", "account.profile_front", "account.edit_front"]');

-- Role 2: Usuario (Solo permisos de frontend)
INSERT INTO rolemeta (role_id, rolemeta_key, rolemeta_value) VALUES 
(2, 'permissions', '["account.profile_front", "account.edit_front"]');