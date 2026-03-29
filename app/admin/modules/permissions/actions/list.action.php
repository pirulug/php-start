<?php

// ======================= ACCIÓN MASIVA (CAMBIAR DE GRUPO) =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'move_group') {

  $selected_permissions = $_POST['permissions'] ?? [];
  $group_id = intval($_POST['new_group_id'] ?? 0);
  $new_group_name = trim($_POST['bulk_new_group_name'] ?? '');
  $new_group_key = trim($_POST['bulk_new_group_key'] ?? '');

  if (!empty($selected_permissions)) {
    try {
      $connect->beginTransaction();

      // 1. Crear nuevo grupo si se solicita
      if ($group_id === -1 && $new_group_name !== '' && $new_group_key !== '') {
        $stmt_new_group = $connect->prepare("INSERT INTO permission_groups (permission_group_name, permission_group_key_name) VALUES (:name, :key)");
        $stmt_new_group->bindParam(':name', $new_group_name, PDO::PARAM_STR);
        $stmt_new_group->bindParam(':key', $new_group_key, PDO::PARAM_STR);
        $stmt_new_group->execute();
        $group_id = $connect->lastInsertId();
      }

      if ($group_id > 0) {
        // 2. Mover permisos al grupo (existente o recién creado)
        foreach ($selected_permissions as $perm_id) {
          $stmt = $connect->prepare("UPDATE permissions SET permission_group_id = :group_id WHERE permission_id = :id");
          $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
          $stmt->bindParam(':id', $perm_id, PDO::PARAM_INT);
          $stmt->execute();
        }

        $connect->commit();

        $count = count($selected_permissions);
        $notifier
          ->message("Se han movido {$count} permisos correctamente.")
          ->bootstrap()
          ->success()
          ->add();

        header("Location: " . admin_route("permissions"));
        exit;
      } else {
        throw new Exception("Debes seleccionar un grupo válido o completar los datos del nuevo grupo.");
      }
    } catch (Exception $e) {
      $connect->rollBack();
      $notifier
        ->message("Error al realizar la acción masiva: " . $e->getMessage())
        ->bootstrap()
        ->danger()
        ->add();
    }
  } else {
    $notifier
      ->message("Debes seleccionar al menos un permiso.")
      ->bootstrap()
      ->warning()
      ->add();
  }
}

// ======================= OBTENER PERMISOS =======================
$sql = "
SELECT
  pg.permission_group_id,
  pg.permission_group_name,

  pc.permission_context_id,
  pc.permission_context_key,
  pc.permission_context_name,

  p.permission_id,
  p.permission_name,
  p.permission_key_name
FROM permissions p
INNER JOIN permission_groups pg 
  ON p.permission_group_id = pg.permission_group_id
INNER JOIN permission_contexts pc
  ON p.permission_context_id = pc.permission_context_id
ORDER BY
  pg.permission_group_name,
  pc.permission_context_name,
  p.permission_key_name
";

$stmt = $connect->prepare($sql);
$stmt->execute();
$permissions = $stmt->fetchAll(PDO::FETCH_OBJ);

// Grupos para el selector masivo
$stmt_groups = $connect->prepare("SELECT permission_group_id, permission_group_name FROM permission_groups ORDER BY permission_group_name ASC");
$stmt_groups->execute();
$allGroups = $stmt_groups->fetchAll(PDO::FETCH_OBJ);

/**
 * Agrupación:
 * Grupo -> Contexto -> Permisos
 */
$groupedPermissions = [];

foreach ($permissions as $perm) {
  $groupedPermissions
    [$perm->permission_group_name]
    [$perm->permission_context_key][] = $perm;
}
