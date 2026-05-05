-- =========================================================
-- TABLA: VISITORS
-- =========================================================
CREATE TABLE visitors (
  visitor_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_user_id INT DEFAULT NULL,
  visitor_hash VARCHAR(16) NOT NULL UNIQUE,
  visitor_ip VARCHAR(60) NOT NULL,
  visitor_user_agent VARCHAR(512) NOT NULL,
  visitor_browser VARCHAR(100) DEFAULT NULL,
  visitor_platform VARCHAR(100) DEFAULT NULL,
  visitor_os_version VARCHAR(50) DEFAULT NULL,
  visitor_device VARCHAR(50) DEFAULT 'Desktop',
  visitor_device_brand VARCHAR(100) DEFAULT NULL,
  visitor_device_model VARCHAR(100) DEFAULT NULL,
  visitor_is_bot TINYINT(1) DEFAULT 0,
  visitor_country VARCHAR(100) DEFAULT NULL,
  visitor_region VARCHAR(100) DEFAULT NULL,
  visitor_city VARCHAR(100) DEFAULT NULL,
  visitor_referer VARCHAR(512) DEFAULT NULL,
  visitor_traffic_source ENUM('direct', 'search', 'social', 'referral') DEFAULT 'direct',
  visitor_first_visit DATETIME DEFAULT CURRENT_TIMESTAMP,
  visitor_last_visit DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  visitor_total_hits INT DEFAULT 0,
  UNIQUE KEY uniq_visitor_ip_ua (visitor_ip, visitor_user_agent(255)),
  INDEX idx_visitor_hash (visitor_hash),
  INDEX idx_visitor_ip (visitor_ip),
  INDEX idx_visitor_country (visitor_country),
  CONSTRAINT fk_visitor_user FOREIGN KEY (visitor_user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLA: VISITORMETA
-- =========================================================
CREATE TABLE visitormeta (
  visitormeta_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_id BIGINT UNSIGNED NULL,
  visitormeta_key VARCHAR(150) NOT NULL,
  visitormeta_value TEXT NULL,
  UNIQUE KEY uniq_visitor_meta (visitor_id, visitormeta_key),
  FOREIGN KEY (visitor_id) REFERENCES visitors(visitor_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- DATOS INICIALES
-- =========================================================

-- 1. VISITANTES
INSERT INTO visitors (visitor_id, visitor_user_id, visitor_hash, visitor_ip, visitor_user_agent, visitor_browser, visitor_platform, visitor_os_version, visitor_device, visitor_device_brand, visitor_device_model, visitor_is_bot, visitor_country, visitor_region, visitor_city, visitor_referer, visitor_traffic_source, visitor_first_visit, visitor_last_visit, visitor_total_hits) VALUES 
(1, 2, '64853b65', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Chrome', 'Windows', 'Windows 10/11', 'Desktop', 'Unknown', 'Unknown', 0, 'LC', 'Local', 'Localhost', 'http://php-start.test/account/profile', 'referral', '2026-04-19 22:06:52', '2026-04-20 22:48:30', 74);

-- 2. METADATOS DE PÁGINAS (ESTADÍSTICAS GLOBALES)
INSERT INTO visitormeta (visitor_id, visitormeta_key, visitormeta_value) VALUES 
(NULL, 'page_stats:/', '{"views": 24, "unique": 20, "last": "2026-04-20 22:48:30"}'),
(NULL, 'page_stats:/signin', '{"views": 21, "unique": 7, "last": "2026-04-20 22:29:31"}'),
(NULL, 'page_stats:/signup', '{"views": 3, "unique": 3, "last": "2026-04-20 15:28:25"}'),
(NULL, 'page_stats:/account/profile', '{"views": 26, "unique": 26, "last": "2026-04-20 22:48:01"}');

-- 3. SESIONES Y ESTADO VIVO
INSERT INTO visitormeta (visitor_id, visitormeta_key, visitormeta_value) VALUES 
-- Sesión activa del visitante 1
(1, 'session:ce99808f5d743140b25a53a6d68a45cb', '{"start_time": "2026-04-20 15:39:35", "last_time": "2026-04-20 22:48:30", "start_page": "/signin", "end_page": "/", "path_count": 17}'),
-- Estado Online
(1, 'online_presence', '{"last_activity": "2026-04-20 22:48:30", "current_uri": "/account/profile", "ip": "127.0.0.1"}');