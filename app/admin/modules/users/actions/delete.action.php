<?php

$id = $args['id'] ?? null;

if (!isset($id) || $id == "") {
  $notifier
    ->message("Tienes que tener un id.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

$id = $cipher->decrypt($id);

if (!is_numeric($id)) {
  // $notifier->add("El id no encontrado.", "danger");
  $notifier
    ->message("El id no es válido.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

$query = "SELECT * FROM users WHERE user_id = $id";
$stmt  = $connect->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);


if (empty($user)) {
  // $notifier->add("Usuario no encontrado.", "danger");
  $notifier
    ->message("Usuario no encontrado.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

$statement = $connect->prepare('DELETE FROM users WHERE user_id = :id');
$statement->execute(array('id' => $id));

$notifier
  ->message("Usuario eliminado correctamente.")
  ->bootstrap()
  ->success()
  ->add();
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
