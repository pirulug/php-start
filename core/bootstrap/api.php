<?php
// bootstrap/api.php

require_once __DIR__ . '/base.php';

// Middlewares API
require_once BASE_DIR . "/core/middlewares/auth_api.middleware.php";

// Rutas API
require_once BASE_DIR . '/core/routers/api.route.php';