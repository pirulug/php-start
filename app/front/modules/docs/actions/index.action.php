<?php

require_once BASE_DIR . "/core/vendors/parsedown/Parsedown.php";

$page = route()["params"]["page"] ?? "Router"; 
$content_dir = str_replace("\\", "/", realpath(__DIR__ . "/../content"));
$file_path = "{$content_dir}/{$page}.md";

if (!file_exists($file_path)) {
  $page = "Router";
  $file_path = "{$content_dir}/Router.md";
}

$markdown_raw = file_get_contents($file_path);
$parsedown = new Parsedown();
$html_content = $parsedown->text($markdown_raw);

// Obtener lista de archivos para el menú lateral
$files = scandir($content_dir);
$docs_menu = [];
foreach ($files as $f) {
  if (str_ends_with($f, ".md")) {
    $name = str_replace(".md", "", $f);
    $docs_menu[] = [
      "label" => $name,
      "link" => front_route("docs/{$name}"),
      "active" => $page === $name
    ];
  }
}
