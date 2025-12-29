<?php

// Admin route helper

function admin_route($path = '') {
  $path = trim($path, '/');

  if ($path === '') {
    return '/' . PATH_ADMIN;
  }

  // Evitar duplicar PATH_ADMIN
  if (strpos($path, PATH_ADMIN) === 0) {
    return '/' . trim($path, '/');
  }

  return '/' . PATH_ADMIN . '/' . $path;
}

// Home route helper

function home_route($path = "") {
  $path = trim($path, '/');

  if ($path === '') {
    return '/';
  }

  return '/' . trim($path, '/');
}