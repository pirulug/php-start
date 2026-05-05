<?php

$query = "
  SELECT 
  users.*,
  roles.* 
  FROM 
  users
  INNER JOIN
  usermeta ON users.user_id = usermeta.user_id AND usermeta.usermeta_key = 'role_id'
  INNER JOIN
  roles ON usermeta.usermeta_value = roles.role_id
  WHERE 
  users.user_id = :user_id
";

$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Obtener user meta
$query = "
  SELECT *  
  FROM usermeta
  WHERE usermeta.user_id = :user_id
";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $user->user_id);
$stmt->execute();
$metadata = $stmt->fetchAll(PDO::FETCH_OBJ);

$usermeta = new stdClass();

foreach ($metadata as $meta) {
  $key   = $meta->usermeta_key;
  $value = $meta->usermeta_value;

  $usermeta->$key = $value;
}