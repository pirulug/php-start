<?php
// =============================================
// ELIMINAR ROL
// =============================================

// Verifica que se haya recibido un ID válido
$rol_id = $id;

if ($rol_id <= 0) {
  $notifier->add("No se ha especificado un rol válido.", "warning");
  exit;
}

try {
  $connect->beginTransaction();

  // =============================================
  // Verificar que el rol exista
  // =============================================
  $check = $connect->prepare("SELECT role_id FROM roles WHERE role_id = :id");
  $check->bindParam(':id', $rol_id, PDO::PARAM_INT);
  $check->execute();
  $role = $check->fetch(PDO::FETCH_OBJ);

  if (!$role) {
    $notifier->add("El rol especificado no existe.", "error");
    $connect->rollBack();
    exit;
  }

  // =============================================
  // Eliminar permisos asociados
  // =============================================
  $deletePerms = $connect->prepare("DELETE FROM role_permissions WHERE role_id = :id");
  $deletePerms->bindParam(':id', $rol_id, PDO::PARAM_INT);
  $deletePerms->execute();

  // =============================================
  // Eliminar el rol
  // =============================================
  $deleteRole = $connect->prepare("DELETE FROM roles WHERE role_id = :id");
  $deleteRole->bindParam(':id', $rol_id, PDO::PARAM_INT);
  $deleteRole->execute();

  $connect->commit();

  $notifier->add("Rol eliminado correctamente.", "success");
  header("Location: " . SITE_URL_ADMIN . "/roles");
  exit;

} catch (Exception $e) {
  $connect->rollBack();
  $notifier->add("Error al eliminar el rol: " . $e->getMessage(), "danger");
  header("Location: " . SITE_URL_ADMIN . "/roles");
}
