<?php

require_once "../../core.php";

// Session Manager
$check_access = $sessionManager->checkUserAccess();

// Comprobaciones
if (!isset($_GET["id"]) || $_GET["id"] == "") {
  add_message("Tienes que tener un id.", "danger");
  header("Location: list.php");
  exit();
}

$id = decrypt($_GET["id"]);

if (!is_numeric($id)) {
  add_message("El id no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$query = "SELECT * FROM users WHERE user_id = $id";
$stmt  = $connect->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);


if (empty($user)) {
  add_message("Usuario no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$statement = $connect->prepare('DELETE FROM users WHERE user_id = :id');
$statement->execute(array('id' => $id));

add_message("Usuario eliminado correctamente.", "success");
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();




