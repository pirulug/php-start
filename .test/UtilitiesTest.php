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

// -----------------------------------------------------------------------------
// SECCIÓN: PRUEBAS DE MANEJADOR DE LAYOUTS (VIEW BLOCKS)
// -----------------------------------------------------------------------------
// 6.1 Prueba: Captura básica
block_start("test_basic_block");
echo "Contenido del Bloque";
block_end();
Tester::assertEquals("Contenido del Bloque", block_get("test_basic_block"), "block_get debe recuperar el contenido básico capturado");

// 6.2 Prueba: Anidamiento de bloques (Pila / Stack)
block_start("parent_block");
echo "Parent Inicio | ";
block_start("child_block");
echo "Child Contenido";
block_end();
echo " | Parent Fin";
block_end();

Tester::assertEquals("Child Contenido", block_get("child_block"), "El bloque hijo anidado debe capturarse correctamente");
Tester::assertEquals("Parent Inicio |  | Parent Fin", block_get("parent_block"), "El bloque padre debe conservar su contenido y soportar la pila");

// 6.3 Prueba: Modos append y prepend
block_clear("test_append_prepend");
block_define("test_append_prepend", "Original");
block_append("test_append_prepend");
echo " + Appended";
block_end();
block_prepend("test_append_prepend");
echo "Prepended + ";
block_end();

Tester::assertEquals("Prepended + Original + Appended", block_get("test_append_prepend"), "Los bloques append y prepend deben concatenar el contenido en el orden correcto");

// 6.4 Prueba: define_block programático y has_block
block_clear("test_define");
Tester::assertTrue(!block_has("test_define"), "block_has debe retornar false si el bloque no está definido o vacío");

block_define("test_define", "Contenido Directo");
Tester::assertTrue(block_has("test_define"), "block_has debe retornar true si el bloque contiene texto");
Tester::assertEquals("Contenido Directo", block_get("test_define"), "block_define debe almacenar el contenido de forma directa sin buffers");

// 6.5 Prueba: render_block directo
ob_start();
block_render("test_define");
$rendered = ob_get_clean();
Tester::assertEquals("Contenido Directo", $rendered, "block_render debe imprimir directamente en pantalla el contenido del bloque");

// 6.6 Prueba: Alias de compatibilidad retroactiva
start_block("test_alias");
echo "Alias Ok";
end_block();
Tester::assertEquals("Alias Ok", get_block("test_alias"), "Los alias heredados start_block/get_block deben funcionar correctamente");

block_clear("test_basic_block");
block_clear("parent_block");
block_clear("child_block");
block_clear("test_append_prepend");
block_clear("test_define");
block_clear("test_alias");

