<?php

class FaviconGenerator {
  private string $uploadDir;
  private string $hash;
  private array $generatedFiles = [];

  public function __construct(string $uploadDir) {
    $this->uploadDir = rtrim($uploadDir, '/') . '/';

    if (!is_dir($this->uploadDir)) {
      mkdir($this->uploadDir, 0777, true);
    }
  }

  public function generate(string $sourceFile): array {
    if (!file_exists($sourceFile) || mime_content_type($sourceFile) !== 'image/png') {
      throw new Exception("El archivo debe ser un PNG válido.");
    }

    $sourceImage = @imagecreatefrompng($sourceFile);
    if (!$sourceImage) {
      throw new Exception("No se pudo cargar el archivo PNG.");
    }

    imagesavealpha($sourceImage, true);

    $this->hash = substr(md5(uniqid(mt_rand(), true)), 0, 8); // Ej: "f3c9a1b2"

    // Generar los archivos con el hash
    $this->resizeAndSave($sourceImage, 192, 192, 'android-chrome-192x192');
    $this->resizeAndSave($sourceImage, 512, 512, 'android-chrome-512x512');
    $this->resizeAndSave($sourceImage, 180, 180, 'apple-touch-icon');
    $this->resizeAndSave($sourceImage, 16, 16, 'favicon-16x16');
    $this->resizeAndSave($sourceImage, 32, 32, 'favicon-32x32');
    $this->generateIco($sourceImage);
    $this->generateWebManifest();

    imagedestroy($sourceImage);

    return $this->generatedFiles;
  }

  private function resizeAndSave($sourceImage, int $width, int $height, string $baseName): void {
    $resizedImage = imagecreatetruecolor($width, $height);
    imagealphablending($resizedImage, false);
    imagesavealpha($resizedImage, true);

    $transparentColor = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
    imagefill($resizedImage, 0, 0, $transparentColor);

    imagecopyresampled(
      $resizedImage,
      $sourceImage,
      0, 0, 0, 0,
      $width, $height,
      imagesx($sourceImage),
      imagesy($sourceImage)
    );

    $filename   = $baseName . '-' . $this->hash . '.png';
    $outputPath = $this->uploadDir . $filename;
    imagepng($resizedImage, $outputPath);
    imagedestroy($resizedImage);

    $this->generatedFiles[$baseName] = $filename;
  }

  private function generateIco($sourceImage): void {
    $sizes       = [16, 32, 48, 64, 128];
    $icoFileName = 'favicon-' . $this->hash . '.ico';
    $icoFilePath = $this->uploadDir . $icoFileName;

    $tempImageFile = tempnam(sys_get_temp_dir(), 'favicon_') . '.png';
    imagepng($sourceImage, $tempImageFile);

    $imagick = new Imagick();
    $imagick->readImage($tempImageFile);
    $imagick->setImageFormat('png');
    $imagick->setImageBackgroundColor('transparent');

    $ico = new Imagick();
    foreach ($sizes as $size) {
      $resizedImage = clone $imagick;
      $resizedImage->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
      $ico->addImage($resizedImage);
      $resizedImage->destroy();
    }

    $ico->setFormat('ico');
    $ico->writeImage($icoFilePath);

    $imagick->destroy();
    $ico->destroy();
    unlink($tempImageFile);

    $this->generatedFiles['favicon.ico'] = $icoFileName;
  }

  private function generateWebManifest(): void {
    $manifest = [
      "name"  => "Favicon Generator",
      "icons" => [
        [
          "src"   => $this->generatedFiles['android-chrome-192x192'] ?? '',
          "sizes" => "192x192",
          "type"  => "image/png"
        ],
        [
          "src"   => $this->generatedFiles['android-chrome-512x512'] ?? '',
          "sizes" => "512x512",
          "type"  => "image/png"
        ],
        [
          "src"   => $this->generatedFiles['apple-touch-icon'] ?? '',
          "sizes" => "180x180",
          "type"  => "image/png"
        ]
      ]
    ];

    $manifestPath = $this->uploadDir . 'site-' . $this->hash . '.webmanifest';
    file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $this->generatedFiles['webmanifest'] = basename($manifestPath);
  }
}
