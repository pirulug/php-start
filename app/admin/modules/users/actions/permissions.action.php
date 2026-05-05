<?php

$id = $args['id'] ?? null;

// Verificar ID
if (!isset($id) || $id == "") {
  $notifier
    ->message("Tienes que tener un id.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

$id = $cipher->decrypt($id);

// Si no es un numero
if (!is_numeric($id)) {
  $notifier
    ->message("El id no encontrado.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Obtener los datos del usuario
$query = "SELECT * FROM users WHERE user_id = :id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Si no encuentra el usuario
if (empty($user)) {
  $notifier
    ->message("Usuario no encontrado.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Obtener permisos actuales del usuario (desde usermeta - personalizados)
$stmt_meta = $connect->prepare("SELECT usermeta_value FROM usermeta WHERE user_id = :id AND usermeta_key = 'permissions'");
$stmt_meta->execute([':id' => $id]);
$userPermissions = json_decode($stmt_meta->fetchColumn(), true) ?: [];

// Obtener el role_id del usuario para identificar permisos heredados
$stmt_role = $connect->prepare("SELECT usermeta_value FROM usermeta WHERE user_id = :id AND usermeta_key = 'role_id'");
$stmt_role->execute([':id' => $id]);
$role_id = $stmt_role->fetchColumn();

// Obtener nombre del rol
if ($role_id) {
  $stmt_rn = $connect->prepare("SELECT role_name FROM roles WHERE role_id = :rid");
  $stmt_rn->execute([':rid' => $role_id]);
  $user->role_name = $stmt_rn->fetchColumn();
}

// Obtener permisos del rol
$rolePermissions = [];
if ($role_id) {
  $stmt_rp = $connect->prepare("SELECT rolemeta_value FROM rolemeta WHERE role_id = :rid AND rolemeta_key = 'permissions'");
  $stmt_rp->execute([':rid' => $role_id]);
  $rolePermissions = json_decode($stmt_rp->fetchColumn(), true) ?: [];
}

// Obtener todos los permisos disponibles (desde options)
$stmt_p = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
$stmt_p->execute();
$allPermissionsData = json_decode($stmt_p->fetchColumn(), true) ?: ['admin' => [], 'front' => []];

$groupedPermissions = [];
foreach ($allPermissionsData as $context => $permissions) {
  foreach ($permissions as $key => $data) {
    $group                                  = $data['group'] ?? 'otros';
    $groupedPermissions[$group][$context][] = (object) [
      'key'     => "{$context}:{$key}",
      'name'    => $data['name'] ?? $key,
      'context' => $context
    ];
  }
}
ksort($groupedPermissions);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $selected_permissions = $_POST['permissions'] ?? [];

  // Guardar en usermeta
  $stmt_save = $connect->prepare("
        INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) 
        VALUES (:uid, 'permissions', :val)
        ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)
    ");

  $json_val = json_encode($selected_permissions);

  if ($stmt_save->execute([':uid' => $id, ':val' => $json_val])) {
    $notifier
      ->message("Permisos actualizados correctamente para " . $user->user_login)
      ->bootstrap()
      ->success()
      ->add();
    header("Location: " . admin_route("user/permissions", [$cipher->encrypt($id)]));
    exit();
  } else {
    $notifier
      ->message("Error al actualizar los permisos.")
      ->bootstrap()
      ->danger()
      ->add();
  }
}
