<?php
/**
 * UtilitiesTest
 * Pruebas para utilidades: SiteDate, Gravatar, AntiXSS, Notifier, Logger.
 */

echo "\nEJECUTANDO: UtilitiesTest\n";

// 1. Prueba: AntiXSS
$antixss = new AntiXSS();
$dirty = "<script>alert('xss')</script><b>Hola</b><a href='javascript:void(0)'>Link</a>";
$clean = $antixss->clean($dirty);
Tester::assertTrue(strpos($clean, "<script>") === false, "AntiXSS debe eliminar etiquetas <script>");
Tester::assertTrue(strpos($clean, "javascript:") === false, "AntiXSS debe eliminar enlaces javascript:");
Tester::assertEquals("HolaLink", $clean, "AntiXSS debe dejar solo el texto limpio (con escape por defecto)");

// 2. Prueba: SiteDate
global $site_date;
$date_str = "2024-01-01 15:30:00";
Tester::assertTrue(is_string($site_date->date($date_str)) && !empty($site_date->date($date_str)), "SiteDate debe devolver un string de fecha");
Tester::assertEquals("03:30 pm", strtolower($site_date->time($date_str)), "SiteDate debe formatear la hora correctamente");

// 3. Prueba: Gravatar
$email = "test@example.com";
$gravatar = Gravatar::email($email)->size(100);
Tester::assertTrue(strpos($gravatar->url(), md5($email)) !== false, "Gravatar debe contener el hash MD5 del email");
Tester::assertTrue(strpos($gravatar->url(), "s=100") !== false, "Gravatar debe contener el tamaño configurado");

// 4. Prueba: Notifier
$notifier = new Notifier();
$notifier->success("Operación exitosa")->add();
Tester::assertTrue($notifier->can()->success(), "Notifier debe detectar el mensaje de éxito en la sesión");
Tester::assertTrue($notifier->any(), "Notifier::any debe devolver true si hay mensajes");

// 5. Prueba: Logger
$log_dir = BASE_DIR . "/storage/logs/tests";
if (!is_dir($log_dir)) mkdir($log_dir, 0777, true);
$logger = new Logger($log_dir);
$logger->info("Prueba de log")->write();
$log_file = $log_dir . "/" . date("Y-m-d") . ".log";
Tester::assertTrue(file_exists($log_file), "Logger debe crear el archivo de log");
Tester::assertTrue(strpos(file_get_contents($log_file), "Prueba de log") !== false, "Logger debe escribir el mensaje en el archivo");
