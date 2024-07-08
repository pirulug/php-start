<?php

require_once "../../core.php";

// Acceso
if (!isset($_SESSION['user_name'])) {
  add_message("no inició session", "danger");
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

$check_access = check_access($connect);

// Admin y superAdmin
if ($check_access['user_role'] != 1 && $check_access['user_role'] != 0) {
  add_message("No eres administrador", "danger");
  header('Location: ' . APP_URL);
  exit();
}

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

header('Location: ' . $_SERVER['HTTP_REFERER']);




