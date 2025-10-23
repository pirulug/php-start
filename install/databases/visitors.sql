-- ============================================================
-- Tabla: anly_pages
-- ============================================================
CREATE TABLE anly_pages (
  anly_pages_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  anly_pages_uri VARCHAR(255) NOT NULL,
  anly_pages_title VARCHAR(255) DEFAULT NULL,
  anly_pages_type VARCHAR(100) DEFAULT 'page',
  anly_pages_total_views INT DEFAULT 0,
  anly_pages_unique_visitors INT DEFAULT 0,
  anly_pages_last_viewed DATETIME DEFAULT NULL,
  anly_pages_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_anly_pages_uri (anly_pages_uri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabla: anly_visitor
-- ============================================================
CREATE TABLE anly_visitor (
  anly_visitor_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  anly_visitor_ip VARCHAR(60) NOT NULL,
  anly_visitor_user_agent TEXT NOT NULL,
  anly_visitor_browser VARCHAR(100) DEFAULT NULL,
  anly_visitor_platform VARCHAR(100) DEFAULT NULL,
  anly_visitor_device VARCHAR(50) DEFAULT NULL,
  anly_visitor_country VARCHAR(100) DEFAULT NULL,
  anly_visitor_region VARCHAR(100) DEFAULT NULL,
  anly_visitor_city VARCHAR(100) DEFAULT NULL,
  anly_visitor_referer TEXT DEFAULT NULL,
  anly_visitor_first_visit DATETIME DEFAULT CURRENT_TIMESTAMP,
  anly_visitor_last_visit DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  anly_visitor_total_hits INT DEFAULT 0,
  UNIQUE KEY uniq_visitor_ip_ua (anly_visitor_ip(45), anly_visitor_user_agent(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabla: anly_useronline
-- ============================================================
CREATE TABLE anly_useronline (
  anly_useronline_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  anly_useronline_visitor_id BIGINT UNSIGNED NOT NULL,
  anly_useronline_page_id BIGINT UNSIGNED DEFAULT NULL,
  anly_useronline_ip VARCHAR(60) NOT NULL,
  anly_useronline_last_activity DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  anly_useronline_referer TEXT DEFAULT NULL,
  anly_useronline_agent VARCHAR(255) DEFAULT NULL,
  anly_useronline_platform VARCHAR(100) DEFAULT NULL,
  anly_useronline_country VARCHAR(100) DEFAULT NULL,
  FOREIGN KEY (anly_useronline_visitor_id) REFERENCES anly_visitor (anly_visitor_id) ON DELETE CASCADE,
  FOREIGN KEY (anly_useronline_page_id) REFERENCES anly_pages (anly_pages_id) ON DELETE SET NULL,
  INDEX idx_useronline_last_activity (anly_useronline_last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabla: anly_sessions
-- ============================================================
CREATE TABLE anly_sessions (
  anly_sessions_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  anly_sessions_visitor_id BIGINT UNSIGNED NOT NULL,
  anly_sessions_cookie VARCHAR(64) NOT NULL,
  anly_sessions_start_page VARCHAR(255) DEFAULT NULL,
  anly_sessions_end_page VARCHAR(255) DEFAULT NULL,
  anly_sessions_path LONGTEXT DEFAULT NULL,
  anly_sessions_start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  anly_sessions_end_time DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (anly_sessions_visitor_id) REFERENCES anly_visitor (anly_visitor_id) ON DELETE CASCADE,
  UNIQUE KEY uniq_cookie (anly_sessions_cookie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
