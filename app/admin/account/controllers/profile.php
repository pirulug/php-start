<?php

// Obtener datos del usuario logeado
$id_user = $_SESSION["user_id"];

$query = "SELECT 
  users.*,
  roles.* 
FROM 
  users
INNER JOIN
  roles
ON
  users.role_id = roles.role_id
WHERE 
  user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);