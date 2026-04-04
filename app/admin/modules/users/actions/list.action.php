<?php

// 1. Configuración de Paginación Manual
$p      = (int)($_GET['p'] ?? 1);
$limit  = 10;
$offset = ($p - 1) * $limit;
$search = trim($_GET['search'] ?? '');

// 2. Obtener Total de Filas para el Paginador
$count_query = "SELECT COUNT(*) as total FROM users u 
                LEFT JOIN roles r ON u.role_id = r.role_id 
                WHERE u.user_id != :sess_id AND u.user_id != :super_id";

if ($search) {
  $count_query .= " AND (u.user_login LIKE :search1 OR u.user_email LIKE :search2 OR r.role_name LIKE :search3)";
}

$stmt_count = $connect->prepare($count_query);

// Variables intermedias obligatorias para bindParam (Referencia)
$sess_id  = $_SESSION['user_id'];
$super_id = SUPERADMIN_ID[0];

$stmt_count->bindParam(':sess_id', $sess_id, PDO::PARAM_INT);
$stmt_count->bindParam(':super_id', $super_id, PDO::PARAM_INT);

if ($search) {
  $search_param = "%$search%";
  $stmt_count->bindParam(':search1', $search_param, PDO::PARAM_STR);
  $stmt_count->bindParam(':search2', $search_param, PDO::PARAM_STR);
  $stmt_count->bindParam(':search3', $search_param, PDO::PARAM_STR);
}

$stmt_count->execute();
$total_rows = $stmt_count->fetch(PDO::FETCH_OBJ)->total;
$total_pages = (int)ceil($total_rows / $limit);

// 3. Obtener Datos Reales
$query = "SELECT u.user_id, u.user_image, u.user_login, u.user_email, r.role_name, u.user_status, u.user_created 
          FROM users u 
          LEFT JOIN roles r ON u.role_id = r.role_id 
          WHERE u.user_id != :sess_id AND u.user_id != :super_id";

if ($search) {
  $query .= " AND (u.user_login LIKE :search1 OR u.user_email LIKE :search2 OR r.role_name LIKE :search3)";
}

$query .= " ORDER BY u.user_id DESC LIMIT :limit OFFSET :offset";

$stmt = $connect->prepare($query);

// Variables intermedias para los límites
$stmt->bindParam(':sess_id', $sess_id, PDO::PARAM_INT);
$stmt->bindParam(':super_id', $super_id, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

if ($search) {
  $search_param = "%$search%";
  $stmt->bindParam(':search1', $search_param, PDO::PARAM_STR);
  $stmt->bindParam(':search2', $search_param, PDO::PARAM_STR);
  $stmt->bindParam(':search3', $search_param, PDO::PARAM_STR);
}

$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);