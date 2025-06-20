<?php

require_once "../../core.php";

$accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/404.php");

// Si no tine id
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario y limpiarlos
  $user_id       = $encryption->decrypt(clear_data($_POST['user_id']));
  $user_name     = clear_data($_POST['user_name']);
  $user_email    = clear_data($_POST['user_email']);
  $user_role     = clear_data($_POST['user_role']);
  $user_status   = clear_data($_POST['user_status']);
  $user_password = clear_data($_POST['user_password']);
  // $user_password_save = cleardata($_POST['user_password_save']);

  // Validar el nombre de usuario (mínimo 4 caracteres)
  if (strlen($user_name) < 4) {
    $messageHandler->addMessage("El nombre de usuario debe tener al menos 4 caracteres.", "danger");
  } else {
    // Verificar si el nombre de usuario ya existe en la base de datos
    $query     = "SELECT * FROM users WHERE user_name = :user_name AND user_id != :user_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':user_name', $user_name);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    if (!empty($result)) {
      $messageHandler->addMessage("El nombre de usuario ya está en uso.", "danger");
    }
  }

  // Validar el formato y la unicidad del email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $messageHandler->addMessage("El email ingresado no es válido.", "danger");
  } else {
    // Verificar si el email ya está registrado en la base de datos
    $query     = "SELECT * FROM users WHERE user_email = :user_email AND user_id != :user_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':user_email', $user_email);
    $statement->bindParam(':user_id', $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    if (!empty($result)) {
      $messageHandler->addMessage("El correo electrónico ya está registrado.", "danger");
    }
  }

  // Contraseña
  if (strlen($user_password) < 6) {
    $messageHandler->addMessage("La contraseña debe tener al menos 6 caracteres.", "danger");
  } else {
    $user_password = $encryption->encrypt($user_password);
  }

  // Validar selected
  if (!in_array($user_role, [2, 3])) {
    $messageHandler->addMessage("Seleccionar rol.", "danger");
  }

  // Validar selected
  if (!in_array($user_status, [1, 2])) {
    $messageHandler->addMessage("Seleccionar estatus.", "danger");
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

      if (!$user_image['success']) {
        $messageHandler->addMessage($user_image['message'], "danger");
      } else {
        $user_image = $user_image['file_name'];

        if ($user->user_image && file_exists($upload_path . $user->user_image) && $user->user_image !== 'default.webp') {
          unlink($upload_path . $user->user_image);
        }
      }

    } else {
      $user_image = $user->user_image;
    }

    var_dump($messageHandler->hasMessagesOfType('danger'));
  } else {
    $user_image = $user->user_image;
  }

  // Si no hay mensajes de error, proceder con la inserción
  if (!$messageHandler->hasMessagesOfType('danger')) {
    $query = "UPDATE users 
              SET 
              user_name = :user_name, 
              user_email = :user_email, 
              user_role = :user_role, 
              user_status = :user_status, 
              user_password = :user_password, 
              user_image = :user_image, 
              user_updated = CURRENT_TIME 
              WHERE 
              user_id = :user_id";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':user_email', $user_email);
    $stmt->bindParam(':user_role', $user_role);
    $stmt->bindParam(':user_status', $user_status);
    $stmt->bindParam(':user_password', $user_password);
    $stmt->bindParam(':user_image', $user_image);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $messageHandler->addMessage("Usuario se actualizo correctamente", "success", "tf");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }
}

/* ========== Theme config ========= */
$theme_title = "Editar usuario";
$theme_path  = "user-new";
include BASE_DIR_ADMIN . "/views/users/edit.view.php";
/* ================================= */


