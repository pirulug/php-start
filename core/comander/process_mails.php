<?php

/**
 * Procesador de Cola de Correos Electrónicos
 * 
 * Lee los archivos JSON en storage/mails/ y los envía usando MailService.
 * Si el envío es exitoso, elimina el archivo. Si falla, lo renombra a .failed.
 */

// Asegurar que se carguen los helpers requeridos
require_once BASE_DIR . "/core/services/mail/mail.helper.php";

echo "\nProcesador de Cola de Correos - PHP-Start\n";
echo "--------------------------------------------------------\n";

$mails_dir = BASE_DIR . "/storage/mails";

if (!is_dir($mails_dir)) {
  echo "[INFO] No se encontró el directorio de cola de correos.\n";
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

// Buscar archivos JSON que representen correos encolados
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
  echo "[INFO] No hay correos pendientes en la cola.\n\n";
  exit();
}

echo "Encontrados " . count($mail_files) . " correos en la cola.\n";
echo "Iniciando envío...\n\n";

// Inicializar el servicio de correo
$mail_service = Mail::init();

$success_count = 0;
$failed_count = 0;

foreach ($mail_files as $file_path) {
  $processing_path = $file_path . ".processing";

  // Intentar adquirir el bloqueo renombrando el archivo de forma atómica
  if (!@rename($file_path, $processing_path)) {
    continue;
  }

  $filename = basename($processing_path);
  $json_content = file_get_contents($processing_path);
  $mail_data = json_decode($json_content, true);

  if (!$mail_data || !isset($mail_data["to"], $mail_data["subject"], $mail_data["body"])) {
    echo "[ADVERTENCIA] Archivo inválido o corrupto: {$filename}. Renombrando a .invalid\n";
    rename($processing_path, str_replace(".json.processing", ".json.invalid", $processing_path));
    continue;
  }

  $to = $mail_data["to"];
  $subject = $mail_data["subject"];
  $body = $mail_data["body"];
  $attachments = $mail_data["attachments"] ?? [];
  $lang = $mail_data["lang"] ?? DEFAULT_LANG;

  echo "Enviando a: {$to} | Asunto: {$subject}...\n";

  // Intentar el envío forzando el bypass de la cola
  $result = $mail_service->lang($lang)->send($to, $subject, $body, $attachments, true);

  if ($result["success"]) {
    echo "  [OK] Correo enviado correctamente.\n";
    unlink($processing_path);
    $success_count++;
  } else {
    echo "  [ERROR] Falló el envío: " . $result["message"] . "\n";
    echo "  Renombrando archivo a .failed\n";
    rename($processing_path, str_replace(".json.processing", ".json.failed", $processing_path));
    $failed_count++;
  }
}

echo "\n--------------------------------------------------------\n";
echo "Proceso finalizado.\n";
echo "Exitosos: {$success_count} | Fallidos: {$failed_count}\n\n";
