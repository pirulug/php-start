<?php

/**
 * Sitemap Action
 * Gestión del archivo sitemap.xml siguiendo estándares de integridad y SEO.
 */

$file_path = BASE_DIR . "/sitemap.xml";
$pages     = [];

// Cargar el archivo XML y obtener las páginas actuales
if (file_exists($file_path)) {
  try {
    $xml = simplexml_load_file($file_path);
    if ($xml !== false) {
      foreach ($xml->url as $url) {
        $pages[] = [
          'loc'        => (string) $url->loc,
          'lastmod'    => (string) $url->lastmod,
          'changefreq' => (string) $url->changefreq,
          'priority'   => (string) $url->priority
        ];
      }
    }
  } catch (Exception $e) {
    // Si el XML está corrupto, empezamos con una lista vacía
    $pages = [];
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  // Recoger los datos del formulario sanitizados
  $urls       = $_POST['url'] ?? [];
  $lastmod    = $_POST['lastmod'] ?? [];
  $changefreq = $_POST['changefreq'] ?? [];
  $priority   = $_POST['priority'] ?? [];

  try {
    if (empty($urls)) {
      throw new Exception("Debe existir al menos una URL en el sitemap.");
    }

    // Crear el XML con el estándar de sitemaps.org
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

    foreach ($urls as $i => $url) {
      $cleanUrl = trim($url);
      
      // Validaciones básicas
      if (filter_var($cleanUrl, FILTER_VALIDATE_URL) === false) {
        continue; // Omitir URLs inválidas en lugar de fallar todo el proceso
      }

      $prio = (float)($priority[$i] ?? 0.5);
      if ($prio < 0) $prio = 0.0;
      if ($prio > 1) $prio = 1.0;

      $urlElement = $xml->addChild('url');
      $urlElement->addChild('loc', htmlspecialchars($cleanUrl));
      $urlElement->addChild('lastmod', htmlspecialchars($lastmod[$i] ?? date('Y-m-d')));
      $urlElement->addChild('changefreq', htmlspecialchars($changefreq[$i] ?? 'monthly'));
      $urlElement->addChild('priority', number_format($prio, 1));
    }

    // Guardar el archivo actualizado con formato legible
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    
    if ($dom->save($file_path) === false) {
      throw new Exception("No se pudo escribir el archivo sitemap.xml. Verifique los permisos.");
    }

    $notifier
      ->message("Sitemap actualizado correctamente.")
      ->bootstrap()
      ->success()
      ->add();

  } catch (Exception $e) {
    $notifier
      ->message("Error: " . $e->getMessage())
      ->bootstrap()
      ->danger()
      ->add();
  }

  header("Location: " . $_SERVER['REQUEST_URI']);
  exit();
}
