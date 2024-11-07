<?php
session_start();

$currentStep = isset($_GET['step']) ? $_GET['step'] : 1;

if (file_exists("../config.php")) {
  echo '<meta http-equiv="refresh" content="0; url=../" />';
  exit;
}

require_once "libs/Encryption.php";
require_once "functions.php";

$encryption = new Encryption();

switch ($currentStep) {
  case 1:
    include 'steps/step1_welcome.php';
    break;
  case 2:
    include 'steps/step2_requirements.php';
    break;
  case 3:
    include 'steps/step3_database.php';
    break;
  case 4:
    include 'steps/step4_siteconfig.php';
    break;
  case 5:
    include 'steps/step5_finish.php';
    break;
  default:
    include 'steps/step1_welcome.php';
    break;
}
