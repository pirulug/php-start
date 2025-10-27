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

// $dt = new DataTableServer($connect);

// $dt->from('users u')
//   ->join('INNER JOIN roles r ON r.role_id = u.role_id')
//   ->columns([
//     ['db' => 'u.user_id', 'dt' => 'id'],
//     ['db' => 'u.user_name', 'dt' => 'username'],
//     ['db' => 'u.user_email', 'dt' => 'email'],
//     ['db' => 'u.user_first_name', 'dt' => 'first_name'],
//     ['db' => 'u.user_last_name', 'dt' => 'last_name'],
//     ['db' => 'r.role_name', 'dt' => 'role'],
//     ['db' => 'u.user_status', 'dt' => 'status'],
//     ['db' => 'u.user_created', 'dt' => 'created'],
//   ])
//   ->render('status', function ($val) {
//     return $val == 1
//       ? '<span class="badge bg-success">Activo</span>'
//       : '<span class="badge bg-secondary">Inactivo</span>';
//   })
//   ->render('username', function ($val, $row) {
//     return "<strong>{$val}</strong><br><small>{$row['email']}</small>";
//   })
//   ->generate();