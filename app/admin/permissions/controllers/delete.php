<?php
// =============================================
// ELIMINAR PERMISO
// =============================================

// Obtener ID del permiso
$permission_id = $id;

if ($permission_id <= 0) {
  $notifier->add("No se ha especificado un permiso válido.", "warning", "sa");
  header("Location: " . SITE_URL_ADMIN . "/permissions");
  exit;
}

try {
  // Iniciar transacción
  $connect->beginTransaction();

  // =============================================
  // Verificar que el permiso exista
  // =============================================
  $stmt = $connect->prepare("SELECT * FROM permissions WHERE permission_id = :id");
  $stmt->bindParam(':id', $permission_id, PDO::PARAM_INT);
  $stmt->execute();
  $permission = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$permission) {
    $notifier->add("El permiso especificado no existe.", "danger", "sa");
    $connect->rollBack();
    exit;
  }

  // =============================================
  // Eliminar asociaciones en role_permissions
  // =============================================
  $deleteRelations = $connect->prepare("DELETE FROM role_permissions WHERE permission_id = :id");
  $deleteRelations->bindParam(':id', $permission_id, PDO::PARAM_INT);
  $deleteRelations->execute();

  // =============================================
  // Eliminar el permiso
  // =============================================
  $deletePerm = $connect->prepare("DELETE FROM permissions WHERE permission_id = :id");
  $deletePerm->bindParam(':id', $permission_id, PDO::PARAM_INT);
  $deletePerm->execute();

  $connect->commit();

  $notifier->add("Permiso «{$permission->permission_name}» eliminado correctamente.", "success", "sa");
  header("Location: " . SITE_URL_ADMIN . "/permissions");
  exit;

} catch (PDOException $e) {
  $connect->rollBack();

  // Manejar error de restricción de clave foránea
  if ($e->getCode() == 23000) {
    $notifier->add(
      "No se puede eliminar el permiso porque está asociado a uno o más roles.",
      "warning", "sa"
    );
    header("Location: " . SITE_URL_ADMIN . "/permissions");
    exit;
  } else {
    $notifier->add("Error al eliminar el permiso: " . htmlspecialchars($e->getMessage()), "danger", "sa");
    header("Location: " . SITE_URL_ADMIN . "/permissions");
    exit;
  }
}
