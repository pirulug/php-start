<?php

require_once "../../core.php";

if (!isUserLoggedIn()) {
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

if (!$accessControl->hasAccess([0], $_SESSION['user_role'])) {
  header("Location: " . APP_URL . "/admin/controllers/dashboard.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st_smtphost     = cleardata($_POST['st_smtphost']);
  $st_smtpemail    = cleardata($_POST['st_smtpemail']);
  $st_smtppassword = cleardata($_POST['st_smtppassword']);
  $st_smtpport     = cleardata($_POST['st_smtpport']);
  $st_smtpencrypt  = cleardata($_POST['st_smtpencrypt']);

  $query = "UPDATE smtp SET
              st_smtphost = :st_smtphost,
              st_smtpemail = :st_smtpemail,
              st_smtppassword = :st_smtppassword,
              st_smtpport = :st_smtpport,
              st_smtpencrypt = :st_smtpencrypt";

  $stmt = $connect->prepare($query);
  $stmt->bindParam(':st_smtphost', $st_smtphost);
  $stmt->bindParam(':st_smtpemail', $st_smtpemail);
  $stmt->bindParam(':st_smtppassword', $st_smtppassword);
  $stmt->bindParam(':st_smtpport', $st_smtpport);
  $stmt->bindParam(':st_smtpencrypt', $st_smtpencrypt);

  $stmt->execute();

  add_message('Se actualizo de manera correcta', 'success');
  header("Refresh:0");
  exit();
}

// Obtener SMTP de la base de datos
$query = "SELECT * FROM smtp";
$smtp  = $connect->query($query)->fetch(PDO::FETCH_OBJ);


/* ========== Theme config ========= */
$theme_title = "SMTP";
$theme_path  = "smtp";
include BASE_DIR_ADMIN . "/views/settings/smtp.view.php";
/* ================================= */