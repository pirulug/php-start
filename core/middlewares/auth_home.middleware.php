<?php

function auth_home_middleware(array $route) {
  if (!isset($_SESSION['signin'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    }

    header('Location: ' . APP_URL . '/signin');
    exit();
  }
}
