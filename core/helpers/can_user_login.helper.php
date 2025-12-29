<?php

/**
 * Verifica si un usuario puede iniciar sesión
 */
function can_user_login(PDO $connect, int $user_id): bool {
  // Super admin siempre puede
  if (defined('SUPERADMIN_ID') && in_array($user_id, SUPERADMIN_ID, true)) {
    return true;
  }

  $sql = "
        SELECT 1
        FROM role_permissions rp
        INNER JOIN permissions p ON p.permission_id = rp.permission_id
        INNER JOIN permission_contexts pc ON pc.permission_context_id = p.permission_context_id
        INNER JOIN users u ON u.role_id = rp.role_id
        WHERE u.user_id = :user_id
          AND p.permission_key_name = 'auth.login'
          AND pc.permission_context_key = :context
        LIMIT 1
    ";

  $context = CTX_ADMIN;

  $stmt = $connect->prepare($sql);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':context', $context);
  $stmt->execute();

  return (bool) $stmt->fetchColumn();
}
