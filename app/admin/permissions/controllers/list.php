<?php

// $paginator = new Paginator($connect, 'permissions', 10);
// $paginator->setSearchColumns(['permission_name']);
// $paginator->setOrder('permission_id', 'ASC');

// $permissions = $paginator->getResults();

$sql  = "
SELECT 
  pg.permission_group_id,
  pg.permission_group_name,
  p.permission_id,
  p.permission_name,
  p.permission_key_name
FROM permissions p
INNER JOIN permission_groups pg ON p.permission_group_id = pg.permission_group_id
ORDER BY pg.permission_group_name, p.permission_name
";
$stmt = $connect->prepare($sql);
$stmt->execute();
$permissions = $stmt->fetchAll(PDO::FETCH_OBJ);

$groupedPermissions = [];

foreach ($permissions as $perm) {
  $groupedPermissions[$perm->permission_group_name][] = $perm;
}