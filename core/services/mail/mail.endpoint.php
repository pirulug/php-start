<?php

/**
 * Endpoint: Procesar Cola de Correos Electrónicos
 * 
 * Permite ejecutar el envío de los correos encolados en storage/mails
 * a través de una petición HTTP (Fetch JS). Devuelve la estadística en JSON.
 */

header("Content-Type: application/json; charset=utf-8");

$mails_dir = BASE_DIR . "/storage/mails";

if (!is_dir($mails_dir)) {
  echo json_encode([
    "success" => true,
    "message" => "Directorio de correos no encontrado.",
    "sent"    => 0,
    "failed"  => 0
  ], JSON_UNESCAPED_UNICODE);
  exit();
}

// Recuperar archivos que quedaron atascados en procesamiento por más de 5 minutos
$processing_files = glob($mails_dir . "/*.json.processing");
if (!empty($processing_files)) {
  foreach ($processing_files as $proc_file) {
    if (filemtime($proc_file) < (time() - 300)) {
      $original_file = substr($proc_file, 0, -11); // Quitar ".processing"
      @rename($proc_file, $original_file);
    }
  }
}

$mail_files = glob($mails_dir . "/*.json");

if (!empty($mail_files)) {
  usort($mail_files, function ($a, $b) {
    preg_match("/mail_(\d{2}-\d{2}-\d{4}-\d{2}-\d{2}-\d{2})/", basename($a), $matches_a);
    preg_match("/mail_(\d{2}-\d{2}-\d{4}-\d{2}-\d{2}-\d{2})/", basename($b), $matches_b);

    $date_a = isset($matches_a[1]) ? DateTime::createFromFormat("d-m-Y-H-i-s", $matches_a[1]) : null;
    $date_b = isset($matches_b[1]) ? DateTime::createFromFormat("d-m-Y-H-i-s", $matches_b[1]) : null;

    if ($date_a && $date_b) {
      return $date_a <=> $date_b;
    }
    return filemtime($a) <=> filemtime($b);
  });
}

if (empty($mail_files)) {
  echo json_encode([
    "success" => true,
    "message" => "No hay correos pendientes en la cola.",
    "sent"    => 0,
    "failed"  => 0
  ], JSON_UNESCAPED_UNICODE);
  exit();
}

require_once __DIR__ . "/mail.helper.php";

$mail_service = Mail::init();
$success_count = 0;
$failed_count = 0;

foreach ($mail_files as $file_path) {
  $processing_path = $file_path . ".processing";

  // Intentar adquirir el bloqueo renombrando el archivo de forma atómica
  if (!@rename($file_path, $processing_path)) {
    continue;
  }

  $json_content = file_get_contents($processing_path);
  $mail_data = json_decode($json_content, true);

  if (!$mail_data || !isset($mail_data["to"], $mail_data["subject"], $mail_data["body"])) {
    rename($processing_path, str_replace(".json.processing", ".json.invalid", $processing_path));
    continue;
  }

  $to = $mail_data["to"];
  $subject = $mail_data["subject"];
  $body = $mail_data["body"];
  $attachments = $mail_data["attachments"] ?? [];
  $lang = $mail_data["lang"] ?? DEFAULT_LANG;

  $result = $mail_service->lang($lang)->send($to, $subject, $body, $attachments, true);

  if ($result["success"]) {
    unlink($processing_path);
    $success_count++;
  } else {
    rename($processing_path, str_replace(".json.processing", ".json.failed", $processing_path));
    $failed_count++;
  }
}

echo json_encode([
  "success" => true,
  "message" => "Procesamiento de cola de correos completado.",
  "sent"    => $success_count,
  "failed"  => $failed_count
], JSON_UNESCAPED_UNICODE);
exit();
