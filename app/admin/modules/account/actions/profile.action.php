<?php

// Obtener datos del usuario logeado
$id_user = $_SESSION["user_id"];

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

$stmt = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);