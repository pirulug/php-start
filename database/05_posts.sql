-- =========================================================
-- PHP START - UNIFIED CMS STRUCTURE (POSTS, PAGES, POLICIES)
-- =========================================================

-- =========================================================
-- TABLA: POSTS
-- =========================================================
CREATE TABLE posts (
  post_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_author INT NULL, -- Referencia al usuario que creó el contenido
  post_title VARCHAR(255) NOT NULL,
  post_slug VARCHAR(255) NOT NULL UNIQUE,
  post_content LONGTEXT NULL, -- Contenido principal
  post_excerpt TEXT NULL, -- Resumen opcional (para blog posts)
  post_type VARCHAR(50) NOT NULL DEFAULT 'post', -- post, page, policy, etc.
  post_status TINYINT NOT NULL DEFAULT 1, -- 1: Publicado, 0: Borrador
  post_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  post_updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (post_author) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLA: POSTMETA
-- =========================================================
CREATE TABLE postmeta (
  postmeta_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id BIGINT UNSIGNED NOT NULL,
  postmeta_key VARCHAR(150) NOT NULL,
  postmeta_value LONGTEXT NULL,
  UNIQUE KEY uniq_post_meta (post_id, postmeta_key),
  FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =========================================================
-- DATOS INICIALES
-- =========================================================

-- 1. MIGRACIÓN DE POLÍTICAS EXISTENTES
INSERT INTO posts (post_id, post_author, post_title, post_slug, post_content, post_type, post_status, post_created_at, post_updated_at) VALUES 
(1, 1, 'FAQs', 'faqs', '[{"q":"¿Cómo funciona?","a":"Funciona mediante el framework PHP-Start."},{"q":"¿Es seguro?","a":"Sí, utiliza PDO y estándares de seguridad."}]', 'policy', 1, '2026-04-13 10:51:11', '2026-04-13 11:13:32'),
(2, 1, 'Política de Privacidad', 'privacy-policy', 'Contenido inicial de privacidad.', 'policy', 1, '2026-04-13 10:51:11', '2026-04-13 11:05:16'),
(3, 1, 'Términos y Condiciones', 'terms-and-conditions', 'Contenido inicial de términos.', 'policy', 1, '2026-04-13 10:51:11', '2026-04-13 11:05:03');

-- 2. METADATOS DE CONFIGURACIÓN
INSERT INTO postmeta (post_id, postmeta_key, postmeta_value) VALUES 
(1, 'type', 'faq'),
(2, 'type', 'markdown'),
(3, 'type', 'markdown');

-- 3. EJEMPLO DE UNA NUEVA PÁGINA (PÁGINA ESTÁTICA)
INSERT INTO posts (post_author, post_title, post_slug, post_content, post_type) VALUES 
(1, 'Sobre Nosotros', 'about-us', 'Bienvenido a nuestra empresa...', 'page');

-- 4. EJEMPLO DE UNA ENTRADA DE BLOG (POST)
INSERT INTO posts (post_author, post_title, post_slug, post_content, post_excerpt, post_type) VALUES 
(1, 'Primer Blog Post', 'mi-primer-post', 'Este es el contenido completo del post...', 'Este es un resumen corto.', 'post');