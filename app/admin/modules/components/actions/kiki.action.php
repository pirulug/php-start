<?php

// -----------------------------------------------------------------------------
// SECCIÓN: PREPARACIÓN DE ARCHIVOS GENERADOS POR KIKI Y HELPERS LOCALES
// -----------------------------------------------------------------------------
$kiki_files = [];
$kiki_dir = BASE_DIR . "/storage/uploads/kiki";

if (is_dir($kiki_dir)) {
  foreach (scandir($kiki_dir) as $file) {
    if ($file === '.' || $file === '..' || str_starts_with($file, '.')) {
      continue;
    }
    
    $file_path = "{$kiki_dir}/{$file}";
    if (is_file($file_path)) {
      $kiki_files[] = [
        'name' => $file,
        'size' => filesize($file_path),
        'date' => filemtime($file_path),
        'ext'  => strtolower(pathinfo($file, PATHINFO_EXTENSION))
      ];
    }
  }
  
  // Ordenar los archivos por fecha de modificacion descendente
  usort($kiki_files, function ($a, $b) {
    return $b['date'] <=> $a['date'];
  });
}

// -----------------------------------------------------------------------------
// SECCIÓN: CONSTRUCCIÓN DE ENLACES KIKI INLINE
// -----------------------------------------------------------------------------
$user_id = $_SESSION['user_id'] ?? 0;
$expiry  = time() + 600;
$site_base = rtrim(APP_URL, "/");
$upload_url = $site_base . admin_route("components/kiki/upload");

// 1. Factura PDF
$fn_pdf = "factura_kiki.pdf";
$tk_pdf = $cipher->encrypt($user_id . ":" . $expiry . ":" . $fn_pdf);
$kiki_link_pdf = "kiki://convert?" . http_build_query([
  "url" => $site_base . admin_route("components/_factura"),
  "upload_url" => $upload_url,
  "token" => $tk_pdf,
  "format" => "pdf",
  "filename" => $fn_pdf,
  "args" => "--margin-top 10 --margin-bottom 10 --margin-left 10 --margin-right 10"
]);

// 2. Factura PNG
$fn_png = "factura_screenshot.png";
$tk_png = $cipher->encrypt($user_id . ":" . $expiry . ":" . $fn_png);
$kiki_link_png = "kiki://convert?" . http_build_query([
  "url" => $site_base . admin_route("components/_factura"),
  "upload_url" => $upload_url,
  "token" => $tk_png,
  "format" => "png",
  "filename" => $fn_png
]);

// 3. Factura Landscape
$fn_land = "factura_landscape.pdf";
$tk_land = $cipher->encrypt($user_id . ":" . $expiry . ":" . $fn_land);
$kiki_link_landscape = "kiki://convert?" . http_build_query([
  "url" => $site_base . admin_route("components/_factura"),
  "upload_url" => $upload_url,
  "token" => $tk_land,
  "format" => "pdf",
  "filename" => $fn_land,
  "args" => "--orientation landscape --margin-top 0 --margin-bottom 0"
]);

// 4. Ticket 80mm PDF
$fn_tk_pdf = "ticket_kiki.pdf";
$tk_tk_pdf = $cipher->encrypt($user_id . ":" . $expiry . ":" . $fn_tk_pdf);
$kiki_link_ticket_pdf = "kiki://convert?" . http_build_query([
  "url" => $site_base . admin_route("components/_ticket"),
  "upload_url" => $upload_url,
  "token" => $tk_tk_pdf,
  "format" => "pdf",
  "filename" => $fn_tk_pdf,
  "args" => "--page-width 80mm --page-height 200mm --margin-top 0 --margin-bottom 0 --margin-left 0 --margin-right 0"
]);

// 5. Ticket 80mm PNG
$fn_tk_png = "ticket_screenshot.png";
$tk_tk_png = $cipher->encrypt($user_id . ":" . $expiry . ":" . $fn_tk_png);
$kiki_link_ticket_png = "kiki://convert?" . http_build_query([
  "url" => $site_base . admin_route("components/_ticket"),
  "upload_url" => $upload_url,
  "token" => $tk_tk_png,
  "format" => "png",
  "filename" => $fn_tk_png,
  "args" => "--width 300"
]);

