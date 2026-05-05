<?php

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Validar campos requeridos
  if (empty($_POST['role_name'])) {
    $notifier
      ->message("El nombre del rol es obligatorio.")
      ->bootstrap()
      ->danger()
      ->add();
  } else {

    try {
      $connect->beginTransaction();

      // Insertar el rol
      $sql       = "INSERT INTO roles (role_name, role_description) VALUES (:name, :desc)";
      $stmt      = $connect->prepare($sql);
      $role_name = clear_input($_POST['role_name']);
      $role_desc = clear_input($_POST['role_description'] ?? '');
      $stmt->bindParam(':name', $role_name, PDO::PARAM_STR);
      $stmt->bindParam(':desc', $role_desc, PDO::PARAM_STR);
      $stmt->execute();
      $role_id = $connect->lastInsertId();

      // Guardar permisos en rolemeta como JSON array ("context:key")
      if (!empty($_POST['permissions']) && is_array($_POST['permissions'])) {
        $permissions_json = json_encode($_POST['permissions']);

        $stmtMeta = $connect->prepare("
      INSERT INTO rolemeta (role_id, rolemeta_key, rolemeta_value) 
      VALUES (:role_id, 'permissions', :value)
    ");
        $stmtMeta->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmtMeta->bindParam(':value', $permissions_json);
        $stmtMeta->execute();
      }

      $connect->commit();

      $notifier
        ->message("Rol agregado correctamente.")
        ->bootstrap()
        ->success()
        ->add();
      header("Location: " . admin_route("roles"));
      exit();

    } catch (PDOException $e) {
      $connect->rollBack();
      $notifier
        ->message("Error al agregar el rol: " . $e->getMessage())
        ->bootstrap()
        ->danger()
        ->add();
    }
  }
}