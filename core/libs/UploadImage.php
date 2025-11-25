<?php

/**
 * Clase UploadImage
 * 
 * Esta clase permite subir, convertir, optimizar y redimensionar imágenes.
 * Cuenta con una interfaz Fluent (encadenamiento de métodos).
 */
class UploadImage {

  private array $file;
  private string $uploadDir;

  private array $supported = ['jpg', 'jpeg', 'png', 'webp'];
  private int $maxSize = 2097152; // 2MB
  private ?string $convertTo = null;
  private int $quality = 7;
  private ?string $fileName = null;
  private string $prefix = "img_";
  private array $resizeVariants = [];

  // NUEVO: tamaño del archivo principal
  private ?int $mainWidth = null;
  private ?int $mainHeight = null;


  /* ============================================================
   * CONFIGURACIONES FLUENT
   * ============================================================ */

  /** Asigna el archivo $_FILES */
  public function file(array $file): self {
    $this->file = $file;
    return $this;
  }

  /** Directorio donde se guardará la imagen */
  public function dir(string $dir): self {
    $this->uploadDir = rtrim($dir, "/");
    return $this;
  }

  /** Extensiones permitidas */
  public function supported(array $types): self {
    $this->supported = $types;
    return $this;
  }

  /** Tamaño máximo permitido (bytes) */
  public function maxSize(int $bytes): self {
    $this->maxSize = $bytes;
    return $this;
  }

  /** Convertir a formato: jpg | png | webp | null */
  public function convertTo(?string $ext): self {
    $this->convertTo = $ext;
    return $this;
  }

  /** Calidad de optimización 0–10 */
  public function optimize(int $value): self {
    $this->quality = max(0, min(10, $value));
    return $this;
  }

  /** Nombre personalizado */
  public function fileName(string $name): self {
    $this->fileName = $name;
    return $this;
  }

  /** Prefijo del archivo */
  public function prefix(string $prefix): self {
    $this->prefix = $prefix;
    return $this;
  }

  /** Añadir variante redimensionada */
  public function resize(string $key, int $width, int $height): self {
    $this->resizeVariants[$key] = [$width, $height];
    return $this;
  }

  /** NUEVO: Redimensionar la imagen principal (width) */
  public function width(int $width): self {
    $this->mainWidth = $width;
    return $this;
  }

  /** NUEVO: Redimensionar la imagen principal (height) */
  public function height(int $height): self {
    $this->mainHeight = $height;
    return $this;
  }


  /* ============================================================
   * PROCESO PRINCIPAL DE SUBIDA
   * ============================================================ */
  public function upload(): array {

    if (!isset($this->file['tmp_name'])) {
      return ["success" => false, "message" => "Archivo no recibido."];
    }

    // Crear carpeta si no existe
    if (!is_dir($this->uploadDir)) {
      mkdir($this->uploadDir, 0777, true);
    }

    // Validar errores
    if ($this->file['error'] !== UPLOAD_ERR_OK) {
      return ["success" => false, "message" => "Error al subir el archivo."];
    }

    // Validar tamaño
    if ($this->file['size'] > $this->maxSize) {
      return ["success" => false, "message" => "El archivo supera el máximo permitido."];
    }

    // Info del archivo
    $info = pathinfo($this->file['name']);
    $ext  = strtolower($info['extension']);

    if (!in_array($ext, $this->supported)) {
      return ["success" => false, "message" => "Extensión .$ext no permitida."];
    }

    // Nombre final
    $finalExt = $this->convertTo ? $this->convertTo : $ext;

    $name = $this->fileName
      ? $this->fileName
      : uniqid($this->prefix, true);

    $name  = preg_replace("/\./", "", $name);
    $name .= ".$finalExt";

    $finalPath = "{$this->uploadDir}/{$name}";

    // Temp
    $temp = "{$this->uploadDir}/temp_" . uniqid() . ".$ext";
    move_uploaded_file($this->file['tmp_name'], $temp);

    /* ========= REDIMENSIONAR IMAGEN PRINCIPAL (SI SE SOLICITÓ) ========= */
    if ($this->mainWidth || $this->mainHeight) {
      $this->resizeImage($temp, $temp, $this->mainWidth ?? 0, $this->mainHeight ?? 0, $this->quality);
    }

    // Procesar imagen principal
    $main = $this->processImage($temp, $finalPath, $finalExt, $this->quality);

    if (!$main['success']) {
      unlink($temp);
      return $main;
    }

    // Variantes
    $variants = [];

    foreach ($this->resizeVariants as $key => [$w, $h]) {
      $variantPath = "{$this->uploadDir}/{$key}_{$name}";
      $res         = $this->resizeImage($temp, $variantPath, $w, $h, $this->quality);

      if (!$res['success']) {
        unlink($temp);
        return $res;
      }

      $variants[$key] = [
        "file" => "{$key}_{$name}",
        "path" => $variantPath
      ];
    }

    unlink($temp);

    return [
      "success"   => true,
      "message"   => "Imagen subida correctamente.",
      "file_name" => $name,
      "file_path" => $finalPath,
      "resized"   => $variants
    ];
  }


  /* ============================================================
   * PROCESAMIENTO (IMAGICK o GD)
   * ============================================================ */

  private function processImage($src, $dest, $ext, $quality) {
    if (class_exists("Imagick")) {
      try {
        $img = new Imagick($src);
        $img->setImageFormat($ext);
        $img->setImageCompressionQuality($quality * 10);
        $img->writeImage($dest);
        return ["success" => true];
      } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
      }
    }

    return $this->convertImageGD($src, $dest, $ext, $quality);
  }

  private function convertImageGD($src, $dest, $ext, $quality) {
    $info = getimagesize($src);

    switch ($info['mime']) {
      case "image/jpeg":
        $img = imagecreatefromjpeg($src);
        break;
      case "image/png":
        $img = imagecreatefrompng($src);
        break;
      case "image/webp":
        $img = imagecreatefromwebp($src);
        break;
      default:
        return ["success" => false, "message" => "Formato no soportado."];
    }

    $q = ($ext === "png") ? 9 - round($quality) : $quality * 10;

    switch ($ext) {
      case "jpg":
      case "jpeg":
        imagejpeg($img, $dest, $q);
        break;
      case "png":
        imagepng($img, $dest, $q);
        break;
      case "webp":
        imagewebp($img, $dest, $q);
        break;
    }

    imagedestroy($img);
    return ["success" => true];
  }

  private function resizeImage($src, $dest, $width, $height, $quality) {
    if (class_exists('Imagick')) {
      try {
        $img = new Imagick($src);

        // Mantener proporción si width o height son 0
        if ($width > 0 && $height == 0) {
          $img->thumbnailImage($width, 0);
        } elseif ($height > 0 && $width == 0) {
          $img->thumbnailImage(0, $height);
        } else {
          $img->cropThumbnailImage($width, $height);
        }

        $img->setImageCompressionQuality($quality * 10);
        $img->writeImage($dest);

        return ["success" => true];
      } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
      }
    }

    return $this->resizeImageGD($src, $dest, $width, $height, $quality);
  }

  private function resizeImageGD($src, $dest, $w, $h, $quality) {
    $info  = getimagesize($src);
    $origW = $info[0];
    $origH = $info[1];
    $orig  = imagecreatefromstring(file_get_contents($src));

    // Mantener proporción si falta width/height
    if ($w > 0 && $h == 0) {
      $ratio = $origH / $origW;
      $h     = intval($w * $ratio);
    }

    if ($h > 0 && $w == 0) {
      $ratio = $origW / $origH;
      $w     = intval($h * $ratio);
    }

    // Si ambos 0 -> no redimensionar
    if ($w == 0 && $h == 0) {
      $w = $origW;
      $h = $origH;
    }

    $resized = imagecreatetruecolor($w, $h);

    // Transparencia
    if (in_array($info['mime'], ['image/png', 'image/webp'])) {
      imagesavealpha($resized, true);
      $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
      imagefill($resized, 0, 0, $transparent);
    }

    imagecopyresampled($resized, $orig, 0, 0, 0, 0, $w, $h, $origW, $origH);

    switch ($info['mime']) {
      case "image/jpeg":
        imagejpeg($resized, $dest, $quality * 10);
        break;
      case "image/png":
        imagepng($resized, $dest, 9 - round($quality));
        break;
      case "image/webp":
        imagewebp($resized, $dest, $quality * 10);
        break;
    }

    imagedestroy($resized);
    imagedestroy($orig);

    return ["success" => true];
  }
}
