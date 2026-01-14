<?php

function auth_ajax_middleware(array $route) {
  if (!isset($_SESSION['signin'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      "success" => false,
      "message" => "No logeado"
    ]);
    exit();
  }
}
