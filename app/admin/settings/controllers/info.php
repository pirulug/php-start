<?php
// info.php

// --- Configuración ---
$minPhpVersion      = '8.0.0';
$requiredExtensions = ['curl', 'pdo_mysql', 'mbstring', 'gd', 'imagick'];
$minMemoryLimit     = 128; // MB
$maxExecutionTime   = 30; // Segundos
$minDbVersion       = '8.0.0';

// --- Verificaciones PHP ---
$currentPhpVersion = phpversion();
$isPhpVersionOk    = version_compare($currentPhpVersion, $minPhpVersion, '>=');

$enabledExtensions = get_loaded_extensions();
$missingExtensions = array_diff($requiredExtensions, $enabledExtensions);
$areExtensionsOk   = empty($missingExtensions);

$memoryLimit     = (int) filter_var(ini_get('memory_limit'), FILTER_SANITIZE_NUMBER_INT);
$isMemoryLimitOk = $memoryLimit >= $minMemoryLimit;

$executionTime     = (int) ini_get('max_execution_time');
$isExecutionTimeOk = $executionTime >= $maxExecutionTime;

// --- Datos del Servidor ---
$freeSpaceMb  = round(disk_free_space("/") / 1024 / 1024);
$totalSpaceMb = round(disk_total_space("/") / 1024 / 1024);
$usedSpaceMb  = $totalSpaceMb - $freeSpaceMb;
$diskPercent  = round(($usedSpaceMb / $totalSpaceMb) * 100);
// Color de barra de disco: Verde < 70%, Amarillo < 90%, Rojo > 90%
$diskClass = $diskPercent > 90 ? 'bg-danger' : ($diskPercent > 70 ? 'bg-warning' : 'bg-success');

// --- Base de Datos ---
try {
  // Asumimos que $connect viene de un include anterior
  $dbVersion = $connect->getAttribute(PDO::ATTR_SERVER_VERSION);
  $query     = $connect->query("SELECT @@version_comment AS dbType");
  $result    = $query->fetch(PDO::FETCH_ASSOC);
  $dbType    = $result['dbType'] ?? 'SQL Genérico';
  $isDbOk    = true;
} catch (Exception $e) {
  $dbVersion = 'N/A';
  $dbType    = 'No conectado';
  $isDbOk    = false;
}
$isDbVersionOk = version_compare($dbVersion, $minDbVersion, '>=');

// --- Estado Global ---
$systemHealthy = $isPhpVersionOk && $areExtensionsOk && $isMemoryLimitOk && $isDbVersionOk;

// --- Helper para UI ---
function getStatusBadge($condition, $successText = 'OK', $failText = 'Error') {
  if ($condition) {
    return '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-check me-1"></i> ' . $successText . '</span>';
  }
  return '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fa-solid fa-xmark me-1"></i> ' . $failText . '</span>';
}
?>