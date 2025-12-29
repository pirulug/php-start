<?php

$id = $_GET['id'] ?? null;

// Verificar ID
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

// Si no es un numero
if (!is_numeric($id)) {
  $notifier
    ->message("El id no encontrado.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Obtener los datos del usuario de la base de datos
$query = "SELECT * FROM users WHERE user_id = :id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Si no encuentra el usuario
if (empty($user)) {
  $notifier
    ->message("Usuario no encontrado.")
    ->bootstrap()
    ->danger()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Obtener roles para el select
$query = "SELECT role_id, role_name FROM roles ORDER BY role_name ASC";
$stmt  = $connect->prepare($query);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario y limpiarlos
  $user_id       = $cipher->decrypt(clear_data($_POST['user_id']));
  $user_login    = clear_data($_POST['user_login']);
  $user_email    = clear_data($_POST['user_email']);
  $role_id       = clear_data($_POST['role_id']);
  $user_status   = clear_data($_POST['user_status']);
  $user_password = clear_data($_POST['user_password']);
  // $user_password_save = cleardata($_POST['user_password_save']);

  // Validar el nombre de usuario (mínimo 4 caracteres)
  if (strlen($user_login) < 4) {
    $notifier
      ->message("El nombre de usuario debe tener al menos 4 caracteres.")
      ->bootstrap()
      ->danger()
      ->add();
  } else {
    // Verificar si el nombre de usuario ya existe en la base de datos
    $query     = "SELECT * FROM users WHERE user_login = :user_login AND user_id != :user_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':user_login', $user_login);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    if (!empty($result)) {
      $notifier
        ->message("El nombre de usuario ya está en uso.")
        ->bootstrap()
        ->danger()
        ->add();
    }
  }

  // Validar el formato y la unicidad del email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $notifier
      ->message("El email ingresado no es válido.")
      ->bootstrap()
      ->danger()
      ->add();
  } else {
    // Verificar si el email ya está registrado en la base de datos
    $query     = "SELECT * FROM users WHERE user_email = :user_email AND user_id != :user_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':user_email', $user_email);
    $statement->bindParam(':user_id', $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    if (!empty($result)) {
      $notifier
        ->message("El correo electrónico ya está registrado.")
        ->bootstrap()
        ->danger()
        ->add();
    }
  }

  // Contraseña
  if (strlen($user_password) < 6) {
    $notifier
      ->message("La contraseña debe tener al menos 6 caracteres.")
      ->bootstrap()
      ->danger()
      ->add();
  } else {
    $user_password = $cipher->encrypt($user_password);
  }

  // Validar selected
  if (empty($role_id) && $role_id !== '') {
    $notifier
      ->message("Seleccionar rol.")
      ->bootstrap()
      ->danger()
      ->add();
  }

  // Validar selected
  if (!in_array($user_status, [1, 2])) {
    $notifier
      ->message("Seleccionar estatus.")
      ->bootstrap()
      ->danger()
      ->add();
  }

  // Imagen
  if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$notifier->can()->danger()) {

      $upload_path = BASE_DIR . '/uploads/user/';

      $user_image = (new UploadImage())
        ->file($_FILES['user_image'])
        ->dir($upload_path)
        ->convertTo("webp")
        ->prefix('u_')
        ->width(100)
        ->height(100)
        ->maxSize(5 * 1024 * 1024)
        ->upload();

      if (!$user_image['success']) {
        // $notifier->add($user_image['message'], "danger");
        $notifier
          ->message($user_image['message'])
          ->bootstrap()
          ->danger()
          ->add();
      } else {
        $user_image = $user_image['file_name'];

        if ($user->user_image && file_exists($upload_path . $user->user_image) && $user->user_image !== 'default.webp') {
          unlink($upload_path . $user->user_image);
        }
      }

    } else {
      $user_image = $user->user_image;
    }

  } else {
    $user_image = $user->user_image;
  }

  // Si no hay mensajes de error, proceder con la inserción
  if (!$notifier->can()->danger()) {
    $query = "
      UPDATE 
        users 
      SET 
        user_login = :user_login, 
        user_email = :user_email, 
        role_id = :role_id, 
        user_status = :user_status, 
        user_password = :user_password, 
        user_image = :user_image, 
        user_updated = CURRENT_TIME 
      WHERE 
        user_id = :user_id
    ";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_login', $user_login);
    $stmt->bindParam(':user_email', $user_email);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':user_status', $user_status);
    $stmt->bindParam(':user_password', $user_password);
    $stmt->bindParam(':user_image', $user_image);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $notifier
      ->message("Usuario se actualizo correctamente.")
      ->bootstrap()
      ->success()
      ->add();
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }
}
