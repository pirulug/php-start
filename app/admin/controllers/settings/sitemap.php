<?php


// Cargar el archivo XML y obtener las páginas actuales
$file_path = BASE_DIR . "/sitemap.xml";
$pages     = [];

if (file_exists($file_path)) {
  // Si el archivo existe, cargarlo
  $xml = simplexml_load_file($file_path);
  foreach ($xml->url as $url) {
    $pages[] = [
      'loc'        => (string) $url->loc,
      'lastmod'    => (string) $url->lastmod,
      'changefreq' => (string) $url->changefreq,
      'priority'   => (string) $url->priority
    ];
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Recoger los datos del formulario
  $urls       = $_POST['url'] ?? [];
  $lastmod    = $_POST['lastmod'] ?? [];
  $changefreq = $_POST['changefreq'] ?? [];
  $priority   = $_POST['priority'] ?? [];

  // Crear el XML o cargar el existente
  $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

  // Recorrer las entradas del formulario y añadirlas al XML
  foreach ($urls as $i => $url) {
    $urlElement = $xml->addChild('url');
    $urlElement->addChild('loc', htmlspecialchars($url));
    $urlElement->addChild('lastmod', $lastmod[$i]);
    $urlElement->addChild('changefreq', $changefreq[$i]);
    $urlElement->addChild('priority', $priority[$i]);
  }

  // Guardar el archivo actualizado
  $xml->asXML($file_path);

  // Mensaje de éxito
  echo '<script>alert("Sitemap actualizado correctamente."); window.location.href = "";</script>';
}

/* ========== Theme config ========= */
$theme->render(
  BASE_DIR_ADMIN . "/views/settings/sitemap.view.php",
  [
    'theme_title' => 'Sitemap.xml',
    'theme_path'  => 'sitemap',
    'file_path'   => $file_path,
    'pages'       => $pages
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);