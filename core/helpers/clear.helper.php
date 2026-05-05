<?php

// --------------------------------------------------------------------------
// SECCIÓN: LIMPIEZA DE DATOS Y SEGURIDAD (XSS)
// --------------------------------------------------------------------------

/**
 * Limpia una cadena de texto para prevenir ataques XSS básicos en inputs.
 * Utiliza la clase AntiXSS interna.
 *
 * @param string $data Texto sucio de un input.
 * @return string Texto limpio.
 */
function clear_input($data) {
  static $antiXss = null;

  if ($antiXss === null) {
    $antiXss = new AntiXSS();
  }

  return $antiXss->clean($data);
}

/**
 * Escapa caracteres especiales para su visualización segura en HTML.
 * (Wrapper de htmlspecialchars para uso en vistas)
 *
 * @param string|null $data El texto a escapar para renderizar.
 * @return string El texto sanitizado para HTML.
 */
function clear_html($data) {
  return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Limpia contenido HTML para áreas de texto con formato (WYSIWYG).
 * Permite etiquetas básicas pero elimina scripts y eventos.
 *
 * @param string $data HTML sucio de un textarea/editor.
 * @param string|null $allowedTags Etiquetas permitidas (opcional).
 * @return string HTML limpio y seguro.
 */
function clear_textarea($data, $allowedTags = null) {
  static $antiXss = null;

  if ($antiXss === null) {
    $antiXss = new AntiXSS();
  }

  return ($allowedTags === null)
    ? $antiXss->cleanHtml($data)
    : $antiXss->cleanHtml($data, $allowedTags);
}

// --------------------------------------------------------------------------
// SECCIÓN: VALIDACIÓN DE ARCHIVOS (IMÁGENES)
// --------------------------------------------------------------------------

/**
 * Verifica si un archivo subido es una imagen real, íntegra y segura.
 * Filtra extensiones peligrosas y valida el MIME type real.
 *
 * @param array $file El array de $_FILES['campo'].
 * @return bool Verdadero si la imagen es segura.
 */
function clear_image($file) {
  // 1. Validar errores de subida
  if ($file['error'] !== UPLOAD_ERR_OK) {
    return false;
  }

  // 2. Validar extensión prohibida (Blacklist estricta)
  $filename  = strtolower($file['name']);
  $forbidden = ['php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'phar', 'exe', 'sh', 'js'];
  $ext       = pathinfo($filename, PATHINFO_EXTENSION);
  if (in_array($ext, $forbidden)) {
    return false;
  }

  // 3. Validar MIME type real (Magic Bytes)
  if (!class_exists('finfo')) {
    return false; // Requiere extensión fileinfo activada en PHP
  }

  $finfo         = new finfo(FILEINFO_MIME_TYPE);
  $mime          = $finfo->file($file['tmp_name']);
  $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

  if (!in_array($mime, $allowed_mimes)) {
    return false;
  }

  // 4. Validar integridad de imagen (Solo para formatos de mapa de bits)
  if ($mime !== 'image/svg+xml') {
    if (!@getimagesize($file['tmp_name'])) {
      return false;
    }
  }

  return true;
}