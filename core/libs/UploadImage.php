<?php

class UploadImage {

  private ?array $file = null;
  private ?string $fileUrl = null;
  private string $uploadDir;

  private array $supported = ['jpg', 'jpeg', 'png', 'webp'];
  private int $maxSize = 2097152;
  private ?string $convertTo = null;
  private int $quality = 7;
  private ?string $fileName = null;
  private string $prefix = 'img_';
  private array $resizeVariants = [];

  private ?int $mainWidth = null;
  private ?int $mainHeight = null;

  public function file(array $file): self {
    $this->file = $file;
    return $this;
  }

  public function url(string $url): self {
    $this->fileUrl = $url;
    return $this;
  }

  public function dir(string $dir): self {
    $this->uploadDir = rtrim($dir, '/');
    return $this;
  }

  public function supported(array $types): self {
    $this->supported = $types;
    return $this;
  }

  public function maxSize(int $bytes): self {
    $this->maxSize = $bytes;
    return $this;
  }

  public function convertTo(?string $ext): self {
    $this->convertTo = $ext;
    return $this;
  }

  public function optimize(int $value): self {
    $this->quality = max(0, min(10, $value));
    return $this;
  }

  public function fileName(string $name): self {
    $this->fileName = $name;
    return $this;
  }

  public function prefix(string $prefix): self {
    $this->prefix = $prefix;
    return $this;
  }

  public function resize(string $key, int $width, int $height): self {
    $this->resizeVariants[$key] = [$width, $height];
    return $this;
  }

  public function width(int $width): self {
    $this->mainWidth = $width;
    return $this;
  }

  public function height(int $height): self {
    $this->mainHeight = $height;
    return $this;
  }

  public function upload(): array {

    if (!$this->file && !$this->fileUrl) {
      return ['success' => false, 'message' => 'No se recibió archivo ni URL.'];
    }

    if (!is_dir($this->uploadDir)) {
      mkdir($this->uploadDir, 0777, true);
    }

    if ($this->fileUrl) {
      $temp = $this->downloadFromUrl($this->fileUrl);
      if (!$temp['success']) {
        return $temp;
      }
      $tempPath = $temp['tmp'];
      $ext      = $temp['ext'];
    } else {

      if ($this->file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al subir el archivo.'];
      }

      if ($this->file['size'] > $this->maxSize) {
        return ['success' => false, 'message' => 'El archivo supera el máximo permitido.'];
      }

      $info = pathinfo($this->file['name']);
      $ext  = strtolower($info['extension'] ?? '');

      if (!in_array($ext, $this->supported, true)) {
        return ['success' => false, 'message' => "Extensión .$ext no permitida."];
      }

      $tempPath = $this->uploadDir . '/temp_' . uniqid() . '.' . $ext;
      move_uploaded_file($this->file['tmp_name'], $tempPath);
    }

    $finalExt  = $this->convertTo ?: $ext;
    $name      = $this->fileName ?: uniqid($this->prefix, true);
    $name      = preg_replace('/\./', '', $name) . '.' . $finalExt;
    $finalPath = $this->uploadDir . '/' . $name;

    if ($this->mainWidth || $this->mainHeight) {
      $this->resizeImage(
        $tempPath,
        $tempPath,
        $this->mainWidth ?? 0,
        $this->mainHeight ?? 0,
        $this->quality
      );
    }

    $main = $this->processImage($tempPath, $finalPath, $finalExt, $this->quality);

    if (!$main['success']) {
      @unlink($tempPath);
      return $main;
    }

    $variants = [];

    foreach ($this->resizeVariants as $key => [$w, $h]) {
      $variantPath = $this->uploadDir . '/' . $key . '_' . $name;
      $res         = $this->resizeImage($tempPath, $variantPath, $w, $h, $this->quality);

      if (!$res['success']) {
        @unlink($tempPath);
        return $res;
      }

      $variants[$key] = [
        'file' => $key . '_' . $name,
        'path' => $variantPath
      ];
    }

    @unlink($tempPath);

    return [
      'success'   => true,
      'message'   => 'Imagen procesada correctamente.',
      'file_name' => $name,
      'file_path' => $finalPath,
      'resized'   => $variants
    ];
  }

  private function downloadFromUrl(string $url): array {

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      return ['success' => false, 'message' => 'URL no válida.'];
    }

    $context = stream_context_create([
      'http' => ['timeout' => 10]
    ]);

    $data = @file_get_contents($url, false, $context);

    if ($data === false) {
      return ['success' => false, 'message' => 'No se pudo descargar la imagen.'];
    }

    if (strlen($data) > $this->maxSize) {
      return ['success' => false, 'message' => 'La imagen remota supera el tamaño permitido.'];
    }

    $info = getimagesizefromstring($data);

    if (!$info) {
      return ['success' => false, 'message' => 'El recurso no es una imagen válida.'];
    }

    $mimeMap = [
      'image/jpeg' => 'jpg',
      'image/png'  => 'png',
      'image/webp' => 'webp'
    ];

    if (!isset($mimeMap[$info['mime']])) {
      return ['success' => false, 'message' => 'Formato no soportado.'];
    }

    $ext = $mimeMap[$info['mime']];

    if (!in_array($ext, $this->supported, true)) {
      return ['success' => false, 'message' => "Extensión .$ext no permitida."];
    }

    $tmp = $this->uploadDir . '/temp_url_' . uniqid() . '.' . $ext;
    file_put_contents($tmp, $data);

    return [
      'success' => true,
      'tmp'     => $tmp,
      'ext'     => $ext
    ];
  }

  private function processImage(string $src, string $dest, string $ext, int $quality): array {

    if (class_exists('Imagick')) {
      try {
        $img = new Imagick($src);
        $img->setImageFormat($ext);
        $img->setImageCompressionQuality($quality * 10);
        $img->writeImage($dest);
        return ['success' => true];
      } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
      }
    }

    return $this->convertImageGD($src, $dest, $ext, $quality);
  }

  private function convertImageGD(string $src, string $dest, string $ext, int $quality): array {

    $data = @file_get_contents($src);
    if ($data === false) {
      return ['success' => false, 'message' => 'No se pudo leer la imagen.'];
    }

    $img = @imagecreatefromstring($data);
    if (!$img) {
      return ['success' => false, 'message' => 'GD no pudo crear la imagen.'];
    }

    $q = ($ext === 'png') ? 9 - round($quality) : $quality * 10;

    switch ($ext) {
      case 'jpg':
      case 'jpeg':
        imagejpeg($img, $dest, $q);
        break;
      case 'png':
        imagepng($img, $dest, $q);
        break;
      case 'webp':
        imagewebp($img, $dest, $q);
        break;
    }

    imagedestroy($img);
    return ['success' => true];
  }

  private function resizeImage(string $src, string $dest, int $w, int $h, int $quality): array {

    if (!class_exists('Imagick')) {
      return ['success' => false, 'message' => 'Imagick no disponible para resize.'];
    }

    $img = new Imagick($src);

    if ($w > 0 && $h === 0) {
      $img->thumbnailImage($w, 0);
    } elseif ($h > 0 && $w === 0) {
      $img->thumbnailImage(0, $h);
    } else {
      $img->cropThumbnailImage($w, $h);
    }

    $img->setImageCompressionQuality($quality * 10);
    $img->writeImage($dest);

    return ['success' => true];
  }
}
