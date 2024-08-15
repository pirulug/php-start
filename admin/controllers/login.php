<?php

require_once "../core.php";

if (isUserLoggedIn()) {
  header('Location: ' . APP_URL . '/admin/controllers/dashboard.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name     = htmlspecialchars(strtolower($_POST['user_name']), ENT_QUOTES, 'UTF-8');
  $user_password = cleardata($_POST['user_password']);
  $password      = $encryption->encrypt($user_password);

  try {
    $connect;
  } catch (PDOException $e) {
    echo "Error: ." . $e->getMessage();
  }

  $query = "SELECT * FROM users WHERE user_name = :user_name AND user_password = :user_password AND user_status = 1 AND user_role IN (0, 1)";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
  $stmt->bindParam(':user_password', $password, PDO::PARAM_STR);
  $stmt->execute();

  $result_login = $stmt->fetch(PDO::FETCH_OBJ);

  if ($result_login !== false) {
    $_SESSION['signedin']  = true;
    $_SESSION['user_id']   = $result_login->user_id;
    $_SESSION['user_role'] = $result_login->user_role;
    $_SESSION['user_name'] = $result_login->user_name;


    add_message("Datos correctos", "success");
    header('Location: ' . APP_URL . '/admin/controllers/dashboard.php');
    exit();
  } else {
    add_message("incorrect login data or access denied", "danger");
  }

}


/* ========== Theme config ========= */
$theme_title = "Lista de usuarios";
$theme_path  = "user-list";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN . "/views/login.view.php";
/* ================================= */