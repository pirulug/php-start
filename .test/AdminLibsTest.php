<?php
/**
 * AdminLibsTest
 * Pruebas para Sidebar y ActionBtn.
 */

echo "\nEJECUTANDO: AdminLibsTest\n";

// Cargar clases que no están en la raíz de libraries
require_once BASE_DIR . "/core/libraries/admin/Sidebar.php";

// 1. Prueba: ActionBtn
$url = "admin/users/edit/1";
$btn = ActionBtn::edit($url)->text("Editar Usuario");
$html = $btn->render();
Tester::assertTrue(strpos($html, "href=\"$url\"") !== false, "ActionBtn debe contener la URL correcta");
Tester::assertTrue(strpos($html, "fa-pen-to-square") !== false, "ActionBtn debe contener el icono de edición");
Tester::assertTrue(strpos($html, "Editar Usuario") !== false, "ActionBtn debe contener el texto configurado");

// 2. Prueba: Sidebar
Sidebar::resetGroup();
Sidebar::item("Dashboard", "admin/dashboard")->icon("fa-home");
Sidebar::group("Usuarios", "fa-users", function($sidebar) {
  $sidebar->item("Listar", "admin/users");
});

$reflection = new ReflectionClass("Sidebar");
$property = $reflection->getProperty("items");
$property->setAccessible(true);
$items = $property->getValue(null);

Tester::assertTrue(count($items) >= 2, "Sidebar debe tener al menos 2 elementos registrados");
Tester::assertEquals("Dashboard", $items[0]["text"], "El primer elemento de Sidebar debe ser 'Dashboard'");
Tester::assertEquals("group", $items[1]["type"], "El segundo elemento de Sidebar debe ser un grupo");
Tester::assertEquals("Listar", $items[1]["items"][0]["text"], "El sub-ítem del grupo debe ser 'Listar'");
