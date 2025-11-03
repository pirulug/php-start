-- ============================================================
-- Tabla: visitor_pages
-- ============================================================
CREATE TABLE visitor_pages (
  visitor_pages_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_pages_uri VARCHAR(255) NOT NULL,
  visitor_pages_title VARCHAR(255) DEFAULT NULL,
  visitor_pages_type VARCHAR(100) DEFAULT 'page',
  visitor_pages_total_views INT DEFAULT 0,
  visitor_pages_unique_visitors INT DEFAULT 0,
  visitor_pages_last_viewed DATETIME DEFAULT NULL,
  visitor_pages_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_visitor_pages_uri (visitor_pages_uri),
  INDEX idx_visitor_pages_type (visitor_pages_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabla: visitors
-- ============================================================
CREATE TABLE visitors (
  visitor_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_ip VARCHAR(60) NOT NULL,
  visitor_user_agent VARCHAR(512) NOT NULL,
  visitor_browser VARCHAR(100) DEFAULT NULL,
  visitor_platform VARCHAR(100) DEFAULT NULL,
  visitor_device VARCHAR(50) DEFAULT NULL,
  visitor_country VARCHAR(100) DEFAULT NULL,
  visitor_region VARCHAR(100) DEFAULT NULL,
  visitor_city VARCHAR(100) DEFAULT NULL,
  visitor_referer VARCHAR(512) DEFAULT NULL,
  visitor_first_visit DATETIME DEFAULT CURRENT_TIMESTAMP,
  visitor_last_visit DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  visitor_total_hits INT DEFAULT 0,
  UNIQUE KEY uniq_visitor_ip_ua (visitor_ip, visitor_user_agent(255)),
  INDEX idx_visitor_country (visitor_country)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabla: visitor_useronline
-- ============================================================
CREATE TABLE visitor_useronline (
  visitor_useronline_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_useronline_visitor_id BIGINT UNSIGNED NOT NULL,
  visitor_useronline_page_id BIGINT UNSIGNED DEFAULT NULL,
  visitor_useronline_ip VARCHAR(60) NOT NULL,
  visitor_useronline_last_activity DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  visitor_useronline_referer VARCHAR(512) DEFAULT NULL,
  visitor_useronline_agent VARCHAR(255) DEFAULT NULL,
  visitor_useronline_platform VARCHAR(100) DEFAULT NULL,
  visitor_useronline_country VARCHAR(100) DEFAULT NULL,
  FOREIGN KEY (visitor_useronline_visitor_id) REFERENCES visitors (visitor_id) ON DELETE CASCADE,
  FOREIGN KEY (visitor_useronline_page_id) REFERENCES visitor_pages (visitor_pages_id) ON DELETE SET NULL,
  INDEX idx_useronline_last_activity (visitor_useronline_last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE visitor_useronline
ADD UNIQUE KEY uniq_useronline_ip_visitor (visitor_useronline_ip, visitor_useronline_visitor_id);

-- ============================================================
-- Tabla: visitor_sessions
-- ============================================================
CREATE TABLE visitor_sessions (
  visitor_sessions_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_sessions_visitor_id BIGINT UNSIGNED NOT NULL,
  visitor_sessions_cookie VARCHAR(64) NOT NULL,
  visitor_sessions_start_page VARCHAR(255) DEFAULT NULL,
  visitor_sessions_end_page VARCHAR(255) DEFAULT NULL,
  visitor_sessions_path TEXT DEFAULT NULL,
  visitor_sessions_start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  visitor_sessions_end_time DATETIME DEFAULT NULL,
  FOREIGN KEY (visitor_sessions_visitor_id) REFERENCES visitors (visitor_id) ON DELETE CASCADE,
  UNIQUE KEY uniq_cookie (visitor_sessions_cookie),
  INDEX idx_sessions_start_time (visitor_sessions_start_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
