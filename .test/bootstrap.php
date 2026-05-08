<?php
/**
 * Bootstrap para pruebas unitarias.
 * Inicializa el entorno del framework para ejecución CLI.
 */

const BASE_DIR = __DIR__ . "/..";

// Mock de superglobales para entorno CLI
$_SESSION = [];
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["REQUEST_METHOD"] = "GET";

// Cargar configuraciones base
require_once BASE_DIR . "/config.php";
require_once BASE_DIR . "/core/configs/cache.config.php";
require_once BASE_DIR . "/core/configs/path.config.php";
require_once BASE_DIR . "/core/configs/security.config.php";
require_once BASE_DIR . "/core/configs/app.config.php";

// Inicializar framework (sin salida de buffer si es posible)
ob_start();
require_once BASE_DIR . "/core/bootstraps/main.bootstrap.php";
ob_end_clean();

// Clase base para pruebas
require_once __DIR__ . "/Tester.php";
