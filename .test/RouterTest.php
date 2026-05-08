<?php
/**
 * RouterTest
 * Pruebas para la clase Router.
 */

echo "\nEJECUTANDO: RouterTest\n";

// Limpiar rutas previas para la prueba
$reflection = new ReflectionClass("Router");
$property = $reflection->getProperty("routes");
$property->setAccessible(true);
$property->setValue(null, []);

// Prueba: Registro de ruta básica
Router::route("test/basic")
  ->action("test@index")
  ->register();

$routes = Router::getRoutes();
Tester::assertTrue(isset($routes["test/basic"]), "La ruta 'test/basic' debe estar registrada");

// Prueba: Resolución de ruta básica
$resolved = Router::resolve("test/basic");
Tester::assertEquals("test/basic", $resolved["uri"] ?? null, "La ruta resuelta debe coincidir con la URI");

// Prueba: Parámetros dinámicos
Router::route("test/user/{id}")
  ->action("test@index")
  ->register();

$resolved_param = Router::resolve("test/user/123");
Tester::assertEquals("123", $resolved_param["params"]["id"] ?? null, "El parámetro 'id' debe ser 123");

// Prueba: Método endpoint (agregado recientemente)
Router::route("test/endpoint")
  ->setContext("admin")
  ->endpoint("test@list")
  ->register();

$resolved_endpoint = Router::resolve("test/endpoint");
Tester::assertTrue(strpos($resolved_endpoint["action"] ?? "", "endpoints") !== false, "La acción del endpoint debe apuntar al directorio endpoints");
Tester::assertTrue(strpos($resolved_endpoint["action"] ?? "", ".endpoint.php") !== false, "La extensión del endpoint debe ser .endpoint.php");

// -----------------------------------------------------------------------------
// SECCIÓN: PRUEBAS DE CONTEXTO ADMIN
// -----------------------------------------------------------------------------
Router::route("admin/test")
  ->setContext("admin")
  ->action("test@list")
  ->register();

$resolved_admin = Router::resolve("admin/test");
Tester::assertEquals("admin", $resolved_admin["context"] ?? null, "El contexto debe ser 'admin'");
Tester::assertTrue(strpos($resolved_admin["action"] ?? "", "app/admin/modules") !== false, "La ruta de la acción debe estar en 'admin/modules'");
Tester::assertTrue(strpos($resolved_admin["action"] ?? "", ".action.php") !== false, "La extensión para admin debe ser '.action.php'");

// -----------------------------------------------------------------------------
// SECCIÓN: PRUEBAS DE CONTEXTO API
// -----------------------------------------------------------------------------
Router::route("api/test")
  ->setContext("api")
  ->action("test@list")
  ->register();

$resolved_api = Router::resolve("api/test");
Tester::assertEquals("api", $resolved_api["context"] ?? null, "El contexto debe ser 'api'");
Tester::assertTrue(strpos($resolved_api["action"] ?? "", "app/api") !== false, "La ruta de la acción debe estar en 'app/api'");
Tester::assertTrue(strpos($resolved_api["action"] ?? "", ".php") !== false && strpos($resolved_api["action"] ?? "", ".action.php") === false, "La extensión para api debe ser '.php' simple");
