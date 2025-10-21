<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// --- Lectura segura de parámetros DataTables ---
$draw   = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start  = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;

// Columnas válidas
$columns = [
  'user_id',
  'user_name',
  'user_email',
  'user_first_name',
  'user_last_name',
  'user_status',
  'role_id',
  'user_created',
  'user_last_login'
];

// Orden
$orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? 'user_id';
$orderDir = ($_GET['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

// Búsqueda
$searchValue = $_GET['search']['value'] ?? '';
$searchQuery = '';
$params = [];

if (!empty($searchValue)) {
  $searchQuery = "WHERE user_name LIKE :search 
                  OR user_email LIKE :search 
                  OR user_first_name LIKE :search 
                  OR user_last_name LIKE :search";
  $params[':search'] = "%$searchValue%";
}

// --- Totales ---
$totalRecords = $connect->query("SELECT COUNT(*) FROM users")->fetchColumn();

if ($searchQuery) {
  $stmt = $connect->prepare("SELECT COUNT(*) FROM users $searchQuery");
  $stmt->execute($params);
  $totalFiltered = $stmt->fetchColumn();
} else {
  $totalFiltered = $totalRecords;
}

// --- Consulta principal ---
$sql = "
  SELECT user_id, user_name, user_email, user_first_name, user_last_name,
         user_status, role_id, user_created, user_last_login
  FROM users
  $searchQuery
  ORDER BY $orderColumn $orderDir
  LIMIT :start, :length
";

$stmt = $connect->prepare($sql);

// Asignar parámetros de búsqueda y paginación
foreach ($params as $key => $val) {
  $stmt->bindValue($key, $val, PDO::PARAM_STR);
}
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);

$stmt->execute();

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $row['user_status'] = ($row['user_status'] == 1) ? 'Activo' : 'Inactivo';
  $data[] = $row;
}

// --- Respuesta JSON ---
echo json_encode([
  'draw' => $draw,
  'recordsTotal' => $totalRecords,
  'recordsFiltered' => $totalFiltered,
  'data' => $data
]);
