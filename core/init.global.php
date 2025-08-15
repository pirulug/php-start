<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../config.php';

/**
 * Carga todos los archivos PHP de un directorio de forma recursiva.
 */
function require_all($dir) {
  $iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
  );
  foreach ($iterator as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
      require_once $file;
    }
  }
}

// =============================================================================
// 1. Configuración
// =============================================================================
require_all(BASE_DIR . '/core/config');

// =============================================================================
// 2. Helpers
// =============================================================================
if (is_dir(BASE_DIR . '/core/helpers')) {
  require_all(BASE_DIR . '/core/helpers');
}

// =============================================================================
// 4. Librerías
// =============================================================================
require_all(BASE_DIR . '/core/libs');


// =============================================================================
// 3. Funciones
// =============================================================================
require_all(BASE_DIR . '/core/functions');

// =============================================================================

// =============================================================================
// Conexión a base de datos
// =============================================================================
$db      = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$connect = $db->getConnection();

if (!$connect) {
  die("Error de conexión a la base de datos");
}

// =============================================================================
// Template Engine
// =============================================================================
$theme = new TemplateEngine();

// =============================================================================
// Mensajes
// =============================================================================
$messageHandler = new MessageHandler();

// =============================================================================
// Encryption
// =============================================================================
$encryption = new Encryption(ENCRYPT_METHOD, SECRET_KEY, SECRET_IV);

// =============================================================================
// User log
// =============================================================================
$log = new Log($connect, BASE_DIR . "/logs");
