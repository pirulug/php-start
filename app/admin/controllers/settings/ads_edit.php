<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id      = clear_data($_POST['id']);
  $content = clear_data($_POST['content']);
  $status  = clear_data($_POST['status']);

  $query = "UPDATE ads SET ad_content = :content, ad_status = :status WHERE ad_id = :id";
  $stmt  = $connect->prepare($query);
  $stmt->bindParam(":content", $content);
  $stmt->bindParam(":status", $status);
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  $messageHandler->addMessage("Actualizado de manera correcta","success");
  header('Location: ./ads.php');
  exit();
}

$querySelect = "SELECT * FROM ads WHERE ad_id=:id";
$stmt        = $connect->prepare($querySelect);
$stmt->bindParam(":id", $_GET['id']);
$stmt->execute();
$ad = $stmt->fetch(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Ediar Ads";
$theme_path  = "ads";
include BASE_DIR_ADMIN . "/views/settings/ads_edit.view.php";
/* ================================= */