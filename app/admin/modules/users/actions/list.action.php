<?php

// 1. Configuración de Paginación Manual
$p        = (int) ($_GET['p'] ?? 1);
$limit    = 10;
$offset   = ($p - 1) * $limit;
$search   = trim($_GET['search'] ?? '');
$role_f   = $_GET['role'] ?? '';
$status_f = $_GET['status'] ?? '';

// 2. Obtener Roles para el filtro
$roles = $connect->query("SELECT role_id, role_name FROM roles ORDER BY role_name ASC")->fetchAll(PDO::FETCH_OBJ);

// 3. Construcción de Filtros SQL
$filters = " WHERE u.user_id != :sess_id AND u.user_id != :super_id";
$params  = [
  ':sess_id'  => $_SESSION['user_id'],
  ':super_id' => SUPERADMIN_ID[0]
];

if ($search) {
  $filters            .= " AND (u.user_login LIKE :search1 OR u.user_email LIKE :search2 OR r.role_name LIKE :search3)";
  $params[':search1']  = "%$search%";
  $params[':search2']  = "%$search%";
  $params[':search3']  = "%$search%";
}

if ($role_f !== '') {
  $filters         .= " AND um.usermeta_value = :role";
  $params[':role']  = $role_f;
}

if ($status_f !== '') {
  $filters           .= " AND u.user_status = :status";
  $params[':status']  = $status_f;
}

// 4. Obtener Total de Filas para el Paginador
$count_query = "SELECT COUNT(*) as total FROM users u 
        LEFT JOIN usermeta um ON um.user_id = u.user_id AND um.usermeta_key = 'role_id'
        LEFT JOIN roles r ON um.usermeta_value = r.role_id" . $filters;

$stmt_count = $connect->prepare($count_query);

// Variables intermedias para bindParam
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

if ($role_f !== '') {
  $stmt_count->bindParam(':role', $role_f);
}

if ($status_f !== '') {
  $stmt_count->bindParam(':status', $status_f);
}

$stmt_count->execute();
$total_rows  = $stmt_count->fetch(PDO::FETCH_OBJ)->total;
$total_pages = (int) ceil($total_rows / $limit);

// 5. Obtener Datos Reales
$query = "SELECT u.user_id, u.user_image, u.user_login, u.user_email, r.role_name, u.user_status, u.user_created 
          FROM users u 
          LEFT JOIN usermeta um ON um.user_id = u.user_id AND um.usermeta_key = 'role_id'
          LEFT JOIN roles r ON um.usermeta_value = r.role_id" . $filters;

$query .= " ORDER BY u.user_id DESC LIMIT :limit OFFSET :offset";

$stmt = $connect->prepare($query);

$stmt->bindParam(':sess_id', $sess_id, PDO::PARAM_INT);
$stmt->bindParam(':super_id', $super_id, PDO::PARAM_INT);

if ($search) {
  $search_param = "%$search%";
  $stmt->bindParam(':search1', $search_param, PDO::PARAM_STR);
  $stmt->bindParam(':search2', $search_param, PDO::PARAM_STR);
  $stmt->bindParam(':search3', $search_param, PDO::PARAM_STR);
}

if ($role_f !== '') {
  $stmt->bindParam(':role', $role_f);
}

if ($status_f !== '') {
  $stmt->bindParam(':status', $status_f);
}

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);
