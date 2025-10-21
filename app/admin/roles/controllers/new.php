<?php

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Validar campos requeridos
  if (empty($_POST['role_name'])) {
    $notifier->add("El nombre del rol es obligatorio.", "error");
  } else {

    try {
      // Iniciar transacción (por si falla algo)
      $connect->beginTransaction();

      // Insertar el rol
      $sql  = "INSERT INTO roles (role_name, role_description) VALUES (:name, :desc)";
      $stmt = $connect->prepare($sql);
      $stmt->bindParam(':name', $_POST['role_name'], PDO::PARAM_STR);
      $stmt->bindParam(':desc', $_POST['role_description'], PDO::PARAM_STR);
      $stmt->execute();

      // Obtener el ID del nuevo rol
      $role_id = $connect->lastInsertId();

      // Insertar permisos si existen
      if (!empty($_POST['permissions']) && is_array($_POST['permissions'])) {
        $sqlPerm  = "INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :perm_id)";
        $stmtPerm = $connect->prepare($sqlPerm);

        foreach ($_POST['permissions'] as $perm_id) {
          $stmtPerm->bindParam(':role_id', $role_id, PDO::PARAM_INT);
          $stmtPerm->bindParam(':perm_id', $perm_id, PDO::PARAM_INT);
          $stmtPerm->execute();
        }
      }

      // Confirmar transacción
      $connect->commit();

      $notifier->add("Rol agregado correctamente.", "success");
      header("Location: " . SITE_URL_ADMIN . "/roles");
      exit();

    } catch (PDOException $e) {
      // Revertir si algo falla
      $connect->rollBack();
      $notifier->add("Error al agregar el rol: " . $e->getMessage(), "error");
    }
  }
}
