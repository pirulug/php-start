<?php

require_once "../../core.php";

$accessControl->require_login(SITE_URL_ADMIN . "/controllers/login.php");
$accessControl->check_access([1], SITE_URL . "/404.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id      = cleardata($_POST['id']);
  $content = cleardata($_POST['content']);
  $status  = cleardata($_POST['status']);

  $query = "UPDATE ads SET content = :content, status = :status WHERE id = :id";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(":content", $content);
  $stmt->bindParam(":status", $status);
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  $messageHandler->addMessage("Actualizado de manera correcta","success");
  header('Location: ./ads.php');
  exit();
}

$querySelect = "SELECT * FROM ads WHERE id=:id";
$stmt        = $connect->prepare($querySelect);
$stmt->bindParam(":id", $_GET['id']);
$stmt->execute();
$ad = $stmt->fetch(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Ediar Ads";
$theme_path  = "ads";
include BASE_DIR_ADMIN . "/views/settings/ads_edit.view.php";
/* ================================= */