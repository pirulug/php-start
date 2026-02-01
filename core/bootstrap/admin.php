<?php

require_once __DIR__ . '/base.php';

// Middlewares de Admin
require_once BASE_DIR . "/core/middlewares/auth_admin.middleware.php";
require_once BASE_DIR . "/core/middlewares/permission.middleware.php";

// Rutas de Admin
require_once BASE_DIR . '/core/routers/admin.route.php';
