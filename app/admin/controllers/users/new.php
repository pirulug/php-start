<?php

require_once "core/init.php";

// $accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/404.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario y limpiarlos
  $user_name   = clear_data($_POST['user_name']);
  $user_email  = clear_data($_POST['user_email']);
  $user_role   = clear_data($_POST['user_role']);
  $user_status = clear_data($_POST['user_status']);
  $password    = clear_data($_POST['user_password']);

  // Validar el nombre de usuario
  if (strlen($user_name) < 4) {
    $notifier->add("El nombre de usuario debe tener al menos 4 caracteres.", "danger");
    // $log->log('error', "Validación fallida: nombre demasiado corto", $user_name);
  } else {
    try {
      $query     = "SELECT COUNT(*) AS count FROM users WHERE user_name = :user_name";
      $statement = $connect->prepare($query);
      $statement->bindParam(':user_name', $user_name);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        $notifier->add("El nombre de usuario ya está en uso.", "danger");
        // $log->log('error', "Nombre de usuario ya existe", $user_name);
      }
    } catch (PDOException $e) {
      // $log->log('error', "Error en verificación de nombre de usuario", $e->getMessage());
    }
  }

  // Validar email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $notifier->add("El email ingresado no es válido.", "danger");
    // $log->log('error', "Email inválido", $user_email);
  } else {
    try {
      $query     = "SELECT COUNT(*) AS count FROM users WHERE user_email = :user_email";
      $statement = $connect->prepare($query);
      $statement->bindParam(':user_email', $user_email);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        $notifier->add("El email ya está registrado.", "danger");
        // $log->log('error', "Email ya registrado", $user_email);
      }
    } catch (PDOException $e) {
      // $log->log('error', "Error en verificación de email", $e->getMessage());
    }
  }

  // Validar rol y estatus
  if (!in_array($user_role, [2, 3])) {
    $notifier->add("Seleccionar rol.", "danger");
    // $log->log('error', "Rol inválido", $user_role);
  }

  if (!in_array($user_status, [1, 2])) {
    $notifier->add("Seleccionar estatus.", "danger");
    // $log->log('error', "Estatus inválido", $user_status);
  }

  // Imagen
  if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$notifier->has('bootstrap', 'danger')) {
      $upload_path = BASE_DIR . '/uploads/user/';
      $user_image  = upload_image(
        $_FILES["user_image"],
        $upload_path,
        100,
        100,
        ['convertTo' => 'webp', 'prefix' => 'u-']
      );

      if (!$user_image['success']) {
        $notifier->add($user_image['message'], "danger");
        // $log->log('error', "Error al subir imagen", $user_image['message']);
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
  if (!$notifier->has('bootstrap', 'danger')) {
    $hashed_password = $cipher->encrypt($password);

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
        $notifier->add("El nuevo usuario se insertó correctamente.", "success");

        // Log de acción del usuario actual
        // $log->logUser($_SESSION['user_id'], "Crear usuario", "Se creó el usuario: $user_name");

        header("Location: " . SITE_URL_ADMIN . "/users");
        exit();
      } else {
        $notifier->add("Hubo un error al intentar insertar el nuevo usuario.", "danger");
        // $log->log('error', "Falló el INSERT del nuevo usuario", json_encode($_POST));
      }
    } catch (PDOException $e) {
      $notifier->add("Error de base de datos: " . $e->getMessage(), "danger");
      // $log->log('error', "Excepción en INSERT de nuevo usuario", $e->getMessage());
    }
  }
}

// Renderizar dashboard
$theme->render(
  BASE_DIR_ADMIN . "/views/users/new.view.php",
  [
    'theme_title' => 'Nuevo usuario',
    'theme_path'  => 'user-new'
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);
