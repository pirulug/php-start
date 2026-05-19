<?php
/**
 * Test Runner
 * Ejecuta todas las pruebas unitarias del proyecto.
 */

require_once __DIR__ . "/bootstrap.php";

echo "=============================================================================\n";
echo "INICIANDO PRUEBAS UNITARIAS - PHP-START\n";
echo "=============================================================================\n";

// Listado de archivos de prueba a ejecutar
$tests = [
  "RouterTest.php",
  "CipherTest.php",
  "UtilitiesTest.php",
  "AdminLibsTest.php",
  "SecurityTest.php",
  "ConfigDbTest.php",
  "FileLibsTest.php",
  "LoginTest.php"
];

foreach ($tests as $test) {
  require_once __DIR__ . "/" . $test;
}

Tester::report();
