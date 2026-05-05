<?php

/**
 * Middleware para autenticación en el frontend.
 * Verifica si el usuario ha iniciado sesión antes de permitir el acceso.
 * 
 * @param array $route Datos de la ruta actual.
 * @param mixed $params Parámetros adicionales (no utilizados).
 * @return void
 */
function auth_home_middleware(array $route, $params = null) {
  if (!is_logged_in()) {

    // Guardar la URL solicitada (solo GET) para redirección posterior
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    }

    header("Location: " . front_route("signin"));
    exit();
  }
}