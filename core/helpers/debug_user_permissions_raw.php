<?php

/**
 * Debug RAW de permisos del usuario logueado
 * Obtiene el user_id desde la sesión
 *
 * @param PDO $connect Conexión a la base de datos
 */
function debug_user_permissions_raw(PDO $connect): void {
  echo "<div style='border:1px solid #ccc; padding:15px; margin:10px; font-family:monospace;'>";
  echo "<strong>DEBUG DE PERMISOS (RAW)</strong><hr>";

  /* --------------------------------------------------
   * 1. Validar sesión
   * -------------------------------------------------- */
  if (!isset($_SESSION['user_id'])) {
    echo "[!] No hay user_id en la sesión.";
    echo "</div>";
    return;
  }

  $userId = (int) $_SESSION['user_id'];

  echo "User ID en sesión: <strong>{$userId}</strong><br><br>";

  /* --------------------------------------------------
   * 2. Super Admin (bypass total)
   * -------------------------------------------------- */
  if (defined('SUPERADMIN_ID') && in_array($userId, SUPERADMIN_ID, true)) {
    echo "<div style='color:green; border:1px dashed green; padding:8px;'>";
    echo "<strong>MODO SUPER ADMIN</strong><br>";
    echo "Este usuario tiene acceso total (bypass de permisos).";
    echo "</div></div>";
    return;
  }

  /* --------------------------------------------------
   * 3. Obtener datos del usuario (role_id, login)
   * -------------------------------------------------- */
  $stmtUser = $connect->prepare("
        SELECT user_login, role_id
        FROM users
        WHERE user_id = :user_id
        LIMIT 1
    ");
  $stmtUser->bindParam(':user_id', $userId, PDO::PARAM_INT);
  $stmtUser->execute();

  $user = $stmtUser->fetch(PDO::FETCH_OBJ);

  if (!$user) {
    echo "Usuario no encontrado en la base de datos.";
    echo "</div>";
    return;
  }

  $userName = $user->user_login;
  $roleId   = $user->role_id;

  echo "Usuario: <strong>{$userName}</strong><br>";
  echo "Role ID: <strong>{$roleId}</strong><br><br>";

  if (!$roleId) {
    echo "El usuario no tiene rol asignado.";
    echo "</div>";
    return;
  }

  /* --------------------------------------------------
   * 4. Consultar permisos por rol
   * -------------------------------------------------- */
  try {

    $stmt = $connect->prepare("
            SELECT 
                pc.permission_context_key,
                p.permission_key_name,
                p.permission_name
            FROM role_permissions rp
            INNER JOIN permissions p 
                ON p.permission_id = rp.permission_id
            INNER JOIN permission_contexts pc 
                ON pc.permission_context_id = p.permission_context_id
            WHERE rp.role_id = :role_id
            ORDER BY pc.permission_context_key, p.permission_key_name
        ");

    $stmt->bindParam(':role_id', $roleId, PDO::PARAM_INT);
    $stmt->execute();

    $permissions = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo "<strong>Permisos en Base de Datos ({$stmt->rowCount()}):</strong><br>";

    if (!$permissions) {
      echo "<span style='color:orange;'>Este rol no tiene permisos asignados.</span>";
      echo "</div>";
      return;
    }

    echo "<ul style='margin-top:8px; padding-left:20px;'>";

    foreach ($permissions as $perm) {
      echo "<li>";
      echo "[{$perm->permission_context_key}] ";
      echo "<strong>{$perm->permission_key_name}</strong>";
      echo " <small>({$perm->permission_name})</small>";
      echo "</li>";
    }

    echo "</ul>";

  } catch (PDOException $e) {
    echo "<div style='color:red;'>Error SQL: {$e->getMessage()}</div>";
  }

  echo "</div>";
}
