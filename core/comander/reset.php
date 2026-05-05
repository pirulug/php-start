<?php

/**
 * Script para la limpieza de caché del sistema y opcionalmente OPcache.
 */

// El entorno y las configuraciones ya vienen cargadas desde el archivo ps

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

$is_full = in_array('--full', $argv);

if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nLimpiador de Caché\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps reset [--full]\n\n";
  echo "Parámetros:\n";
  echo "  --full    (Opcional) Además de limpiar archivos, resetea el OPcache.\n\n";
  echo "Descripción:\n";
  echo "  Purga el directorio storage/caches/ para forzar regeneración.\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================

echo "\n[SOLICITADO] Iniciando limpieza de caché\n";
echo "--------------------------------------------------------\n";

// 1. Limpieza de Archivos
$cache_dir     = BASE_DIR . '/storage/caches';
$files_deleted = 0;

if (is_dir($cache_dir)) {
  $files = glob($cache_dir . '/*.php');
  foreach ($files as $file) {
    if (is_file($file)) {
      if (unlink($file)) {
        echo "ELIMINADO: " . basename($file) . "\n";
        $files_deleted++;
      }
    }
  }
  echo "[OK] Caché de archivos liberada ($files_deleted archivos).\n";
} else {
  echo "[ERROR] El directorio de caché no existe.\n";
  echo "RUTA: $cache_dir\n";
}

// 2. Limpieza de OPcache (Full)
if ($is_full) {
  echo "PROCESANDO: Reset de OPcache... ";
  if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
      echo "[OK]\n";
    } else {
      echo "[ERROR]\n";
    }
  } else {
    echo "[SALTEADO] No disponible en CLI\n";
  }
}

echo "--------------------------------------------------------\n";
echo "[OK] Proceso de reseteo completado.\n\n";