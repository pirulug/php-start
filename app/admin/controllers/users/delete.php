<?php

require_once "core/init.php";

// $accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/404.php");

// Comprobaciones
if (!isset($id) || $id == "") {
  $notifier->add("Tienes que tener un id.", "danger");
  header("Location: list.php");
  exit();
}

$id = $cipher->decrypt($id);

if (!is_numeric($id)) {
  $notifier->add("El id no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$query = "SELECT * FROM users WHERE user_id = $id";
$stmt  = $connect->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);


if (empty($user)) {
  $notifier->add("Usuario no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$statement = $connect->prepare('DELETE FROM users WHERE user_id = :id');
$statement->execute(array('id' => $id));

$notifier->add("Usuario eliminado correctamente.", "success", "sa");
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();




