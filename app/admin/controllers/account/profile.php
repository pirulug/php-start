<?php

// Obtener datos del usuario logeado
$id_user = $_SESSION["user_id"];

$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

$theme->render(
  BASE_DIR_ADMIN . "/views/account/profile.view.php",
  [
    'theme_title' => $theme_title,
    'theme_path'  => $theme_path,
    "user"        => $user
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);
