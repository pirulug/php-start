<?php

// ======================= ACCIÓN MASIVA (CAMBIAR DE GRUPO) =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'move_group') {

  $selected_keys  = $_POST['permissions'] ?? []; // Ahora viene como "context:key"
  $new_group_id   = $_POST['new_group_id'] ?? '0';
  $new_group_name = '';

  if ($new_group_id === '-1') {
    $new_group_name = clear_input($_POST['bulk_new_group_name'] ?? '');
  } elseif ($new_group_id !== '0') {
    $new_group_name = clear_input($new_group_id);
  }

  if (!empty($selected_keys) && $new_group_name !== '') {
    try {
      // 1. Obtener JSON actual (Anidado)
      $stmt = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
      $stmt->execute();
      $allPermissions = json_decode($stmt->fetchColumn(), true) ?: [];

      // 2. Actualizar grupo para las llaves seleccionadas
      foreach ($selected_keys as $compound_key) {
        if (strpos($compound_key, ':') !== false) {
          [$context, $key] = explode(':', $compound_key, 2);
          if (isset($allPermissions[$context][$key])) {
            $allPermissions[$context][$key]['group'] = $new_group_name;
          }
        }
      }

      // 3. Guardar de nuevo
      $stmt_upd = $connect->prepare("UPDATE options SET option_value = :val WHERE option_key = 'site_permissions'");
      $new_json = json_encode($allPermissions);
      $stmt_upd->bindParam(':val', $new_json);
      $stmt_upd->execute();

      $notifier->message("Permisos movidos al grupo '{$new_group_name}' con éxito.")->bootstrap()->success()->add();
      header("Location: " . admin_route("permissions"));
      exit();

    } catch (Exception $e) {
      $notifier->message("Error: " . $e->getMessage())->bootstrap()->danger()->add();
    }
  }
}

// ======================= OBTENER PERMISOS (Desde Options) =======================
$stmt_p = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
$stmt_p->execute();
$allPermissions = json_decode($stmt_p->fetchColumn(), true) ?: ['admin' => [], 'front' => []];

$groupedPermissions = [];
$distinctGroups     = [];

foreach ($allPermissions as $context => $permissions) {
  foreach ($permissions as $key => $data) {
    $group                  = $data['group'] ?? 'otros';
    $distinctGroups[$group] = true;

    $groupedPermissions[$group][$context][] = (object) [
      'permission_id'           => "{$context}:{$key}",
      'permission_key_name'     => $key,
      'permission_name'         => $data['name'] ?? $key,
      'permission_context_name' => ucfirst($context),
      'permission_context_key'  => $context
    ];
  }
}

ksort($groupedPermissions);
$allGroups = array_keys($distinctGroups);