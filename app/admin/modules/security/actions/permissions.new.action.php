<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $permission_name = clear_input($_POST['permission_name'] ?? '');
  $permission_key  = clear_input($_POST['permission_key_name'] ?? '');
  $group_name      = clear_input($_POST['group_name'] ?? '');
  $new_group_name  = clear_input($_POST['new_group_name'] ?? '');
  $context         = clear_input($_POST['context'] ?? 'admin');
  $description     = clear_input($_POST['permission_description'] ?? '');

  if ($new_group_name !== '') {
    $group_name = $new_group_name;
  }

  if ($permission_name && $permission_key && $group_name) {
    try {
      // 1. Obtener JSON actual (Anidado)
      $stmt = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
      $stmt->execute();
      $allPermissions = json_decode($stmt->fetchColumn(), true) ?: ['admin' => [], 'front' => []];

      // Asegurar que existan los buckets
      if (!isset($allPermissions['admin']))
        $allPermissions['admin'] = [];
      if (!isset($allPermissions['front']))
        $allPermissions['front'] = [];

      // 2. Agregar el nuevo permiso al bucket correspondiente
      $allPermissions[$context][$permission_key] = [
        'name'  => $permission_name,
        'group' => $group_name,
        'desc'  => $description
      ];

      // 3. Guardar
      $stmt_upd = $connect->prepare("UPDATE options SET option_value = :val WHERE option_key = 'site_permissions'");
      $new_json = json_encode($allPermissions);
      $stmt_upd->bindParam(':val', $new_json);
      $stmt_upd->execute();

      $notifier->message("Permiso '{$permission_name}' agregado correctamente en contexto " . ucfirst($context) . ".")
        ->bootstrap()->success()->add();
      
      // Refrescar caché de configuración
      $config->refresh();

      header("Location: " . admin_route("permissions"));
      exit();

    } catch (Exception $e) {
      $notifier->message("Error: " . $e->getMessage())->bootstrap()->danger()->add();
    }
  } else {
    $notifier->message("Debes completar todos los campos obligatorios.")->bootstrap()->warning()->add();
  }
}

// Para el selector de grupos existentes
$stmt_p = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
$stmt_p->execute();
$allPerms = json_decode($stmt_p->fetchColumn(), true) ?: [];

$groups = [];
foreach ($allPerms as $context => $perms) {
  foreach ($perms as $p) {
    if (isset($p['group']))
      $groups[] = $p['group'];
  }
}
$groups = array_unique($groups);
asort($groups);