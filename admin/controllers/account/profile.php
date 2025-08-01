<?php

require_once "../../core.php";

$accessControl->check_access([1, 2], SITE_URL . "/404.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name  = $_POST['name'];
  $user_email = $_POST['email'];
  $user_id    = $_POST['id'];

  $update = false;

  // Obtener los datos actuales del usuario en la base de datos
  $query = "SELECT user_name, user_email, user_image FROM users WHERE user_id = :user_id";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  $current_user = $stmt->fetch(PDO::FETCH_OBJ);

  // Validar el nombre de usuario (mínimo 4 caracteres)
  if (strlen($user_name) < 4) {
    $messageHandler->addMessage("El nombre de usuario debe tener al menos 4 caracteres.", "danger");
  } else {
    // Verificar si el nombre de usuario es diferente al actual
    if ($current_user->user_name !== $user_name) {
      // Verificar si el nuevo nombre de usuario ya existe en la base de datos
      $query = "SELECT COUNT(*) AS count FROM users WHERE user_name = :user_name";
      $stmt  = $connect->prepare($query);
      $stmt->bindParam(':user_name', $user_name);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);

      if ($result->count > 0) {
        $messageHandler->addMessage("El nombre de usuario ya está en uso.", "danger");
      } else {
        $log->logUser($user_id, 'Actualizado', "Usuario actualizo usuario $current_user->user_name a $user_name.");

        $update = true;
      }
    }
  }

  // Validar el formato y la unicidad del email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $messageHandler->addMessage("El email ingresado no es válido.", "danger");
  } else {
    // Verificar si el email es diferente al actual
    if ($current_user->user_email !== $user_email) {
      // Verificar si el nuevo email ya está registrado en la base de datos
      $query = "SELECT COUNT(*) AS count FROM users WHERE user_email = :user_email";
      $stmt  = $connect->prepare($query);
      $stmt->bindParam(':user_email', $user_email);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);

      if ($result->count > 0) {
        $messageHandler->addMessage("El email ya está registrado.", "danger");
      } else {
        $log->logUser($user_id, 'Actualizado', "Usuario actualizo email $current_user->user_email a $user_email.");

        $update = true;
      }
    }
  }

  // Imagen
  if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$messageHandler->hasMessagesOfType('danger')) {

      $upload_path = BASE_DIR . '/uploads/user/';
      $user_image  = $_FILES["user_image"];
      $user_image  = upload_image(
        $user_image,
        $upload_path,
        100,
        100,
        [
          'convertTo' => 'webp',
          'prefix'    => 'u-'
        ]);

      // var_dump($user_image);

      if (!$user_image['success']) {
        $messageHandler->addMessage($user_image['message'], "danger");
      } else {
        $user_image = $user_image['file_name'];
        if ($current_user->user_image && file_exists($upload_path . $current_user->user_image) && $current_user->user_image !== 'default.webp') {
          unlink($upload_path . $current_user->user_image);
        }
         $update = true;
      }

    } else {
      $user_image = $current_user->user_image;
    }
  } else {
    $user_image = $current_user->user_image;
  }

  // Si no hay errores y se requiere actualización, proceder a actualizar el usuario
  if (!$messageHandler->hasMessagesOfType('danger') && $update) {
    $query = "UPDATE users 
              SET 
                  user_name = :user_name, 
                  user_email = :user_email,
                  user_image = :user_image
              WHERE 
                  user_id = :user_id";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':user_email', $user_email);
    $stmt->bindParam(':user_image', $user_image);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // $_SESSION['user_name'] == $user_name;

    $messageHandler->addMessage("Usuario actualizado correctamente", "success", "toast");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }
}

// Obtener datos del usuario logeado
$id_user = $_SESSION["user_id"];

$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Perfil Usuario";
$theme_path  = "profile-new";
include BASE_DIR_ADMIN . "/views/account/profile.view.php";
/* ================================= */