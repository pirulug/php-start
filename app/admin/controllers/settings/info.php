<?php

// Requisitos mínimos
$minPhpVersion      = '8.0.0';
$requiredExtensions = ['curl', 'pdo_mysql', 'mbstring', 'gd', 'imagick'];
$minMemoryLimit     = 128; // en MB
$maxExecutionTime   = 30; // en segundos
$minDbVersion       = '8.0.0';

// Verificaciones
$isPhpVersionOk    = version_compare(phpversion(), $minPhpVersion, '>=');
$enabledExtensions = get_loaded_extensions();
$missingExtensions = array_diff($requiredExtensions, $enabledExtensions);
$memoryLimit       = (int) filter_var(ini_get('memory_limit'), FILTER_SANITIZE_NUMBER_INT);
$isMemoryLimitOk   = $memoryLimit >= $minMemoryLimit;
$executionTime     = (int) ini_get('max_execution_time');
$isExecutionTimeOk = $executionTime >= $maxExecutionTime;

// Iconos
function getStatusIcon($status) {
  return $status
    ? '<i class="fa-solid fa-check text-success"></i>'
    : '<i class="fa-solid fa-x text-danger"></i>';
}

$phpVersion       = phpversion();
$loadedExtensions = implode(', ', get_loaded_extensions());
$serverSoftware   = $_SERVER['SERVER_SOFTWARE'];
$dbVersion        = $connect->getAttribute(PDO::ATTR_SERVER_VERSION);

// Identificar el tipo de base de datos (MySQL o MariaDB)
$query  = $connect->query("SELECT @@version_comment AS dbType");
$result = $query->fetch(PDO::FETCH_ASSOC);
$dbType = $result['dbType'] ?? 'Desconocido';

$isDbVersionOk = version_compare($dbVersion, $minDbVersion, '>=');


$theme->render(
  BASE_DIR_ADMIN . "/views/settings/info.view.php",
  [
    'theme_title'        => 'Informacion',
    'theme_path'         => 'info',
    'requiredExtensions' => $requiredExtensions,
    'isPhpVersionOk'     => $isPhpVersionOk,
    'isMemoryLimitOk'    => $isMemoryLimitOk,
    'isExecutionTimeOk'  => $isExecutionTimeOk,
    'executionTime'      => $executionTime,
    'dbVersion'          => $dbVersion,
    'dbType'             => $dbType,
    'minDbVersion'       => $minDbVersion,
    'isDbVersionOk'       => $isDbVersionOk,
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);
