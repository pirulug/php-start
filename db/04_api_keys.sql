-- =========================================================
-- TABLA: USER API KEYS
-- =========================================================
CREATE TABLE user_api_keys (
  api_key_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  api_key VARCHAR(64) NOT NULL UNIQUE,
  api_key_status TINYINT NOT NULL DEFAULT 1,
  api_key_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  api_key_last_used DATETIME DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
