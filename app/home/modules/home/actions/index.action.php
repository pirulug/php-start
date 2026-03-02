<?php

if (
  (!isset($_SESSION['signin']) || $_SESSION['signin'] !== true)
  && isset($_COOKIE['php-start'])
) {
  header("Location: " . home_route("signin"));
  exit();
}