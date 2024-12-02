<?php

require_once "../core.php";

if ($accessControl->is_user_logged_in()) {
  header('Location: ' . SITE_URL . '/admin/controllers/dashboard.php');
  exit();
}

// Verificar si existe la cookie 'psloggin' y si es válida
if (isset($_COOKIE['psloggin'])) {
  $user_id = $encryption->decrypt($_COOKIE['psloggin']);

  // Consultar si el usuario existe y está activo
  $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1 AND user_role IN (1, 2)";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $result_cookie = $stmt->fetch(PDO::FETCH_OBJ);

  if ($result_cookie !== false) {
    // Establecer la sesión del usuario
    $_SESSION['signedin']  = true;
    $_SESSION['user_id']   = $result_cookie->user_id;
    $_SESSION['user_role'] = $result_cookie->user_role;
    $_SESSION['user_name'] = $result_cookie->user_name;

    $log->logAction($_SESSION['user_id'], 'Ingreso', $_SESSION['user_name'] . " ingresó automáticamente con cookie.");
    header('Location: ' . SITE_URL . '/admin/controllers/dashboard.php');
    exit();
  } else {
    // Si la cookie es inválida, eliminarla
    setcookie('psloggin', '', time() - 3600, "/");
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name     = cleardata($_POST['user-name']);
  $user_password = $encryption->encrypt(cleardata($_POST['user-password']));
  $remember_me   = $_POST['remember-me'];

  $query = "SELECT * FROM users WHERE user_name = :user_name AND user_password = :user_password AND user_status = 1 AND user_role IN (1, 2)";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
  $stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
  $stmt->execute();

  $result_login = $stmt->fetch(PDO::FETCH_OBJ);

  if ($result_login !== false) {
    $_SESSION['signedin']  = true;
    $_SESSION['user_id']   = $result_login->user_id;
    $_SESSION['user_role'] = $result_login->user_role;
    $_SESSION['user_name'] = $result_login->user_name;

    if (isset($remember_me)) {
      setcookie('psloggin', $encryption->encrypt($result_login->user_id), time() + (86400 * 30), "/");
    }

    $log->logAction($_SESSION['user_id'], 'Ingreso', $_SESSION['user_name'] . " Ingreso.");
    $messageHandler->addMessage("Datos correctos", "success");
    header('Location: ' . SITE_URL . '/admin/controllers/dashboard.php');
    exit();
  } else {
    $messageHandler->addMessage("incorrect login data or access denied", "danger");
  }

}

/* ========== Theme config ========= */
$theme_title = "Lista de usuarios";
$theme_path  = "user-list";
include BASE_DIR_ADMIN . "/views/login.view.php";
/* ================================= */