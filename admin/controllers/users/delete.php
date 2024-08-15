<?php

require_once "../../core.php";

if (!isUserLoggedIn()) {
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

if (!$accessControl->hasAccess([0, 1], $_SESSION['user_role'])) {
  header("Location: " . APP_URL . "/admin/controllers/dashboard.php");
  exit();
}

// Comprobaciones
if (!isset($_GET["id"]) || $_GET["id"] == "") {
  add_message("Tienes que tener un id.", "danger");
  header("Location: list.php");
  exit();
}

$id = $encryption->decrypt($_GET["id"]);

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




