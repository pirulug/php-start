<?php

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
