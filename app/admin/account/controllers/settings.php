<?php

// Obtener ID
$id_user = $_SESSION["user_id"];

// Obtener user
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Obtener user meta
$query = "
  SELECT *  
  FROM usermeta
  WHERE usermeta.user_id = :user_id
";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $user->user_id);
$stmt->execute();
$metadata = $stmt->fetchAll(PDO::FETCH_OBJ);

$usermeta = new stdClass();

foreach ($metadata as $meta) {
  $key   = $meta->usermeta_key;
  $value = $meta->usermeta_value;

  $usermeta->$key = $value;
}

// Formulario POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['update_profile'])) {

    // Datos de usuario
    $user_id           = $_POST['id'];
    $user_email        = $_POST['user_email'];
    $user_nickname     = $_POST['user_nickname'];
    $user_display_name = $_POST['user_display_name'];

    // Datos user meta
    $usermeta_first_name = $_POST['user_first_name'];
    $usermeta_last_name  = $_POST['user_last_name'];

    $update = false;

    // Obtener datos actuales del usuario
    $query = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $current_user = $stmt->fetch(PDO::FETCH_OBJ);

    // Validar email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
      $notifier->add("El email ingresado no es válido.", "danger");
    } else {
      // Verificar si cambió y no está duplicado
      if ($current_user->user_email !== $user_email) {
        $query = "SELECT COUNT(*) AS count FROM users WHERE user_email = :user_email AND user_id != :user_id";
        $stmt  = $connect->prepare($query);
        $stmt->bindParam(':user_email', $user_email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result->count > 0) {
          $notifier->add("El email ya está registrado.", "danger");
        } else {
          $update = true;
        }
      }
    }

    // NickName
    if (empty($user_nickname) && $user_nickname == "") {
      $notifier->add("Por favor, introduce un alias.", "danger");
    }

    // Imagen
    if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
      if (!$notifier->has("bootstrap", 'danger')) {

        $upload_path = BASE_DIR . '/uploads/user/';
        $user_image  = upload_image(
          $_FILES["user_image"],
          $upload_path,
          100,
          100,
          [
            'convertTo' => 'webp',
            'prefix'    => 'u-'
          ]
        );

        if (!$user_image['success']) {
          $notifier->add($user_image['message'], "danger");
        } else {
          // Eliminar imagen anterior si no es la predeterminada
          if ($current_user->user_image && file_exists($upload_path . $current_user->user_image) && $current_user->user_image !== 'default.webp') {
            unlink($upload_path . $current_user->user_image);
          }
          $user_image = $user_image['file_name'];
          $update     = true;
        }

      } else {
        $user_image = $current_user->user_image;
      }
    } else {
      $user_image = $current_user->user_image;
    }

    // Si no hay errores, actualizar datos
    if (!$notifier->has("bootstrap", 'danger')) {

      $query = "UPDATE users SET 
                  user_email = :user_email,
                  user_nickname = :user_nickname,
                  user_display_name = :user_display_name,
                  user_image = :user_image,
                  user_updated = NOW()
                WHERE user_id = :user_id";

      $stmt = $connect->prepare($query);
      $stmt->bindParam(':user_email', $user_email);
      $stmt->bindParam(':user_nickname', $user_nickname);
      $stmt->bindParam(':user_display_name', $user_display_name);
      $stmt->bindParam(':user_image', $user_image);
      $stmt->bindParam(':user_id', $user_id);

      if ($stmt->execute()) {

        // UPDATE USERMETA
        $usermeta_data = [
          'first_name' => $usermeta_first_name,
          'last_name'  => $usermeta_last_name,
        ];

        $query_meta = "
          UPDATE usermeta 
          SET usermeta_value = :value
          WHERE user_id = :user_id AND usermeta_key = :key
        ";
        $stmt_meta  = $connect->prepare($query_meta);

        foreach ($usermeta_data as $key => $value) {
          $stmt_meta->bindParam(':value', $value);
          $stmt_meta->bindParam(':user_id', $user_id);
          $stmt_meta->bindParam(':key', $key);
          $stmt_meta->execute();
        }

        $notifier->add("Perfil actualizado correctamente.", "success");

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
      } else {
        $notifier->add("Error al actualizar el perfil.", "danger");
      }
    }
  }

  // Cambio de contraseña (sin cambios)
  if (isset($_POST['change_password'])) {
    $currentPassword = trim($_POST['current_password']);
    $newPassword     = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $userId          = $_SESSION['user_id'];

    if (empty($currentPassword)) {
      $notifier->add("El campo 'Contraseña actual' no puede estar vacío.", "danger");
    } else {
      $currentPasswordEncrypted = $cipher->encrypt($currentPassword);
      $sql                      = "SELECT user_password FROM users WHERE user_id = :user_id";
      $stmt                     = $connect->prepare($sql);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      if (!$user || $currentPasswordEncrypted !== $user->user_password) {
        $notifier->add("La contraseña actual es incorrecta.", "danger");
      }
    }

    if (empty($newPassword) || empty($confirmPassword)) {
      $notifier->add("El campo de nueva contraseña no puede estar vacío.", "danger");
    } elseif ($newPassword !== $confirmPassword) {
      $notifier->add("La nueva contraseña y la confirmación no coinciden.", "danger");
    }

    if (!$notifier->has("bootstrap", 'danger')) {
      $hashedPassword = $cipher->encrypt($newPassword);
      $sql            = "UPDATE users SET user_password = :new_password WHERE user_id = :user_id";
      $stmt           = $connect->prepare($sql);
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