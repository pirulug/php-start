<?php

// =============================================
// OBTENER ID DEL ROL
// =============================================

$rol_id = $cipher->decrypt($args['id']) ?? null;

if ($rol_id <= 0) {
  $notifier
    ->message("No se ha especificado un rol válido.")
    ->bootstrap()
    ->warning()
    ->add();
  header("Location: " . admin_route("roles"));
  exit();
}

// =============================================
// OBTENER INFORMACIÓN DEL ROL
// =============================================
$sql  = "SELECT * FROM roles WHERE role_id = :id";
$stmt = $connect->prepare($sql);
$stmt->bindParam(':id', $rol_id, PDO::PARAM_INT);
$stmt->execute();
$role = $stmt->fetch(PDO::FETCH_OBJ);

if (!$role) {
  $notifier
    ->message("El rol especificado no existe.")
    ->bootstrap()
    ->warning()
    ->add();
  exit();
}

// =============================================
// OBTENER TODOS LOS PERMISOS (Desde Options - Estructura Anidada)
// =============================================
$stmt_p = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
$stmt_p->execute();
$allPermissions = json_decode($stmt_p->fetchColumn(), true) ?: ['admin' => [], 'front' => []];

$groupedPermissions = [];
foreach ($allPermissions as $context => $perms) {
  foreach ($perms as $key => $data) {
    $group = $data['group'] ?? 'Sin Grupo';

    $groupedPermissions[$group][$context][] = (object) [
      'permission_id'           => "{$context}:{$key}",
      'permission_key_name'     => $key,
      'permission_name'         => $data['name'] ?? $key,
      'permission_context_name' => ucfirst($context),
      'permission_context_key'  => $context,
      'permission_group_name'   => $group
    ];
  }
}

// =============================================
// OBTENER PERMISOS ASIGNADOS (Desde Meta)
// =============================================
$sql  = "SELECT rolemeta_value FROM rolemeta WHERE role_id = :id AND rolemeta_key = 'permissions'";
$stmt = $connect->prepare($sql);
$stmt->bindParam(':id', $rol_id, PDO::PARAM_INT);
$stmt->execute();
$raw_permissions      = $stmt->fetchColumn();
$assigned_permissions = json_decode($raw_permissions, true) ?: [];

// =============================================
// GUARDAR CAMBIOS AL ENVIAR FORMULARIO
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $connect->beginTransaction();

    // =============================================
    // ACTUALIZAR NOMBRE Y DESCRIPCIÓN DEL ROL
    // =============================================
    $updateRole = $connect->prepare("
      UPDATE roles 
      SET role_name = :name, role_description = :desc 
      WHERE role_id = :id
  ");
    $role_name  = clear_input($_POST['role_name']);
    $role_desc  = clear_input($_POST['role_description'] ?? '');
    $updateRole->bindParam(':name', $role_name, PDO::PARAM_STR);
    $updateRole->bindParam(':desc', $role_desc, PDO::PARAM_STR);
    $updateRole->bindParam(':id', $rol_id, PDO::PARAM_INT);
    $updateRole->execute();

    // =============================================
    // ACTUALIZAR PERMISOS EN ROLEMETA (UPSERT)
    // =============================================
    $permissions_json = json_encode($_POST['permissions'] ?? []);

    $stmtMeta = $connect->prepare("
    INSERT INTO rolemeta (role_id, rolemeta_key, rolemeta_value) 
    VALUES (:role_id, 'permissions', :value)
    ON DUPLICATE KEY UPDATE rolemeta_value = VALUES(rolemeta_value)
  ");
    $stmtMeta->bindParam(':role_id', $rol_id, PDO::PARAM_INT);
    $stmtMeta->bindParam(':value', $permissions_json);
    $stmtMeta->execute();

    $connect->commit();

    $notifier
      ->message("Rol actualizado correctamente.")
      ->bootstrap()
      ->success()
      ->add();
    header("Location: " . admin_route("roles"));
    exit();

  } catch (Exception $e) {
    $connect->rollBack();
    $notifier
      ->message("Error al actualizar el rol: " . $e->getMessage())
      ->bootstrap()
      ->danger()
      ->add();
  }
}