<?php

if (isset($_SESSION['signin']) && $_SESSION['signin'] == true) {
  header('Location: ' . url_admin("dashboard"));
  exit();
} else {
  if (isset($_COOKIE['psloggin'])) {
    setcookie('psloggin', '', time() - 3600, "/");
  }
}

// Verificar si existe la cookie 'psloggin' y si es válida
if (isset($_COOKIE['psloggin'])) {
  $user_id = $cipher->decrypt($_COOKIE['psloggin']);

  // Consultar si el usuario existe y está activo
  $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1 AND user_role IN (1, 2)";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $result_cookie = $stmt->fetch(PDO::FETCH_OBJ);

  if ($result_cookie !== false) {
    $_SESSION['signin']  = true;
    $_SESSION['user_id'] = $result_cookie->user_id;

    header('Location: ' . url_admin("dashboard"));
    exit();
  } else {
    setcookie('psloggin', '', time() - 3600, "/");
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name     = clear_data($_POST['user-name']);
  $user_password = $cipher->encrypt(clear_data($_POST['user-password']));
  $remember_me   = $_POST['remember-me'] ?? null;

  $query = "SELECT * FROM users WHERE user_name = :user_name AND user_password = :user_password AND user_status = 1 AND user_role IN (1, 2)";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
  $stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
  $stmt->execute();

  $result_login = $stmt->fetch(PDO::FETCH_OBJ);

  if ($result_login !== false) {
    $_SESSION['signin']  = true;
    $_SESSION['user_id'] = $result_login->user_id;

    if (isset($remember_me)) {
      setcookie('psloggin', $cipher->encrypt($result_login->user_id), time() + (86400 * 30), "/");
    }

    // $log->logUser($_SESSION['user_id'], 'Ingreso', $result_login->user_name . " Ingreso.");
    $notifier->add("Datos correctos", "success");
    header('Location: ' . url_admin("dashboard"));
    exit();
  } else {
    $notifier->add("incorrect login data or access denied", "danger");
  }
}

// Renderizar dashboard
$theme->render(
  BASE_DIR_ADMIN . "/views/login.view.php",
  [
    'theme_title' => 'Dashboard',
    'theme_path'  => 'dashboard',
    // 'count_user'  => $count_user,
    // 'stats'       => $stats
  ],
  BASE_DIR_ADMIN . "/views/layouts/auth.layout.php"
);