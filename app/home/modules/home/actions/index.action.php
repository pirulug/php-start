<?php

if (
  (!isset($_SESSION['signin']) || $_SESSION['signin'] !== true)
  && isset($_COOKIE[COOKIE_PREFIX . 'auth'])
) {
  header("Location: " . home_route("signin"));
  exit();
}