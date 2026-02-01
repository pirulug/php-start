<?php
// bootstrap/home.php

require_once __DIR__ . '/base.php';

// Middlewares de Home
require_once BASE_DIR . "/core/middlewares/auth_home.middleware.php";
require_once BASE_DIR . "/core/middlewares/permission.middleware.php";

// Rutas de Home
require_once BASE_DIR . '/core/routers/home.route.php';