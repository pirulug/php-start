<?php

if (
  (!isset($_SESSION['signin']) || $_SESSION['signin'] !== true)
  && isset($_COOKIE[COOKIE_PREFIX . 'auth'])
) {
  $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
  header("Location: " . home_route("signin"));
  exit();
}