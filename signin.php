<?php

require_once "core.php";

if (isset($_SESSION['signedin']) && $_SESSION['signedin']) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name     = htmlspecialchars(strtolower($_POST['user_name']), ENT_QUOTES, 'UTF-8');
  $user_password = cleardata($_POST['user_password']);
  $password      = $encryption->encrypt($user_password);

  // echo $password;

  try {
    $connect;
  } catch (PDOException $e) {
    echo "Error: ." . $e->getMessage();
  }

  $query = "SELECT * FROM users WHERE user_name = :user_name AND user_password = :user_password AND user_status = 1";
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
    header('Location: ' . SITE_URL . "/404.php");
    exit();
  } else {
    add_message("incorrect login data or access denied", "danger");
  }

}

$pageTitle = "Login";
include "views/signin.view.php";