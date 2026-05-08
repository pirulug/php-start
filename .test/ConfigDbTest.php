<?php
/**
 * ConfigDbTest
 * Pruebas para DataBase y SiteConfig.
 */

echo "\nEJECUTANDO: ConfigDbTest\n";

global $connect, $config;

// 1. Prueba: DataBase
Tester::assertTrue($connect instanceof PDO, "La conexión global \$connect debe ser una instancia de PDO");
Tester::assertEquals(PDO::ERRMODE_EXCEPTION, $connect->getAttribute(PDO::ATTR_ERRMODE), "PDO debe estar en modo EXCEPTION");

// 2. Prueba: SiteConfig
Tester::assertTrue(is_string($config->siteName()), "SiteConfig::siteName debe devolver un string");
Tester::assertTrue(is_string($config->siteUrl()), "SiteConfig::siteUrl debe devolver un string");
Tester::assertTrue(is_array($config->social()), "SiteConfig::social debe devolver un array");

$original_name = $config->siteName();
Tester::assertEquals($original_name, $config->get("site_name"), "SiteConfig::get debe devolver el mismo valor que el método mágico");
