<?php

require_once "../../core/init.admin.php";

$accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/404.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario y limpiarlos
  $user_name   = clear_data($_POST['user_name']);
  $user_email  = clear_data($_POST['user_email']);
  $user_role   = clear_data($_POST['user_role']);
  $user_status = clear_data($_POST['user_status']);
  $password    = clear_data($_POST['user_password']);

  // Validar el nombre de usuario
  if (strlen($user_name) < 4) {
    $messageHandler->addMessage("El nombre de usuario debe tener al menos 4 caracteres.", "danger");
    $log->log('error', "Validación fallida: nombre demasiado corto", $user_name);
  } else {
    try {
      $query     = "SELECT COUNT(*) AS count FROM users WHERE user_name = :user_name";
      $statement = $connect->prepare($query);
      $statement->bindParam(':user_name', $user_name);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        $messageHandler->addMessage("El nombre de usuario ya está en uso.", "danger");
        $log->log('error', "Nombre de usuario ya existe", $user_name);
      }
    } catch (PDOException $e) {
      $log->log('error', "Error en verificación de nombre de usuario", $e->getMessage());
    }
  }

  // Validar email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $messageHandler->addMessage("El email ingresado no es válido.", "danger");
    $log->log('error', "Email inválido", $user_email);
  } else {
    try {
      $query     = "SELECT COUNT(*) AS count FROM users WHERE user_email = :user_email";
      $statement = $connect->prepare($query);
      $statement->bindParam(':user_email', $user_email);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        $messageHandler->addMessage("El email ya está registrado.", "danger");
        $log->log('error', "Email ya registrado", $user_email);
      }
    } catch (PDOException $e) {
      $log->log('error', "Error en verificación de email", $e->getMessage());
    }
  }

  // Validar rol y estatus
  if (!in_array($user_role, [2, 3])) {
    $messageHandler->addMessage("Seleccionar rol.", "danger");
    $log->log('error', "Rol inválido", $user_role);
  }

  if (!in_array($user_status, [1, 2])) {
    $messageHandler->addMessage("Seleccionar estatus.", "danger");
    $log->log('error', "Estatus inválido", $user_status);
  }

  // Imagen
  if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$messageHandler->hasMessagesOfType('danger')) {
      $upload_path = BASE_DIR . '/uploads/user/';
      $user_image  = upload_image(
        $_FILES["user_image"],
        $upload_path,
        100,
        100,
        ['convertTo' => 'webp', 'prefix' => 'u-']
      );

      if (!$user_image['success']) {
        $messageHandler->addMessage($user_image['message'], "danger");
        $log->log('error', "Error al subir imagen", $user_image['message']);
      } else {
        $user_image = $user_image['file_name'];
      }
    } else {
      $user_image = "default.webp";
    }
  } else {
    $user_image = "default.webp";
  }

  // Si no hay errores, insertar
  if (!$messageHandler->hasMessagesOfType('danger')) {
    $hashed_password = $encryption->encrypt($password);

    try {
      $query     = "INSERT INTO users 
        (user_name, user_email, user_role, user_status, user_password, user_image, user_updated) 
        VALUES 
        (:user_name, :user_email, :user_role, :user_status, :user_password, :user_image, CURRENT_TIME)";
      $statement = $connect->prepare($query);

      $statement->bindParam(':user_name', $user_name);
      $statement->bindParam(':user_email', $user_email);
      $statement->bindParam(':user_role', $user_role);
      $statement->bindParam(':user_status', $user_status);
      $statement->bindParam(':user_password', $hashed_password);
      $statement->bindParam(':user_image', $user_image);

      if ($statement->execute()) {
        $messageHandler->addMessage("El nuevo usuario se insertó correctamente.", "success");

        // Log de acción del usuario actual
        $log->logUser($_SESSION['user_id'], "Crear usuario", "Se creó el usuario: $user_name");

        header("Location: list.php");
        exit();
      } else {
        $messageHandler->addMessage("Hubo un error al intentar insertar el nuevo usuario.", "danger");
        $log->log('error', "Falló el INSERT del nuevo usuario", json_encode($_POST));
      }
    } catch (PDOException $e) {
      $messageHandler->addMessage("Error de base de datos: " . $e->getMessage(), "danger");
      $log->log('error', "Excepción en INSERT de nuevo usuario", $e->getMessage());
    }
  }
}

/* ========== Theme config ========= */
$theme_title = "Usuario nuevo";
$theme_path  = "user-new";
include BASE_DIR_ADMIN . "/views/users/new.view.php";
/* ================================= */


