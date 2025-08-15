<?php
require_once __DIR__ . '/init.global.php';

// =============================================================================
// Obtener Información de usuario
// =============================================================================
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $user_session = get_user_session_information($connect, $_SESSION["user_id"]);
}

// =============================================================================
// Access Control
// =============================================================================
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $accessControl = new AccessControl($_SESSION["signin"], $user_session);
} else {
  $accessControl = new AccessControl(false, null);
}

// =============================================================================
// OPTIONS
// =============================================================================
$siteOptions = new SiteOptions($connect);

// =============================================================================
// URL Helper
// =============================================================================
$static_url = new StaticUrl(SITE_URL);
$site_url   = new SiteUrl(SITE_URL);