<?php
// =============================================
// ELIMINAR PERMISO
// =============================================

// Obtener ID del permiso
$permission_id = $_GET['id'] ?? null;

if ($permission_id <= 0) {
  $notifier
    ->message("No se ha especificado un permiso válido.")
    ->bootstrap()
    ->warning()
    ->add();
  header("Location: " . admin_route("permissions"));
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
    $notifier
      ->message("El permiso especificado no existe.")
      ->bootstrap()
      ->danger()
      ->add();
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

  $notifier
    ->message("Permiso «" . htmlspecialchars($permission->permission_name) . "» eliminado correctamente.")
    ->bootstrap()
    ->success()
    ->add();
  header("Location: " . admin_route("permissions"));
  exit;

} catch (PDOException $e) {
  $connect->rollBack();

  // Manejar error de restricción de clave foránea
  if ($e->getCode() == 23000) {
    $notifier
      ->message("No se puede eliminar el permiso porque está asociado a uno o más roles.")
      ->bootstrap()
      ->warning()
      ->add();
    header("Location: " . admin_route("permissions"));
    exit;
  } else {
    $notifier
      ->message("Error al eliminar el permiso: " . htmlspecialchars($e->getMessage()))
      ->bootstrap()
      ->danger()
      ->add();
    header("Location: " . admin_route("permissions"));
    exit;
  }
}
