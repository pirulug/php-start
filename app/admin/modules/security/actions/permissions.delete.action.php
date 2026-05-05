<?php

$compound_id = $args['id'] ?? ''; // Ahora es "context:key"

if (strpos($compound_id, ':') !== false) {
  [$context, $key] = explode(':', $compound_id, 2);

  try {
    $stmt = $connect->prepare("SELECT option_value FROM options WHERE option_key = 'site_permissions'");
    $stmt->execute();
    $allPermissions = json_decode($stmt->fetchColumn(), true) ?: [];

    if (isset($allPermissions[$context][$key])) {
      unset($allPermissions[$context][$key]);

      $stmt_upd = $connect->prepare("UPDATE options SET option_value = :val WHERE option_key = 'site_permissions'");
      $stmt_upd->execute([':val' => json_encode($allPermissions)]);

      $notifier->message("Permiso eliminado con éxito.")->bootstrap()->success()->add();
    } else {
      $notifier->message("El permiso no existe o ya fue eliminado.")->bootstrap()->warning()->add();
    }

  } catch (Exception $e) {
    $notifier->message("Error: " . $e->getMessage())->bootstrap()->danger()->add();
  }
}

// Refrescar caché de configuración
if (isset($config)) {
  $config->refresh();
}

header("Location: " . admin_route("permissions"));
exit();