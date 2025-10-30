<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  if (!$accessManager->can_login($user_session->user_id, $user_session->user_name)) {
    header("Location: " . SITE_URL);
    exit();
  }
}

// --- AUTO LOGIN CON COOKIE ---
if (isset($_COOKIE['php-start'])) {
  try {
    // Intentar descifrar el user_id
    $user_id = $cipher->decrypt($_COOKIE['php-start']);

    // Validar que sea numérico (protección adicional)
    if (!is_numeric($user_id)) {
      throw new Exception("ID inválido en cookie");
    }

    // $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1 AND role_id IN (1, 2)";
    $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Verificar si aún puede iniciar sesión
      if ($accessManager->can_login($user->role_id, $user->user_name)) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['signin']  = true;

        header("Location: " . url_admin("dashboard"));
        exit();
      } else {
        // Sin permisos => borrar cookie
        setcookie("php-start", "", time() - 3600, "/");
      }
    } else {
      // Usuario no existe o está inactivo => borrar cookie
      setcookie("php-start", "", time() - 3600, "/");
    }
  } catch (Exception $e) {
    // Error al descifrar => cookie corrupta o manipulada => eliminar
    setcookie("php-start", "", time() - 3600, "/");
  }
}

// --- LOGIN NORMAL ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_name     = clear_data($_POST['user-name']);
  $user_password = clear_data($_POST['user-password']);
  $remember_me   = $_POST['remember-me'];

  // Validar usuario
  if (empty($user_name)) {
    $notifier->add("El campo usuario es obligatorio", "danger");
  }

  // Validar contraseña
  if (empty($user_password)) {
    $notifier->add("El campo contraseña es obligatorio", "danger");
  } else {
    $user_password = $cipher->encrypt($user_password);
  }

  // Recordar usuario
  $remember_me = ($remember_me === 'remember-me') ? true : false;

  // Si no hay errores, comprobar usuario y contraseña
  if (!$notifier->hasErrors()) {
    $query = "SELECT * FROM users WHERE user_name = :user_name AND user_password = :user_password AND user_status = 1 AND role_id IN (1, 2)";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    $stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      if (!$accessManager->can_login($user->role_id, $user->user_name)) {
        exit("No tiene permiso para iniciar sesión en el sistema.");
      } else {

        // Crear sesión
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['signin']  = true;

        // Recordar usuario
        if ($remember_me) {
          setcookie("php-start", $cipher->encrypt($user->user_id), time() + (30 * 24 * 60 * 60), "/");
        }

        $notifier->add("¡Bienvenido de nuevo, {$user->user_name}!", "success");
        // Redirigir al dashboard
        header("Location: " . url_admin("dashboard"));
        exit();
      }
    } else {
      $notifier->add("Usuario o contraseña incorrectos", "danger");
    }
  }
}