<?php

$cacheDir = __DIR__ . '/../storage/cache';

if (!is_dir($cacheDir)) {
  echo "Cache directory not found. <br>";
  echo $cacheDir;
  exit;
}

$filesDeleted = 0;

foreach (glob($cacheDir . '/*.php') as $cacheFile) {
  if (is_file($cacheFile)) {
    unlink($cacheFile);
    $filesDeleted++;
  }
}

echo "Cache reset completed. Files deleted: {$filesDeleted}\n";
