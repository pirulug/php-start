<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql  = "INSERT INTO roles (role_name, role_description) VALUES (:name, :desc)";
  $stmt = $connect->prepare($sql);
  $stmt->bindParam(':name', $_POST['role_name'], PDO::PARAM_STR);
  $stmt->bindParam(':desc', $_POST['role_description'], PDO::PARAM_STR);
  if ($stmt->execute()) {
    $notifier->add("Rol agregado correctamente.", "success");
    header("Location: " . SITE_URL_ADMIN . "/roles");
    exit();
  } else {
    $notifier->add("Error al agregar el rol.", "success");
  }
}

