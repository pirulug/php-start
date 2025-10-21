<?php

// =============================================
// OBTENER ID DEL ROL
// =============================================

$rol_id = $id;

if ($rol_id <= 0) {
  $notifier->add("No se ha especificado un rol válido.", "warning");
  exit;
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
  $notifier->add("El rol especificado no existe.", "error");
  exit;
}

// =============================================
// OBTENER TODOS LOS PERMISOS
// =============================================
$sql  = "
SELECT 
  pg.permission_group_id,
  pg.permission_group_name,
  p.permission_id,
  p.permission_name,
  p.permission_key_name
FROM permissions p
INNER JOIN permission_groups pg ON p.permission_group_id = pg.permission_group_id
ORDER BY pg.permission_group_name, p.permission_name
";
$stmt = $connect->prepare($sql);
$stmt->execute();
$permissions = $stmt->fetchAll(PDO::FETCH_OBJ);

$groupedPermissions = [];

foreach ($permissions as $perm) {
  $groupedPermissions[$perm->permission_group_name][] = $perm;
}


// =============================================
// OBTENER PERMISOS ASIGNADOS
// =============================================
$sql  = "SELECT permission_id FROM role_permissions WHERE role_id = :id";
$stmt = $connect->prepare($sql);
$stmt->bindParam(':id', $rol_id, PDO::PARAM_INT);
$stmt->execute();
$assigned_permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

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
    $updateRole->bindParam(':name', $_POST['role_name'], PDO::PARAM_STR);
    $updateRole->bindParam(':desc', $_POST['role_description'], PDO::PARAM_STR);
    $updateRole->bindParam(':id', $rol_id, PDO::PARAM_INT);
    $updateRole->execute();

    // =============================================
    // ELIMINAR PERMISOS ANTIGUOS
    // =============================================
    $delete = $connect->prepare("DELETE FROM role_permissions WHERE role_id = :id");
    $delete->bindParam(':id', $rol_id, PDO::PARAM_INT);
    $delete->execute();

    // =============================================
    // INSERTAR NUEVOS PERMISOS
    // =============================================
    if (!empty($_POST['permissions']) && is_array($_POST['permissions'])) {
      $insert = $connect->prepare("
        INSERT INTO role_permissions (role_id, permission_id) 
        VALUES (:role, :perm)
      ");
      foreach ($_POST['permissions'] as $perm_id) {
        $insert->bindParam(':role', $rol_id, PDO::PARAM_INT);
        $insert->bindParam(':perm', $perm_id, PDO::PARAM_INT);
        $insert->execute();
      }
    }

    $connect->commit();

    $notifier->add("Rol actualizado correctamente.", "success");
    header("Location: " . SITE_URL_ADMIN . "/roles");
    exit();

  } catch (Exception $e) {
    $connect->rollBack();
    $notifier->add("Error al actualizar el rol: " . $e->getMessage(), "error");
  }
}