<?php

/**
 * Listado de Políticas.
 * Proporciona el acceso centralizado a los documentos legales.
 */

// 1. Configuración de Paginación
$p      = (int) ($_GET['p'] ?? 1);
$limit  = 10;
$offset = ($p - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$status_f = $_GET['status'] ?? '';

// 2. Construcción de Filtros SQL
$filters = " WHERE p.post_type = 'policy'";

if ($search) {
  $filters .= " AND (p.post_title LIKE :search1 OR p.post_slug LIKE :search2)";
}

if ($status_f !== '') {
  $filters .= " AND p.post_status = :status";
}

// 3. Obtener Total de Filas
$count_query = "SELECT COUNT(*) as total FROM posts p" . $filters;
$stmt_count = $connect->prepare($count_query);

if ($search) {
  $search_param = "%$search%";
  $stmt_count->bindParam(':search1', $search_param, PDO::PARAM_STR);
  $stmt_count->bindParam(':search2', $search_param, PDO::PARAM_STR);
}

if ($status_f !== '') {
  $stmt_count->bindParam(':status', $status_f);
}

$stmt_count->execute();
$total_rows  = $stmt_count->fetch(PDO::FETCH_OBJ)->total;
$total_pages = (int) ceil($total_rows / $limit);

// 4. Obtener Datos Reales
$query = "
  SELECT p.*, pm.postmeta_value as policy_type 
  FROM posts p
  LEFT JOIN postmeta pm ON p.post_id = pm.post_id AND pm.postmeta_key = 'type'
" . $filters . "
  ORDER BY p.post_created_at DESC
  LIMIT :limit OFFSET :offset
";

$stmt = $connect->prepare($query);

if ($search) {
  $search_param = "%$search%";
  $stmt->bindParam(':search1', $search_param, PDO::PARAM_STR);
  $stmt->bindParam(':search2', $search_param, PDO::PARAM_STR);
}

if ($status_f !== '') {
  $stmt->bindParam(':status', $status_f);
}

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$policies = $stmt->fetchAll(PDO::FETCH_OBJ);

// Slugs protegidos que no se pueden eliminar
$protected_slugs = ['faqs', 'privacy-policy', 'terms-and-conditions'];