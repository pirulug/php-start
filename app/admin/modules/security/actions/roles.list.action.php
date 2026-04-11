<?php

$limit = 10;
$p = (int)($_GET['p'] ?? 1);
if ($p < 1) $p = 1;

$search = trim($_GET['search'] ?? '');

$where_clause = "";
$params = [];

if ($search !== '') {
  $where_clause = "WHERE role_name LIKE :search OR role_description LIKE :search";
  $params[':search'] = "%$search%";
}

// 1. COUNT
$stmt_count = $connect->prepare("SELECT COUNT(*) FROM roles $where_clause");
foreach ($params as $key => $val) {
  $stmt_count->bindValue($key, $val, PDO::PARAM_STR);
}
$stmt_count->execute();
$total_rows = $stmt_count->fetchColumn();

// 2. Pagination calculation
$total_pages = ceil($total_rows / $limit);
if ($total_pages > 0 && $p > $total_pages) {
  $p = $total_pages;
}
$offset = ($p - 1) * $limit;

// 3. Select Data
$sql = "SELECT role_id, role_name, role_description FROM roles $where_clause ORDER BY role_id DESC LIMIT :limit OFFSET :offset";
$stmt = $connect->prepare($sql);

foreach ($params as $key => $val) {
  $stmt->bindValue($key, $val, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$roles = $stmt->fetchAll(PDO::FETCH_OBJ);