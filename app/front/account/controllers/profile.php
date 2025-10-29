<?php

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
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);