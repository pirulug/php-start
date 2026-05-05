<?php

ob_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);

if (!defined('BASE_DIR')) {
  define('BASE_DIR', __DIR__); 
}

require_once BASE_DIR . '/core/vendors/qrcode/qrcode.php';

$data    = $_GET['d'] ?? 'Pirulug';
$options = $_GET;

if (!extension_loaded('gd')) {
  if (ob_get_length()) {
    ob_end_clean();
  }
  die("Error: La extensión GD no está instalada o habilitada.");
}

$qr = new QRCode($data, $options);

try {
  if (ob_get_length()) {
    ob_end_clean();
  }
  $qr->output_image();
} catch (Exception $e) {
  if (ob_get_length()) {
    ob_end_clean();
  }
  header('Content-Type: text/plain');
  echo "Error: " . $e->getMessage();
}

exit();