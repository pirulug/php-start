<?php

/**
 * Acción de Visualización de Políticas.
 */

$slug = $args['slug'] ?? '';

if (empty($slug)) {
  header("Location: " . front_route());
  exit();
}

// Búsqueda segura (Post + Meta Type)
$query = "
  SELECT p.*, pm.postmeta_value as policy_type 
  FROM posts p
  LEFT JOIN postmeta pm ON p.post_id = pm.post_id AND pm.postmeta_key = 'type'
  WHERE p.post_slug = :slug AND p.post_type = 'policy' AND p.post_status = 1
";
$stmt  = $connect->prepare($query);
$v_slug = $slug;
$stmt->bindParam(':slug', $v_slug);
$stmt->execute();

$policy = $stmt->fetch(PDO::FETCH_OBJ);

// Fallback 404
if (!$policy) {
  require_once front_action('errors/404');
  ob_start();
  require_once front_view('errors/404');
  $content = ob_get_clean();
  require_once front_layout(); 
  exit();
}

// Determinar si es FAQ (Contenido JSON) mediante el tipo de política
$is_faq = ($policy->policy_type === 'faq');
$faqs   = $is_faq ? json_decode($policy->post_content, true) : [];