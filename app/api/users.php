<?php

$query = "SELECT * FROM users";
$stmt  = $connect->prepare($query);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_OBJ);

echo json_encode($users);

?>