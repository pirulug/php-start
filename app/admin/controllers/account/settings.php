<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['update_profile'])) {
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
      $notifier->add("El nombre de usuario debe tener al menos 4 caracteres.", "danger");
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
          $notifier->add("El nombre de usuario ya está en uso.", "danger");
        } else {
          // $log->logUser($user_id, 'Actualizado', "Usuario actualizo usuario $current_user->user_name a $user_name.");

          $update = true;
        }
      }
    }

    // Validar el formato y la unicidad del email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
      $notifier->add("El email ingresado no es válido.", "danger");
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
          $notifier->add("El email ya está registrado.", "danger");
        } else {
          $update = true;
        }
      }
    }

    // Imagen
    if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
      if (!$notifier->has("bootstrap", 'danger')) {

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
          $notifier->add($user_image['message'], "danger");
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
    if (!$notifier->has("bootstrap", 'danger') && $update) {
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

      $notifier->add("Usuario actualizado correctamente", "success");
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit();
    }
  }

  // Si se envió el formulario de actualizar perfil
  if (isset($_POST['change_password'])) {
   
    $currentPassword = trim($_POST['current_password']);
    $newPassword     = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $userId          = $_SESSION['user_id'];

    // Verificar que el campo de la contraseña actual no esté vacío
    if (empty($currentPassword)) {
      $notifier->add("El campo 'Contraseña actual' no puede estar vacío.", "danger");
    } else {
      $currentPasswordEncrypted = $cipher->encrypt($currentPassword);

      // Verificar la contraseña actual en la base de datos
      $sql  = "SELECT user_password FROM users WHERE user_id = :user_id";
      $stmt = $connect->prepare($sql);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      if (!$user || $currentPasswordEncrypted !== $user->user_password) {
        $notifier->add("La contraseña actual es incorrecta.", "danger");
      }
    }

    // Verificar que las nuevas contraseñas no estén vacías y coincidan
    // if (!$messageHandler->hasMessagesOfType('danger')) {
    if (empty($newPassword) || empty($confirmPassword)) {
      $notifier->add("El campo de nueva contraseña no puede estar vacío.", "danger");
    } elseif ($newPassword !== $confirmPassword) {
      $notifier->add("La nueva contraseña y la confirmación no coinciden.", "danger");
    }
    // }

    // Actualizar la contraseña si no hay errores
    if (!$notifier->has("bootstrap", 'danger')) {
      $hashedPassword = $cipher->encrypt($newPassword);

      // Actualizar la contraseña en la base de datos
      $sql  = "UPDATE users SET user_password = :new_password WHERE user_id = :user_id";
      $stmt = $connect->prepare($sql);
      $stmt->bindParam(':new_password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

      if ($stmt->execute()) {
        $notifier->add("La contraseña se ha actualizado correctamente.", "success");
        header("Refresh: 0");
        exit();
      } else {
        $notifier->add("Hubo un error al actualizar la contraseña.", "danger");
      }
    }
  }
}

// Obtener datos del usuario logeado
$id_user = $_SESSION["user_id"];

$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

$theme->render(
  BASE_DIR_ADMIN . "/views/account/settings.view.php",
  [
    'theme_title' => 'Profile',
    'theme_path'  => 'profile',
    "user"        => $user
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);
