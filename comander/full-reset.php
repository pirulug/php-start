<?php
/**
 * Script para limpiar profundamente la caché de PHP y del framework.
 */

// 1. Limpiar OPcache (Memoria)
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache reset: OK\n";
} else {
    echo "OPcache not available.\n";
}

// 2. Limpiar caché de archivos del framework
$cacheDir = __DIR__ . '/../storage/cache';
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '/*.php');
    foreach ($files as $file) {
        unlink($file);
    }
    echo "Files deleted in storage/cache: " . count($files) . "\n";
} else {
    echo "Cache directory not found: $cacheDir\n";
}

echo "Full reset completed.\n";
