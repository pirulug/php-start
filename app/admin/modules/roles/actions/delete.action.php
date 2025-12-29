<?php
// =============================================
// ELIMINAR ROL
// =============================================

// Verifica que se haya recibido un ID válido
$rol_id = $_GET['id'] ?? null;

if ($rol_id <= 0) {
  $notifier
    ->message("No se ha especificado un rol válido.")
    ->bootstrap()
    ->warning()
    ->add();
  header("Location: " . admin_route("roles"));
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
    // $notifier->add("El rol especificado no existe.", "error");
    $notifier
      ->message("El rol especificado no existe.")
      ->bootstrap()
      ->danger()
      ->add();
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

  $notifier
    ->message("Rol eliminado correctamente.")
    ->bootstrap()
    ->success()
    ->add();
  header("Location: " . admin_route("roles"));
  exit;

} catch (Exception $e) {
  $connect->rollBack();
  // $notifier->add("Error al eliminar el rol: " . $e->getMessage(), "danger");
  $notifier
    ->message("Error al eliminar el rol: " . htmlspecialchars($e->getMessage()))
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("roles"));
}
