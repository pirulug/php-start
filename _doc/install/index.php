<?php
session_start();

$currentStep = isset($_GET['step']) ? $_GET['step'] : 1;

if (file_exists("../config.php")) {
  // echo '<meta http-equiv="refresh" content="0; url=../" />';
  header("Location: update.php");
  exit;
}

require_once "libs/Encryption.php";
require_once "functions.php";

$encryption = new Encryption();

switch ($currentStep) {
  case 1:
    include 'install/step1_welcome.php';
    break;
  case 2:
    include 'install/step2_requirements.php';
    break;
  case 3:
    include 'install/step3_database.php';
    break;
  case 4:
    include 'install/step4_siteconfig.php';
    break;
  case 5:
    include 'install/step5_finish.php';
    break;
  default:
    include 'install/step1_welcome.php';
    break;
}
