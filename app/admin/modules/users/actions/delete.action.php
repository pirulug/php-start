<?php

$id = $args['id'] ?? null;

// Verificar y Descifrar ID
if (!isset($id) || $id == "") {
  $notifier->message("Tienes que tener un id.")->danger()->bootstrap()->add();
  header("Location: " . admin_route("users"));
  exit();
}

$id = $cipher->decrypt($id);

if (!is_numeric($id)) {
  $notifier->message("El id no es válido.")->danger()->bootstrap()->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Consulta Segura (Preparada)
$query = "SELECT user_id, user_image FROM users WHERE user_id = :id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

if (empty($user)) {
  $notifier->message("Usuario no encontrado.")->danger()->bootstrap()->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Borrado físico de imagen 
if ($user->user_image && $user->user_image !== 'default.webp') {
  $upload_path = BASE_DIR . '/storage/uploads/user/';
  $file_path   = $upload_path . $user->user_image;
  
  if (file_exists($file_path)) {
    unlink($file_path);
  }
}

// Eliminación Segura
$statement = $connect->prepare('DELETE FROM users WHERE user_id = :id');
$statement->bindParam(':id', $id, PDO::PARAM_INT);

if ($statement->execute()) {
  $notifier->message("Usuario eliminado correctamente.")->success()->bootstrap()->add();
} else {
  $notifier->message("Error al eliminar el usuario.")->danger()->bootstrap()->add();
}

header('Location: ' . admin_route("users"));
exit();
