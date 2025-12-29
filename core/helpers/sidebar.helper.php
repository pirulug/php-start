<?php

function current_url(): string {
  return trim($_GET['url'] ?? '', '/');
}

function can(string $permission, string $context = CTX_ADMIN): bool {
  if (!isset($_SESSION['user_id'])) {
    return false;
  }

  $user_id = (int) $_SESSION['user_id'];

  // Super admin: acceso total
  if (in_array($user_id, SUPERADMIN_ID, true)) {
    return true;
  }

  global $connect;

  $sql = "
        SELECT 1
        FROM users u
        INNER JOIN role_permissions rp 
            ON rp.role_id = u.role_id
        INNER JOIN permissions p 
            ON p.permission_id = rp.permission_id
        INNER JOIN permission_contexts pc 
            ON pc.permission_context_id = p.permission_context_id
        WHERE u.user_id = ?
          AND p.permission_key_name = ?
          AND pc.permission_context_key = ?
        LIMIT 1
    ";

  $stmt = $connect->prepare($sql);
  $stmt->execute([
    $user_id,
    $permission,
    $context
  ]);

  return (bool) $stmt->fetchColumn();
}
