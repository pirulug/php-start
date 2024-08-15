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

// Saber si existe el METHOD POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $uploadsPath = BASE_DIR . '/uploads/site/';

  $st_favicon   = uploadSiteImage('st_favicon', $_POST['st_favicon_save'], $uploadsPath);
  $st_whitelogo = uploadSiteImage('st_whitelogo', $_POST['st_whitelogo_save'], $uploadsPath);
  $st_darklogo  = uploadSiteImage('st_darklogo', $_POST['st_darklogo_save'], $uploadsPath);

  $query = "UPDATE brand SET
            st_favicon = :st_favicon,
            st_whitelogo = :st_whitelogo,
            st_darklogo = :st_darklogo";

  $statement = $connect->prepare($query);
  $statement->bindParam(':st_favicon', $st_favicon);
  $statement->bindParam(':st_whitelogo', $st_whitelogo);
  $statement->bindParam(':st_darklogo', $st_darklogo);
  $statement->execute();

  add_message("Se actualizo las imagenes de manera correcta", "success");
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}

// Obtener datos
$querySelect = "SELECT * FROM brand";
$brand       = $connect->query($querySelect)->fetch(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Brand";
$theme_path  = "brand";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN_VIEW . "/settings/brand.view.php";
/* ================================= */