CREATE TABLE iplocation (
  iplo_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  iplo_ip_from DECIMAL(39,0) UNSIGNED NOT NULL,
  iplo_ip_to DECIMAL(39,0) UNSIGNED NOT NULL,
  iplo_country_code CHAR(2),
  iplo_country_name VARCHAR(64),
  iplo_region_name VARCHAR(128),
  iplo_city_name VARCHAR(128),
  iplo_latitude DECIMAL(10,6),
  iplo_longitude DECIMAL(10,6),
  iplo_zipcode VARCHAR(30),
  iplo_timezone VARCHAR(40),
  INDEX idx_ip_range (iplo_ip_from, iplo_ip_to)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
