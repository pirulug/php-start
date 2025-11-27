<?php

/**
 * Herramienta de depuración externa.
 * Imprime los permisos crudos desde la base de datos sin pasar por la clase.
 * * @param PDO $db Conexión a base de datos
 * @param object $user Objeto de usuario (debe tener ->role_id y ->user_login)
 */
function debug_user_permissions_raw($db, $user) {
  echo "<div style='border:1px solid #ccc; padding:15px; margin:10px; font-family:monospace;'>";
  echo "<strong>🔍 DEBUG DE PERMISOS (EXTERNO)</strong><hr>";

  // 1. Validar Usuario
  if (!$user) {
    echo "<span style='color:red;'>[!] No hay usuario logueado en la sesión.</span></div>";
    return;
  }

  $userName = htmlspecialchars($user->user_login ?? 'Desconocido');
  $roleId   = htmlspecialchars($user->role_id ?? 'N/A');

  echo "Usuario: <strong>{$userName}</strong><br>";
  echo "Rol ID: <strong>{$roleId}</strong><br><br>";

  // 2. Chequeo de Superadmin (Lógica directa, sin usar la clase)
  $isSuperAdmin = false;
  if (defined('SUPERADMIN_USERNAMES') && is_array(SUPERADMIN_USERNAMES)) {
    $admins = array_map('strtolower', SUPERADMIN_USERNAMES);
    if (in_array(strtolower($userName), $admins)) {
      $isSuperAdmin = true;
    }
  }

  if ($isSuperAdmin) {
    echo "<div style='color:green; border:1px dashed green; padding:5px;'>";
    echo "<strong>★ MODO SUPER ADMINISTRADOR DETECTADO</strong><br>";
    echo "Este usuario tiene acceso total (bypass de base de datos).";
    echo "</div></div>";
    return;
  }

  // 3. Consulta directa a la Base de Datos
  if (!$roleId || $roleId === 'N/A') {
    echo "No se puede consultar permisos: Falta Role ID.</div>";
    return;
  }

  try {
    $stmt = $db->prepare("
            SELECT p.permission_key_name, p.permission_name
            FROM role_permissions rp
            INNER JOIN permissions p ON rp.permission_id = p.permission_id
            WHERE rp.role_id = :role_id
            ORDER BY p.permission_key_name ASC
        ");
    $stmt->bindParam(':role_id', $user->role_id, PDO::PARAM_INT);
    $stmt->execute();
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<strong>Permisos en Base de Datos ({$stmt->rowCount()}):</strong><br>";
    echo "<ul style='margin-top:5px; padding-left:20px;'>";

    if ($permissions) {
      foreach ($permissions as $perm) {
        echo "<li>";
        echo "<span style='color:#007bff;'>" . htmlspecialchars($perm['permission_key_name']) . "</span>";
        echo " <small style='color:#666;'>(" . htmlspecialchars($perm['permission_name']) . ")</small>";
        echo "</li>";
      }
    } else {
      echo "<li style='color:orange;'>Este rol no tiene permisos asignados en la BD.</li>";
    }
    echo "</ul>";

  } catch (PDOException $e) {
    echo "<div style='color:red;'>Error SQL: " . $e->getMessage() . "</div>";
  }

  echo "</div>";
}