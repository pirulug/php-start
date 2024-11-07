<?php

require_once "../../core.php";

$accessControl->check_access([1, 2]);

// Comprobaciones
if (!isset($_GET["id"]) || $_GET["id"] == "") {
  $messageHandler->addMessage("Tienes que tener un id.", "danger");
  header("Location: list.php");
  exit();
}

$id = $encryption->decrypt($_GET["id"]);

if (!is_numeric($id)) {
  $messageHandler->addMessage("El id no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$query = "SELECT * FROM users WHERE user_id = $id";
$stmt  = $connect->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);


if (empty($user)) {
  $messageHandler->addMessage("Usuario no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$statement = $connect->prepare('DELETE FROM users WHERE user_id = :id');
$statement->execute(array('id' => $id));

$messageHandler->addMessage("Usuario eliminado correctamente.", "success");
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();




