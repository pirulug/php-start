<?php

// Obtener información del usuario si ha iniciado sesión
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $user_session = get_user_session_information($connect, $_SESSION["user_id"]);
}

$accessControl = new AccessControl(
  $_SESSION["signin"] ?? false,
  $user_session ?? null
);