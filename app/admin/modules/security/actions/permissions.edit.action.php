<?php

$compound_id = $args['id'] ?? ''; // Ahora es "context:key"

if (strpos($compound_id, ':') === false) {
  $notifier->message("ID de permiso inválido.")->bootstrap()->danger()->add();
  header("Location: " . admin_route("permissions"));
  exit();
}

[$original_context, $original_key] = explode(':', $compound_id, 2);

// 1. Obtener definición actual
$stmt = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
$stmt->execute();
$allPermissions = json_decode($stmt->fetchColumn(), true) ?: [];

if (!isset($allPermissions[$original_context][$original_key])) {
  $notifier->message("El permiso no existe.")->bootstrap()->danger()->add();
  header("Location: " . admin_route("permissions"));
  exit();
}

$permission_data = $allPermissions[$original_context][$original_key];

// Objeto para la vista (compatibilidad)
$permission = (object) [
  'permission_key_name'    => $original_key,
  'permission_name'        => $permission_data['name'] ?? '',
  'group_name'             => $permission_data['group'] ?? '',
  'context'                => $original_context,
  'permission_description' => $permission_data['desc'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $permission_name = clear_input($_POST['permission_name'] ?? '');
  $permission_key  = clear_input($_POST['permission_key_name'] ?? '');
  $group_name      = clear_input($_POST['group_name'] ?? '');
  $new_group_name  = clear_input($_POST['new_group_name'] ?? '');
  $context         = clear_input($_POST['context'] ?? $original_context);
  $description     = clear_input($_POST['permission_description'] ?? '');

  if ($new_group_name !== '') {
    $group_name = $new_group_name;
  }

  if ($permission_name && $permission_key && $group_name) {
    try {
      // Eliminar el viejo si cambió la llave o el contexto
      if ($original_key !== $permission_key || $original_context !== $context) {
        unset($allPermissions[$original_context][$original_key]);
      }

      // Guardar el nuevo/actualizado en el bucket destino
      $allPermissions[$context][$permission_key] = [
        'name'  => $permission_name,
        'group' => $group_name,
        'desc'  => $description
      ];

      $stmt_upd = $connect->prepare("UPDATE options SET option_value = :val WHERE option_key = 'site_permissions'");
      $stmt_upd->execute([':val' => json_encode($allPermissions)]);

      $notifier->message("Permiso actualizado correctamente.")->bootstrap()->success()->add();
      
      // Refrescar caché de configuración
      $config->refresh();

      header("Location: " . admin_route("permissions"));
      exit();

    } catch (Exception $e) {
      $notifier->message("Error: " . $e->getMessage())->bootstrap()->danger()->add();
    }
  }
}

// Grupos para el selector
$groups = [];
foreach ($allPermissions as $ctx => $perms) {
  foreach ($perms as $p) {
    if (isset($p['group']))
      $groups[] = $p['group'];
  }
}
$groups = array_unique($groups);
asort($groups);