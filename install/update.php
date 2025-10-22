<?php
session_start();

$currentStep = isset($_GET['step']) ? $_GET['step'] : 1;

if (!file_exists("../config.php")) {
  header("Location: index.php");
  exit();
}

require_once "../config.php";
require_once "libs/Cipher.php";
require_once "functions.php";

$cipher  = new Cipher(ENCRYPT_METHOD, ENCRYPT_KEY, ENCRYPT_IV);
$connect = connect();

switch ($currentStep) {
  case 1:
    include 'updates/step1_choose_options.php';
    break;
  case 2:
    include 'updates/step2_update_url.php';
    break;
  case 3:
    include 'updates/step3_update_logos.php';
    break;
  case 4:
    include 'updates/step4_regenerate_keys.php';
    break;
  case 5:
    include 'updates/step5_new_user.php';
    break;
  case 6:
    include 'updates/step6_finish.php';
    break;
  default:
    include 'updates/step1_choose_options.php';
    break;
}
