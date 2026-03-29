<?php

// Obtener ID
$id_user = $_SESSION["user_id"];

// Obtener user
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Obtener user meta
$query = "SELECT * FROM usermeta WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $user->user_id, PDO::PARAM_INT);
$stmt->execute();
$metadata = $stmt->fetchAll(PDO::FETCH_OBJ);

$usermeta = new stdClass();
foreach ($metadata as $meta) {
  $key   = $meta->usermeta_key;
  $value = $meta->usermeta_value;
  $usermeta->$key = $value;
}

// Formulario POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {

  $user_id           = intval($_POST['id']);
  $user_email        = trim($_POST['user_email'] ?? '');
  $user_nickname     = trim($_POST['user_nickname'] ?? '');
  $user_display_name = trim($_POST['user_display_name'] ?? '');

  // Datos user meta
  $usermeta_first_name       = trim($_POST['user_first_name'] ?? '');
  $usermeta_last_name        = trim($_POST['user_last_name'] ?? '');
  $usermeta_second_last_name = trim($_POST['user_second_last_name'] ?? '');

  // Validar email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $notifier->message("El email ingresado no es válido.")->bootstrap()->danger()->add();
  } else {
    // Verificar duplicado
    $query = "SELECT COUNT(*) AS count FROM users WHERE user_email = :user_email AND user_id != :user_id";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_email', $user_email);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if ($result->count > 0) {
      $notifier->message("El email ya está registrado.")->bootstrap()->danger()->add();
    }
  }

  // NickName
  if (empty($user_nickname)) {
    $notifier->message("Por favor, introduce un alias.")->bootstrap()->danger()->add();
  }

  // Imagen
  $user_image = $user->user_image;
  if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$notifier->can()->danger()) {
      $upload_path = BASE_DIR . '/storage/uploads/user/';
      
      $up_res = (new UploadImage())
        ->file($_FILES['user_image'])
        ->dir($upload_path)
        ->convertTo("webp")
        ->width(150)
        ->height(150)
        ->upload();

      if (!$up_res['success']) {
        $notifier->message($up_res['message'])->danger()->bootstrap()->add();
      } else {
        // Eliminar anterior
        if ($user_image && $user_image !== 'default.webp' && file_exists($upload_path . $user_image)) {
          unlink($upload_path . $user_image);
        }
        $user_image = $up_res['file_name'];
      }
    }
  }

  // Si no hay errores, actualizar
  if (!$notifier->can()->danger()) {
    try {
      $connect->beginTransaction();

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
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();

      // Meta
      $usermeta_data = [
        'first_name'       => $usermeta_first_name,
        'last_name'        => $usermeta_last_name,
        'second_last_name' => $usermeta_second_last_name,
      ];

      $query_meta = "INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
                     VALUES (:user_id, :key, :value)
                     ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)";
      $stmt_meta  = $connect->prepare($query_meta);

      foreach ($usermeta_data as $key => $value) {
        $stmt_meta->execute([
          ':user_id' => $user_id,
          ':key'     => $key,
          ':value'   => $value
        ]);
      }

      $connect->commit();
      $notifier->message("Perfil actualizado correctamente.")->success()->bootstrap()->add();
      header("Location: " . admin_route("account/settings/profile"));
      exit();
    } catch (Exception $e) {
      $connect->rollBack();
      $notifier->message("Error: " . $e->getMessage())->danger()->bootstrap()->add();
    }
  }
}
