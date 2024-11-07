<?php

require_once "../../core.php";

$accessControl->require_login(SITE_URL_ADMIN . "/controllers/login.php");
$accessControl->check_access([1], SITE_URL . "/404.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $st_sitename    = cleardata($_POST['st_sitename']);
  $st_description = cleardata($_POST['st_description']);

  $st_keywords = json_decode($_POST['st_keywords'], true);

  if (is_array($st_keywords)) {
    $st_keywords_values = array_map(function ($keyword) {
      return $keyword['value'];
    }, $st_keywords);

    $st_keywords_imploded = implode(', ', $st_keywords_values);
  } else {
    $st_keywords_imploded = '';
  }

  $st_facebook  = cleardata($_POST['st_facebook']);
  $st_twitter   = cleardata($_POST['st_twitter']);
  $st_instagram = cleardata($_POST['st_instagram']);
  $st_youtube   = cleardata($_POST['st_youtube']);

  $query = "UPDATE settings SET
        st_sitename = :st_sitename,
        st_description = :st_description,
        st_keywords = :st_keywords,
        st_facebook = :st_facebook,
        st_twitter = :st_twitter,
        st_instagram = :st_instagram,
        st_youtube = :st_youtube";

  $stmt = $connect->prepare($query);

  $stmt->bindParam(':st_sitename', $st_sitename);
  $stmt->bindParam(':st_description', $st_description);
  $stmt->bindParam(':st_keywords', $st_keywords_imploded);
  $stmt->bindParam(':st_facebook', $st_facebook);
  $stmt->bindParam(':st_twitter', $st_twitter);
  $stmt->bindParam(':st_instagram', $st_instagram);
  $stmt->bindParam(':st_youtube', $st_youtube);

  $stmt->execute();

  $messageHandler->addMessage('Se actualizo de manera correcta', 'success');
  header("Refresh:0");
  exit();
}

$querySelect = "SELECT * FROM settings";
$settings    = $connect->query($querySelect)->fetch(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "General";
$theme_path  = "general";
include BASE_DIR_ADMIN . "/views/settings/general.view.php";
/* ================================= */